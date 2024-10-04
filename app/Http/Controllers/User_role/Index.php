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

class Index extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        SQ::responseConfig([
            'hide' => [],
            'decode' => ['menus','permissions'],
            'decode_array' => [],
        ]);

        $request->merge([]);

        $query = SQ::make('QueryFilter')
            ->request($request->all())
            ->select([
                'id',
                'name',
                'menus',
                'permissions',
            ])
            ->search([
                'name',
            ])
            ->query(DB::table('user_roles'));

        return $this->response($query->get());
    }
}
