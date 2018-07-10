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
use think\Cache;
use think\Controller;
use app\db\validate\Token as TokenValidata;
use think\Request;

class Token extends Controller
{
    public function createToken($code=''){
        (new TokenValidata())->goCheck();
        $tokenService = new UserToken($code);
        $token = $tokenService->getToken();
        return json($token);
    }

    public function getUserInfo(){
        $token =  Request::instance()->header('token');
        $result = Cache::get($token);
        if(!is_array($result)){
            $result = json_decode($result,true);
        }
       $uid = $result['uid'];
    }
}