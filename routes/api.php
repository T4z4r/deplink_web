<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\TeamConversationController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\ProjectRequirementController;


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/email/verification-notification', [AuthenticationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');

Route::get('/users', [AuthenticationController::class, 'users']);


Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);


    // Fetch tasks for a project
    Route::get('/projects/{project}/tasks', [TaskController::class, 'getProjectTasks']);

    // Fetch requirements for a project
    Route::get('/projects/{project}/requirements', [ProjectRequirementController::class, 'getProjectRequirements']);
    Route::get('projects/{project}/requirements', [ProjectRequirementController::class, 'index']);
    Route::post('projects/{project}/requirements', [ProjectRequirementController::class, 'store']);
    Route::put('requirements/{requirement}', [ProjectRequirementController::class, 'update']);
    Route::delete('requirements/{requirement}', [ProjectRequirementController::class, 'destroy']);


    // For Teams
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('teams/{id}', [TeamController::class, 'update']);
    Route::delete('teams/{id}', [TeamController::class, 'destroy']);
    Route::get('teams/{team}/members', [TeamController::class, 'getTeamMembers']);

    // Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember']);
    // Route::post('/teams/{team}/remove-member', [TeamController::class, 'removeMember']);

    // Route::get('/teams/{team}/conversations', [TeamConversationController::class, 'index']);
    // Route::post('/teams/{team}/conversations', [TeamConversationController::class, 'store']);


    Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::get('files/{filename}', [MessageController::class, 'downloadFile']); // Add this line
});

Route::prefix('teams')->group(function () {
    // Route::get('/', [TeamController::class, 'index']);
    Route::post('{teamId}/add-members', [TeamController::class, 'addMembers']);
    Route::post('{teamId}/remove-members', [TeamController::class, 'removeMembers']);

    Route::prefix('{teamId}/conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::post('/', [ConversationController::class, 'store']);
    });
});
