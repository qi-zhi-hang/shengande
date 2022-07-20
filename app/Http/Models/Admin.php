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
}
