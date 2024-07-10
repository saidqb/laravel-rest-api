<?php

namespace App\Http\Core;

use Illuminate\Routing\Controller;
use App\Supports\SQ;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\DB;

class BaseCore extends Controller
{
    // use ValidatesRequests;
    use Concerns\HasValidation;

    protected $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function response($data, $status = ResponseCode::HTTP_OK, $message = ResponseCode::HTTP_OK_MESSAGE, $error_code = 0)
    {
        return SQ::response($data, $status, $message, $error_code);
    }
}
