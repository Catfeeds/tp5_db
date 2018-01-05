<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/4
 * Time: 12:29
 */

namespace app\model;

use think\Model;

/***
 * 模型名称：登录表模型
 * Class Demo
 * @package app\model
 */

class Login extends Model
{
    //数据库表名不含表前缀
   // protected $table = 'snake_demo';



    /****
     * 新增/更新
     * @param $data
     * @return bool|mixed
     */
    public function addedit($data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            return $this->update($data) === false ? false : true;
        } else {
            $data['addtime'] = NOW_TIME;
            $this->data($data);
            $this->save();
            if ($this->id > 0) {
                return $this->id;
            } else {
                return false;
            }
        }

    }

    /****
     * 删除数据
     * @param $data
     * @return bool
     */
    public function del($data)
    {
        return $this::destroy($data) === false ? false : true;
    }
}