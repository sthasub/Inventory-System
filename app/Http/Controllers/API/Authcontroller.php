<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Authcontroller extends Controller
{
    public function login(Request $request){
        $validated = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validated->fails())  return response()->json(['error'=>"Not valid"],401);

        try {
            $user = auth()->attempt($validated->validated());
        } catch (ValidationException $e) {
            throw response($e);
        }

        if(!$user) return response()->json(["error"=>"unauthorised"],401);

        $token = auth()->user()->createToken("name")->plainTextToken;

        return response()->json(["token"=>$token], 201);
    }

    protected function  user()
    {
        return auth()->user();
    }
}
