<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Match_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwipeController extends Controller
{
    public function swipe(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|in:like,pass',
        ]);

        $userId = Auth::id();
        $targetId = $request->user_id;

        if ($request->action === 'pass') {
            Match_::create([
                'user_1_id' => $userId,
                'user_2_id' => $targetId,
                'statut' => 'refuse',
            ]);

            return response()->json(['status' => 'passed']);
        }

        // Check if the other user already liked us
        $existingMatch = Match_::where('user_1_id', $targetId)
            ->where('user_2_id', $userId)
            ->where('statut', 'en_attente')
            ->first();

        if ($existingMatch) {
            $existingMatch->update(['statut' => 'accepte']);
            Chat::create(['match_id' => $existingMatch->id]);

            return response()->json(['status' => 'matched', 'message' => 'C\'est un match!']);
        }

        Match_::create([
            'user_1_id' => $userId,
            'user_2_id' => $targetId,
            'statut' => 'en_attente',
        ]);

        return response()->json(['status' => 'liked']);
    }
}
