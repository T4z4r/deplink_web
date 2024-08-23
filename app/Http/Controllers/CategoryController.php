<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // 'created_by' => 'required|exists:users,id',
    ]);

    if ($request->hasFile('icon')) {
        $iconPath = $request->file('icon')->store('icons', 'public');
        $validatedData['icon'] = $iconPath;
    }
    $validatedData['created_by']=Auth::user()->id;

    $category = Category::create($validatedData);
    return response()->json($category, 201);
}


    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:active,inactive',
            'icon' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:2048', // Validate icon if it's a file
        ]);

        // Handle the file upload
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $path = $file->store('icons', 'public');
            $validatedData['icon'] = $path;
        }

        $category->update($validatedData);
        return response()->json($category);
    }


    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
