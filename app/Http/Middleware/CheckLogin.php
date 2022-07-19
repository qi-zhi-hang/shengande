<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header("token");
        if(empty($token)){
            return response()->json(['code'=>100,'msg'=>'请先登陆']);
        }
        $uid = Redis::get($token);
        if(empty($uid)){
            return response()->json(['code'=>100,'msg'=>'登陆信息为空，请先登陆']);
        }

        return $next($request);
    }
}
