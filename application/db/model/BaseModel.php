<?php
/**
 * @Class: BaseModel.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/27
 */

namespace app\db\model;


use think\Model;

class BaseModel extends Model
{
    protected function getImgPrefix($value,$data){
        $imgUrl = $value;
        if(false===strpos($value,'//')){
            $imgUrl = 'http://gl.xlh.net/public/images'.$value;
        }
        return $imgUrl;
    }
}