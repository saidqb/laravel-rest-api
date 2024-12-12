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

class Store extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => sprintf('required|email|unique:users,email'),
            'password' => 'required',
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        DB::beginTransaction();
        try {
            $data = DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        DB::commit();
        return $this->response($data);
    }
}
