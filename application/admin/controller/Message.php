<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use think\View;

class Message extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function lists()
    {
        $view = new View();
        $M    = db('message');


        if (!empty(input('beginTime'))) $M->whereTime('addtime', '>=', input('beginTime'));
        if (!empty(input('endTime'))) {

            $endTime = input('endTime');
            $endTime = date('Y-m-d', strtotime('+1 days ' . $endTime));

            $M->whereTime('addtime', '<=', $endTime);
        }

        if (!empty(input('search'))) $M->where('title', 'like', "%" . input('search') . "%");

        $M->order('id desc');
        $lists = $M->paginate(10);

        $view->assign('lists', $lists);
        return $view->fetch();
    }


}
