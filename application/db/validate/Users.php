<?php
/**
 * @Class: Users.php
 * @Description: user验证器
 * @Author: gl
 * @Date: 2018/6/11
 */
namespace app\db\validate;
use think\Validate;
use think\Db;

class Users extends Validate
{
    // 验证规则
    protected $rule = [
        'uid' => 'require|number',
        'username' => 'require|min:6|max:16|unique:users',
        'realname' => 'require|min:4|max:8',
        'groupid' => 'require|number',
        'status' => 'require|number'
    ];
    // 错误消息
    protected $message = [
        'uid.require' => 'uid必填',
        'uid.number' => 'uid必须是数字',
        'username.require' => '用户名不能为空',
        'username.min' => '用户名长度至少为3个字符',
        'username.max' => '用户名长度最多为16个字符',
        'realname.require' => '真实姓名不能为空',
        'realname.min' => '真实姓名最少为2位',
        'realname.max' => '真实姓名最多为8位',
        'groupid.require' => '分组不能为空',
        'groupid.number' => '分组必须为数字',
        'status.require' => '状态不能为空',
        'status.number' => '状态必须为数字',
    ];

    //验证场景
    protected $scene = [
        'add'  => ['username', 'realname', 'groupid','status'],
        'edit' => ['uid','username', 'realname', 'groupid','status'],
        'del'  => ['uid'],
    ];
}