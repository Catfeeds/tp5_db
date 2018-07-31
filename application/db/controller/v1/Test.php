<?php
/**
 * Created by PhpStorm.
 * Test.php
 * Author: gl
 * Date: 2018/1/13
 * Description:
 */

namespace app\db\controller\v1;


use function action;
use app\db\model\Users as User;
use app\api\controller\CacheRedis;

// use think\Controller;
// use think\Exception;
// use think\Loader;
// use think\Config;
// use think\Request;
// use think\Log;
use app\db\validate\IdIsInt;
use think\{
    Controller, Exception, Loader, Config, Request, Log, Verify
};//php 7 支持此种模式
use app\db\validate\Users;
use app\db\model\Test as TestModel;
use extend\jwt\Jwt;
use app\db\validate\Test as TestValidate;
use app\lib\exception\UserMissException;


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

    /**
     * @description  分页
     */
    public function page(){
        $user = new User();
        $list = $user->getpage();
        // $list = $user->where('status','=',1)->order('uid desc')->paginate(10); //等价上面 使用模型
        // return json($list);

        return $this->fetch('test/page',['userlist'=>$list]);
    }
    //分页数据模板
    public function pageindex()
    {
        //$page = input('page', 0);
        //$limit = input('limit', 10);
        //$count = $this->user->userconut();
        //$list = $this->user->ajaxlist($page, $limit);
        //$list = json_encode($list);
        //$this->assign('count', $count);
        //$this->assign('list', $list);
        //$this->assign('page',$page);
        //$this->assign('limit',$limit);
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

        echo request()->domain();//获取请求域名
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
        // $list = $user->with('roomuser')->get(1);
        renderjson(200, 'success', $list);
        // log_message(var_export(json($list),true));
        // return json($list);//有问题 返回不了数据


    }

    /**
     * @description 添加数据
     */
    private function add()
    {
        // $data = request()->param();
        // $validate = Loader::validate('Users');
        // if (false === $validate->scene('add')->check($data)) {
        //     $error = $validate->getError();
        //     renderjson(200, 'error', $error);
        //     dump($validate->getError());
        // }
        // $result = $this->validate($data,'Users.add');
        // if (true !== $result) {
        //     renderjson(200, 'error', $result);
        // }else{
        //     //执行添加
        // }
        (new Users())->scene('add')->goCheck();//不通过 自动输出错误信息 并终止代码
    }

    /**
     * @description 修改数据
     */
    private function update()
    {
        // $data = request()->param();
        // // $result = $this->validate($data,'Users.edit',[],true);//批量验证
        // $result = $this->validate($data,'Users.edit');
        (new Users())->scene('edit')->goCheck();
        // if (true !== $result) {
        //     renderjson(200, 'error', $result);
        // }else{
        //     //执行修改
        // }
    }

    /**
     * @description 删除数据
     */
    private function delete()
    {
        // $data = request()->param();
        // $validate = Loader::validate('Users');
        // if (false === $validate->scene('del')->check($data)) {
        //     $error = $validate->getError();
        //     renderjson(200, 'error', $error);
        // }
        (new Users())->scene('del')->goCheck();
    }

    /**
     * @description
     */
    public function test4(){
        // $jwt = config('jwt')['key'];
        // dump($jwt);die;
        // $class = new \Jwt();
        (new IdIsInt())->goCheck();
        $jwt = (new Jwt(config('jwt')['key']))->encode(['uid' => 123]);
        echo $jwt;die;
        $request = request()->param();
        $data = [
            'uid' => $request['id']
        ];
        (new Users())->scene('get')->goCheck($data);//自定义传参 默认会传递 ['id'=>21] 这样与验证规则不符
    }
    public function test5(){
        echo '路由规则验证';
    }
    // 异常测试
    public function testException()
    {
        // log_message(1111);
        $userInfo = $this->user->getUserById(12);
        if(!$userInfo){
            throw new UserMissException();
        }
        return $userInfo;
    }

    /**
     * @description 关联模型测试
     * @param $id
     */
    public function orm($id){
        $request = Request::instance()->param();
        $param['status'] = isset($request['status']) ? (int)$request['status'] : 1;
        $param['sex'] = isset($request['sex']) ? (int)$request['sex'] : 0;
        $param['q'] = isset($request['q']) ? trim($request['q']) : 't';
        $user = User::getUserList($param);
        // echo (new User)->Ceshi;die;// 获取不存在的属性
        // $user = User::getUserByUid2($id);
        // if($user){
        //    return redirect('db/index/redirect2')->remember();//记住当前的URL重定向
        // }
        //重写的json方法 第一个code是数据加入的code信息 第二个code 是请求返回状态值
        return json($user,201,'success',200);
        // return xml($user,200);
        // renderjson(200,'success',$user);
    }

    /**
     * @description  tp5 cache使用
     * @return mixed
     */
    public function basetest(){
        $key='123456';
        $value = md5('fdsgfdsgdfgtg');
        // echo config('database.hostname');die;
        // $boolean = cache($key,$value,config('setting.token_expire'));
        $bool = false;
        while(!$bool){
            $res = cache($key,$value,config('setting.token_expire'));
            $bool = &$res;
        }
        // echo token();die;
        // return cache($key);
        return json($key,400,'sss');
    }

    /**
     * @description 获取验证码 并保存到session中
     */
    public function getCaptcha(){
        $capConfig = config('captcha');
        $Verify = new Verify($capConfig);
        $Verify->entry("admin_login");
        exit();
    }

    /**
     * @description 校验验证码
     */
    public function chechCaptcha(){
        $verify = new Verify();
        if (!$verify->check($code, "admin_login")) {
           echo '验证码错误';
        }
    }

    /**
     * @description
     * @return string|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function testSql(){
        // $list = User::all([1,2,3]);
        // $list = User::where('uid','=','12')->find();
        // $list = TestModel::where('id',1)->find();
        $list = db('test')->cache(true,10)->select([1,2]);//将查询结果缓存起来 10秒过期 不设置永久
        db('test')->update(['id'=>1,'name'=>'我被改变了']);
        return json($list);
    }

    /**
     * @description 添加数据 验证时间戳自动插入 输出自动转换
     * @return string|\think\response\Json
     */
    public function addData(){
        $testModel = new TestModel();
        //------------单条数据插入--------------
        // $testModel->name = '张三';
        // $testModel->value = '{"id":"1","name":"zhangsan"}';
        // $testModel->save();
        // $id = $testModel->id;//生成的主键id
        // $info = $testModel::find($id);
        //--------------单条插入结束------------

        //多条插入
        $data = [
            ['name'=>'李四','value'=>'hehela'],
            ['name'=>'王五','value'=>'wangwu']
        ];
        (new TestValidate())->goCheck(['users'=>$data]);
        $result = $testModel->saveAll($data);
        return json($result);
    }

    /**
     * @description 增删改查示例操作
     * @return string|\think\response\Json
     */
    public function getTestData(TestModel $testModel){
        // var_dump($testModel);die;//获取TestModel 实例
        // $list = TestModel::limit(10)->select();
        $list = (new TestModel())->limit(10)->select();
        // $res = TestModel::destroy([6,7,8]);//批量删除
        // $res = TestModel::destroy('6,7,8');//批量删除
        // $res = TestModel::update(['name'=>'呵呵哒2','id'=>1]);//更新方法1
        // $res = (new TestModel())->save(['name'=>'呵呵哒2'],['id'=>2]);//更新方法2
        // $res = (new TestModel())->isUpdate(true)->save(['name'=>'呵呵哒4','id'=>4]);//更新方法3
        // $res = (new TestModel())->where(['id'=>2])->lock(true)->update(['name'=>'呵呵哒3']);//更新方法4(加锁)
        // $res = (new TestModel())->where('id','=','3')->setInc('total',-7);//total字段自加 (也会成功 下同)
        // $res = (new TestModel())->where('id','=','3')->setDec('total',3);//total字段自减
        // $res = action('worker/Index/test',['id'=>2]);//跨模块调用
        // return json($list);
        $json = json($list);
        $arr=json_decode($json->getContent(),true);//将json对象转为数组 注意：不加getContent()会报错的
        var_dump($arr);
    }
}