<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * QQ:776329498
 * Date: 2018/1/19
 * Time: 10:26
 */

namespace mikkle\tp_master;


abstract class Command extends \Command
{

     protected function execute(\think\console\Input $input, \think\console\Output $output){

         $this->executeHandle($input,  $output);
    }
    abstract protected function executeHandle($input,  $output);
}