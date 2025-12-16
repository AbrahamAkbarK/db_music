<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use App\Models\Artist;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Supports filtering by status and type.
     * Examples:
     * - GET /api/contracts
     * - GET /api/contracts?status=expired
     * - GET /api/contracts?type=artist
     * - GET /api/contracts?type=song&status=active
     */
    public function index(Request $request):JsonResponse
    {
        // Start with a base query and eager-load the parent relationship
        $query = Contract::query()->with('contractable');

        // Filter by contract status (e.g., 'active', 'draft', 'expired')
        $query->when($request->filled('status'), function ($q) use ($request) {
            $status = $request->query('status');
            if ($status === 'expired') {
                // Use the custom 'expired' query scope defined in the Contract model
                return $q->expired();
            }
            return $q->where('status', $status);
        });

        // Filter by the type of parent model (Artist or Song)
        $query->when($request->filled('type'), function ($q) use ($request) {
            $type = $request->query('type');
            if ($type === 'artist') {
                return $q->where('contractable_type', Artist::class);
            }
            if ($type === 'song') {
                return $q->where('contractable_type', Song::class);
            }
        });

        // Paginate the results and ensure filter parameters are kept on pagination links.
        $contracts = $query->latest()->paginate(20)->withQueryString();

        return response()->json($contracts);
    }

    public function store(Request $request, Song $song)
    {
        $validatedData = $request->validate([
            'contract_number' => 'required|string|max:255',
            'status' => ['required', Rule::in(['draft', 'active', 'expired',])],
            'contract_type' => [ 'required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $contract = $song->contracts()->create($validatedData);

        return response()->json([
            'message' => 'Successfully created contract',
            'data' => $contract
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * Eager-loads the contract's parent (contractable) and any associated parties.
     */
    public function show(Contract $contract):JsonResponse
    {
        $contract->load('contractable.link');

        return response()->json($contract);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validatedData = $request->validate([
            'contract_number' => [
                'sometimes', // 'sometimes' means only validate if present in the request
                'required',
                'string',
                'max:255',
                // Ensure the contract number is unique, but ignore the current contract's ID
                Rule::unique('contracts')->ignore($contract->id),
            ],
            'contract_type' => ['sometimes', 'required', 'string', 'max:255'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'active', 'expired'])],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $contract->update($validatedData);

        // Load relationships to ensure the returned resource is complete.
        $contract->load('contractable');

        return response()->json($contract);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        // Optional: Add authorization logic here to ensure the user has permission.
        // $this->authorize('delete', $contract);

        $contract->delete();

        // Return a standard 204 No Content response on successful deletion.
        return response()->noContent();
    }
}
