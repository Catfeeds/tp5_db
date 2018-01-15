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
}