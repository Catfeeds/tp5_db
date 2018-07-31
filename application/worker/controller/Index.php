<?php

namespace app\worker\controller;

use function request;
use think\Controller;
use function var_dump;

class Index extends Controller
{
    protected $id;
    public function getdata(){
        $id = input('id');
        if($id!=0){
            return json([],'参数为空');
        }
        return json([],'success id='.$id);
    }

    public function test($id){
        $this->id =$id;
        return $this->getdata();
    }

    public function index()
    {
        // return view('index/index');
        return $this->fetch();//等价上面
    }
}
