<?php
/**
 * Created by PhpStorm.
 * User.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */
namespace  app\db\model;
use think\Model;
use think\DB;
class User extends Model
{
    public function userlist($page=0,$limit=50)
    {

        $list  = DB::table('ebh_users')->field('uid,username')->limit($page,$limit)->select();
        return $list;
    }
}