<?php

namespace App\Http\Controllers;

use App\Models\Match_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;

        // ── Swipes I made (I'm always user_1 when I swipe) ──
        $myLikes = Match_::where('user_1_id', $userId)
            ->whereIn('statut', ['en_attente', 'accepte'])
            ->with('user2.interets')
            ->latest()
            ->get();

        $myPasses = Match_::where('user_1_id', $userId)
            ->where('statut', 'refuse')
            ->with('user2')
            ->latest()
            ->get();

        // ── People who liked me (they're user_1) ──
        $incomingLikes = Match_::where('user_2_id', $userId)
            ->whereIn('statut', ['en_attente', 'accepte'])
            ->with('user1')
            ->latest()
            ->get();

        // ── Mutual matches ──
        $mutualMatches = Match_::where(function ($q) use ($userId) {
            $q->where('user_1_id', $userId)->orWhere('user_2_id', $userId);
        })
        ->where('statut', 'accepte')
        ->with(['user1', 'user2'])
        ->latest()
        ->get();

        // ── Stats ──
        $totalLikes    = $myLikes->count();
        $totalPasses   = $myPasses->count();
        $totalIncoming = $incomingLikes->count();
        $totalMatches  = $mutualMatches->count();
        $totalSwipes   = $totalLikes + $totalPasses;

        $acceptanceRate = $totalLikes  > 0 ? (int) round($totalMatches  / $totalLikes  * 100) : 0;
        $likeRatio      = $totalSwipes > 0 ? (int) round($totalLikes    / $totalSwipes * 100) : 0;
        $returnRate     = $totalLikes  > 0 ? (int) round($totalIncoming / $totalLikes  * 100) : 0;

        // ── Full history (all swipes I made, newest first) ──
        $history = Match_::where('user_1_id', $userId)
            ->with('user2')
            ->latest()
            ->limit(50)
            ->get();

        return view('dashboard', compact(
            'user',
            'myLikes', 'myPasses', 'incomingLikes', 'mutualMatches',
            'totalLikes', 'totalPasses', 'totalIncoming', 'totalMatches',
            'totalSwipes', 'acceptanceRate', 'likeRatio', 'returnRate',
            'history'
        ));
    }

    /**
     * Undo a swipe (delete the match record so the person reappears in discovery).
     * Only works for 'refuse' (pass) and 'en_attente' (pending like I sent).
     */
    public function undo(Match_ $match)
    {
        $userId = Auth::id();

        abort_if($match->user_1_id !== $userId, 403);
        abort_if(! in_array($match->statut, ['refuse', 'en_attente']), 403);

        // Cancel pending message request: delete chat + messages first
        if ($match->statut === 'en_attente' && $match->chat) {
            $match->chat->messages()->delete();
            $match->chat->delete();
        }

        $match->delete();

        return back()->with('success', 'Swipe annulé — ce profil réapparaîtra dans la découverte.');
    }
}
