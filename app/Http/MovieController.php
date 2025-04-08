<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie; 

class MovieController extends Controller
{
    // Fetch all movies
    public function index()
    {
        $movies = Movie::all()->map(function ($movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description,
                'poster' => $movie->poster, // Ensure the 'poster' column exists in your database
            ];
        });
    
        return response()->json(['movies' => $movies]);
    }

    // Store a new movie
    public function store(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster' => 'required|image|mimes:jpg,png,jpeg|max:2048', // Ensure it's an image
        ]);
    
        // Store the uploaded file
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public'); // Save in 'storage/app/public/posters'
        } else {
            return response()->json([
                'message' => 'Failed to upload poster.',
            ], 400);
        }
    
        // Create movie record
        $movie = Movie::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'poster' => $posterPath, // Store the file path in DB
        ]);
    
        // Return JSON response
        return response()->json([
            'message' => 'Movie created successfully',
            'movie' => $movie,
        ], 201);
    }
}