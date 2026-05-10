<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Google_Client;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($request->token);
            
            if ($payload) {
                $user = User::firstOrCreate(
                    ['email' => $payload['email']],
                    [
                        'name' => $payload['name'],
                        'google_id' => $payload['sub'],
                    ]
                );

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ]);
            } else {
                return response()->json(['message' => 'Invalid Google token'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json(['message' => 'Authentication failed'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
