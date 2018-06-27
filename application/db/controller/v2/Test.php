<?php
/**
 * @Class: Test.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/6/27
 */
namespace app\db\controller\v2;
use think\Controller;

class Test extends Controller
{
    /**
     * @description 关联模型测试
     * @param $id
     */
    public function orm($id){
        return 'This is V2 Version';
    }
}