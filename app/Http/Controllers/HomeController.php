<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $usersToSwipe = User::where('id', '!=', $user->id)
            ->where('blacklisted', false)
            ->whereDoesntHave('matchesAsUser1', function ($q) use ($user) {
                $q->where('user_2_id', $user->id);
            })
            ->whereDoesntHave('matchesAsUser2', function ($q) use ($user) {
                $q->where('user_1_id', $user->id);
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('home', compact('user', 'usersToSwipe'));
    }
}
