<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->name);

        //attach token to user
        $user->token = $token->plainTextToken;

        //format user with auth resource
        $formatUser = new AuthResource($user);

        return ApiHelper::response($formatUser, 'User created successfully', 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken($user->name);

        //attach token to user
        $user->token = $token->plainTextToken;

        //format user with auth resource
        $formatUser = new AuthResource($user);

        return ApiHelper::response($formatUser, 'User logged in successfully', 200);
    }
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return ApiHelper::response(null, 'User logged out successfully', 200);
    }
}