<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $me     = Auth::user()->load('interets');

        // Mark all message/match notifications as read
        Notification::where('user_id', $userId)
            ->where('lu', false)
            ->whereIn('type', ['message', 'match'])
            ->update(['lu' => true]);

        // Active chats
        $chats = Chat::where('request_statut', 'accepte')
            ->whereHas('match', function ($q) use ($userId) {
                $q->where(function ($q2) use ($userId) {
                    $q2->where('user_1_id', $userId)
                       ->orWhere('user_2_id', $userId);
                });
            })
            ->with(['match.user1.interets', 'match.user2.interets'])
            ->get();

        // Pending requests received — load sender's interests for match score
        $pendingRequests = Chat::where('request_statut', 'en_attente')
            ->whereHas('match', function ($q) use ($userId) {
                $q->where('user_2_id', $userId);
            })
            ->with(['match.user1.interets', 'messages' => function ($q) {
                $q->oldest('date_envoi');
            }])
            ->get()
            ->map(function ($chat) use ($me) {
                $chat->matchScore = $me->matchScore($chat->match->user1);
                return $chat;
            });

        // Requests sent by current user
        $sentRequests = Chat::where('request_statut', 'en_attente')
            ->whereHas('match', function ($q) use ($userId) {
                $q->where('user_1_id', $userId);
            })
            ->with(['match.user2.interets', 'messages' => function ($q) {
                $q->oldest('date_envoi');
            }])
            ->get()
            ->map(function ($chat) use ($me) {
                $chat->matchScore = $me->matchScore($chat->match->user2);
                return $chat;
            });

        return view('chats', compact('chats', 'pendingRequests', 'sentRequests'));
    }

    public function show(Chat $chat)
    {
        $userId = Auth::id();
        $match  = $chat->match;

        if ($match->user_1_id !== $userId && $match->user_2_id !== $userId) {
            abort(403);
        }

        if ($chat->request_statut === 'refuse') {
            abort(404);
        }

        Message::where('chat_id', $chat->id)
            ->where('user_id', '!=', $userId)
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages  = $chat->messages()->with('user')->orderBy('date_envoi')->get();
        $otherUser = $match->user_1_id === $userId ? $match->user2 : $match->user1;
        $isRecipient = $match->user_2_id === $userId;

        // Load interests for match score display
        Auth::user()->load('interets');
        $otherUser->load('interets');
        $matchScore = Auth::user()->matchScore($otherUser);

        return view('chat_detail', compact('chat', 'messages', 'otherUser', 'isRecipient', 'matchScore'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate(['contenu' => 'required|string|max:2000']);

        $userId = Auth::id();
        $match  = $chat->match;

        if ($match->user_1_id !== $userId && $match->user_2_id !== $userId) {
            abort(403);
        }

        if ($chat->request_statut !== 'accepte') {
            return back()->with('error', 'La conversation n\'est pas encore active.');
        }

        Message::create([
            'chat_id'    => $chat->id,
            'user_id'    => $userId,
            'contenu'    => $request->contenu,
            'date_envoi' => now(),
            'lu'         => false,
        ]);

        $otherId = $match->user_1_id === $userId ? $match->user_2_id : $match->user_1_id;

        // Deduplicate: update existing unread message notif from this sender, or create one
        $existing = Notification::where('user_id', $otherId)
            ->where('from_user_id', $userId)
            ->where('type', 'message')
            ->where('lu', false)
            ->first();

        if ($existing) {
            $existing->update([
                'contenu' => Auth::user()->prenom . ' t\'a envoyé un nouveau message.',
                'updated_at' => now(),
            ]);
        } else {
            Notification::create([
                'user_id'      => $otherId,
                'from_user_id' => $userId,
                'titre'        => 'Nouveau message',
                'contenu'      => Auth::user()->prenom . ' t\'a envoyé un message.',
                'type'         => 'message',
            ]);
        }

        return redirect()->route('chats.show', $chat);
    }

    public function respondToRequest(Request $request, Chat $chat)
    {
        $request->validate(['action' => 'required|in:accept,decline']);

        $userId = Auth::id();
        $match  = $chat->match;

        if ($match->user_2_id !== $userId) {
            abort(403);
        }

        if ($request->action === 'accept') {
            $chat->update(['request_statut' => 'accepte']);

            Notification::create([
                'user_id'      => $match->user_1_id,
                'from_user_id' => $userId,
                'titre'        => 'Demande acceptée!',
                'contenu'      => Auth::user()->prenom . ' a accepté ta demande de message.',
                'type'         => 'match',
            ]);

            return redirect()->route('chats.show', $chat);
        }

        $chat->update(['request_statut' => 'refuse']);

        Notification::create([
            'user_id'      => $match->user_1_id,
            'from_user_id' => $userId,
            'titre'        => 'Demande refusée',
            'contenu'      => 'Ta demande de message n\'a pas été acceptée.',
            'type'         => 'systeme',
        ]);

        return redirect()->route('chats');
    }
}
