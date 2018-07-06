<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/7/24
 * Time: 15:10
 */

namespace mikkle\tp_master;


use think\Facade;

class Exception extends Facade
{

    protected static function getFacadeClass()
    {
        return 'think\Exception';
    }
}