<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/4
 * Time: 12:07
 */
namespace app\index\login\controller;
use think\Controller;

class Login extends Controller{
    public function index()
    {
        return 'login';
    }

    public function demo()
    {
        return 'demo';
    }
}