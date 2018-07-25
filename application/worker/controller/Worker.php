<?php

namespace app\worker\controller;
use think\worker\Server;

class Worker extends Server
{

    // protected $socket = 'http://0.0.0.0:2346';
    protected $socket = 'websocket://0.0.0.0:2000';
    public function index(){
        parent::__construct();
    }

    /**
     * 收到信息
     * @param $connection
     * @param $data
     * 命令行运行 php server.php 即可
     */
    public  function onMessage($connection, $data)
    {
        $connection->send('你好 workerman!');
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {

    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

    }
    // public function start(){
    //     self::runAll();
    // }

}

/** 上面的类暂时还不知道怎么使用 目前链接使用下面的方式 入门测试
 * 切换到 public目录 执行 php server.php start 浏览器输入对应网址加端口即可
 */
// $worker = new WS('http://gl.xlh.net:2346');
// $worker->count = 4;
// $worker->onMessage = function($connection,$data){
//     $connection->send('你好 my workerman!');
// };
// WS::runAll();