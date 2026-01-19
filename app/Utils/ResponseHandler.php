<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

trait ResponseHandler
{
    public function createResponse($data = null, $responseCode = 200, $message = null, $code = null): JsonResponse
    {
        $response = [];

        if ($data) {
            $response['data'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        if ($code) {
            $response['code'] = $code;
        }

        return response()->json($response, $responseCode);
    }
}
