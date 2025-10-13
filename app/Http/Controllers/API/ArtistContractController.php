<?php

namespace App\Http\Controllers\Api;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ArtistContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Artist $artist)
    {
        return $artist->contracts()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Artist $artist):JsonResponse
    {
        // The validation logic is identical to adding a contract to a song
        $validated = $request->validate([
            'contract_number' => ['required', 'string', 'max:255', 'unique:contracts,contract_number'],
            'contract_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Create the contract using the polymorphic relationship
        $contract = $artist->contracts()->create($validated);

        return response()->json($contract)
                ->setStatusCode(201);
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
