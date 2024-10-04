<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\Hash;
use Saidqb\LaravelSupport\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use Saidqb\LaravelSupport\Make\FilterQuery;

class UpdatePassword extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, string $id)
    {

        $validator = $this->validate($request, [
            'new_password' => ['required', Password::min(8)->mixedCase()->numbers()],
        ]);

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
