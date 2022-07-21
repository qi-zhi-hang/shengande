<?php


namespace App\Http\Service;


use App\Http\Models\Admin;
use App\Http\Models\AdminGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class AdminService extends Service
{

    public  $adminModel = null;
    public  $adminGroupModel =  null;
    const LOGIN_OUT_TIME  =  7*24*3600;
    public function __construct(){
        $this->adminModel = new Admin();
        $this->adminGroupModel =  new AdminGroup();
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

    /**
     * get user list
     * @param $page
     * @param $pageSize
     * @param $uid
     * @param string $adminName
     * @return array
     */
    public function getAdminList($page,$pageSize,$uid,string  $adminName='')
    {

        if(!is_numeric($page) || !is_numeric($pageSize)){
            return  [];
        }
        $start  = ($page - 1)*$pageSize;
        $where[] = ['a.status','=',1];
        if($adminName){
            $where[] = ['a.admin_name','like',"%{$adminName}%"];
        }
        $adminInfo = $this->adminModel->getOneInfo(['id'=>$uid,'status'=>1]);
        if(empty($adminInfo)){
            return  [];
        }
        $groupInfo = $this->adminGroupModel->getOneInfo(['id'=>$adminInfo['group_id']]);

        if($groupInfo['permission_num'] <= 0){
            return  [];
        }
        $where[] = ['g.permission_num','>=',$groupInfo['permission_num']];

        $data =  $this->adminModel->getAdminList($where,$start,$pageSize);

        $list = $data['list'];
        if(empty($list)){
            return  [];
        }
        return  ['list'=>$list,'count'=>$data['count']];
    }

    /**
     * get one account  detail
     * @param $id
     * @return array|\Illuminate\Database\Query\Builder|mixed
     */
    public function getOneInfo($id,$uid)
    {
        $info = $this->adminModel->getOneInfo(['id'=>$id,'status'=>1]);
        if($id  == $uid){
            return  $info;
        }

        $ownInfo = $this->adminModel->getOneInfo(['id'=>$uid,'status'=>1]);
        $promiseLevelOwn  = $this->adminGroupModel->getOneInfo(['id'=>$ownInfo['group_id']]);
        $promiseLevelOther  = $this->adminGroupModel->getOneInfo(['id'=>$info['group_id']]);

        if($promiseLevelOwn['permission_num'] < $promiseLevelOther['permission_num']){
            return false;
        }

        return  $ownInfo;
    }

    public function delOneAccount($id,$uid)
    {
        $info = $this->adminModel->getOneInfo(['id'=>$id,'status'=>1]);
        if(empty($info)){
            return  false;
        }

        $ownInfo = $this->adminModel->getOneInfo(['id'=>$uid,'status'=>1]);
        if(empty($ownInfo)){
            return  false;
        }
        $promiseLevelOwn  = $this->adminGroupModel->getOneInfo(['id'=>$ownInfo['group_id']]);
        $promiseLevelOther  = $this->adminGroupModel->getOneInfo(['id'=>$info['group_id']]);

        if($promiseLevelOwn['permission_num'] < $promiseLevelOther['permission_num']){
            return false;
        }

        return $this->adminModel->updateAdmin($id,['status'=>0,'updated_at'=>date("Y-m-d H:i:s")]);

    }
}
