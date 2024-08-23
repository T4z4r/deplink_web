<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function show($id)
    {
        return Project::findOrFail($id);
    }

    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        // Log::info('Request Data:', $request->all());

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required',
            'end_date' => 'nullable',
            'status' => 'required|in:pending,cancelled,completed',
            'label' => 'required|in:personal,office',
            'client' => 'nullable|string',
        ]);

        // Assign the authenticated user's ID to the 'created_by' field
        $validatedData['created_by'] = Auth::id();
        // $validatedData['type'] = "office";

        // Create a new project record
        $project = Project::create($validatedData);

        // Return the created project with a 201 status code
        return response()->json($project, 201);
    }


    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,cancelled,completed',
            'label' => 'sometimes|required|in:personal,office',
            'client' => 'nullable|string',
            // 'created_by' => 'sometimes|required|exists:users,id',
        ]);

        $project->update($validatedData);
        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json(null, 204);
    }
}
