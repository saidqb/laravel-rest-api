<?php

namespace App\Supports\Concerns;

use App\Supports\ResponseCode;

trait HasResponse
{
    /**
     * Return a new JSON response from the application.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  string  $message
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    static function response($data = [], $status = ResponseCode::HTTP_OK, $message = ResponseCode::HTTP_OK_MESSAGE, $error_code = 0)
    {

        $resData = [
            'status' => $status,
            'success' => $status == ResponseCode::HTTP_OK ? true : false, // 'true' or 'false
            'error_code' => $error_code,
            'message' => $message,
            'data' => [],
        ];

        if (isset($data['items'])) {
            $items = [];

            if(!empty($data['items'])) {
                $items = $data['items'];
            }

            $resData['data']['items'] = $items;

            if (!isset($data['pagination'])) {
                $resData['data']['pagination'] = (object) null;
            } else {
                if($resData['data']['pagination'] == false){
                    unset($resData['data']['pagination']);
                }
            }

            return response()->json($resData, $status);
        }

        if (isset($data['item'])) {
            $resData['data'] = $data;
            return response()->json($resData, $status);
        }

        $item = (object) null;

        if(!empty($data)) {
            $item = $data;
        }

        $resData['data']['item'] = $item;
        return response()->json($resData, $status);
    }
}
