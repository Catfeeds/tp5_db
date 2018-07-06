<?php
/**
 * @Class: jwt.php
 * @Description: json web token配置文件
 * @Author: gl
 * @Date: 2018/6/15
 */

//json web token配置
$jwt = array(
    'key'   =>  '123456', //加密key
    'exp'   =>  8640000, //有效时间(秒)
    'leeway'    =>  0 //验证iat exp 等字段时会添加时间偏差
);
return $jwt;