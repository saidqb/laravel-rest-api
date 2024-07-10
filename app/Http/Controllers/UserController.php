<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Hash;
use App\Supports\SQ;

class UserController extends AuthCore
{
    public function __construct()
    {
        parent::__construct();

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

        $query = $this->db::table('users');

        SQ::queryBuilder($request->all(), $query, $setFilter);

        return $this->response(SQ::queryBuilderResult());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        $this->db->beginTransaction();
        try {
            $data = $this->db::table('users')->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->response($data);
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to create');
        }
        $this->db->commit();
        return $this->response($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->db::table('users')->find($id);
        // print_r($data->to);die();

        return $this->response($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->db->beginTransaction();
        try {

            $validator = $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) return $this->validationError($validator);

            $this->db::table('users')->find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        $this->db->commit();
        return $this->response($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $this->db->beginTransaction();
        try {

            $this->db::table('users')->find($id)->delete();
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        $this->db->commit();
        return $this->response(ResponseCode::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_bulk(Request $request)
    {

        $this->db->beginTransaction();
        try {
            $validator = $this->validate($request, [
                'id' => 'required|array',
            ]);

            if ($validator->fails()) return $this->validationError($validator);

            $id = $request->id;

            foreach ($id as $value) {
                $this->db::table('users')->find($value)->delete();
            }
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        $this->db->commit();
        return $this->response(ResponseCode::HTTP_OK);
    }
}
