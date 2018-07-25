<?php
/**
 * @Class: Shejimoshi_danli.php
 * @Description: PHP设计模式-单例模式 数据库应用
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/20
 */

class Db1
{
    public static $instance = null;

    public static function getInstance()
    {

        if (null === self::$instance) {
            self::$instance = new self();
        }
        // or
        // if (null === static::$instance) {
        //     static::$instance = new static();
        // }
        return self::$instance;
    }

    /**
     * 防止使用 new 创建多个实例
     * Db1 constructor.
     */
    private function __construct()
    {
    }

    /**
     * @description 防止 clone 多个实例
     */
    private function __clone()
    {
    }

    /**
     * @description 防止反序列化
     */
    private function __wakeup()
    {
    }

}
$db4 = Db1::getInstance();
$db5= Db1::getInstance();
var_dump($db4);
echo '<br>';
var_dump($db5);
echo '<br>';
die;