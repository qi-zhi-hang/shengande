<?php


namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminGroup extends Model
{
    public $table = "sad_admin_group";
    public $timestamps = false;

    /**
     * get one sad_admin_group info
     * @param $where
     * @return array|Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getOneInfo($where){

        if(empty($where)){
            return  [];
        }
        return DB::table($this->table)->where($where)->first();
    }
}
