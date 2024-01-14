<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Authcontroller extends Controller
{
    public function login(Request $request):JsonResponse
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

        $token = $user->createToken($value)->plainTextToken;

        return response()->json(["token" => $token], 201);
    }

    public function user():JsonResponse
    {
        return response()->json(['user'=>json_decode(auth()->user()['name'])],200);
    }

    public function logout():JsonResponse{
        auth()->user()->tokens()->delete();
        return response()->json(['logout'=>'Successfully'], 200);
    }
}
