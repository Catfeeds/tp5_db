<?php
/**
 * @Class: BaseValidate.php
 * @Description: 基类验证器
 * @Author: gl
 * @Date: 2018/6/15
 */

namespace app\db\validate;


use think\Validate;
use think\Request;

class BaseValidate extends Validate
{
    public function goCheck($data = null)
    {
        if (null !== $data) {
            $params = $data;
        } else {
            $request = Request::instance();
            $params = $request->param();
        }
        $result = $this->check($params);
        if ($result) {
            return true;
        } else {
            renderjson(400, 'fail', $this->getError());
        }
    }
}