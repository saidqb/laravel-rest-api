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
use Illuminate\Support\Facades\Route;

class Permission extends AuthCore
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $routeCollection = Route::getRoutes();
        $nr = [];
        foreach ($routeCollection as $value) {
            if (in_array('api_role', $value->action['middleware'])) {
                $nr[] = [
                    'name' => $value->action['as'],
                ];
            }
        }

        $data['items'] = $nr;
        return $this->response($data);
    }
}
