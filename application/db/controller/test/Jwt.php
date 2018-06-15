<?php
/**
 * @Class: Jwt.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/15
 */

namespace app\db\controller\test;

use app\db\controller\test\AuthBase;
use function var_dump;

class Jwt extends AuthBase
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
        import('jwt.Jwt', EXTEND_PATH);
        $jwt = (new \Jwt(config('jwt')['key']))->encode(['uid' => 123456]);
        // Log::write('dfdfdf');
        renderjson(200,'success',['jwt'=>$jwt]);
    }

    /**
     * @description jwt 测试
     */
    private function checkJwt()
    {
        var_dump($this->userinfo);
        // Log::write('dfdfdf');
    }
    // -----------------------------jwt 测试结束----------------------------
}