<?php

namespace App\Http\Controllers\Api;

use App\Models\Composer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ComposerController extends Controller
{
    public function index(Request $request)
    {
        $query = Composer::withCount('songs')
            ->orderBy('name', 'asc');
        // Optional: Filter by nationality (e.g., ?nationality=German)
        if ($request->has('nationality')) {
            $query->where('nationality', $request->nationality);
        }
        // Optional: Search by name (e.g., ?search=Beethoven)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $composers = $query->paginate(10);  // 10 per page
        return response()->json($composers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:composers,name',
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'email' => 'nullable|email|unique:composers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $composer = Composer::create($validator->validated());

        return response()->json([
            'message' => 'Composer created successfully',
            'composer' => $composer,
        ], 201);
    }

    public function show(Composer $composer)
    {
        $composer->load([
            'songs' => function ($query) {
                $query->select('id', 'title', 'album_id', 'artist_id', 'composer_id','label','genre','isrc_code','status',)
                ->with('artist:id,name,stage_name')->paginate(10);
            }
        ]);
        $composer->append(['songs_count']);
        return response()->json([
            'message' => 'Composer retrieved successfully',
            'composer' => $composer,
        ]);
    }

    public function getSongs(Composer $composer)
    {
        // Thanks to route model binding, Laravel has already fetched the
        // composer from the database based on the ID in the URL.

        // Now, we just access the 'songs' relationship we defined earlier.
        $songs = $composer->songs()->paginate(10);

        // Return the songs as a JSON response.
        return response()->json($songs);
    }

    public function update(Request $request, Composer $composer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:composers,name,' . $composer->id,
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'email' => 'nullable|email|unique:composers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $composer->update($request->validated());

        return response()->json([
            'message' => 'Composer updated successfully',
            'composer' => $composer->fresh(),
        ]);
    }

    public function destroy(Composer $composer)
    {
        // Soft delete if using SoftDeletes trait in model
        $composer->delete();

        return response()->json([
            'message' => 'Composer deleted successfully',
        ]);
    }
}
