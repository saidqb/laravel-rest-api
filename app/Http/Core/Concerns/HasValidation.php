<?php
namespace App\Http\Core\Concerns;

use App\Supports\SQ;
use App\Supports\ResponseCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait HasValidation
{

    public function validate(Request $request, array $rules, array $messages = [], array $attributes = [])
    {
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        return $validator;
    }

    public function validationError($validator)
    {
        return $this->response($validator->errors()->messages(), ResponseCode::HTTP_VALIDATION_ERROR, ResponseCode::HTTP_VALIDATION_ERROR_MESSAGE);
    }
}
