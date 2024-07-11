<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AccountController extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $item = $request->user()->toArray();
        return $this->response($item, ResponseCode::HTTP_OK, 'User data');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->user()->id;

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => sprintf('required|email|unique:users,email,%s,id', $id),
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        DB::beginTransaction();
        try {

            DB::table('users')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        DB::commit();
        return $this->response($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_password(Request $request)
    {
        $id = $request->user()->id;

        $validator = $this->validate($request, [
            'password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'new_password_confirmation' => 'required',
        ]);

        if(Hash::check($request->password, $request->user()->password) == false) {
            return $this->response(ResponseCode::HTTP_UNPROCESSABLE_ENTITY, 'Password is incorrect');
        }

        if ($validator->fails()) return $this->validationError($validator);

        DB::beginTransaction();
        try {


            DB::table('users')->where('id', $id)->update([
                'password' => Hash::make($request->new_password),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        DB::commit();
        return $this->response($request->all());
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
