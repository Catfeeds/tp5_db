<?php
/**
 * @Class: AuthBase.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/15
 */

namespace app\db\controller\v1;


use think\Controller;
use think\Config;
use app\db\model\User;
use extend\jwt\Jwt;
use think\Request;
class AuthBase extends Controller
{
    protected $userinfo;
    public function _initialize(){
        parent::_initialize();
        // import('jwt.Jwt', EXTEND_PATH);
        $authorization = getHeader('authorization');//或下面的方式
        $authorization = Request::instance()->header('authorization');
        $jwt = (new Jwt(Config::get('jwt')['key']))->decode($authorization);
        if(!$jwt){
            renderjson(403,'fail','用户登录授权失败');
        }
       if( $jwt['uid'] <= 0){
           renderjson(403,'fail','用户登录授权失败');
       }
       $userinfo = (new User())->getUserByUid($jwt['uid']);
       if(!$userinfo){
           renderjson(401,'fail','用户信息不存在');
       }else{
           $this->userinfo = $userinfo;
       }
    }
}