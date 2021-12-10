<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * @param $msg string | array
     * @param $code integer | 200
     * @return  \Illuminate\Http\JsonResponse
     */
    public function response($msg, $code = 200)
    {
        $data = is_array($msg) ? $msg : ['message' => $msg];
        $data['code'] = $code;
        $data['success'] = $code >= 200 && $code <= 299;
        $data['error'] = !$data['success'];
        return response()->json($data, $code);
    }
}