<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Core\AuthCore;

use App\Models\User;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class Show extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {

        $item = $request->user()->toArray();
        return $this->response($item, ResponseCode::HTTP_OK, 'User data');
    }
}
