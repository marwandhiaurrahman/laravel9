<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    public function sendResponse($response, $message)
    {
        $response = [
            'response' => $response,
            'metadata' => [
                'message' => $message,
                'code' => 200
            ],
        ];
        return response()->json($response, 200);
    }
    public function sendError($error, $errorMessages, $code)
    {
        $response = [
            'response' => [],
            'metadata' => [
                'error' => $error,
                'message' => $errorMessages,
                'code' => $code,
            ],
        ];
        return response()->json($response, $code);
    }
}
