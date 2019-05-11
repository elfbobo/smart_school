<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    //
    public function responseToJson($data = [], $code = 1000, $msg = 'ok')
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'extra' => $data,
        ]);
    }
}
