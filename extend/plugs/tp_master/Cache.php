<?php
/**
 * Created by PhpStorm.
 * User: 77632
 * Date: 2017/12/8
 * Time: 10:23
 */

namespace mikkle\tp_master;


use think\Facade;

class Cache extends Facade
{
    protected static function getFacadeClass()
    {
        return 'think\Cache';
    }

}