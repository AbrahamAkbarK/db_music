<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Composer;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Song $song)
    {
        $validated = $request->validate(['content' => 'required|string']);

        // Buat note baru yang langsung terhubung dengan $song
        $note = $song->notes()->create($validated);

        return response()->json($note, 201);
    }

    public function storeForArtist(Request $request, Artist $artist)
    {
        $validated = $request->validate(['content' => 'required|string']);

        // Buat note baru yang langsung terhubung dengan $song
        $note = $artist->notes()->create($validated);

        return response()->json($note, 201);
    }

    public function storeForComposer(Request $request, Composer $composer)
    {
        $validated = $request->validate(['content' => 'required|string']);

        // Buat note baru yang langsung terhubung dengan $song
        $note = $composer->notes()->create($validated);

        return response()->json($note, 201);
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
