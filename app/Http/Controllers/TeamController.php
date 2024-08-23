<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{

    public function getTeamMembers(Team $team)
    {
        // Fetch team members
        $members = $team->users; // Assuming you have a `members` relationship
        return response()->json($members);
    }

    public function index()
    {
        $teams = Team::with('users')->get();
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        // Optionally, add members to the team
        $team->members()->attach($request->user_ids);

        return response()->json($team);
    }

    public function addMember(Request $request, Team $team)
    {
        $team->members()->attach($request->user_id);
        return response()->json('Member added');
    }

    public function removeMember(Request $request, Team $team)
    {
        $team->members()->detach($request->user_id);
        return response()->json('Member removed');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Team::findOrFail($id);
        $team->update($request->only('name'));

        return response()->json($team);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function addMembers(Request $request, $teamId)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id', // Validate each ID is an integer and exists in users table
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // Return validation errors
        }

        // Find the team or return a 404 error if not found
        $team = Team::findOrFail($teamId);

        // Get the array of user IDs from the request
        $userIds = $request->input('user_ids');

        // Insert users into the team_members table
        foreach ($userIds as $userId) {
            DB::table('team_members')->insert([
                'team_id' => $teamId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Members added successfully']);
    }
    public function removeMembers(Request $request, $teamId)
    {
        $team = Team::findOrFail($teamId);
        $userIds = $request->input('user_ids');
        $team->users()->detach($userIds);

        return response()->json(['message' => 'Members removed successfully']);
    }

}
