<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use think\View;

class News extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function newsin()
    {
        $view = new View();
        $M    = db('news');


        if (!empty(input('beginTime'))) $M->whereTime('addtime', '>=', input('beginTime'));
        if (!empty(input('endTime'))) {

            $endTime = input('endTime');
            $endTime = date('Y-m-d', strtotime('+1 days ' . $endTime));

            $M->whereTime('addtime', '<=', $endTime);
        }

        if (!empty(input('search'))) $M->where('title', 'like', "%".input('search')."%");
        $M->where('type', 1);
        $lists = $M->paginate(10);

        $view->assign('lists', $lists);

        return $view->fetch();

    }

    //提交
    public function postnews()
    {
        $data            = input('post.');
        if(isset($data['editorValue'])){

            $data['content'] = $data['editorValue'];
            unset($data['editorValue']);
        }
        $N = new \app\model\News();

        if ($N->addedit($data)) {
            return json(['code' => 0, 'msg' => '操作成功']);
        } else {
            return json(['code' => 1, 'msg' => '操作失败']);
        }
    }

    public function newsout()
    {
        $view = new View();
        $M    = db('news');


        if (!empty(input('beginTime'))) $M->whereTime('addtime', '>=', input('beginTime'));
        if (!empty(input('endTime'))) {

            $endTime = input('endTime');
            $endTime = date('Y-m-d', strtotime('+1 days ' . $endTime));

            $M->whereTime('addtime', '<=', $endTime);
        }

        if (!empty(input('search'))) $M->where('title', 'like', '%'.input('search').'%');
        $M->where('type', 2);
        $lists = $M->paginate(10);
        $view->assign('lists', $lists);
        return $view->fetch();
    }

    //新闻编辑页面
    public function addedit()
    {
        $view = new View();
        if (!empty(input('id'))) {

            $info = db('news')
                ->where('type', input('type'));
            $info = $info->where('id', input('id'));
            $info = $info->field(['content', 'picurl', 'videourl', 'title','commit'])->find();
            $view->assign('info', $info);
        }
        return $view->fetch();

    }

    //删除新闻页面
    public function del()
    {
        if (empty(input('id'))) {
            return json(['code' => 1, '缺少主要参数']);
        } else {
            $N = new \app\model\News();
            if ($N->del(input('id'))) {
                return json(['code' => 0, '删除新闻完成']);
            } else {
                return json(['code' => 1, '删除新闻失败']);
            }
        }
    }
}
