<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index($teamId)
    {
        return response()->json(Conversation::where('team_id', $teamId)->get());
    }

    public function store(Request $request, $teamId)
    {
        $conversation = Conversation::create([
            'team_id' =>  $request->input('team_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json($conversation, 201);
    }
}
