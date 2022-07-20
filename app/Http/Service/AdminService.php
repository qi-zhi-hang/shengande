<?php


namespace App\Http\Service;


use App\Http\Models\Admin;
use App\Http\Models\AdminGroup;


class AdminService extends Service
{

    public  $adminModel = null;
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
}
