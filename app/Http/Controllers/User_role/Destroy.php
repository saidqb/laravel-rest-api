<?php

namespace App\Http\Controllers\User_role;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\Hash;
use Saidqb\LaravelSupport\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use Saidqb\LaravelSupport\Make\FilterQuery;

class Destroy extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, string $id)
    {

        DB::beginTransaction();
        try {

            DB::table('user_roles')->where('id', $id)->delete();
            return $this->response(ResponseCode::HTTP_OK, 'Successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete');
        }
        DB::commit();
        return $this->response(ResponseCode::HTTP_OK);
    }
}
