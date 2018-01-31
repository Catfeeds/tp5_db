<?php
/**
 * Created by PhpStorm.
 * Shejimoshi_celue.php
 * Author: gl
 * Date: 2018/1/16
 * Description:
 */

namespace app\db\controller;

//php设计模式之策略模式(计算器)
interface Calc
{
    //定义处理方法
    public function process($a, $b);
}

//加法运算
class Jia implements Calc
{
    public function process($a, $b)
    {
        // TODO: Implement process() method.
        return $a + $b;
    }
}

//减法运算
class Jian implements Calc
{
    public function process($a, $b)
    {
        // TODO: Implement process() method.
        return $a - $b;
    }
}

//乘法运算
class Cheng implements Calc
{
    public function process($a, $b)
    {
        // TODO: Implement process() method.
        return $a * $b;
    }
}

//除法运算
class Chu implements Calc
{
    public function process($a, $b)
    {
        // TODO: Implement process() method.
        if (!$b == 0) {
            return $a / $b;
        } else {
            return '除数不能为零';
        }

    }
}

//计算器类
class Calclator
{
    //保存计算类
    protected $calc = null;

    public function __construct($oper)
    {
        $this->calc = new $oper();
    }

    public function calc($a, $b)
    {
        return $this->calc->process($a, $b);
    }

}

$calc = new Calclator('Jian');
echo $calc->calc(10, 0);