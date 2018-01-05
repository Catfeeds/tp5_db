<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;

class Notice extends Controller
{
    /***
     * 链接列表
     * @return mixed
     */
    public function lists()
    {
        return $this->fetch();
    }


}
