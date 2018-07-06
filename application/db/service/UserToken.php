<?php
/**
 * @Class: Token.php
 * @Description: Token.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/5
 */

namespace app\db\service;


use app\db\model\Users;
use app\lib\exception\WxException;
use function json_encode;
use Think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppId,$this->wxAppSecret,$this->code);
    }

    public function getToken()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('请求失败,微信服务器内部错误');
        }else{
            if(isset($wxResult['errcode'])){
                throw new WxException([
                    'errorCode' => $wxResult['errcode'],
                    'msg' => $wxResult['errmsg']
                ]);
            }else{
                return $this->getUserToken($wxResult);
            }
        }
    }

    /**
     * @description 生成token
     * @param $data
     */
    private function getUserToken($data){
        $tokenArr = $data;
        $openid = $data['openid'];
        $session_key = $data['session_key'];
        $user = new Users();
        $result = $user->getUserById($openid);
        if(!$result){
            $newuser=$user::create([
                'wxopid'=>$openid
            ]);
            $tokenArr['uid'] = $newuser['uid'];
        }else{
            $tokenArr['uid'] = $result['uid'];
        }
        $key = self::grantToken();
        $value = md5(json_encode($tokenArr));
        $bool = false;
        while(!$bool){
            $res = cache($key,$value,config('setting.token_expire'));
            $bool = &$res;
        }
        return json($value,200,'cuccess');//返回token
    }

}