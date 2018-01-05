<?php

namespace app\admin\controller;

use app\model\Goodsclass;
use think\View;

class Goods extends Admin
{
    public function index()
    {
        return $this->fetch();
    }


    /***
     * 分类管理
     */
    public function Classifiedmanagementclass()
    {
        $p     = input('p');
        $sql   = db('goodsclass')
            ->fetchSql(true)
            ->select();
        $lists = [];
        $page  = $this->getPage($sql, 10, 1);
        $lists = db('goodsclass')->paginate($p, $page['count'], ['list_rows' => 10]);

        $view = new View();
        $view->assign('lists', $lists);
        $view->assign('page', $lists->render());
        return $view->fetch();

    }

    /***
     * 添加编辑
     * @return \think\response\Json
     */
    public function classAddedit()
    {
        $data            = input('post.');
        $data['addtime'] = NOW_TIME;
        $GC              = new Goodsclass();
        if ($GC->addedit($data)) {
            return json(['code' => 0, 'msg' => '保存分类成功']);
        } else {
            return json(['code' => 1, 'msg' => '保存分类失败']);
        }
    }

    /***
     * 删除
     */
    public function del()
    {
        $id = input('id');
        $GC = new Goodsclass();
        if ($GC->del($id)) {
            return json(['code' => 0, '删除完成']);
        } else {
            return json(['code' => 1, '删除失败']);
        }
    }

    /***
     * 产品管理
     */
    public function lists()
    {
        $seach = input('seach');
        $view  = new View();
        $model = db('goods g');
        if ($seach) {
            $model->where('g.title', 'like', $seach);

        }
        $model->join('lsg_goodsclass gc', 'g.cid=gc.id','left')
            ->field(['g.*', 'gc.title as className']);

        $lists = $model->paginate(10);

        $view->assign('lists', $lists);

        $view->assign('seach', $seach);
        $view->assign('page', $lists->render());
        return $view->fetch();

    }

    //新增、编辑
    public function addedit()
    {
        $view = new View();
        $id   = input('id');
        if ($id > 0) {
            $info = db('goods')->where('id', $id)->find();
            $view->assign('info', $info);
        }
        $class = db('goodsclass')->select();
        if (isset($info)) {
            foreach ($class as $value) {
                if ($info['cid'] == $value['id']) {
                    array_unshift($class, $value);
                    break;
                }
            }
        }
        $view->assign('class', $class);
        return $view->fetch();
    }

    //执行新增编辑
    public function postaddedit()
    {
        $data            = input('post.');
        $data['content'] = $data['editorValue'];

        unset($data['editorValue']);
        $G = new \app\model\Goods();
        if ($G->addedit($data)) {
            return json(['code' => 0, 'msg' => '操作完成']);
        } else {
            return json(['code' => 1, 'msg' => '操作失败']);
        }
    }

    //产品删除
    public function goodsdel()
    {
        $id = input('id');
        $G  = new \app\model\Goods();
        if ($G->del($id)) {
            return json(['code' => 0, 'msg' => '删除完成']);
        } else {
            return json(['code' => 1, 'msg' => '删除失败']);
        }
    }
}
