<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class RefreshToken extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
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
}
