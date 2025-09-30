<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::first();

        if (! $user) {
            return response()->json([
                'statusCode' => 404,
                'status' => 'failed',
                'message' => 'No user found to attach token',
                'data' => [],
            ]);
        }

        // Create Sanctum token
        $authToken = $user->createToken('authToken')->plainTextToken;

        // Fake user details if you don't want to expose DB user info
        $userDetails = [
            'name' => 'Test User',
            'email' => 'test@vd.com',
            'phone' => 'test Phone',
        ];

        return response()->json([
            'statusCode' => 200,
            'status' => 'success',
            'message' => 'You are successfully logged in',
            'data' => [
                'status' => true,
                'user' => $userDetails,
                'authToken' => $authToken,
            ],
        ]);
    }
}
