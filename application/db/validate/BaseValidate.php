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
    /**
     * @description  自定义的检验方法
     * @param null $data
     * @return bool
     * @throws ParameterException
     */
    protected $params = null;
    public function goCheck($data = null)
    {
        if (null !== $data) {
            $this->params = $data;
        } else {
            $request = Request::instance();
           $this->params = $request->param();
        }
        $result = $this->batch()->check($this->params);//批量验证
        // $result = $this->check($params);
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

    /**
     * @description 判断字符串是否是 正负整数、正负小数、正负数
     * @param      $num 字符串
     * @param bool $positive 正负开关 true 正 false 负
     * @param bool $int 整数/小数判断开关
     * @return bool
     */
    protected function positive_integer($num, $positive = true, $int = true)
    {
        if ($num) {
            if (is_numeric($num)) {
                if ($positive && $num > 0 && !$int) {
                    return true;        //正数
                } elseif ($int && floor($num) == $num && !$positive) {
                    return true;        //整数
                } elseif ($positive && $int && $num > 0 && floor($num) == $num) {
                    return true;    //正整数
                } elseif ($positive && $int && $num > 0 && floor($num) != $num) {
                    return true;    //正小数
                } elseif ($positive && $num < 0 && !$int) {
                    return false;       //负数
                } elseif ($int && floor($num) != $num && !$positive) {
                    return false;       //小数
                } elseif ($positive && $int && $num < 0 && floor($num) != $num) {
                    return false;   //负小数
                } elseif ($positive && $int && $num < 0 && floor($num) == $num) {
                    return false;   //负整数
                } else {
                    return false; //未知类型的数字
                }
            } else {
                return false;   //不是数字
            }
        } else{
            return false;    //表单未填写
        }

    }

    /**
     * @description 验证是否是字符串
     * @param $value
     * @return bool
     * @throws ParameterException
     */
    public function isstring($value){
        if('' == $value || !is_string($value)){
            throw new ParameterException([
                'code'=>400,'msg'=>'参数格式不正常'
            ]);
        }
        return true;
    }
    /**
     * @description 返回仅在验证规则中的数据
     * @param $data
     * @return array
     */
    public function getDataByRule($requestData=null){
        if($requestData==null){
            $data = $this->params;
        }else{
            $data = $requestData;
        }
        if(!$data){
            throw new ParameterException();
        }

        $newDate = [];
        foreach($data as $key=>$value){
            if(isset($data[$this->rule[$key]])){
                $newDate[$key] = $value;
            }
        }
        return $newDate;
    }

}