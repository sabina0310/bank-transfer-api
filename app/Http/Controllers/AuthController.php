<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validator input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // If validator error then send error response
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        // Check if data user valid 
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Email/Password Salah'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        // Create refresh token using id user with custom expiry time 
        $refreshToken = Auth::setTTL(1440)->tokenById(Auth::id());

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken
        ]);
    }

    public function refresh()
    {
        try {
            // Check if token valid 
            JWTAuth::parseToken()->authenticate();

            // Create new token also blacklist the input refresh token 
            return $this->respondWithToken(Auth::refresh(true, true));
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token Expired'], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token Blacklist'], 401);
        }
    }
}
