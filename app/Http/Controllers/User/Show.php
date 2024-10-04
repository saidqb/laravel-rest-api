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

class Show extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(string $id)
    {

        $data = DB::table('users')->find($id);
        // print_r($data->to);die();

        return $this->response($data);
    }
}
