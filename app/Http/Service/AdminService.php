<?php


namespace App\Http\Service;


use App\Http\Models\Admin;
use App\Http\Models\AdminGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class AdminService extends Service
{

    public  $adminModel = null;
    const LOGIN_OUT_TIME  =  7*24*3600;
    public function __construct(){
        $this->adminModel = new Admin();
    }

    public function addAdminUser($userName,$password,$groupId)
    {
        if(empty($userName) || empty($password) || $groupId <= 0){
           throw new \Exception("参数错误",100);
        }

        $adminInfo =  $this->adminModel->getOneInfo(['admin_name'=>$userName,'status'=>1]);
        if(!empty($adminInfo)){
            throw new \Exception("用户名已存在",100);
        }

        $groupInfo = (new AdminGroup())->getOneInfo(['id'=>$groupId]);
        if(empty($groupInfo)){
            throw new \Exception("用户组不存在",100);
        }

        $insertData = [
            'admin_name' => $userName,
            'pwd' => bcrypt($password),
            'group_id' => $groupId
        ];

        $result = $this->adminModel->addAdmin($insertData);
        if($result){
            return true;
        }
        throw new \Exception("新增用户失败",100);
    }

    /**
     * check admin user account and password
     * @param $adminName
     * @param $password
     * @return false|string
     */
    public function checkUserPwd($adminName,$password)
    {
        if(empty($adminName) || empty($password)){
            return false;
        }
        $userInfo = (new Admin())->getOneInfo(['admin_name'=>$adminName,'status'=>1]);
        if(empty($userInfo)){
            return  false;
        }
        $dbPwd = $userInfo['admin_password'];
        $isRight = Hash::check($password,$dbPwd);
        if(!$isRight){
            return  false;
        }
        //如果登陆验证通过，则生成token
        $uid = $userInfo['id'];
        $nowTime = date("YmdHis");
        $token = md5($uid.$nowTime);
        $loginRes  = Redis::setex($token,self::LOGIN_OUT_TIME,$uid);
        if($loginRes){
            return  $token;
        }
        return  false;

    }
}
