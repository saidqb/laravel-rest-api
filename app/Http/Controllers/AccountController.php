<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;

class AccountController extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $item = $request->user()->toArray();
        return $this->response($item,ResponseCode::HTTP_OK, 'User data');
    }

    public function refresh_token(Request $request)
    {
        $validator = $this->validate($request, [
            'device_name' => 'required',
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        $tokenArr = explode('|', $request->user()->createToken($request->device_name)->plainTextToken);
        return $this->response([
            'token' => $tokenArr[1],
        ], ResponseCode::HTTP_OK, 'Token refreshed');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response(ResponseCode::HTTP_OK, 'Successfully logged out');
    }
}
