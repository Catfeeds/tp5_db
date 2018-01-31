<?php
/**
 * Created by PhpStorm.
 * User.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\model;

use think\Model;
use think\DB;

class User extends Model
{
    //用户列表
    public function userlist($page = 0, $limit = 50)
    {

        $list = DB::table('ebh_users')->field('uid,username')->limit($page, $limit)->select();
        return $list;
    }

    //
    public function ajaxlist($page, $limit = 10)
    {
        $list = DB::name('users')->where('status', 1)->limit($page, $limit)->select();
        return $list;
    }

    public function userconut()
    {
        //$list = DB::name('users')->where('status',1)->select();
        //$count = count($list);
        //上面的查询方法太耗内存
        $list = DB::query('SELECT COUNT(*) count from `ebh_users` where `status`=?', [1]);
        $count = $list[0]['count'];
        if ($count) {
            return $count;
        }

    }

}