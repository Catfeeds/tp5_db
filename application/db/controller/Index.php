<?php

namespace app\db\controller;

use app\model\Demo;
use function MongoDB\BSON\toJSON;
use think\Controller;
use think\View;
use think\Db;

class Index extends Controller
{
    /***
     * 空操作方法
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        echo $name.'控制器不存在';
        //$this->index();

    }

    public function index()
    {
        //判断是否调试模式
        if (config('app_debug') == false) {

            return '该功能只能调试模式下，开发人员使用';;
        }
        //要查询的数据库
        $dbname = config('database');
        $dbname = $dbname['database'];

        $seach = input('seach');
//        debug('begin');
        if ($seach) {
            $where = " AND (`TABLE_NAME` regexp  '" . $seach . "' OR `TABLE_COMMENT` regexp  '" . $seach . "' )";
            $tables1 = Db::query('SELECT  TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema=\'' . $dbname . '\' AND COLUMN_NAME REGEXP \'' . $seach . '\'');
            if (!empty($tables1)) {
                $tableName = '';
                foreach ($tables1 as $value) {
                    $tableName .= '\'' . $value['TABLE_NAME'] . '\',';
                }

                $tableName = substr($tableName, 0, -1);
                $tables1 = Db::query('SELECT TABLE_NAME,TABLE_COMMENT,TABLE_ROWS  FROM information_schema.TABLES WHERE table_schema=' . '\'' . $dbname . '\'' . ' AND `TABLE_NAME` in(' . $tableName . ')');
            }
        }

        $tables = Db::query('SELECT TABLE_NAME,TABLE_COMMENT,TABLE_ROWS FROM information_schema.TABLES WHERE table_schema=' . '\'' . $dbname . '\'' . (isset($where) ? $where : ''));

        if (isset($tables1) && !empty($tables1)) {
            $tables = array_merge($tables, $tables1);
        }
        foreach ($tables as $key => &$value) {

            $arr = Db::query('SELECT * FROM INFORMATION_SCHEMA.Columns WHERE table_name=' . '\'' . $value['TABLE_NAME'] . '\' AND table_schema=' . '\'' . $dbname . '\'');
            $value['field'] = $arr;

        }
//        debug('end');
//        echo debug('begin','end').'s'; die;
        $view = new View();
        $view->lists = $tables;
        $view->seach = $seach;
        return $view->fetch();

    }

    //联想功能
    public function getData()
    {
        //要查询的数据库
        $dbname = config('database');
        $dbname = $dbname['database'];
        $search = input('search');
        $type   = input('type',1);

        if ($search) {
            switch ($type){
                case 2:
                    $where = " AND (`TABLE_NAME` regexp  '" . $search . "' OR `TABLE_COMMENT` regexp  '" . $search . "' )";
                    $tables = Db::query('SELECT  *  FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema=\'' . $dbname . '\' AND COLUMN_NAME REGEXP \'' . $search . '\'');
                    break;
                case 1:
                    $where = " AND (`TABLE_NAME` regexp  '" . $search . "' OR `TABLE_COMMENT` regexp  '" . $search . "' )";
                    $tables = Db::query('SELECT  * FROM information_schema.TABLES WHERE table_schema=\'' . $dbname   . '\''.$where);

                    break;
                default:
                    $tables = [];
            }
            if(empty($tables)){
                return json(['code'=>1]);
            }else{
                return json(['code'=>0,'data'=>$tables]);
            }

        }else{
            return json(['code'=>1]);
        }
    }

    public function testHttp()
    {

        return $this->fetch();
    }

    public function getcallbackdata()
    {
        $data = input('request.');
        switch ($data['type']) {
            case 'GET':
                $r = doPost($data['url'], $data, [], 'GET');
                break;
            case 'POST':
                $r = doPost($data['url'], $data, [], 'POST');
                break;
        }

        echo '<pre>';
        var_dump($r);
        echo '<pre>';
    }

    /**
     * @describe:序列化工具
     * @User:gl
     */
    public function getSerialize(){
       // dump(json_encode(['a'=>'我是好人啊']));
        return $this->fetch();
    }

    /**
     * @describe:处理部分
     * @User:gl
     */
    public function postData(){
        $txtContent = $_POST['txt'];
        $type       = input('type');
        if($type == 2)
        $txtContent = str_replace('\\','',$txtContent);
        $ret = @unserialize($txtContent);
        if(empty($ret)){
            $ret = json_decode($txtContent,true);
        }
        $str = '<pre>';
        $str .= $ret?print_r($ret,true):($type==2?'非正确格式序列化字符串':'非正确格式JSON字符');
        $str .= '<pre>';

        return json(['code'=>0,'msg'=>'','data'=>$str]);
    }
    private function _getString($vars,$label = '', $return = false){

            if (ini_get('html_errors')) {
                $content = "<pre>\n";
                if ($label != '') {
                    $content .= "<strong>{$label} :</strong>\n";
                }
                $content .= htmlspecialchars(var_dump($vars, true));
                $content .= "\n</pre>\n";
            } else {
                $content = $label . " :\n" . var_dump($vars, true);
            }
             return $content;

        }


}
