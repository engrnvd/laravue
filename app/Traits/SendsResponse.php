<?php
/**
 * Created by  naveedulhassan
 * Date: 8/5/16
 * Time: 2:22 PM
 */

namespace App\Traits;


trait SendsResponse
{
    protected function response($msg, $success = true)
    {
        return [
            'message' => $msg,
            'error' => !$success,
        ];
    }

    protected function errorResponse($msg)
    {
        return $this->response($msg, false);
    }
}