<?php
/**
 * @Class: WxException.php
 * @Description: WxException.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/5
 */

namespace app\lib\exception;
use app\lib\exception\BaseException;

class WxException extends BaseException
{
    public $code = 400;
    public $msg = '请求失败，微信服务器错误';
    public $errorCode = 40000;
}