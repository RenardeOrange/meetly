<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $myEvents = Event::whereHas('participants', fn ($query) => $query->where('user_id', $userId))
            ->orWhere('creator_id', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->get()
            ->unique('id');

        $upcomingEvents = Event::where('type_acces', 'public')
            ->where('statut', 'actif')
            ->where('date_evenement', '>=', now()->toDateString())
            ->whereDoesntHave('participants', fn ($query) => $query->where('user_id', $userId))
            ->where('creator_id', '!=', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->limit(20)
            ->get();

        $requestEvents = Event::where('type_acces', 'sur_demande')
            ->where('statut', 'actif')
            ->where('date_evenement', '>=', now()->toDateString())
            ->whereDoesntHave('participants', fn ($query) => $query->where('user_id', $userId))
            ->where('creator_id', '!=', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->limit(20)
            ->get();

        return view('events.index', compact('myEvents', 'upcomingEvents', 'requestEvents'));
    }

    public function create()
    {
        $myGroups = $this->manageableGroups();

        return view('events.create', compact('myGroups'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);
        $groupId = $this->validatedGroupId($request->input('group_id'));

        $event = Event::create([
            'creator_id' => Auth::id(),
            'group_id' => $groupId,
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? null,
            'date_evenement' => $validated['date_evenement'],
            'heure_debut' => $validated['heure_debut'],
            'lieu' => $validated['lieu'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'prix' => $validated['prix'] ?? 0,
            'type_acces' => $validated['type_acces'],
        ]);

        $event->participants()->attach(Auth::id(), ['statut' => 'confirme']);

        return redirect()->route('events.show', $event)->with('success', 'Evenement cree.');
    }

    public function show(Event $event)
    {
        $userId = Auth::id();

        if ($event->type_acces === 'prive') {
            $isParticipant = $event->participants()->where('user_id', $userId)->exists();
            $isCreator = $event->creator_id === $userId;
            if (! $isParticipant && ! $isCreator) {
                abort(403, 'Cet evenement est prive.');
            }
        }

        $event->load(['creator', 'group', 'confirmedParticipants', 'pendingParticipants']);
        $myStatus = $event->participants()->where('user_id', $userId)->value('statut');
        $isCreator = $event->creator_id === $userId;
        $myGroups = $isCreator ? $this->manageableGroups() : collect();

        return view('events.show', compact('event', 'myStatus', 'isCreator', 'myGroups'));
    }

    public function update(Request $request, Event $event)
    {
        $this->requireEventOwner($event);

        $validated = $this->validateEvent($request, $event);
        $groupId = $this->validatedGroupId($request->input('group_id'));

        $event->update([
            'group_id' => $groupId,
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? null,
            'date_evenement' => $validated['date_evenement'],
            'heure_debut' => $validated['heure_debut'],
            'lieu' => $validated['lieu'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'prix' => $validated['prix'] ?? 0,
            'type_acces' => $validated['type_acces'],
        ]);

        if ($event->statut === 'complet' && ! $event->isFull()) {
            $event->update(['statut' => 'actif']);
        }

        return back()->with('success', 'Evenement mis a jour.');
    }

    public function destroy(Event $event)
    {
        $this->requireEventOwner($event);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evenement supprime.');
    }

    public function join(Event $event)
    {
        $userId = Auth::id();

        if ($event->statut !== 'actif') {
            return back()->with('error', 'Cet evenement n\'est plus actif.');
        }

        if ($event->participants()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Vous participez deja a cet evenement.');
        }

        if ($event->type_acces === 'prive') {
            abort(403);
        }

        if ($event->isFull()) {
            return back()->with('error', 'Cet evenement est complet.');
        }

        $status = $event->type_acces === 'sur_demande' ? 'en_attente' : 'confirme';
        $event->participants()->attach($userId, ['statut' => $status]);

        if ($event->isFull()) {
            $event->update(['statut' => 'complet']);
        }

        $message = $status === 'en_attente'
            ? 'Demande envoyee. En attente de confirmation.'
            : 'Vous participez maintenant a cet evenement.';

        return back()->with('success', $message);
    }

    public function cancelJoin(Event $event)
    {
        $userId = Auth::id();

        if ($event->creator_id === $userId) {
            return back()->with('error', 'Le createur ne peut pas quitter son propre evenement.');
        }

        $event->participants()->detach($userId);

        if ($event->statut === 'complet') {
            $event->update(['statut' => 'actif']);
        }

        return back()->with('success', 'Participation annulee.');
    }

    public function respondRequest(Request $request, Event $event, int $userId)
    {
        if ($event->creator_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['action' => 'required|in:accept,refuse']);

        if ($request->action === 'accept') {
            if ($event->isFull()) {
                return back()->with('error', 'Evenement complet.');
            }

            $event->participants()->updateExistingPivot($userId, ['statut' => 'confirme']);

            if ($event->isFull()) {
                $event->update(['statut' => 'complet']);
            }
        } else {
            $event->participants()->updateExistingPivot($userId, ['statut' => 'refuse']);
        }

        return back()->with('success', 'Demande traitee.');
    }

    public function cancel(Event $event)
    {
        $this->requireEventOwner($event);

        $event->update(['statut' => 'annule']);

        return back()->with('success', 'Evenement annule.');
    }

    private function manageableGroups()
    {
        $userId = Auth::id();

        return Group::whereHas('members', fn ($query) => $query->where('user_id', $userId)->wherePivot('role', 'admin'))
            ->get();
    }

    private function validatedGroupId(?string $groupId): ?int
    {
        if (! $groupId) {
            return null;
        }

        $allowedGroupId = $this->manageableGroups()
            ->pluck('id')
            ->first(fn ($id) => (string) $id === (string) $groupId);

        abort_unless($allowedGroupId, 422, 'Groupe invalide.');

        return (int) $allowedGroupId;
    }

    private function validateEvent(Request $request, ?Event $event = null): array
    {
        $dateRule = $event ? 'required|date' : 'required|date|after_or_equal:today';

        return $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'nullable|string|max:2000',
            'date_evenement' => $dateRule,
            'heure_debut' => 'required',
            'lieu' => 'nullable|string|max:200',
            'max_participants' => 'nullable|integer|min:2',
            'prix' => 'nullable|numeric|min:0',
            'type_acces' => 'required|in:public,sur_demande,prive',
            'group_id' => 'nullable',
        ]);
    }

    private function requireEventOwner(Event $event): void
    {
        if ($event->creator_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
