<?php
/**
 * @Class: Test.php
 * @Description: Test.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/23
 */

namespace app\db\validate;
use app\lib\exception\ParameterException;

class Test extends BaseValidate
{
    protected $rule = [
        'users' => 'checkValues'
    ];

    protected $singleRule = [
        'name'=>'require|isstring',
        'value'=>'require|isstring'
    ];

    /**
     * @description 首先对整个二维数组数据进行验证
     * @param $values
     * @return bool
     * @throws ParameterException
     */
    public function checkValues($values)
    {
        if (empty($values)) {
            throw new ParameterException([
                'code' => 401, 'msg' => '请求参数不能为空'
            ]);
        }
        if (!is_array($values)) {
            throw new ParameterException([
                'code' => 401, 'msg' => '参数列表格式不正确'
            ]);
        }
        foreach ($values as $val) {
            $this->checkSingle($val);
        }
        return true;
    }


    /**
     * @description 对每个元素进行验证
     * @param $value
     * @return bool
     * @throws ParameterException
     */
    public function checkSingle($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if (!$result) {
            throw new ParameterException();
        }
        return true;
    }
}