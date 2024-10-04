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

class Show extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(string $id)
    {
        SQ::responseConfig([
            'hide' => [],
            'decode' => ['menus','permissions'],
            'decode_array' => [],
        ]);

        $data = DB::table('user_roles')->find($id);

        return $this->response($data);
    }
}
