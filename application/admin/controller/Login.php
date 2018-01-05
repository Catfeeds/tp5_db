<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function _empty()
    {
        $this->redirect(url('Error/index'));
    }

    /***
     * 生成验证码
     * @return \think\Response
     */
    public function verify()
    {
        $captcha           = new Captcha();
        $captcha->useCurve = false;
        $captcha->useNoise = false;
        $captcha->length   = 4;
        return $captcha->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串，$id多个验证码标识
    protected function check_verify($code, $id = '')
    {
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }

    /***
     * 登录
     * @return \think\response\Json
     */
    public function login()
    {
        $data = input('');
//        return json(['code'=>1,'msg'=>'333']);
        if ($this->check_verify($data['code']) == false) {
            return json(['code' => 1, 'msg' => '验证码错误']);
        }
        $info = db('adminuser')
            ->where('user', $data['user'])
            ->find();
        if (empty($info)) {
            return json(['code' => 1, 'msg' => '不存在该账户']);
        }
        if (md5($data['password']) !== $info['password']) {
            return json(['code' => 1, 'msg' => '密码错误']);
        }
        session('adminuser', $info);
        return json(['code' => 0, 'msg' => '登录成功!']);
    }

}
