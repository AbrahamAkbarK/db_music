<?php

namespace App\Http\Controllers\API;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Composer;

class AttachmentController extends Controller
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

    //     Log::info('--- UPLOAD ATTACHMENT CHECK ---');
    // Log::info('Request All:', $request->all());
    // Log::info('Request hasFile attachment:', [$request->hasFile('attachment')]);
    // Log::info('Request file object:', [$request->file('attachment')]);
    // Log::info('-------------------------------');

        $request->validate([
            'attachment' => 'required|file|max:10240' // Cth: file, max 10MB
        ]);

        $file = $request->file('attachment');

        // Simpan file ke disk 'public' di dalam folder 'attachments'
        $path = $file->store('attachments', 'public');

        $attachment = $song->attachments()->create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path'      => $path,
            'file_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
        ]);

        return response()->json($attachment, 201);
    }

    public function storeForArtist(Request $request, Artist $artist)
    {

    //     Log::info('--- UPLOAD ATTACHMENT CHECK ---');
    // Log::info('Request All:', $request->all());
    // Log::info('Request hasFile attachment:', [$request->hasFile('attachment')]);
    // Log::info('Request file object:', [$request->file('attachment')]);
    // Log::info('-------------------------------');

        $request->validate([
            'attachment' => 'required|file|max:10240' // Cth: file, max 10MB
        ]);

        $file = $request->file('attachment');

        // Simpan file ke disk 'public' di dalam folder 'attachments'
        $path = $file->store('attachments', 'public');

        $attachment = $artist->attachments()->create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path'      => $path,
            'file_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
        ]);

        return response()->json($attachment, 201);
    }

    public function storeForComposer(Request $request, Composer $composer)
    {

    //     Log::info('--- UPLOAD ATTACHMENT CHECK ---');
    // Log::info('Request All:', $request->all());
    // Log::info('Request hasFile attachment:', [$request->hasFile('attachment')]);
    // Log::info('Request file object:', [$request->file('attachment')]);
    // Log::info('-------------------------------');

        $request->validate([
            'attachment' => 'required|file|max:10240' // Cth: file, max 10MB
        ]);

        $file = $request->file('attachment');

        // Simpan file ke disk 'public' di dalam folder 'attachments'
        $path = $file->store('attachments', 'public');

        $attachment = $composer->attachments()->create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path'      => $path,
            'file_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
        ]);

        return response()->json($attachment, 201);
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
