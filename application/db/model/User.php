<?php
/**
 * Created by PhpStorm.
 * User.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\model;

use function foo\func;
use think\Model;
use think\DB;

class User extends Model
{
    //用户列表
    public function userlist($page = 0, $limit = 50)
    {

        $list = DB::table('ebh_users')->field('uid,username')->limit($page, $limit)->select();
        $list = DB::table('ebh_users')->field('uid,username')->limit($page, $limit)->count();
        $list = DB::table('ebh_takes')
            ->alias('t')
            ->join('ebh_classrooms cr','cr.crid=t.crid','left')
            ->field(['cr.crid','cr.crname','cr.domain','sum(t.total) as total2','count(1) times'])
            ->where(['state'=>1,'del'=>['<>',1]])
            ->group('crid')
            ->order('total2 desc')
            ->select();
        
        //--------------------------------
        $list = db('takes')//使用助手函数 不用加前缀
            ->alias('t')
            ->join('ebh_classrooms cr','cr.crid=t.crid','left')
            ->field(['cr.crid','cr.crname','cr.domain','sum(t.total) as total2','count(1) times'])
            ->where(['state'=>1,'del'=>['<>',1]])
            ->group('crid')
            ->order('total2 desc')
            ->select();
        //---------------原生写法
        $list = DB::query('SELECT `cr`.`crid`,`cr`.`crname`,`cr`.`domain`,sum(t.total) as total2,count(1) times FROM `ebh_takes` `t` LEFT JOIN `ebh_classrooms` `cr` ON `cr`.`crid`=`t`.`crid` WHERE  `state` = 1  AND `del` <> 1 GROUP BY crid ORDER BY total2 desc');
        log_message(DB::table('ebh_users')->getLastSql());
        return $list;
    }

    //
    public function ajaxlist($page, $limit = 10)
    {
        $offset = ($page-1)*$limit;
        $list = DB::name('users')->where('status', 1)->limit($offset, $limit)->select();
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