<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Core\BaseCore;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Saidqb\LaravelSupport\ResponseCode;
use Saidqb\LaravelSupport\SQ;

class Login extends BaseCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        SQ::responseConfig([
            'hide' => ['password'],
            'decode' => [],
            'decode_array' => [],
        ]);

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
            'item' => $user?->toArray(),
            'token' => $tokenArr[1],
        ], ResponseCode::HTTP_OK, 'Login successful');
    }
}
