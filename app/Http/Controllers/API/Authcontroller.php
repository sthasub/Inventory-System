<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Authcontroller extends Controller
{
    public function login(Request $request){
        $validated = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
            'username'=>'required',
        ]);

        $token = $request->user()->createToken("name")->plainText;
        if($validated->fails())  return response()->json(['error'=>888]);
        return response()->json(["nope"=>$validated->getData(), "token"=>$token]);
    }
}
