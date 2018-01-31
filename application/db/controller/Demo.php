<?php

namespace app\db\controller;

use think\Controller;
use think\Db;
use think\Log;

class Demo extends Controller
{
    /***
     * 空操作方法
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        echo $name . '控制器不存在';
        //$this->index();

    }

    public function index()
    {
        //       $a = [['cc'=>444],['b'=>3]];
        //
        //       dump(array_column($a,'b'));
        //       dump(array_shift($a));
        $getH = function ($min) {
            $time = '';
            if ($min >= 3600) {
                $H = intval($min / 3600);
                $time .= $H . '小时';
                $min = $min % 3600;
            }
            if ($min >= 60) {
                $I = intval($min / 60);
                $time .= $I . '分';
                $min = $min % 60;
            }
            if ($min > 0) {
                $S = $min;
                $time .= $S . '秒';
            }
            return $time;

        };
        //        $m = input('m');
        //        var_dump($m);
        //        return $getH($m);
        return $this->fetch();


    }

    //联想功能
    public function getData()
    {
        //要查询的数据库
        $dbname = config('database');
        $dbname = $dbname['database'];
        $search = input('search');
        $type = input('type', 1);

        if ($search) {
            switch ($type) {
                case 2:
                    $where = " AND (`TABLE_NAME` regexp  '" . $search . "' OR `TABLE_COMMENT` regexp  '" . $search . "' )";
                    $tables = Db::query('SELECT  *  FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema=\'' . $dbname . '\' AND COLUMN_NAME REGEXP \'' . $search . '\'');
                    break;
                case 1:
                    $where = " AND (`TABLE_NAME` regexp  '" . $search . "' OR `TABLE_COMMENT` regexp  '" . $search . "' )";
                    $tables = Db::query('SELECT  * FROM information_schema.TABLES WHERE table_schema=\'' . $dbname . '\'' . $where);

                    break;
                default:
                    $tables = [];
            }
            if (empty($tables)) {
                return json(['code' => 1]);
            } else {
                return json(['code' => 0, 'data' => $tables]);
            }

        } else {
            return json(['code' => 1]);
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

    private function demo2($data1, $data2)
    {
        dump($data1);
        dump($data2);
    }

    public function demo3()
    {
        var_dump(explode(',', '343434'));

        //        $arr = array( array('id'=>1,'user'=>1),array('id'=>2,'user'=>2));
        //        $temp =   array_column($arr,'id');
        //        array_multisort($arr,$temp,'SORT_DESC',$newarray);
        //        var_dump($newarray);

    }

    private function doPost($url, $agent = '')
    {
        //$url = "http://www.yoursite.com/background-script.php";
        Log::write('url:' . $url);
        $ref_url = $url;
        $data = array(
            "key1" => "value1",
            "key2" => "value2",
        );
        $agent = 'user';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_REFERER, $ref_url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        curl_exec($ch);
        curl_close($ch);
    }

    public function asyncRequest()
    {
        return $this->fetch('index');
    }
}
