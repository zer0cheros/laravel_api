<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req){
        echo($req->input('name'));
        $user = User::create([
            'name' => $req->input('name'),
            'email' => $req->input('email'),
            'password' => Hash::make($req->input('password')),
        ]);
    
        // Generate the API token for the user
        $token = $user->createToken('api-token')->plainTextToken;
    
        // Return the token to the client or perform any other action
        return response()->json(['token' => $token], 201);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['token deleted']);
    } 
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
