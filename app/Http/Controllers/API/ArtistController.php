<?php

namespace App\Http\Controllers\API;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Artist::with(['albums', 'songs']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('stage_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $artists = $query->latest()->paginate($request->per_page ?? 10);

        return response()->json($artists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage_name' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|unique:artists,email',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'category' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'spotify_url' => 'nullable|url',
            'apple_music_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'profile_image' => 'nullable|string|max:255',
            'status' => 'in:active,inactive,on_hold',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
        ]);

        $artist = Artist::create($validated);

        return response()->json([
            'message' => 'Artist created successfully',
            'data' => $artist->load(['albums', 'contracts'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist): JsonResponse  // Route model binding: Auto-finds by ID
    {
        // Eager load relationships
        // $artist->load(['songs' => function ($query) {
        //     $query->with(['composers:id,name']);  // Now safe if composers() defined
        // }]);
        // Add computed counts
        // $artist->append(['songs_count']);
        $artist->load(['albums', 'songs', 'members']);
        return response()->json([
            'message' => 'Artist retrieved successfully',
            'artist' => $artist,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'stage_name' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|unique:artists,email,' . $artist->id,
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'category' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'spotify_url' => 'nullable|url',
            'apple_music_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'profile_image' => 'nullable|string|max:255',
            'status' => 'in:active,inactive,on_hold',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
        ]);

        $artist->update($validated);

        return response()->json([
            'message' => 'Artist updated successfully',
            'data' => $artist->fresh()->load(['albums', 'contracts'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist): JsonResponse
    {
        $artist->delete();

        return response()->json([
            'message' => 'Artist deleted successfully'
        ]);
    }


    public function albums(Artist $artist): JsonResponse
    {
        $albums = $artist->albums()
            ->with(['songs', 'streamingStats'])
            ->withPivot('role')
            ->get();

        return response()->json(['data' => $albums]);
    }

    public function songs(Artist $artist): JsonResponse
    {
        $songs = $artist->songs()
            ->with(['album', 'streamingStats'])
            ->withPivot('role')
            ->get();

        return response()->json(['data' => $songs]);
    }

    public function stats(Artist $artist): JsonResponse
    {
        $stats = [
            'total_albums' => $artist->albums()->count(),
            'total_songs' => $artist->songs()->count(),
            'total_streams' => $artist->streamingStats()->sum('play_count'),
            'total_revenue' => $artist->sales()->sum('total_amount'),
            'active_contracts' => $artist->contracts()->where('status', 'active')->count(),
            'top_songs' => $artist->songs()
                ->with(['album', 'streamingStats'])
                ->withSum('streamingStats', 'play_count')
                ->orderBy('streaming_stats_sum_play_count', 'desc')
                ->limit(5)
                ->get(),
            'monthly_streams' => $artist->streamingStats()
                ->selectRaw('DATE_FORMAT(stats_date, "%Y-%m") as month, SUM(play_count) as streams')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
        ];

        return response()->json(['data' => $stats]);
    }

    public function getArtistsByCategoryCount()
    {
        $artistCounts = Artist::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->whereNotNull('category')  // Exclude null categories
            ->orderBy('count', 'desc')
            ->get();
        return response()->json($artistCounts);
    }

    public function getUpcomingBirthdays(Request $request, int $days = 90)
    {
        $today = now();
        $endDate = now()->addDays($days);

        $artists = Artist::query()
            ->where(function ($query) use ($today, $endDate) {
                $todayFormatted = $today->format('m-d');
                $endDateFormatted = $endDate->format('m-d');

                // Ganti DATE_FORMAT menjadi strftime
                $dateFormatRaw = "strftime('%m-%d', birth_date)";

                if ($today->year === $endDate->year) {
                    $query->whereRaw("$dateFormatRaw BETWEEN ? AND ?", [$todayFormatted, $endDateFormatted]);
                } else {
                    $query->where(function ($query) use ($todayFormatted, $endDateFormatted, $dateFormatRaw) {
                        $query->whereRaw("$dateFormatRaw >= ?", [$todayFormatted])
                              ->orWhereRaw("$dateFormatRaw <= ?", [$endDateFormatted]);
                    });
                }
            })
            // Ganti juga di sini untuk sorting
            ->orderByRaw("CASE
                WHEN strftime('%m-%d', birth_date) >= ? THEN 1
                ELSE 2
            END", [$today->format('m-d')])
            // Dan di sini
            ->orderByRaw("strftime('%m-%d', birth_date) ASC")
            ->get();

        return response()->json($artists);
    }



}
