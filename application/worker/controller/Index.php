<?php

namespace app\worker\controller;

use think\Controller;

class Index extends Controller
{

    public function index()
    {
        // return view('index/index');
        return $this->fetch();//等价上面
    }
}
