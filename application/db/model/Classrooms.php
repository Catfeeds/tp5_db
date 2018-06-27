<?php
/**
 * @Class: Classrooms.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/25
 */

namespace app\db\model;

use app\db\model\BaseModel;
use Think\Model;

class Classrooms extends BaseModel
{
    protected $hidden=[];
    protected $visible=['crid','crname','domain','fulldomain','cface'];

    /**
     * @description 获取器 根据实际需要 确定是否拼接图片前缀
     * @param $value
     * @param $data
     * @return string
     */
    public function getCfaceAttr($value,$data){
        return $this->getImgPrefix($value,$data);
    }
}