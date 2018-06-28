<?php
/**
 * @Class: Groups.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/28
 */

namespace app\db\model;
use app\db\model\BaseModel;

class Groups extends BaseModel
{
    protected $hidden = ['upid','image'];
    protected $visible = [];
}