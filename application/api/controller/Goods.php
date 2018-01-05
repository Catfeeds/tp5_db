<?php

namespace app\api\controller;

use app\admin\controller\Admin;

class Goods extends Admin
{
    public function index()
    {


    }

    public function welcome()
    {
        return phpinfo();

    }

    /***
     * 图片管理
     */
    public function picturelist()
    {
        return $this->fetch('picture-list');
    }
}
