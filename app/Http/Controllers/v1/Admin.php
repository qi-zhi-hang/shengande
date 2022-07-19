<?php


namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;

class Admin extends Controller
{


    /**
     * management login
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $userName = request()->post("user_name");
        $password = request()->post("pwd");
        if(empty($userName) || empty($password)){
            return  $this->error(__("password or account is empty"));
        }
        return $this->error("参数错误",['pwd'=>$password,'user_name'=>$userName]);
    }

    /**
     * add account
     */
    public function addAdmin()
    {
        $userName = request()->post("user_name");
        $password = request()->post("pwd");
    }

}
