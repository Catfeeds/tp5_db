<?php
/**
 * @Class: Token.php
 * @Description: Token.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/5
 */

namespace app\db\validate;
use app\db\validate\BaseValidate;

class Token extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isString'
    ];
    protected $message = [
        'code.require' => '缺少code参数',
        'code.isString' => '非法的code参数',
    ];

    protected function isString($value, $rule, $data, $field)
    {
        if ('' === $value) {
            return false;
        } elseif (is_string(trim($value))) {
            return true;
        } else {
            return false;
        }
    }
}