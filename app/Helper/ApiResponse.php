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

    public static function error($code = 400 , $msg = 'Error' , $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $msg,
            'errors'  => $errors,
        ], $code);
    }
}
