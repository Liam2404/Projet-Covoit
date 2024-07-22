<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;


class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId): JsonResponse
    {
        try {
            // Trouver l'utilisateur par ID
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Récupérer tous les voyages associés à cet utilisateur
            $trips = $user->trips;

            return response()->json($trips);
        } catch (\Exception $e) {
            Log::error('Error fetching trips: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the trips'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'start_location' => 'required',
            'end_location' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'distance' => 'required',
            'price' => 'required',
        ]);
    
        try {
            $trip = Trip::create($request->all()); 
    
            return response()->json($trip, 201);
        } catch (\Exception $e) {
            Log::error('Error creating trip: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the trip'], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = Trip::findOrFail($id);
    
        return response()->json($trip);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $this->validate($request, [
            'start_location' => 'required',
            'end_location' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'distance' => 'required',
            'price' => 'required',
        ]);
    
        try {
            $trip->update($request->only([
                'start_location',
                'end_location',
                'start_time',
                'end_time',
                'distance',
                'price',
            ]));
    
            return response()->json($trip, 200);
        } catch (\Exception $e) {
            Log::error('Error updating trip: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the trip'], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        try {
            $trip->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting trip: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the trip'], 500);
        }
    }
    
}
