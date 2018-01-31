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

    //定义redis缓存hset方法
    public function hset($hashname, $field, $data)
    {
        if (is_array($data)) {
            return $this->handler->hset($hashname, $field, serialize($data));
        }
        return $this->handler->hset($hashname, $field, $data);
    }

    /**
     *填充hash表的值
     * @param string $name hash表的名字
     * @param array $arr hash表名对应的键值对 如 array('key1'=>'value1','key2'=>'value2') 相当于 hset($name,'key1','value1')和hset($name,'key2','value2')
     */
    public function hMset($name, $arr)
    {
        return $this->handler->hMset($name, $arr);
    }

    /**
     * @param $name
     * @param null $key
     * @param bool $serialize
     * @return mixed
     */
    public function hget($name, $key = null, $serialize = false)
    {
        if ($key) {
            $row = $this->handler->hget($name, $key);
            if ($row && $serialize) {
                $row = unserialize($row);
            }
            return $row;
        }
        return $this->handler->hgetAll($name);
    }

    /**
     *    delete hash opeation
     */
    public function hdel($name, $key = null)
    {
        if ($key) {
            return $this->handler->hdel($name, $key);
        }
        return $this->handler->del($name);
    }

    public function del($name)
    {
        return $this->handler->del($name);
    }
}