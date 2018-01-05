<?php

namespace app\admin\controller;

use think\Controller;

/***
 * 通用方法控制器
 * Class Link
 * @package app\admin\controller
 */
class Base extends Controller
{
    /***
     *
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /***
     * 上传文件
     */
    public function upload()
    {
        // 获取表单上传文件
        $host = 'http://' . $_SERVER['HTTP_HOST'];

        $files = $_FILES;
        if (empty($files)) {
            return json(['code' => '1', 'msg' => '没有上传任何文件']);
        }
        foreach ($files as $key => $value) {
            if (is_array($value['name'])) {
                return json(['code' => 1, 'msg', '请单次上传文件']);
                //多文件上传处理
            } else {
                //单文件上传处理
                $file_name = explode('.', $value['name']);
                $file_name = NOW_TIME . rand(200, 999) . '.' . end($file_name);
                if (move_uploaded_file($value['tmp_name'], './uploads/pic/' . $file_name)) {
                    return json(['code' => 0, 'msg' => '上传成功', 'url' => $host . '/uploads/pic/' . $file_name]);
                } else {
                    return json(['code' => 1, 'msg' => '上传错误']);
                }
            }
        }


    }

    public function uploadOne()
    {
        $host = $_SERVER['HTTP_HOST'];

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}
