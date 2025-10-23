<?php
namespace App\Helpers;

class   ApiResponseHelper
{
    public static function response($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,

        ]);
    }
}
