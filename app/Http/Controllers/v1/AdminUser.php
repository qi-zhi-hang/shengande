<?php


namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Http\Service\AdminService;

class AdminUser extends Controller
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
        $groupId = request()->post("group_id");
        if(empty($userName) || empty($password) || empty($groupId)){
            return $this->error(__("param is error"));
        }

        // check password length
        if (!preg_match("/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-z]|[A-Z]|[0-9]){6,}$/",$password)) {
            return $this->error(__("password rule"));
        }
        $adminService = new AdminService();
        try {
            $addRes = $adminService->addAdminUser($userName,$password,$groupId);
            if($addRes){
                return $this->success("新增成功",[]);
            }
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),[]);
        }
    }

}
