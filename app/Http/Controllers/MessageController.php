<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('user')->get();
        return response()->json($messages);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file', // Add validation for file
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('files'); // Store file in storage/app/files
        }

        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'file_path' => $filePath,
        ]);

        return response()->json($message, 201);
    }

    public function downloadFile($filename)
    {
        $path = storage_path("app/files/$filename");
        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
