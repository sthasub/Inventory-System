<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'identity' => 'required|string',
            'password' => 'required|string',
            'device' => 'nullable|string',
        ]);

        if ($validated->fails()) return response()->json(['error' => "Not valid"], 422);

        //check identity
        $identity = $request->input('identity');
        try {
            //get user data as per key and value for example, email key and its value OR username key and its value
            /** @var  User $user */ //Typehint
            $user = User::query()->where('email',$identity)->orWhere('username',$identity)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error'=>'invalid credentials'],401);
        }

        //check password
        $status = Hash::check($request->input('password'), $user->password);
        if(!$status) return response()->json(['error'=>'invalid credentials'],401);

        //create token if all condition passed, and found user in the User table
        $token = $user->createToken($request->input('device','firefox'), ['*'], now()->addMinutes(20))->plainTextToken;

        return response()->json(["token" => $token], 201);
    }

    public function user(): JsonResponse
    {
        // send User data
        return response()->json(['user' => json_decode(auth()->user())], 200);
    }

    public function logout(): JsonResponse
    {
        //delete user token in Personal access token
        /** @var  User $user */
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'logout Successfully'], 200);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        // Get the token from the Authorization header
        $token = $request->header('Authorization');

        if (empty($token)) {
            return response()->json(['message' => 'Token is invalid'], 422);
        }

        // Remove 'Bearer ' from the token
        $token = explode('Bearer ', $token)[1];

        // Find the token
        $token = PersonalAccessToken::findToken($token);

        //check if token is empty or not OR check the $token is object of User Model or not
        if (empty($token) || !$token->tokenable instanceof User) {
            return response()->json(['message' => 'Token is invalid'], 422);
        }

        // decoding json and getting name of user from User table
        $tokenName = json_decode(auth()->user()->name)->name;

        //delete current token from Personal access token Token table
        auth()->user()->currentAccessToken()->delete();

        // Create a new token
        $newToken = auth()->user()->createToken($tokenName)->plainTextToken;

        return response()->json(['message' => 'success', 'data' => ['access_token' => $newToken]], 200);
    }
}
