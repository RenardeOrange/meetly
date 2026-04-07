<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\Interet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /** Discover public groups + groups I'm in */
    public function index()
    {
        $userId = Auth::id();

        $myGroups = Group::whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->with(['members', 'interets', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->get();

        $publicGroups = Group::where('est_public', true)
            ->whereDoesntHave('members', fn($q) => $q->where('user_id', $userId))
            ->with(['members', 'interets'])
            ->get();

        $interets = Interet::orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');

        return view('groups.index', compact('myGroups', 'publicGroups', 'interets'));
    }

    /** Show create form */
    public function create()
    {
        $interets = Interet::orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');
        return view('groups.create', compact('interets'));
    }

    /** Store a new group */
    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string|max:60',
            'description'=> 'nullable|string|max:500',
            'est_public' => 'boolean',
            'interets'   => 'nullable|array',
            'interets.*' => 'exists:interets,id',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('group-avatars', 'public');
        }

        $group = Group::create([
            'creator_id'  => Auth::id(),
            'nom'         => $request->nom,
            'description' => $request->description,
            'avatar_url'  => $avatarPath,
            'est_public'  => $request->boolean('est_public'),
        ]);

        $group->members()->attach(Auth::id(), ['role' => 'admin']);

        if ($request->filled('interets')) {
            $group->interets()->sync($request->interets);
        }

        return redirect()->route('groups.show', $group)->with('success', 'Groupe créé!');
    }

    /** Show group chat */
    public function show(Group $group)
    {
        $userId = Auth::id();

        if (!$group->est_public && !$group->hasMember($userId)) {
            abort(403, 'Ce groupe est privé.');
        }

        $isMember  = $group->hasMember($userId);
        $isAdmin   = $group->members()->wherePivot('role', 'admin')->where('user_id', $userId)->exists();
        $messages  = $group->messages()->with('user')->orderBy('created_at')->get();
        $members   = $group->members()->withPivot('role')->get();
        $group->load('interets');

        return view('groups.show', compact('group', 'messages', 'members', 'isMember', 'isAdmin'));
    }

    /** Join a public group */
    public function join(Group $group)
    {
        $userId = Auth::id();

        if (!$group->est_public) {
            abort(403);
        }

        if (!$group->hasMember($userId)) {
            $group->members()->attach($userId, ['role' => 'membre']);
        }

        return redirect()->route('groups.show', $group);
    }

    /** Send a message in the group */
    public function sendMessage(Request $request, Group $group)
    {
        $request->validate(['contenu' => 'required|string|max:2000']);

        $userId = Auth::id();

        if (!$group->hasMember($userId)) {
            abort(403);
        }

        GroupMessage::create([
            'group_id' => $group->id,
            'user_id'  => $userId,
            'contenu'  => $request->contenu,
        ]);

        return redirect()->route('groups.show', $group);
    }

    /** Leave a group */
    public function leave(Group $group)
    {
        $userId = Auth::id();

        if ($group->creator_id === $userId) {
            return back()->with('error', 'Le créateur ne peut pas quitter le groupe.');
        }

        $group->members()->detach($userId);

        return redirect()->route('groups.index')->with('success', 'Vous avez quitté le groupe.');
    }

    /** Kick a member (admin only) */
    public function kick(Group $group, User $user)
    {
        $this->requireAdmin($group);

        if ($user->id === $group->creator_id) {
            return back()->with('error', 'Le créateur ne peut pas être expulsé.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', $user->prenom . ' a été retiré du groupe.');
    }

    /** Promote a member to admin */
    public function promote(Group $group, User $user)
    {
        $this->requireAdmin($group);

        $group->members()->updateExistingPivot($user->id, ['role' => 'admin']);

        return back()->with('success', $user->prenom . ' est maintenant admin.');
    }

    /** Demote an admin to member */
    public function demote(Group $group, User $user)
    {
        $this->requireAdmin($group);

        if ($user->id === $group->creator_id) {
            return back()->with('error', 'Le créateur ne peut pas être rétrogradé.');
        }

        $group->members()->updateExistingPivot($user->id, ['role' => 'membre']);

        return back()->with('success', $user->prenom . ' est maintenant membre.');
    }

    /** Search users to invite (AJAX, returns JSON) */
    public function searchUsers(Request $request, Group $group)
    {
        $this->requireAdmin($group);

        $q = $request->input('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $memberIds = $group->members()->pluck('user_id');

        $users = User::where(function ($query) use ($q) {
                $query->where('prenom', 'like', "%{$q}%")
                      ->orWhere('nom', 'like', "%{$q}%");
            })
            ->whereNotIn('id', $memberIds)
            ->limit(8)
            ->get(['id', 'prenom', 'nom', 'avatar_url']);

        return response()->json($users);
    }

    /** Invite (add) a user to the group (admin only) */
    public function invite(Request $request, Group $group)
    {
        $this->requireAdmin($group);

        // Support both JSON (from AJAX) and form POST
        $userId = $request->input('user_id') ?? $request->json('user_id');

        if (!$userId || !\App\Models\User::find($userId)) {
            return response()->json(['error' => 'Utilisateur introuvable.'], 422);
        }

        if (!$group->hasMember($userId)) {
            $group->members()->attach($userId, ['role' => 'membre']);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Utilisateur ajouté au groupe.');
    }

    /** Assert current user is an admin of the group */
    private function requireAdmin(Group $group): void
    {
        $userId = Auth::id();
        $isAdmin = $group->members()->wherePivot('role', 'admin')->where('user_id', $userId)->exists();
        if (!$isAdmin) {
            abort(403);
        }
    }
}
