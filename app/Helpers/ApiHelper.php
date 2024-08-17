<?php

namespace App\Helpers;

class ApiHelper
{
    public static function response($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }
}