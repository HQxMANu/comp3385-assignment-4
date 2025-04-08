<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{

    public function index()
    {
        $movies = Movie::all();

        return response()->json([
            'movies' => $movies
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster' => 'required|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first() . ' (and ' . ($validator->errors()->count() - 1) . ' more error)',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('posters'), $filename);
            $posterPath = 'posters/' . $filename;
        }

        $movie = Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'poster' => $posterPath ?? null,
        ]);

        return response()->json([
            'message' => 'Movie created successfully',
            'movie' => $movie
        ]);
    }
}