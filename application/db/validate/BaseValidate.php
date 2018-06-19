<?php
/**
 * @Class: BaseValidate.php
 * @Description: 基类验证器
 * @Author: gl
 * @Date: 2018/6/15
 */

namespace app\db\validate;


use app\lib\exception\ParameterException;
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
        $result = $this->batch()->check($params);//批量验证
        $result = $this->check($params);
        if ($result) {
            return true;
        } else {
            // renderjson(400, 'fail', $this->getError());
            $e = new ParameterException([
                'msg' => $this->getError(),
                // 'code' => 400,
                // 'errorCode' => 10002, //自由定义
            ]);
            throw $e;
        }
    }
}