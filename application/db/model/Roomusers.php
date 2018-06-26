<?php

namespace app\db\model;

use think\Model;

class Roomusers extends Model
{
    //
    // protected $hidden = ['email'];
    protected $visible = ['crid','mobile','email','cstatus','roominfo','status'];

    public function roominfo(){
        // return $this->hasMany('classrooms','crid','crid');
        return $this->belongsTo('classrooms','crid','crid');
    }
    /**
     *  读取器的使用 方法格式 get[字段名称首字母大写]Attr
     */
     public function getCstatusAttr($value,$data){
        return $value>=1 ? '正常状态' : '非正常状态' ;
     }

    /**
     * @description 修改器 可以设置对不存在的属性进行赋值 调用方式 模型对象->属性名 RoomusersObj->Status;
     * @param $value
     * @param $data
     * @return string
     */
     public function getStatusAttr($value,$data){
         return $data['cstatus']>=1 ? '正常状态1' : '非正常状态1';
     }
}
