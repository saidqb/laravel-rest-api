<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Hash;

class UserController extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $items = User::all();

        return $this->response($items);
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
            $data = User::create([
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
        $data = User::find($id);
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

            User::find($id)->update([
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

            User::find($id)->delete();
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            $this->db->rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        $this->db->commit();
        return $this->response(ResponseCode::HTTP_OK);
    }
}
