<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index():JsonResponse{
        $users = User::query()->orderBy('id','asc')->get();
        return response()->json($users);
    }
}
