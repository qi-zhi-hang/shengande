<?php


namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Http\Service\AdminService;
use Illuminate\Support\Facades\Redis;

class AdminUser extends Controller
{
    public $adminService = null;
    const ERROR_LOGIN_OUT_TIME = 3600;
    public function __construct()
    {
        $this->adminService = new AdminService();
    }

    /**
     * management login
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $userName = request()->post("user_name");
        $password = request()->post("password");
        if(empty($userName) || empty($password)){
            return  $this->error(__("password or account is empty"));
        }
        $loginErrNum = Redis::get($userName);
        if($loginErrNum >=3){
            return  $this->error(__("login error num"));
        }
        $token = $this->adminService->checkUserPwd($userName,$password);
        if($token === false){
            Redis::incr($userName);
            Redis::expire($userName,self::ERROR_LOGIN_OUT_TIME);
            return $this->error(__("login error"));
        }
        return $this->success(__("login success"),['token'=> $token]);
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
