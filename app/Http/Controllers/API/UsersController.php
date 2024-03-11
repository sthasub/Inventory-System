<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()->orderBy('id', 'asc')->get();
        return response()->json($users);
    }

    public function addUser(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['name'] = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ];
        User::query()->create($data);
        return response()->json('201');
    }

    public function updateUser($userId, Request $request): JsonResponse
    {
        $data = $request->all();
        $data['name'] = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name')
        ];

        $user = User::query()->findOrFail($userId);

        $user->update($data);
        return response()->json('201');
    }

    public function getUser($id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        return response()->json($user);
    }

    public function deleteUser($id){
        $user = User::query()->findOrFail($id);
        $result = $user->delete();
        return response()->json(["message"=>"success ".$result]);
    }
}
