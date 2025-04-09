<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    // Get all movies
    public function getAll()
    {
        $movies = Movie::join('directors', 'movies.director_id', '=', 'directors.id')
            ->select(
                'movies.id',
                'title',
                'release_date',
                'rating',
                'movie_image',
                'directors.name as director_name'
            )
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json($movies);
    }

    // Get a single movie with cast
    public function getOne($id)
    {
        $movie = Movie::join('directors', 'movies.director_id', '=', 'directors.id')
            ->select(
                'movies.id',
                'title',
                'description',
                'movie_image',
                'rating',
                'release_date',
                'directors.name as director_name',
                'directors.bio'
            )
            ->where('movies.id', '=', $id)
            ->first();

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $cast = DB::table('movie_cast')
            ->join('cast_members', 'movie_cast.cast_member_id', '=', 'cast_members.id')
            ->where('movie_cast.movie_id', '=', $id)
            ->pluck('cast_members.name');

        $movie->cast = $cast;

        return response()->json($movie);
    }

    // Save a new movie
    public function save(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'movie_image' => 'required',
            'rating' => 'required|numeric',
            'release_date' => 'required|date',
            'director_id' => 'required|exists:directors,id',
        ]);

        $movie = Movie::create($request->all());

        return response()->json($movie, 201);
    }

    // Update a movie
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'movie_image' => 'required',
            'rating' => 'required|numeric',
            'release_date' => 'required|date',
            'director_id' => 'required|exists:directors,id',
        ]);

        $movie->update($request->all());

        return response()->json($movie);
    }

    // Delete a movie
    public function delete($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return response()->json(null, 204);
    }
}
