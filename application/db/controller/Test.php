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
    {   $p = input('get.p');
        $p = $p ? $p :0;
        $user =  new User();
        $list = $user->userlist($p,50);
        //trace($p,'info');die;
        echo json_encode($list);
    }
    //加载测试视图
    public function  testlist()
    {
        return $this->fetch('test/testlist');
    }
    //分页数据模板
    public function  pageindex()
    {
        return $this->fetch('test/pageindex');
    }
    //ajax请求数据
    public function ajaxpage()
    {
        $page = $this->input->get('page');
        $limit = $this->input->get('limit');
        $limit = $limit ? $limit : 10;
        $page = $page ? ($page-1)*$limit : 0;
        $user =  new User();
        $list = $user->ajaxlist($page,$limit);
        echo json_encode($list);
    }
    //redis测试hash类型使用
    public function redis()
    {
        $options = array(
            'host'       => '192.168.0.24',
            'port'       => 6379
        );
        $redis = new CacheRedis($options);
        $arr = array('id'=>1,'score'=>98);
        $redis->hset('study','zhangsan',$arr);
        return ($redis->hget('study','zhangsan'));
    }


}