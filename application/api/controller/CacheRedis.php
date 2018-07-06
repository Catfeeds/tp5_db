<?php
/**
 * Created by PhpStorm.
 * CacheRedis.php
 * Author: gl
 * Date: 2018/1/22
 * Description:定义redis缓存拓展类，主要是弥补redis缓存基类的缓存方式太少的不足(注：原类中只有set get)
 */

namespace app\api\controller;

use think\cache\driver\Redis;

class CacheRedis extends Redis
{
    //初始化options配置参数
    public function __construct($options)
    {
        parent::__construct($options);
    }


    /**
     *填充hash表的值
     * @param string $name hash表的名字
     * @param array $arr hash表名对应的键值对 如 array('key1'=>'value1','key2'=>'value2') 相当于 hset($name,'key1','value1')和hset($name,'key2','value2')
     */
    public function hMset($name, $arr)
    {
        return $this->handler->hmset($name, $arr);
    }

    /**
     *    delete hash opeation
     */

    public function del($name)
    {
        return $this->handler->del($name);
    }

    public function delete($key) {
        return $this->handler->delete($this->formatKey($key));
    }

    protected function unformatValue($value) {
        return unserialize($value);
    }
    protected function formatValue($value) {
        return serialize($value);
    }
    //这里可以定义前缀
    protected function formatKey($key) {
        return $key;
    }

    /**
     * 直接返回redis实例
     * @return Redis
     */
    public function getRedis(){
        return $this->handler;
    }

    public function hIncrBy($name, $key, $num = 1){
        return $this->handler->hIncrBy($this->formatKey($name), $key, $num);
    }
    /**
     *    set hash opeation
     */
    public function hSet($name,$key,$value) {
        return $this->handler->hset($this->formatKey($name),$key,$this->formatValue($value));
    }

    /**
     *    get hash opeation
     */
    public function hGet($name,$key = null, $unserializeable = true) {
        if($key){
            $value = $this->handler->hget($this->formatKey($name),$key);
        } else {
            $value = $this->handler->hgetAll($this->formatKey($name));
        }
        if (empty($value)) {
            return '';
        }
        if (empty($unserializeable)) {
            return $value;
        }
        return $this->unformatValue($value);
    }

    /**
     *    delete hash opeation
     */
    public function hDel($name,$key = null){
        if($key){
            return $this->handler->hdel($this->formatKey($name),$key);
        }
        return $this->handler->delete($this->formatKey($name));
    }

    /*******************************************************
    队列操作开始 start 通过list模拟队列queue操作
     ********************************************************/
    /**
     * 入列
     * @param $queueName string 队列名称
     * @param $value object 入列元素的值
     */
    public function qPush($queueName,$value) {
        return $this->handler->rpush($this->formatKey($queueName),$this->formatValue($value));
    }
    /**
     * 出列
     * @param $queueNam
     */
    public function qPop($queueName) {
        $value = $this->handler->lpop($this->formatKey($queueName));
        if (!empty($value))
            $value = $this->unformatValue($value);
        return $value;
    }
    /**
     * 获取队列长度
     */
    public function qLen($queueName) {
        return $this->handler->llen($this->formatKey($queueName));
    }
    /*******************************************************
    队列操作结束 end
     ********************************************************/
    /**
     * 获取队列中的元素
     * @Author:tzq
     * @Date:2018.3.6
     * @param $queueName string 要获取的键名,不包含前缀
     * @param int $start  int   获取开始位置 默认为0
     * @param int $end    int   要获取的结束位置 默认全部获取
     * @return array
     */
    public function lRange($queueName,$start=0,$end=-1){
        $res =  $this->handler->lRange($this->formatKey($queueName),$start,$end);
        if($res){
            foreach ($res as $index => &$re) {
                $re = $this->unformatValue($re);
            }
        }
        return $res;
    }

    /**
     * 通过下标获取redis列表中的值
     * @Author:tzq
     * @Date:2018.3.6
     * @param $key string  键名
     * @param int $index   下标值
     * @return string
     */
    public function lIndex($key,$index = 0){
        return $this->unformatValue($this->handler->lIndex($this->formatKey($key),$index));
    }

    /**
     * 通过下标设置redis的值
     * @Author:tzq
     * @Date:2018.3.6
     * @param $key  string 不含前缀的key
     * @param $index int   redis下标
     * @param $value string 要设置的值
     * @return void
     */
    public function lSet($key,$index,$value){
        $this->handler->lSet($this->formatKey($key),$index,$this->formatValue($value));
    }

    /**
     * 按指定值删除redis元素
     * @Author:tzq
     * @Date:2018.3.6
     * @param $key  string 不含前缀的key
     * @param $value  string 要删除的值
     * @param int $count  count > 0 : 从表头开始向表尾搜索，移除与 VALUE 相等的元素，数量为 COUNT 。
     * count < 0 : 从表尾开始向表头搜索，移除与 VALUE 相等的元素，数量为 COUNT 的绝对值。
     * count = 0 : 移除表中所有与 VALUE 相等的值。
     * @return int  返回被删除的行数
     */
    public function lRem($key,$value,$count=0){
        return $this->handler->lRem($this->formatKey($key),$this->formatValue($value),$count);
    }
}