<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/11/27
 * Time: 16:11
 */

namespace mikkle\tp_master;


use think\Facade;

class Config extends Facade
{
    protected static function getFacadeClass()
    {
        return 'think\Config';
    }

}