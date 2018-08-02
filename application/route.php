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
// Route::rule('/','db/test/testlist','GET');
Route::get('/','db/test/testlist');//等价上面
Route::any('dir','db/v1.Dir/getDir',['method'=>'get|post']);// 调用shell命令 递归创建目录
Route::any('xlh','db/index/getSerialize',['method'=>'get|post']);
Route::any('layuiindex','db/v1.Test/pageindex');
Route::any('layui','db/v1.Test/ajaxpage',['method'=>'get|post']);


Route::post('hello','db/test/test2');//传参
// Route::any('any','db/test/test3');//传参
Route::any('test','db/v1.Test/basetest');
Route::any('api/v1/any','db/test.Test/test3');//带目录的控制器 传参

Route::any('get/list','db/v1.Test/test5',['method'=>'post|put|get']);
// Route::any('get/:id','db/v1.Test/test4',[],['id'=>'\d+']);
Route::any('get/:id','db/v1.Test/test4');

Route::any('jwt','db/v1.Myjwt/testjwt');// api接口访问验证
Route::any('exc','db/test.Test/testException');// 异常处理测试
// Route::get('api/v1/orm/:id','db/v1.Test/orm');// 关联模型测试
// Route::get('api/:version/orm/:id','db/:version.Test/orm');// 版本自由切换
// Route::post('api/:version/user/token','db/:version.Token/getToken');// 版本自由切换
Route::get('work','worker/worker');
//路由分组
Route::group('api/:version',function(){
    Route::get('/orm/:id','db/:version.Test/orm');
    Route::get('/test/page','db/:version.Test/page');//分页
    Route::get('/user/token','db/:version.Token/createToken');
});
Route::get('testdata','db/v1.Test/getTestData');//数据增删改查操作
Route::get('before','db/v1.Before/second');//控制器前置操作
Route::get('captcha','db/v1.Test/getCaptcha');//验证码
Route::get('sql','db/v1.Test/testSql');//测试sql
Route::get('auto','db/v1.Test/addData');//测试自动写入时间戳
Route::get('ws','worker/Index/index');//websocket测试
Route::get('block','db/v1.Block/test');//区块链测试
Route::get('trans','db/v1.Test/testTrans');//事务测试
Route::get('xgq','db/v1.Test/modifier');//修改器的使用