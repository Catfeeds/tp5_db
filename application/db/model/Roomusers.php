<?php

namespace app\db\model;

use think\Model;

class Roomusers extends Model
{
    //
    protected $hidden = ['email'];
    // protected $visible = ['crid','mobile','email'];

    public function roominfo(){
        return $this->hasOne('classrooms','crid','crid');
    }
    /**
     *  读取器的使用
     */
     public function getCstatusAttr($value,&$data){
        return $value>1 ? '正常状态' : '非正常状态' ;
     }
}
