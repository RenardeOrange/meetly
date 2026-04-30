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
    public function index()
    {
        $userId = Auth::id();

        $myGroups = Group::whereHas('members', fn ($query) => $query->where('user_id', $userId))
            ->with(['members', 'interets', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->get();

        $publicGroups = Group::where('est_public', true)
            ->whereDoesntHave('members', fn ($query) => $query->where('user_id', $userId))
            ->with(['members', 'interets'])
            ->get();

        $interets = Interet::orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');

        return view('groups.index', compact('myGroups', 'publicGroups', 'interets'));
    }

    public function create()
    {
        $interets = Interet::orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');

        return view('groups.create', compact('interets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:60',
            'description' => 'nullable|string|max:500',
            'est_public' => 'boolean',
            'interets' => 'nullable|array',
            'interets.*' => 'exists:interets,id',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('group-avatars', 'public');
        }

        $group = Group::create([
            'creator_id' => Auth::id(),
            'nom' => $request->nom,
            'description' => $request->description,
            'avatar_url' => $avatarPath,
            'est_public' => $request->boolean('est_public'),
        ]);

        $group->members()->attach(Auth::id(), ['role' => 'admin']);

        if ($request->filled('interets')) {
            $group->interets()->sync($request->interets);
        }

        return redirect()->route('groups.show', $group)->with('success', 'Groupe cree.');
    }

    public function show(Group $group)
    {
        $userId = Auth::id();

        if (! $group->est_public && ! $group->hasMember($userId)) {
            abort(403, 'Ce groupe est prive.');
        }

        $isMember = $group->hasMember($userId);
        $isAdmin = $group->members()->wherePivot('role', 'admin')->where('user_id', $userId)->exists();
        $messages = $group->messages()->with('user')->orderBy('created_at')->get();
        $members = $group->members()->withPivot('role')->get();
        $group->load('interets');

        return view('groups.show', compact('group', 'messages', 'members', 'isMember', 'isAdmin'));
    }

    public function join(Group $group)
    {
        $userId = Auth::id();

        if (! $group->est_public) {
            abort(403);
        }

        if (! $group->hasMember($userId)) {
            $group->members()->attach($userId, ['role' => 'membre']);
        }

        return redirect()->route('groups.show', $group);
    }

    public function sendMessage(Request $request, Group $group)
    {
        $request->validate(['contenu' => 'required|string|max:2000']);

        $userId = Auth::id();

        if (! $group->hasMember($userId)) {
            abort(403);
        }

        GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $userId,
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('groups.show', $group);
    }

    public function leave(Group $group)
    {
        $userId = Auth::id();

        if ($group->creator_id === $userId) {
            return back()->with('error', 'Le createur ne peut pas quitter le groupe.');
        }

        $group->members()->detach($userId);

        return redirect()->route('groups.index')->with('success', 'Vous avez quitte le groupe.');
    }

    public function kick(Group $group, User $user)
    {
        $this->requireAdmin($group);

        if ($user->id === $group->creator_id) {
            return back()->with('error', 'Le createur ne peut pas etre expulse.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', $user->prenom . ' a ete retire du groupe.');
    }

    public function promote(Group $group, User $user)
    {
        $this->requireAdmin($group);

        $group->members()->updateExistingPivot($user->id, ['role' => 'admin']);

        return back()->with('success', $user->prenom . ' est maintenant admin.');
    }

    public function demote(Group $group, User $user)
    {
        $this->requireAdmin($group);

        if ($user->id === $group->creator_id) {
            return back()->with('error', 'Le createur ne peut pas etre retrograde.');
        }

        $group->members()->updateExistingPivot($user->id, ['role' => 'membre']);

        return back()->with('success', $user->prenom . ' est maintenant membre.');
    }

    public function searchUsers(Request $request, Group $group)
    {
        $this->requireAdmin($group);

        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $memberIds = $group->members()->pluck('user_id');

        $users = User::where(function ($builder) use ($query) {
                $builder->where('prenom', 'like', "%{$query}%")
                    ->orWhere('nom', 'like', "%{$query}%");
            })
            ->whereNotIn('id', $memberIds)
            ->limit(8)
            ->get(['id', 'prenom', 'nom', 'avatar_url']);

        return response()->json($users);
    }

    public function invite(Request $request, Group $group)
    {
        $this->requireAdmin($group);

        $userId = $request->input('user_id') ?? $request->json('user_id');

        if (! $userId || ! User::find($userId)) {
            return response()->json(['error' => 'Utilisateur introuvable.'], 422);
        }

        if (! $group->hasMember($userId)) {
            $group->members()->attach($userId, ['role' => 'membre']);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Utilisateur ajoute au groupe.');
    }

    public function update(Request $request, Group $group)
    {
        $this->requireAdmin($group);

        $request->validate([
            'nom' => 'required|string|max:60',
            'description' => 'nullable|string|max:500',
            'est_public' => 'boolean',
        ]);

        $group->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'est_public' => $request->boolean('est_public'),
        ]);

        return back()->with('success', 'Groupe mis a jour.');
    }

    public function destroy(Group $group)
    {
        $this->requireAdmin($group);

        if ($group->avatar_url) {
            Storage::disk('public')->delete($group->avatar_url);
        }

        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Groupe supprime.');
    }

    private function requireAdmin(Group $group): void
    {
        $userId = Auth::id();
        $isAppAdmin = Auth::user()->role === 'admin';
        $isGroupAdmin = $group->members()->wherePivot('role', 'admin')->where('user_id', $userId)->exists();

        if (! $isGroupAdmin && ! $isAppAdmin) {
            abort(403);
        }
    }
}
