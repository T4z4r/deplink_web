<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectRequirement;
use Illuminate\Support\Facades\Auth;

class ProjectRequirementController extends Controller
{
    public function index($projectId)
    {
        $requirements = ProjectRequirement::where('project_id', $projectId)->get();
        return response()->json($requirements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string',
            'status' => 'required|in:draft,pending,working,testing,completed',
        ]);

        $validated['created_by']=Auth::user()->id;
        $requirement = ProjectRequirement::create($validated);

        return response()->json($requirement, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:draft,pending,working,testing,completed',
        ]);

        $requirement = ProjectRequirement::findOrFail($id);
        $requirement->update($validated);

        return response()->json($requirement);
    }

    public function destroy($id)
    {
        $requirement = ProjectRequirement::findOrFail($id);
        $requirement->delete();

        return response()->json(null, 204);
    }


    public function getProjectRequirements($projectId)
    {
        // Fetch requirements associated with the given project ID
        $requirements = ProjectRequirement::where('project_id', $projectId)->get();

        return response()->json($requirements);
    }
}
