<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Init extends Controller
{
    protected $uid = 0;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $token = input('token');

//        if (empty($token)) {
////            return json(['code'=>1,'msg'=>'缺少token参数']);
//
//
//            die(json_encode(['code' => 1, 'msg' => '缺少token参数'], JSON_UNESCAPED_UNICODE));
//        }
//        $sql = db('login')
//            ->whereOr(['oldtoken' => $token])
//            ->whereOr(['nowtoken' => $token])
//            ->fetchSql(true)
//            ->select();
//        dump($sql);
//        $sql = db('login')
//            ->fetchSql(true)
//            ->where(['id' => 1])
//            ->update(['oldtoken' => ['exp', 'old+1']]);
//        dump($sql);

    }

    /***
     * 空操作方法
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        echo '不存在方法' . $name;

    }

}
