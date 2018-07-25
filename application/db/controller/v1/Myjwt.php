<?php
/**
 * @Class: Myjwt.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/15
 */

namespace app\db\controller\v1;
// 注意 如果想通过命名空间使用第三方类库 记得在 thinkphp\base 中 注册 对应的第三方类库的命名空间 同时
use extend\jwt\Jwt;
use app\db\controller\v1\AuthBase;
use const EXTEND_PATH;
use think\Controller;
use app\db\validate\Users;

class Myjwt extends Controller
{
    /**
     * @description  ------------------jwt 测试开始----------------------
     */
    public function testjwt()
    {
        $method = request()->method();
        switch ($method) {
            case 'GET':
                $this->getJwt();
                break;
            case 'POST':
                $this->checkJwt();
                break;
        }
    }

    /**
     * @description jwt 测试
     */
    private function getJwt()
    {
        // import('jwt.Jwt',EXTEND_PATH);
        // $jwt = (new \Jwt(config('jwt')['key']))->encode(['uid' => 123456]); // 使用这种方式引入第三方类库 切记 第三发类不能设置 命名空间
        $jwt = (new Jwt(config('jwt')['key']))->encode(['uid' => 123456]);
        // import('test.Test',EXTEND_PATH);
        // // $res = \Test->getOk();
        // $res = (new \Test())::getHello();
        // echo $res;die;
        // Log::write('dfdfdf');
        renderjson(200,'success',['jwt'=>$jwt]);
    }

    /**
     * @description jwt 测试
     */
    private function checkJwt()
    {
        (new Users())->scene('test')->goCheck();
        // var_dump($this->userinfo);
        // Log::write('dfdfdf');
    }
    // -----------------------------jwt 测试结束----------------------------
}