<?php
/**
 * @Class: IdIsInt.php
 * @Description: IdIsInt.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/10
 */

namespace app\db\validate;


class IdIsInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|positive_integer'
    ];
    protected $message = [
        'id' => 'id必须是正整数'
    ];
}