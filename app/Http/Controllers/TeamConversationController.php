<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamConversationController extends Controller
{
    public function index(Team $team)
    {
        $conversations = $team->conversations()->with('user')->get();
        return response()->json($conversations);
    }

    public function store(Request $request, Team $team)
    {
        $conversation = $team->conversations()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json($conversation);
    }
}
