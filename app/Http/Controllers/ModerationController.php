<?php

namespace App\Http\Controllers;

use App\Models\Match_;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModerationController extends Controller
{
    public function report(Request $request, User $user)
    {
        abort_if($user->id === Auth::id(), 422, 'Impossible de signaler votre propre compte.');

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        UserReport::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $user->id,
            'reason' => trim($validated['reason']),
        ]);

        return response()->json([
            'status' => 'reported',
            'message' => 'Le signalement a ete envoye a l administration.',
        ]);
    }

    public function block(User $user)
    {
        $authId = Auth::id();

        abort_if($user->id === $authId, 422, 'Impossible de bloquer votre propre compte.');

        DB::transaction(function () use ($authId, $user) {
            UserBlock::firstOrCreate([
                'blocker_id' => $authId,
                'blocked_id' => $user->id,
            ]);

            $matches = Match_::query()
                ->where(function ($query) use ($authId, $user) {
                    $query->where('user_1_id', $authId)->where('user_2_id', $user->id);
                })
                ->orWhere(function ($query) use ($authId, $user) {
                    $query->where('user_1_id', $user->id)->where('user_2_id', $authId);
                })
                ->with('chat.messages')
                ->get();

            foreach ($matches as $match) {
                if ($match->chat) {
                    $match->chat->messages()->delete();
                    $match->chat->delete();
                }

                $match->delete();
            }

            Notification::query()
                ->where(function ($query) use ($authId, $user) {
                    $query->where('user_id', $authId)->where('from_user_id', $user->id);
                })
                ->orWhere(function ($query) use ($authId, $user) {
                    $query->where('user_id', $user->id)->where('from_user_id', $authId);
                })
                ->delete();
        });

        return response()->json([
            'status' => 'blocked',
            'message' => 'Ce profil a ete bloque.',
        ]);
    }

    public function unblock(User $user)
    {
        UserBlock::query()
            ->where('blocker_id', Auth::id())
            ->where('blocked_id', $user->id)
            ->delete();

        return back()->with('success', 'Utilisateur debloque.');
    }
}
