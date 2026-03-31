<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $chats = Chat::whereHas('match', function ($q) use ($userId) {
            $q->where('statut', 'accepte')
              ->where(function ($q2) use ($userId) {
                  $q2->where('user_1_id', $userId)
                     ->orWhere('user_2_id', $userId);
              });
        })->with(['match.user1', 'match.user2'])->get();

        return view('chats', compact('chats'));
    }
}
