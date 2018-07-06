<?php
/**
 * @Class: Token.php
 * @Description: Token.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/5
 */

namespace app\db\controller\v1;


use app\db\service\UserToken;
use think\Controller;
use app\db\validate\Token as TokenValidata;
class Token extends Controller
{
    public function getToken($code=''){
        (new TokenValidata())->goCheck();
        $tokenService = new UserToken($code);
        $result = $tokenService->getToken();
        if(!empty($result)){
            return $result;
        }
    }
}