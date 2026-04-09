<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /** List events: public + ones the user participates in */
    public function index()
    {
        $userId = Auth::id();

        $myEvents = Event::whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->orWhere('creator_id', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->get()
            ->unique('id');

        $upcomingEvents = Event::where('type_acces', 'public')
            ->where('statut', 'actif')
            ->where('date_evenement', '>=', now()->toDateString())
            ->whereDoesntHave('participants', fn($q) => $q->where('user_id', $userId))
            ->where('creator_id', '!=', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->limit(20)
            ->get();

        $requestEvents = Event::where('type_acces', 'sur_demande')
            ->where('statut', 'actif')
            ->where('date_evenement', '>=', now()->toDateString())
            ->whereDoesntHave('participants', fn($q) => $q->where('user_id', $userId))
            ->where('creator_id', '!=', $userId)
            ->with(['creator', 'group', 'confirmedParticipants'])
            ->orderBy('date_evenement')
            ->limit(20)
            ->get();

        return view('events.index', compact('myEvents', 'upcomingEvents', 'requestEvents'));
    }

    /** Show create form */
    public function create()
    {
        $userId = Auth::id();
        $myGroups = Group::whereHas('members', fn($q) => $q->where('user_id', $userId)->wherePivot('role', 'admin'))
            ->get();

        return view('events.create', compact('myGroups'));
    }

    /** Store a new event */
    public function store(Request $request)
    {
        $request->validate([
            'titre'            => 'required|string|max:100',
            'description'      => 'nullable|string|max:2000',
            'date_evenement'   => 'required|date|after_or_equal:today',
            'heure_debut'      => 'required',
            'lieu'             => 'nullable|string|max:200',
            'max_participants' => 'nullable|integer|min:2',
            'prix'             => 'nullable|numeric|min:0',
            'type_acces'       => 'required|in:public,sur_demande,prive',
            'group_id'         => 'nullable|exists:groups,id',
        ]);

        $event = Event::create([
            'creator_id'       => Auth::id(),
            'group_id'         => $request->group_id ?: null,
            'titre'            => $request->titre,
            'description'      => $request->description,
            'date_evenement'   => $request->date_evenement,
            'heure_debut'      => $request->heure_debut,
            'lieu'             => $request->lieu,
            'max_participants' => $request->max_participants,
            'prix'             => $request->prix ?? 0,
            'type_acces'       => $request->type_acces,
        ]);

        // Creator automatically joins as confirmed
        $event->participants()->attach(Auth::id(), ['statut' => 'confirme']);

        return redirect()->route('events.show', $event)->with('success', __('app.events') . ' créé!');
    }

    /** Show event details */
    public function show(Event $event)
    {
        $userId = Auth::id();

        if ($event->type_acces === 'prive') {
            $isParticipant = $event->participants()->where('user_id', $userId)->exists();
            $isCreator     = $event->creator_id === $userId;
            if (!$isParticipant && !$isCreator) {
                abort(403, 'Cet événement est privé.');
            }
        }

        $event->load(['creator', 'group', 'confirmedParticipants', 'pendingParticipants']);
        $myStatus = $event->participants()->where('user_id', $userId)->value('statut');
        $isCreator = $event->creator_id === $userId;

        return view('events.show', compact('event', 'myStatus', 'isCreator'));
    }

    /** Join or request to join an event */
    public function join(Event $event)
    {
        $userId = Auth::id();

        if ($event->statut !== 'actif') {
            return back()->with('error', 'Cet événement n\'est plus actif.');
        }

        if ($event->participants()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Vous participez déjà à cet événement.');
        }

        if ($event->type_acces === 'prive') {
            abort(403);
        }

        if ($event->isFull()) {
            return back()->with('error', 'Cet événement est complet.');
        }

        $statut = $event->type_acces === 'sur_demande' ? 'en_attente' : 'confirme';
        $event->participants()->attach($userId, ['statut' => $statut]);

        if ($event->isFull()) {
            $event->update(['statut' => 'complet']);
        }

        $message = $statut === 'en_attente'
            ? 'Demande envoyée. En attente de confirmation.'
            : 'Vous participez maintenant à cet événement!';

        return back()->with('success', $message);
    }

    /** Cancel participation */
    public function cancelJoin(Event $event)
    {
        $userId = Auth::id();

        if ($event->creator_id === $userId) {
            return back()->with('error', 'Le créateur ne peut pas quitter son propre événement.');
        }

        $event->participants()->detach($userId);

        // Reopen if was full
        if ($event->statut === 'complet') {
            $event->update(['statut' => 'actif']);
        }

        return back()->with('success', 'Participation annulée.');
    }

    /** Accept or refuse a join request (creator only) */
    public function respondRequest(Request $request, Event $event, int $userId)
    {
        if ($event->creator_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['action' => 'required|in:accept,refuse']);

        if ($request->action === 'accept') {
            if ($event->isFull()) {
                return back()->with('error', 'Événement complet.');
            }
            $event->participants()->updateExistingPivot($userId, ['statut' => 'confirme']);
            if ($event->isFull()) {
                $event->update(['statut' => 'complet']);
            }
        } else {
            $event->participants()->updateExistingPivot($userId, ['statut' => 'refuse']);
        }

        return back()->with('success', 'Demande traitée.');
    }

    /** Cancel (archive) event — creator only */
    public function cancel(Event $event)
    {
        if ($event->creator_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $event->update(['statut' => 'annule']);

        return back()->with('success', 'Événement annulé.');
    }
}
