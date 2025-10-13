<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Song $song):JsonResponse
    {
        $validated = $request->validate([
           'spotify_url' => 'nullable|url',
            'apple_music_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'langit_musik_url' => 'nullable|url',
            'link_fire_url' => 'nullable|url',
            'trebel_url' => 'nullable|url',
            'youtube_musik_url' => 'nullable|url',
        ]);

        $link = $song->links()->create($validated);

       return response()->json([
            'message'=>'link created success',
            'link'=>$link
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
