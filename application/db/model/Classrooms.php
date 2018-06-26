<?php
/**
 * @Class: Classrooms.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/25
 */

namespace app\db\model;


use Think\Model;

class Classrooms extends Model
{
    protected $hidden=[];
    protected $visible=['crid','crname','domain','fulldomain','cface'];

}