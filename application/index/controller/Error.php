<?php

namespace app\index\controller;

use think\Request;
use think\View;

/***
 * 空控制器
 * Class Error
 * @package app\index\controller
 */
class Error extends \app\admin\controller\Error
{
    public function index(Request $request)
    {

        $view = new View();
        return $view->fetch('Error/index');

    }

}