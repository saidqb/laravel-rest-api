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

class Index extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {

        $request->merge([]);

        $query = SQ::make('QueryFilter')
            ->request($request->all())
            ->select([
                'id',
                'name as full_name',
                'email',
            ])
            ->search([
                'name',
                'email',
            ])
            ->query(DB::table('users'));

        return $this->response($query->get());
    }
}
