<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * QQ:776329498
 * Date: 2017/12/13
 * Time: 15:59
 */

namespace mikkle\tp_master;


use think\Facade;

class Session extends Facade
{
    protected static function getFacadeClass()
    {
        return 'think\Session';
    }

}