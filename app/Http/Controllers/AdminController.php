<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use App\Models\Interet;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'      => User::count(),
            'interets'   => Interet::count(),
            'blacklisted'=> User::where('blacklisted', true)->count(),
            'groups'     => Group::count(),
            'events'     => Event::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($nested) use ($search) {
                $nested->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with('interets')->orderByDesc('created_at')->get();

        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'role'     => 'required|in:admin,user',
            'position' => 'required|in:etudiant,personnel',
        ]);

        $user->update($request->only('nom', 'prenom', 'role', 'position'));

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis a jour.');
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprime.');
    }

    public function toggleBlacklist(User $user)
    {
        $user->update([
            'blacklisted' => !$user->blacklisted,
        ]);

        $status = $user->blacklisted ? 'blackliste' : 'debloque';

        return redirect()->route('admin.users')->with('success', "Utilisateur {$status}.");
    }

    public function interets()
    {
        $interets = Interet::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        $categories = Interet::query()
            ->select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('admin.interets', compact('interets', 'categories'));
    }

    public function storeInteret(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:interets,nom',
            'categorie' => 'required|string|max:255',
        ]);

        Interet::create($request->only('nom', 'categorie'));

        return redirect()->route('admin.interets')->with('success', 'Interet ajoute.');
    }

    public function updateInteret(Request $request, Interet $interet)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:interets,nom,' . $interet->id,
            'categorie' => 'required|string|max:255',
        ]);

        $interet->update($request->only('nom', 'categorie'));

        return redirect()->route('admin.interets')->with('success', 'Interet mis a jour.');
    }

    public function deleteInteret(Interet $interet)
    {
        $interet->users()->detach();
        $interet->delete();

        return redirect()->route('admin.interets')->with('success', 'Interet supprime.');
    }

    // ── Groups ────────────────────────────────────────────────────────────

    public function groups(Request $request)
    {
        $query = Group::with(['creator', 'members']);

        if ($search = $request->get('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        $groups = $query->orderByDesc('created_at')->get();

        return view('admin.groups', compact('groups'));
    }

    public function updateGroup(Request $request, Group $group)
    {
        $request->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'est_public'  => 'boolean',
        ]);

        $group->update([
            'nom'         => $request->input('nom'),
            'description' => $request->input('description'),
            'est_public'  => $request->boolean('est_public'),
        ]);

        return redirect()->route('admin.groups')->with('success', 'Groupe mis a jour.');
    }

    public function deleteGroup(Group $group)
    {
        if ($group->avatar_url) {
            \Storage::disk('public')->delete($group->avatar_url);
        }

        $group->delete();

        return redirect()->route('admin.groups')->with('success', 'Groupe supprime.');
    }

    // ── Events ────────────────────────────────────────────────────────────

    public function events(Request $request)
    {
        $query = Event::with(['creator', 'group', 'confirmedParticipants']);

        if ($search = $request->get('search')) {
            $query->where('titre', 'like', "%{$search}%");
        }

        $events = $query->orderByDesc('created_at')->get();

        return view('admin.events', compact('events'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'titre'            => 'required|string|max:100',
            'description'      => 'nullable|string|max:2000',
            'date_evenement'   => 'required|date',
            'heure_debut'      => 'required',
            'lieu'             => 'nullable|string|max:200',
            'max_participants' => 'nullable|integer|min:2',
            'prix'             => 'nullable|numeric|min:0',
            'type_acces'       => 'required|in:public,sur_demande,prive',
            'statut'           => 'required|in:actif,annule,complet',
        ]);

        $event->update($request->only(
            'titre', 'description', 'date_evenement', 'heure_debut',
            'lieu', 'max_participants', 'prix', 'type_acces', 'statut'
        ));

        return redirect()->route('admin.events')->with('success', 'Evenement mis a jour.');
    }

    public function deleteEvent(Event $event)
    {
        $event->participants()->detach();
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Evenement supprime.');
    }
}
