<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * response data
     * @param $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($msg, $data = []){
        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $data
        ]);
    }


    public function error($msg,$data = []){
        return response()->json([
            'code' => 100,
            'msg' => $msg,
            'data' => $data
        ]);
    }


}
