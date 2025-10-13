<?php

namespace App\Http\Controllers\Api;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ArtistMemberController extends Controller
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
    public function store(Request $request, Artist $artist)
    {
        // 1. Validate the incoming data
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Ensure the name is unique for THIS artist, but can be the same for other artists.
                Rule::unique('members')->where('artist_id', $artist->id)
            ],
            'phone' => 'nullable|string|max:20|unique:members,phone',
            'email' => 'nullable|email|max:255|unique:members,email',
        ]);

        // 2. Create the member using the relationship to automatically set the artist_id
        $member = $artist->members()->create($validatedData);

        // 3. Return the new member data with a "201 Created" status
        return response()->json([
            'message'=>'member created success',
            'member'=>$member
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
