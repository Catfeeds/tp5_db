<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\View;

/***
 * 空控制器
 * Class Error
 * @package app\index\controller
 */
class Error extends Controller
{
    public function index(Request $request)
    {
        return $this->fetch();

        //根据当前控制器名来判断要执行那个城市的操作

    }

}