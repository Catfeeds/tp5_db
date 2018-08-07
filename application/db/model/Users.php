<?php
/**
 * Created by PhpStorm.
 * User.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\model;

// use Think\Exception;
use think\Model;
use think\Db;
use app\db\model\Roomusers;

class Users extends Model
{
    // protected $hidden = [];
    // protected $hidden = ['ppassword','paypassword'];
    protected $hidden = ['lastloginip','dateline'];
    protected $visible = ['uid','username','realname','password','item','groups'];
    //用户列表
    public function userlist($param)
    {
        $limit = (max(0, ($param['page'] - 1) *$param['pagesize'])) . ", {$param['pagesize']}";
        $wherearr['status'] = isset($param['status']) ? (int)$param['status'] :1;
        $list0 = Db::table('ebh_users')->field('uid,username,realname')->where($wherearr)->order('uid desc')->limit($limit)->select();
        log_message(Db::table('ebh_users')->getLastSql());
        $list = Db::table('ebh_users')->field('uid,username')->limit($limit)->count();
        $list = Db::table('ebh_takes')
            ->alias('t')
            ->join('ebh_classrooms cr','cr.crid=t.crid','left')
            ->field(['cr.crid','cr.crname','cr.domain','sum(t.total) as total2','count(1) times'])
            ->where(['state'=>1,'del'=>['<>',1]])
            ->group('crid')
            ->order('total2 desc')
            ->select();

        //--------------------------------
        $list = Db('takes')//使用助手函数 不用加前缀
            ->alias('t')
            ->join('ebh_classrooms cr','cr.crid=t.crid','left')
            ->field(['cr.crid','cr.crname','cr.domain','sum(t.total) as total2','count(1) times'])
            ->where(['state'=>1,'del'=>['<>',1]])
            ->group('crid')
            ->order('total2 desc')
            ->select();
        //---------------原生写法
        $list = Db::query('SELECT `cr`.`crid`,`cr`.`crname`,`cr`.`domain`,sum(t.total) as total2,count(1) times FROM `ebh_takes` `t` LEFT JOIN `ebh_classrooms` `cr` ON `cr`.`crid`=`t`.`crid` WHERE  `state` = 1  AND `del` <> 1 GROUP BY crid ORDER BY total2 desc');
        // log_message(Db::table('ebh_users')->getLastSql());
        // 读取其他的数据库
        $list = Db::connect('database_foo')->table('shop_fees')->select();//获取其他的数据库数据 配置文件在 application/extra/
        return $list0;
    }

    //
    public function ajaxlist($param)
    {
       if($param['pagesize']){
           $pagesize = (int)$param['pagesize'];
       }
        if($param['page']){
            $page = (int)$param['page'];
        }
        $offset = max(0,($page-1))* $pagesize;
       $where = [];
        if ($param['q']) {
            $where['realname|username'] = ['like', '%' . $param['q'] . '%'];
        }
        $list = Db::name('users')->field('uid,username,realname,sex,balance,status')->where($where)->cache(true)->limit($offset, $pagesize)->select();
        // log_message($list);
        return $list;
    }
    public function getpage(){

        $list = Db('users')->field(['uid','username','realname','dateline','lastloginip'])->where('status','=','1')->order('uid desc')->paginate(10);
        return $list;
    }
    public function usercount($param)
    {
        $where = [];
        if($param['q']){
            $where['realname|username'] = ['like', '%'.$param['q'].'%'];
        }
        $count = Db::name('users')->cache(true)->where($where)->count();
        // $list = Db::query('SELECT COUNT(*) count from `ebh_users` where `status`=?', [1]);
        // $count = $list[0]['count'];
        // log_message($list);
        // if ($count) {
        //     return $count;
        // }
        return $count;
    }

    /**
     * @description 根据uid获取用户信息
     * @param $uid
     */
    public function getUserByUid($uid)
    {
        $user = Db::name('users')->find($uid);
        if(null != $user){
            return $user;
        }else{
            return false;
        }
    }

    /**
     * @description 测试全局异常处理
     * @param $id
     */
    public function getUserById($id)
    {
        // try{
        //     1/1;
        //     1/0;
        // }catch (Exception $e){
        //     throw $e;
        // }
        // return 'this is banner info';
        // die;
        return 0;
    }

    /**
     * @description 关联模型
     */
    public function item()
    {
        return $this->hasMany('roomusers', 'uid', 'uid');
        // return $this->belongsTo('roomusers', 'Roomusers.roominfo', 'uid');
    }

    public function groups()
    {
        return $this->belongsTo('groups', 'groupid', 'groupid');
        // return $this->belongsTo('roomusers', 'Roomusers.roominfo', 'uid');
    }
    // 一对一 一个用户和绑定信息
    public static function getUserByUid2($uid)
    {
        $user = self::with('item')->find($uid);
        return $user;
    }
    // 多条记录
    public static function getUserList($param=array())
    {
        $where = [];
        if (isset($param['status'])) {
            $where['status'] = ['=', (int)$param['status']];
        }
        if (isset($param['sex'])) {
             $where['sex'] = ['=', 0];
        }
        if (!empty($param['q'])) {
            $where['realname|username'] = ['like', '%'.$param['q'].'%'];
        }
        // var_dump($where);die;
        // $user = self::query('select uid,username,realname,status,sex from ebh_users limit 10');// 原生sql
        // $user = self::with('item')->limit(10)->select();
        // $user = self::with(['item', 'item.roominfo'])->limit(10)->select();// 关联模型嵌套
        $user = self::with(['item','groups', 'item.roominfo'])->limit(10)->select();// 关联模型嵌套
        // $count = self::with(['item','groups', 'item.roominfo'])->count();// 关联模型嵌套
        // $count2 = self::count();// 关联模型嵌套 等同于上面的统计 实际上只统计了当前模型
        // $user = self::with('item,groups')->limit(10)->select();// 关联模型嵌套
        // $user = Db::name('users')->field('uid,username,realname')->where($where)->order('uid desc')->limit(10)->select();
        return $user;
    }
    // 修改器的使用 设置不存在的属性
    public  function getCeshiAttr($value,$data){
        return '我是测试不存在的属性的';
    }
}