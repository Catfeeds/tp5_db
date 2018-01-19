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

class Test extends Controller
{
    //protected $user;
    //public function __construct()
    //{
    //    $this->user = new User();
    //}
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


}