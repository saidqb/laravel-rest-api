<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Hash;
use App\Supports\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends AuthCore
{
    public function __construct()
    {

        /**
         * Set response config, modify the response data
         */
        SQ::responseConfig([
            'hide' => ['password'],
            'decode' => [],
            'decode_array' => [],
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setFilter = [
            'select' => [
                'id',
                'name as full_name',
                'email',
            ],
            'search' => [
                'name',
                'email',
            ],
        ];

        $request->merge([]);

        $query = DB::table('users');

        SQ::queryBuilder($request->all(), $query, $setFilter);

        return $this->response(SQ::queryBuilderResult());
    }

    /**
     * Store a newly insertd resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('users')->find($id);
        // print_r($data->to);die();

        return $this->response($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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


    /**
     * Update the specified resource in storage.
     */
    public function update_password(Request $request, string $id)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::beginTransaction();
        try {

            DB::table('users')->where('id', $id)->delete();
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        DB::commit();
        return $this->response(ResponseCode::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_bulk(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = $this->validate($request, [
                'id' => 'required|array',
            ]);

            if ($validator->fails()) return $this->validationError($validator);

            $ids = $request->id;

            foreach ($ids as $id) {
                DB::table('users')->where('id', $id)->delete();
            }
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        DB::commit();
        return $this->response(ResponseCode::HTTP_OK);
    }
}
