<?php

namespace app\index\controller;

use app\model\Demo;
use app\model\Login;
use app\model\Message;
use think\Controller;
use think\View;

class Index extends Init
{
    /***
     * 空操作方法
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        $view = new View();
        return $view->fetch('Error/index');

    }

    //首页
    public function index()
    {

        //dblog(json_encode($_SERVER,JSON_UNESCAPED_UNICODE));
        return $this->fetch();
    }

    //关于我们
    public function about()
    {
        $view = new View();
        return $view->fetch();
    }

    //五步蛇
    public function snakeVipe()
    {
        return $this->fetch('snakeVipe');
    }

    //蝮蛇
    public function snakeAdder()
    {
        return $this->fetch('snakeAdder');
    }

    //眼镜蛇
    public function snakeElapoid()
    {
        return $this->fetch('snakeElapoid');
    }

    //企业文化
    public function aboutCulture()
    {
        return $this->fetch('aboutCulture');
    }

    //企业未来
    public function aboutFuture()
    {
        return $this->fetch('aboutFuture');
    }

    //产品中心
    public function productlist()
    {
        $view  = new View();
        $class = db('goodsclass')->select();
        $cid   = input('cid');
        $M     = db('goods');
        if ($cid > 0) {
            $M->where('cid', $cid);
        }
        foreach ($class as &$v) {
            if ($cid == $v['id']) {
                $v['isHave'] = 1;

            } else {
                $v['isHave'] = 0;

            }
        }
        $M->order('id desc');
        $lists = $M->paginate(10);
        $view->assign('class', $class);
        $view->assign('lists', $lists);
        return $view->fetch('product-list');
    }

    //产品详情
    public function productListDetails()
    {
        $view  = new View();
        $id    = input('id');
        $cid   = input('cid');
        $class = db('goodsclass')->select();
        if ($id > 0) {
            $info = db('goods')->where('id', $id)->find();
            //上一条
            $prve = db('goods');
            if ($cid > 0) {
                $prve->where('cid', $cid);
            }
            $prve = $prve->where('id', '<', $id)
                ->order('id desc')
                ->field(['id', 'title'])
                ->find();
            $next = db('goods');
            if ($cid > 0) {
                $next->where('cid', $cid);
            }

            $next = $next->where('id', '>', $id)
                ->order('id asc')
                ->field(['id', 'title'])
                ->find();

            $view->assign('info', $info);
            $view->assign('prve', $prve);
            $view->assign('next', $next);
            $view->assign('class', $class);
            return $view->fetch('product-list-details');

        } else {
            return '这条新闻不见了';
        }

//        return $view->fetch('product-list-details');
    }


    //新闻
    public function newslist()
    {
        $view  = new View();
        $lists = db('news')
            ->order('id desc')
            ->paginate(10);
        $view->assign('lists', $lists);

        return $view->fetch('news-list');
    }

    //新闻-公司新闻
    public function newsCompany()
    {
        $view  = new View();
        $lists = db('news')
            ->where('type', 2)
            ->paginate(10);
        $view->assign('lists', $lists);
        return $view->fetch('news-company');
//        return $this->fetch('news-company');
    }

    //新闻-行业新闻
    public function newsTrade()
    {
        $view  = new View();
        $lists = db('news')
            ->where('type', 1)
            ->order('id desc')
            ->paginate(10);
        $view->assign('lists', $lists);
        return $view->fetch('news-trade');
//        return $this->fetch('news-trade');
    }

    //新闻-详情
    public function newsListDetails()
    {
        $view = new View();
        $id   = input('id');
        if ($id > 0) {
            $info = db('news')->where('id', $id)->find();
            //上一条
            $prve = db('news')->where('type', $info['type'])
                ->where('id', '<', $id)
                ->order('id desc')
                ->field(['id', 'title'])
                ->find();
            $next = db('news')
                ->where('type', $info['type'])
                ->where('id', '>', $id)
                ->order('id asc')
                ->field(['id', 'title'])
                ->find();
            $view->assign('info', $info);
            $view->assign('prve', $prve);
            $view->assign('next', $next);

            return $view->fetch('news-list-details');

        } else {
            return '这条新闻不见了';
        }


        //return $this->fetch('news-list-details');

    }


    //联系我们
    public function contact()
    {
        $view = new View();
        return $view->fetch();
    }

    //留言板-在线留言
    public function contactMessage()
    {
        return $this->fetch('contact-message');
    }

    //提交留言
    public function postMessage()
    {
        $data = input('post.');
        if (empty($data['content'])) {
            return json(['code' => 1, 'msg' => '请输入您要提交的内容']);
        }
        $data['address'] = getIPLoc_sina(get_ip());
        $M               = new Message();
        if ($M->addedit($data)) {
            return json(['code' => 0, 'msg' => '留言成功！感谢您的宝贵建议']);
        } else {
            return json(['code' => 1, 'msg' => '服务器繁忙，请稍后再试']);
        }
    }


}
