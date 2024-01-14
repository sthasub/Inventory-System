<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class Authcontroller extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'email' => 'sometimes|email',
            'username' => 'sometimes',
            'password' => 'required',
        ]);
        if ($validated->fails()) return response()->json(['error' => "Not valid"], 401);

        if (count($validated->validate()) < 2)
            return response()->json(['error' => 'Empty field']);

        if (!empty($validated->validated()['email'])) {
            $value = $validated->validated()['email'];
            $key = 'email';
        } else {
            $value = $validated->validated()['username'];
            $key = 'username';
        }

        try {
            $user = User::where($key, $value)->firstOrFail();
        } catch (ValidationException $e) {
            throw response($e);
        }
        if (!$user) return response()->json(["error" => "unauthorised"], 401);
        $token = $user->createToken($value, ['*'], now()->addMinutes(20))->plainTextToken;

        return response()->json(["token" => $token], 201);
    }

    public function user(): JsonResponse
    {
        return response()->json(['user' => json_decode(auth()->user()['name'])], 200);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['logout' => 'Successfully'], 200);
    }

    public function refreshToken(Request $request):JsonResponse{
        // Get the token from the Authorization header
        $token = $request->header('Authorization');

        if (empty($token)) {
            return response()->json(['message' => 'Token is invalid'], 422);
        }

        // Remove 'Bearer ' from the token
        $token = explode('Bearer ', $token)[1];

        // Find the token
        $token = PersonalAccessToken::findToken($token);
        if (empty($token) || !$token->tokenable instanceof User) {
            return response()->json(['message' => 'Token is invalid'], 422);
        }
        $tokenName = json_decode(auth()->user()->name)->name;
        auth()->user()->currentAccessToken()->delete();
        // Create a new token
        $newToken = auth()->user()->createToken($tokenName)->plainTextToken;

        return response()->json(['status' => 'success', 'data' => ['access_token' => $newToken]]);
    }
}
