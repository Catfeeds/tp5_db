<?php
/**
 * @Class: Dir.php
 * @Description: Dir.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/27
 */

namespace app\db\controller\v1;


use function json_encode;
use think\Controller;

class Dir extends Controller
{
    /**
     * @description 调用shell命令 创建目录
     * @return string|\think\response\Json
     */
    public function getDir()
    {
        // system('start C:\Windows\System32\calc.exe');die;
        $dirname = request()->post('d', '');
        if ('' !== $dirname) {
            $dirname = strtr($dirname, ['\\' => '/']);
            $firstDir = substr($dirname, 0, strpos($dirname, '/'));
            // log_message('目录名称:' . $dirname . "\n" . '主目录:' . $firstDir);
            $shellstr = 'mkdir -p /test/' . $dirname . ' && ' . 'cp -r /test/' . $firstDir . ' /mnt/hgfs/mytest/MYDIR' . ' && ' . 'rm -rf /test/' . $firstDir;// &&符 多命令同时执行 第一个执行完执行第二个
            shell_exec($shellstr);
            // log_message('shell命令:' . $shellstr);
            return json("目录创建成功,目录地址 G:\mytest\MYDIR",'success',0);
        } else {
            return $this->fetch('test/getdir');
        }
    }
}