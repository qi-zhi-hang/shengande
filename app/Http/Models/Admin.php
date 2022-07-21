<?php


namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    public $table = 'sad_admin';
    public $timestamps = false;

    /**
     * get one
     * @param $where
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getOneInfo($where){

        if(empty($where)){
            return  [];
        }
        return DB::table($this->table)->where($where)->first();
    }

    /**
     * @param $data
     * @return bool
     */
    public function addAdmin($data)
    {
        $insertData = [
            'admin_name' => $data['admin_name'],
            'admin_password' => $data['pwd'],
            'group_id' => $data['group_id'],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'status' => 1
        ];
        return DB::table($this->table)->insert($insertData);
    }

    public function getAdminList($where = [],$start = 0,$size = 10){
        $list =  DB::table("{$this->table} as a")
            ->select("a.id","a.admin_name","a.admin_password","a.created_at","a.status","g.group_name")
            ->leftJoin("sad_admin_group as g","a.group_id","=","g.id")
            ->where($where)
            ->limit($size)
            ->get();
        $count = DB::table("{$this->table} as a")
            ->select("a.id","a.admin_name","a.admin_password","a.created_at","a.status","g.group_name")
            ->leftJoin("sad_admin_group as g","a.group_id","=","g.id")
            ->where($where)
            ->count();

        return ['list'=>$list,'count'=>$count];

    }
}
