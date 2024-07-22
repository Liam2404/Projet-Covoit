<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
            'lastname'=>'required|max:30',
            'firstname'=>'required|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'lastname'=>$request->lastname,
                'firstname'=>$request->firstname,
                'password' => bcrypt($request->password),
            ]);
    
            return response()->json(['user' => $user, 'message' => 'User created successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the user'], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => isset($request->password) ? bcrypt($request->password) : $user->password,
            ]);

            return response()->json(['user' => $user, 'message' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the user'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Supprimer l'utilisateur
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 204);
        } catch (\Exception $e) {
            Log::error('Error deleting user: '.$e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the user'], 500);
        }
    }
}
