<?php
/**
 * @Class: Dir.php
 * @Description: Dir.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/27
 */

namespace app\db\validate;


class Dir  extends BaseValidate
{
    protected $rule = [
        'dirname'=>'isstring'
    ];
    protected $message = [
        'dirname'=>'非法的目录参数'
    ];
}