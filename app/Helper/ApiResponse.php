<?php

namespace App\Helper;

class ApiResponse
{
    public static function sendResponse($code = 200, $msg = null, $data = null)
    {
        $response = [
            'status' => $code,
            'message' => $msg,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
}
