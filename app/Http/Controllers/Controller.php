<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $uid =  0;

    public function __construct(){
        $token = request()->header("token");
        if($token){
            $this->uid = Redis::get("token") ?? 0;
        }
    }

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
