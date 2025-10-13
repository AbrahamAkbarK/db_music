<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SongContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Song $song)
    {
        return $song->contracts()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Song $song):JsonResponse
    {
        $validated = $request->validate([
            'contract_number' => ['required', 'string', 'max:255', 'unique:contracts,contract_number'],
            'contract_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['draft', 'active', 'expired', 'terminated'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $contract = $song->contracts()->create($validated);

        return response()->json($contract)
            ->setStatusCode(201); // 201 Created
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
