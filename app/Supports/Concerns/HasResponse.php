<?php

namespace App\Supports\Concerns;

use App\Supports\ResponseCode;

trait HasResponse
{
    /**
     * Response data
     *
     * @param array $data
     * @param int $status
     * @param string $message
     * @param int $error_code
     * @return \Illuminate\Http\JsonResponse
     */
    static function response($data = [], $status = ResponseCode::HTTP_OK, $message = ResponseCode::HTTP_OK_MESSAGE, $error_code = 0)
    {

        // rebuild the response data, if the data is not an array
        if (!is_array($data)) {
             $error_code = $message == ResponseCode::HTTP_OK_MESSAGE ? $error_code : $message;
             $message = $status == ResponseCode::HTTP_OK ? $error_code : $status;
             $status = $data;
        }

        $resData = [
            'status' => $status,
            'success' => $status == ResponseCode::HTTP_OK ? true : false, // 'true' or 'false
            'error_code' => $error_code,
            'message' => $message,
            'data' => [],
        ];


        if (isset($data['items'])) {
            $items = [];

            if (!empty($data['items'])) {
                $items = $data['items'];
            }

            $resData['data']['items'] = $items;

            if (!isset($data['pagination'])) {
                $resData['data']['pagination'] = (object) null;
            } else {
                if ($resData['data']['pagination'] == false) {
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

        if (!empty($data)) {
            if (is_array($data)) {
                $item = $data;
            }
        }

        $resData['data']['item'] = $item;
        return response()->json($resData, $status);
    }
}
