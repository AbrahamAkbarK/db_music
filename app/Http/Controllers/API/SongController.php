<?php

namespace App\Http\Controllers\API;

use App\Models\Song;
use App\Models\Contract;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Song::with(['album', 'artist', 'composer']);

        // Filter by album
        if ($request->has('album_id')) {
            $query->where('album_id', $request->album_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        // Filter explicit content
        if ($request->has('explicit')) {
            $query->where('is_explicit', $request->boolean('explicit'));
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $songs = $query->paginate($request->per_page ?? 10);

        return response()->json($songs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'album_id' => 'required|exists:albums,id',
            'artist_id' => 'required|exists:artists,id',
            'composer_id' => 'required|exists:composers,id',
            'track_number' => 'nullable|integer|min:1',
            'duration_seconds' => 'nullable|integer|min:1',
            'genre' => 'nullable|string|max:255',
            'lyrics' => 'nullable|string',
            'isrc_code' => 'nullable|string|unique:songs,isrc_code',
            'audio_file_path' => 'nullable|string|max:255',
            'demo_file_path' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'status' => 'in:draft,recorded,mixed,mastered,released',
            'composer' => 'nullable|string|max:255',
            'lyricist' => 'nullable|string|max:255',
            'arranger' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'royalty_contract' => 'nullable|string|max:255',
            'is_explicit' => 'boolean',
        ]);

        // Check if track number already exists for this album
        // $existingTrack = Song::where('album_id', $validated['album_id'])
        //                     ->where('track_number', $validated['track_number'])
        //                     ->first();

        // if ($existingTrack) {
        //     return response()->json([
        //         'message' => 'Track number already exists for this album'
        //     ], 422);
        // }

        $validated['slug'] = Str::slug($validated['title']);
        $song = Song::create($validated);

        // Update album total duration and track count
        // $song->album->updateTotalDuration();

        return response()->json([
            'message' => 'Song created successfully',
            'data' => $song->load(['album', 'artists'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Song $song): JsonResponse
    {
        // Laravel has already found the song via route model binding.

        // Eager load the 'composer' and 'artist' relationships.
        // This attaches the related models to the song object efficiently.
        $song->load(['composer', 'artist', 'album', 'link', 'contracts']);

        return response()->json($song);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Song $song): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'slug'  => 'required|alpha_dash|unique:songs,slug,' . $song->id,
            'track_number' => 'integer|min:1',
            'duration_seconds' => 'integer|min:1',
            'genre' => 'nullable|string|max:255',
            'lyrics' => 'nullable|string',
            'isrc_code' => 'nullable|string|unique:songs,isrc_code,' . $song->id,
            'audio_file_path' => 'nullable|string|max:255',
            'demo_file_path' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'status' => 'in:draft,recorded,mixed,mastered,released',
            'composer' => 'nullable|string|max:255',
            'lyricist' => 'nullable|string|max:255',
            'arranger' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'royalty_contract' => 'nullable|string|max:255',
            'is_explicit' => 'boolean',
        ]);

        // Check track number uniqueness for the album if being updated
        if (isset($validated['track_number'])) {
            $existingTrack = Song::where('album_id', $song->album_id)
                ->where('track_number', $validated['track_number'])
                ->where('id', '!=', $song->id)
                ->first();

            if ($existingTrack) {
                return response()->json([
                    'message' => 'Track number already exists for this album'
                ], 422);
            }
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $song->update($validated);

        // Update album total duration if duration changed
        if (isset($validated['duration_seconds'])) {
            $song->album->updateTotalDuration();
        }

        return response()->json([
            'message' => 'Song updated successfully',
            'data' => $song->fresh()->load(['album', 'artists'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song): JsonResponse
    {
        $album = $song->album;
        $song->delete();

        // Update album total duration after song deletion
        $album->updateTotalDuration();

        return response()->json([
            'message' => 'Song deleted successfully'
        ]);
    }

    public function artists(Song $song): JsonResponse
    {
        $artists = $song->artists()->withPivot('role')->get();

        return response()->json(['data' => $artists]);
    }

    public function attachArtist(Request $request, Song $song): JsonResponse
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'role' => 'required|in:main_artist,featured_artist,composer,lyricist,producer'
        ]);

        $song->artists()->attach($validated['artist_id'], ['role' => $validated['role']]);

        return response()->json([
            'message' => 'Artist attached to song successfully',
            'data' => $song->fresh()->load('artists')
        ]);
    }

    public function streamingStats(Song $song): JsonResponse
    {
        $stats = $song->streamingStats()
            ->selectRaw('
                platform,
                SUM(play_count) as total_plays,
                SUM(unique_listeners) as total_listeners,
                SUM(revenue_generated) as total_revenue,
                DATE_FORMAT(stats_date, "%Y-%m") as month
            ')
            ->groupBy(['platform', 'month'])
            ->orderBy('month', 'desc')
            ->get();

        return response()->json(['data' => $stats]);
    }


    public function getTotalSongs()
    {
        $totalSongs = Song::count();  // Simple count of all songs
        return response()->json([
            'total_songs' => $totalSongs,
            'message' => 'Total songs retrieved successfully',
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }
        $songs = Song::where('title', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();
        return response()->json($songs);
    }

    public function expiredContracts(Request $request): JsonResponse
    {
        $query = Song::query();

        $query->when($request->input('status'), function ($q, $status) {
            // "Hanya ambil lagu-lagu YANG MEMILIKI ('whereHas') relasi 'contracts'
            // DI MANA ('where') kolom 'status' pada salah satu kontrak tersebut
            // sama dengan nilai yang diberikan"
            return $q->whereHas('contracts', function ($subQuery) use ($status) {
                $subQuery->where('status', $status);
            });
        });

        $songs = $query->with(['contracts', 'artist', 'composer'])
            ->latest()
            ->paginate(5);

        return response()->json($songs);
    }
}
