<?php

namespace App\Http\Core;

use Illuminate\Routing\Controller;
use Saidqb\LaravelSupport\SQ;
use Saidqb\LaravelSupport\ResponseCode;

class BaseCore extends Controller
{
    // use ValidatesRequests;
    use Concerns\HasValidation;

    public function response($data, $status = ResponseCode::HTTP_OK, $message = ResponseCode::HTTP_OK_MESSAGE, $error_code = 0)
    {
        return SQ::response($data, $status, $message, $error_code);
    }
}
