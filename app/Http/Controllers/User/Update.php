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

class Update extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, string $id)
    {

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => sprintf('required|email|unique:users,email,%s,id', $id)
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
}
