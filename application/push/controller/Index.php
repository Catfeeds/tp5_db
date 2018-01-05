<?php
namespace app\index\controller;

use app\model\Demo;
use think\Controller;

class Index extends Controller
{
    /***
     * 空操作方法
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        echo '不存在方法'.$name;

    }
    public function index()
    {
        $D = new Demo();
        $list = $D->del([2,3]);

        return json(['code'=>'0','data'=>$list]);
        return $this->fetch();
    }

}
