<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;

class Video extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    /***
     * 视频列表
     * @return mixed
     */
    public function lists()
    {
        return $this->fetch();
    }

    /***
     * 视频编辑
     * @return mixed
     */
    public function addedit()
    {
        return $this->fetch();
    }


}
