<?php

namespace App\Http\Core;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthCore extends BaseCore
{
    use AuthorizesRequests;
}
