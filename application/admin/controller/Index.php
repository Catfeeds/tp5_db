<?php

namespace app\admin\controller;

class Index extends Admin
{
    public function index()
    {

        return $this->fetch();
    }

    public function welcome()
    {

        return $this->fetch();

    }

    /***
     * 图片管理
     */
    public function picturelist()
    {
        return $this->fetch('picture-list');
    }
}
