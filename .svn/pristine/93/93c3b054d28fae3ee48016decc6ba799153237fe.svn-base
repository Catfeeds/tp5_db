<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;

class User extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function out()
    {
        session('adminuser', null);
        $this->redirect('Login/index');
    }

}
