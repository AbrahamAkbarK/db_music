<?php

namespace App\Http\Controllers\API;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Album::with(['artists', 'songs']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        // Filter by release year
        if ($request->has('year')) {
            $query->whereYear('release_date', $request->year);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'release_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $albums = $query->paginate($request->per_page ?? 10);

        return response()->json($albums);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'subgenre' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'type' => 'required|in:album,ep,single,compilation',
            'cover_image' => 'nullable|string|max:255',
            'upc_code' => 'nullable|string|unique:albums,upc_code',
            'price' => 'nullable|numeric|min:0',
            'status' => 'in:draft,scheduled,released,archived',
            'producer' => 'nullable|string|max:255',
            'record_label' => 'nullable|string|max:255',
            'recording_studio' => 'nullable|string|max:255',
            'recording_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $album = Album::create($validated);

        return response()->json([
            'message' => 'Album created successfully',
            'data' => $album->load(['artists', 'songs'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album): JsonResponse
    {
        return response()->json([
            'data' => $album->load([
                'artists',
                'songs.artists',
                'streamingStats',
                'sales'
            ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'subgenre' => 'nullable|string|max:255',
            'release_date' => 'date',
            'type' => 'in:album,ep,single,compilation',
            'cover_image' => 'nullable|string|max:255',
            'upc_code' => 'nullable|string|unique:albums,upc_code,' . $album->id,
            'price' => 'nullable|numeric|min:0',
            'status' => 'in:draft,scheduled,released,archived',
            'producer' => 'nullable|string|max:255',
            'record_label' => 'nullable|string|max:255',
            'recording_studio' => 'nullable|string|max:255',
            'recording_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $album->update($validated);

        // Update total duration and tracks if songs changed
        $album->updateTotalDuration();

        return response()->json([
            'message' => 'Album updated successfully',
            'data' => $album->fresh()->load(['artists', 'songs'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Album $album): JsonResponse
    {
        $album->delete();

        return response()->json([
            'message' => 'Album deleted successfully'
        ]);
    }

    public function songs(Album $album): JsonResponse
    {
        $songs = $album->songs()->with(['artists'])->get();

        return response()->json(['data' => $songs]);
    }

    public function artists(Album $album): JsonResponse
    {
        $artists = $album->artists()->withPivot('role')->get();

        return response()->json(['data' => $artists]);
    }

    public function attachArtist(Request $request, Album $album): JsonResponse
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'role' => 'required|in:main_artist,featured_artist,producer,composer,guest'
        ]);

        $album->artists()->attach($validated['artist_id'], ['role' => $validated['role']]);

        return response()->json([
            'message' => 'Artist attached to album successfully',
            'data' => $album->fresh()->load('artists')
        ]);
    }

    public function detachArtist(Album $album, $artistId): JsonResponse
    {
        $album->artists()->detach($artistId);

        return response()->json([
            'message' => 'Artist detached from album successfully'
        ]);
    }
}
