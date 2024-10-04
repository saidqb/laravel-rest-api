<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Hash;
use App\Supports\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use App\Supports\Make\FilterQuery;

class DestroyBulk extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
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
