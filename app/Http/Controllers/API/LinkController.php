<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LinkController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Membuat ATAU Memperbarui record link yang terhubung ke sebuah lagu.
     */
    public function storeOrUpdate(Request $request, Song $song)
    {
        // 1. Validasi semua 9+ kolom link
        // Kita gunakan 'nullable' karena pengguna mungkin hanya mengisi beberapa
        $validatedData = $request->validate([
             'spotify_url' => 'nullable|url',
             'apple_music_url' => 'nullable|url',
             'youtube_url' => 'nullable|url',
             'instagram_url' => 'nullable|url',
             'tiktok_url' => 'nullable|url',
             'langit_musik_url' => 'nullable|url',
             'link_fire_url' => 'nullable|url',
             'trebel_url' => 'nullable|url',
             'youtube_musik_url' => 'nullable|url'
        ]);

        // 2. Logika "Update atau Create" (Upsert)
        // Ini adalah metode Eloquent yang sangat powerful.
        // Dia akan mencari record link yang terhubung ke $song.
        // Jika ada, dia akan UPDATE dengan $validatedData.
        // Jika tidak ada, dia akan CREATE record baru dengan $validatedData.
        $link = $song->link()->updateOrCreate(
            [], // Tidak perlu kondisi pencarian, relasi sudah menanganinya
            $validatedData
        );

        // 3. Kembalikan respons sukses
        return response()->json([
            'message' => 'Links berhasil disimpan.',
            'data' => $link
        ], 200); // 200 OK
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
    public function destroy(Link $link)
    {
        // Optional: Add authorization to ensure the user can delete this link
    // $this->authorize('delete', $link);

    $link->delete();

    return response()->noContent(); // 204 No Content
    }
}
