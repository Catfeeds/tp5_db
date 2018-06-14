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


// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],
//     //'db'=>'@db/index/index',
//     //'getData'=>'@db/index/getData',
//     //'http'=>'@db/index/testHttp',
//     //'getcallbackdata'=>'@db/index/getcallbackdata',
//     //'postData'=>'@db/index/postData',
//     '/'=>'@db/test/testlist',//默认
//     'xlh'=>'@db/index/getSerialize',
//     'redis'=>'@db/test/redis',
//     'test'=>'@db/test/test'
//     //'vue'=>'@db/test/index',
//     ////'getlist'=>'@db/test/getlist',
//     ////'getlist/:p'=>'@db/test/getlist',
//     //'demo'=>'@db/demo/index',
//     //'demo1'=>'@db/demo/asyncRequest',
//     //'demo3'=>'@db/demo/demo3',
//     //'welcome'=>'@admin/index/welcome'
//
//
// ];

use think\Route;
// Route::rule('路由表达式','路由地址','请求类型','路由参数(数组)','请求参数(数组)');//格式
// 请求类型 GET POST PUT DELETE * 默认为* 表示任意类型
Route::rule('/','db/test/testlist','GET');
Route::get('/','db/test/testlist');//等价上面
Route::get('xlh','db/index/getSerialize');
Route::post('hello','db/test/test2');//传参
// Route::any('any','db/test/test3');//传参
Route::any('any','db/test.Test/test3');//带目录的控制器 传参