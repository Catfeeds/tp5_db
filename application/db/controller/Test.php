<?php
/**
 * Created by PhpStorm.
 * Test.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\controller;

use think\Controller;
use app\db\model\Users as User;
use app\api\controller\CacheRedis;
use think\Loader;
use think\Config;
use think\Request;

class Test extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    /**
     * @description 获取指定的配置
     */
    public function test()
    {
        echo '<script>alert(1)</script>';
        die;
        // dump(Config::get('database_foo'));
        $bank = config('bank');//获取其他配置 配置文件在 application/extra/
        dump($bank);
    }

    public function index()
    {
        return $this->fetch('test/index');
    }

    //前端vue瀑布流请求数据
    public function getlist()
    {
        $p = input('get.p');
        $p = $p ? $p : 0;
        $user = new User();
        $list = $user->userlist($p, 50);
        //trace($p,'info');die;
        echo json_encode($list);
    }

    //加载测试视图
    public function testlist()
    {   //log_message(6454545);//新的日志输出方式 改动版
        return $this->fetch('test/testlist');
    }

    //分页数据模板
    public function pageindex()
    {
        // $page = input('page', 0);
        // $limit = input('limit', 10);
        // $count = $this->user->userconut();
        // $list = $this->user->ajaxlist($page, $limit);
        // $list = json_encode($list);
        // $this->assign('count', $count);
        // $this->assign('list', $list);
        // $this->assign('page',$page);
        // $this->assign('limit',$limit);
        return $this->fetch('test/pageindex');
    }

    //ajax请求数据
    public function ajaxpage()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $count = $this->user->userconut();
        $list = $this->user->ajaxlist($page, $limit);
        $data = array(
            'code' => 0, 'msg' => 'ok', 'count' => $count, 'data' => $list
        );
        //trace(json_encode($data),'log');
        //log_message($data);die;
        echo json_encode($data);
    }

    //redis测试hash类型使用
    public function redis()
    {
        $options = array(
            'host' => '127.0.0.1', 'port' => 6379
        );
        $redis = new CacheRedis($options);
        $arr = array('id' => 1, 'score' => 98);
        $redis->hSet('study1', 'zhangsan', $arr);
        var_dump($redis->hGet('study1', 'zhangsan'));
    }

    /**
     * ajax上传文件
     */
    public function ajaxupload()
    {
        if (request()->isAjax()) {
            if (!file_exists('123.zip')) {
                move_uploaded_file($_FILES['part']['tmp_name'], '123.zip');
            } else {
                file_put_contents('123.zip', file_get_contents($_FILES['part']['tmp_name']), FILE_APPEND);
            }
            echo 'ok';
        } else {

            return $this->fetch('test/ajaxupload');
        }
    }

    /**
     * @description 路由测试
     */
    public function test2()
    {
        $request = Request::instance()->param();//获取任意类型的所有参数
        $request = request()->param();//获取任意类型的所有参数
        // $request = Request::instance()->get();//只获取get类型的参数 其他类型同理
        $ip = Request::instance()->ip();//获取请求ip
        $type = Request::instance()->method();//获取请求ip
        $request = input('param.');
        $request2 = input('get.');
        $request3 = input('post.');

        // echo request()->domain();//获取请求域名
        if (request()->isPost()) {
            echo '这是post请求';
            die;//判断是否是POST请求  isPut isGet isDelete isHeader ..
        }
        // echo $ip;die;
        echo $type;
        die;
        $id = input('id', '0', 'int');
        echo json_encode($request3, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @description 请求测试
     */
    public function test3()
    {
        $method = request()->method();
        switch ($method) {
            case 'GET':
                $this->getlist2();
                break;
            case 'POST':
                $this->add();
                break;
            case 'PUT':
                $this->update();
                break;
            case 'DELETE':
                $this->delete();
                break;
        }
    }

    /**
     * @description 获取列表
     */
    private function getlist2()
    {
        $param['page'] = input('page', 2, 'int');//默认值 参数验证
        $param['pagesize'] = input('pagesize', 50, 'int');//默认值 参数验证
        $user = new User();
        $list = $user->userlist($param);
        renderjson(200, 'success', $list);
    }

    /**
     * @description 添加数据
     */
    private function add()
    {
        $data = request()->param();
        // $validate = Loader::validate('Users');
        // if (false === $validate->scene('add')->check($data)) {
        //     $error = $validate->getError();
        //     renderjson(200, 'error', $error);
        //     dump($validate->getError());
        // }
        $result = $this->validate($data,'Users.add');
        if (true !== $result) {
            renderjson(200, 'error', $result);
        }else{
            //执行添加
        }
    }

    /**
     * @description 修改数据
     */
    private function update()
    {
        $data = request()->param();
        $result = $this->validate($data,'Users.edit');
        if (true !== $result) {
            renderjson(200, 'error', $result);
        }else{
            //执行修改
        }
    }

    /**
     * @description 删除数据
     */
    private function delete()
    {
        $data = request()->param();
        $validate = Loader::validate('Users');
        if (false === $validate->scene('del')->check($data)) {
            $error = $validate->getError();
            renderjson(200, 'error', $error);
        }
    }

}