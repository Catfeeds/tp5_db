<?php
/**
 * @Class: UserMissException.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/19
 */

namespace app\lib\exception;
use app\lib\exception\BaseException;
class UserMissException extends BaseException
{
    public $code = 400;
    public $msg = '请求的User不存在';
    public $errorCode = 40000;
}