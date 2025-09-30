<?php

namespace App\Http\Controllers\API;

use App\Models\Playlist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Playlist::with(['songs']);

        // Filter by visibility
        if ($request->has('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $playlists = $query->paginate($request->per_page ?? 15);

        return response()->json($playlists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
            'visibility' => 'in:public,private,unlisted',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $playlist = Playlist::create($validated);

        return response()->json([
            'message' => 'Playlist created successfully',
            'data' => $playlist
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Playlist $playlist): JsonResponse
    {
        return response()->json([
            'data' => $playlist->load(['songs.artists', 'songs.album'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Playlist $playlist): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:255',
            'visibility' => 'in:public,private,unlisted',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $playlist->update($validated);

        return response()->json([
            'message' => 'Playlist updated successfully',
            'data' => $playlist->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Playlist $playlist): JsonResponse
    {
        $playlist->delete();

        return response()->json([
            'message' => 'Playlist deleted successfully'
        ]);
    }

    public function songs(Playlist $playlist): JsonResponse
    {
        $songs = $playlist->songs()
            ->with(['artists', 'album'])
            ->withPivot('position', 'added_at')
            ->get();

        return response()->json(['data' => $songs]);
    }

    public function addSong(Request $request, Playlist $playlist): JsonResponse
    {
        $validated = $request->validate([
            'song_id' => 'required|exists:songs,id',
            'position' => 'nullable|integer|min:0'
        ]);

        $position = $validated['position'] ?? ($playlist->songs()->count() + 1);

        $playlist->songs()->attach($validated['song_id'], [
            'position' => $position,
            'added_at' => now()
        ]);

        $playlist->updateStats();

        return response()->json([
            'message' => 'Song added to playlist successfully',
            'data' => $playlist->fresh()->load('songs')
        ]);
    }

    public function removeSong(Playlist $playlist, $songId): JsonResponse
    {
        $playlist->songs()->detach($songId);
        $playlist->updateStats();

        return response()->json([
            'message' => 'Song removed from playlist successfully'
        ]);
    }
}
