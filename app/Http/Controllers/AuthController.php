<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\BaseCore;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Supports\ResponseCode;

class AuthController extends BaseCore
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $tokenArr = explode('|', $user->createToken($request->device_name)->plainTextToken);
        return $this->response([
            'item' => $user,
            'token' => $tokenArr[1],
        ], ResponseCode::HTTP_OK, 'Login successful');
    }

}
