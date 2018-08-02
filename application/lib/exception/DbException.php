<?php
/**
 * @Class: DbException.php
 * @Description: DbException.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/8/1
 */

namespace app\lib\exception;


class DbException extends BaseException
{
    public $code = 400;
    public $msg = '数据库事务处理失败';
    public $errorCode = 40001;
}