<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    //'db'=>'@db/index/index',
    //'getData'=>'@db/index/getData',
    //'http'=>'@db/index/testHttp',
    //'getcallbackdata'=>'@db/index/getcallbackdata',
    //'postData'=>'@db/index/postData',
    '/'=>'@db/test/testlist',//默认
    'xlh'=>'@db/index/getSerialize',
    'redis'=>'@db/test/redis',
    'test'=>'@db/test/test'
    //'vue'=>'@db/test/index',
    ////'getlist'=>'@db/test/getlist',
    ////'getlist/:p'=>'@db/test/getlist',
    //'demo'=>'@db/demo/index',
    //'demo1'=>'@db/demo/asyncRequest',
    //'demo3'=>'@db/demo/demo3',
    //'welcome'=>'@admin/index/welcome'


];

