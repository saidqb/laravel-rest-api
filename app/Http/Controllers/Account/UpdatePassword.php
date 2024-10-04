<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UpdatePassword extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {

        $id = $request->user()->id;

        $validator = $this->validate($request, [
            'password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'new_password_confirmation' => 'required',
        ]);

        if (Hash::check($request->password, $request->user()->password) == false) {
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
}
