<?php

namespace App\Http\Controllers\User_role;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Hash;
use App\Supports\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use App\Supports\Make\FilterQuery;

class Update extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, string $id)
    {

        $validator = $this->validate($request, [
            'name' => sprintf('required|unique:user_roles,name,%s,id', $id),
            'menus' => sprintf('array'),
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        DB::beginTransaction();
        try {

            DB::table('user_roles')->where('id', $id)->update([
                'name' => $request->name,
                'menus' => $request->menus,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        DB::commit();
        return $this->response($request->all());
    }
}
