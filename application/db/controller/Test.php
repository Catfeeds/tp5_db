<?php
/**
 * Created by PhpStorm.
 * Test.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\controller;

use think\Controller;
use app\db\model\User;
use app\api\controller\CacheRedis;

class Test extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function index()
    {
        return $this->fetch('test/index');
    }

    //前端vue瀑布流请求数据
    public function getlist()
    {
        $p = input('get.p');
        $p = $p ? $p : 0;
        $user = new User();
        $list = $user->userlist($p, 50);
        //trace($p,'info');die;
        echo json_encode($list);
    }

    //加载测试视图
    public function testlist()
    {
        return $this->fetch('test/testlist');
    }

    //分页数据模板
    public function pageindex()
    {
        //$page = input('page', 0);
        //$limit = input('limit', 10);
        //$count = $this->user->userconut();
        //$list = $this->user->ajaxlist($page, $limit);
        //$list = json_encode($list);
        //$this->assign('count', $count);
        //$this->assign('list', $list);
        //$this->assign('page',$page);
        //$this->assign('limit',$limit);
        return $this->fetch('test/pageindex');
    }

    //ajax请求数据
    public function ajaxpage()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $count = $this->user->userconut();
        $list = $this->user->ajaxlist($page, $limit);
        $data = array(
            'code'=>0,
            'msg'=>'ok',
            'count'=>$count,
            'data'=>$list
        );
        //trace(json_encode($data),'log');
        //log_message($data);die;
        echo json_encode($data);
    }

    //redis测试hash类型使用
    public function redis()
    {
        $options = array(
            'host' => '127.0.0.1',
            'port' => 6379
        );
        $redis = new CacheRedis($options);
        $arr = array('id' => 1, 'score' => 98);
        $redis->hSet('study1', 'zhangsan', $arr);
        var_dump($redis->hGet('study1', 'zhangsan'))  ;
    }


}