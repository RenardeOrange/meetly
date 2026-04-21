<?php

namespace App\Http\Controllers;

use App\Mail\MessageRequestMail;
use App\Models\Chat;
use App\Models\Match_;
use App\Models\Message;
use App\Models\Notification;
use App\Models\UserBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SwipeController extends Controller
{
    public function swipe(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action'  => 'required|in:like,pass',
            'message' => 'nullable|string|max:500',
        ]);

        $userId   = Auth::id();
        $targetId = $request->user_id;

        if ($userId === (int) $targetId) {
            return response()->json(['error' => 'Action invalide.'], 422);
        }

        $isBlocked = UserBlock::query()
            ->where(function ($query) use ($userId, $targetId) {
                $query->where('blocker_id', $userId)->where('blocked_id', $targetId);
            })
            ->orWhere(function ($query) use ($userId, $targetId) {
                $query->where('blocker_id', $targetId)->where('blocked_id', $userId);
            })
            ->exists();

        if ($isBlocked) {
            return response()->json(['error' => 'Ce profil n est plus disponible.'], 422);
        }

        if ($request->action === 'pass') {
            Match_::query()
                ->where('user_1_id', $userId)
                ->where('user_2_id', $targetId)
                ->where('statut', 'refuse')
                ->delete();

            Match_::create([
                'user_1_id' => $userId,
                'user_2_id' => $targetId,
                'statut'    => 'refuse',
            ]);

            return response()->json(['status' => 'passed']);
        }

        // Message is required for a like
        $message = trim($request->message ?? '');
        if ($message === '') {
            return response()->json(['error' => 'Un message est requis pour liker.'], 422);
        }

        $sender = Auth::user();

        // Check if the other user already liked us (mutual match)
        $existingMatch = Match_::where('user_1_id', $targetId)
            ->where('user_2_id', $userId)
            ->where('statut', 'en_attente')
            ->first();

        if ($existingMatch) {
            // Mutual match — accept immediately
            $existingMatch->update(['statut' => 'accepte']);
            $chat = Chat::create([
                'match_id'       => $existingMatch->id,
                'request_statut' => 'accepte',
            ]);

            Message::create([
                'chat_id'    => $chat->id,
                'user_id'    => $userId,
                'contenu'    => $message,
                'date_envoi' => now(),
                'lu'         => false,
            ]);

            // Notify both
            Notification::create([
                'user_id'      => $targetId,
                'from_user_id' => $userId,
                'titre'        => 'C\'est un match!',
                'contenu'      => $sender->prenom . ' et toi avez matché! Vérifie tes messages.',
                'type'         => 'match',
            ]);
            Notification::create([
                'user_id'      => $userId,
                'from_user_id' => $targetId,
                'titre'        => 'C\'est un match!',
                'contenu'      => 'Vous avez matché! Ton message a été envoyé.',
                'type'         => 'match',
            ]);

            return response()->json(['status' => 'matched', 'message' => 'C\'est un match!']);
        }

        // One-sided like — create pending message request
        $match = Match_::create([
            'user_1_id' => $userId,
            'user_2_id' => $targetId,
            'statut'    => 'en_attente',
        ]);

        $chat = Chat::create([
            'match_id'       => $match->id,
            'request_statut' => 'en_attente',
        ]);

        Message::create([
            'chat_id'    => $chat->id,
            'user_id'    => $userId,
            'contenu'    => $message,
            'date_envoi' => now(),
            'lu'         => false,
        ]);

        // Notify the target that they have a message request (1 notif for like + message)
        Notification::create([
            'user_id'      => $targetId,
            'from_user_id' => $userId,
            'titre'        => 'Nouvelle demande de message',
            'contenu'      => $sender->prenom . ' t\'a envoyé une demande de message.',
            'type'         => 'message',
        ]);

        // Send email if the target has an email address
        $target = \App\Models\User::find($targetId);
        if ($target && !empty($target->email)) {
            try {
                Mail::to($target->email)->send(new MessageRequestMail($sender, $message));
            } catch (\Exception $e) {
                // Mail not configured — silently skip
            }
        }

        return response()->json(['status' => 'liked']);
    }
}
