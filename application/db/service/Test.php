<?php
/**
 * @Class: Test.php
 * @Description: Test.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/8/1
 */

namespace app\db\service;
use app\db\model\Test as TestModel;
use app\lib\exception\DbException;
use think\Db;
class Test
{
    /**
     * @description 事务测试
     * @throws \think\exception\PDOException
     */
    public function testTrans(){
        $testModel = new TestModel();
        // $testModel->startTrans();方法1
        Db::startTrans();//方法2
        try{
            $testModel->where('id','=','1')->setInc('total',3);
            $testModel->where('id','=','2')->setDec('total',3);
            $testModel->where('id','=','3')->update(['value'=>'提交事务']);
            // $testModel->commit();
            Db::commit();
            return true;
        }catch (\Exception $e){
            log_message('事务提交失败:'.$e->getMessage());
            // $testModel->rollback();
            Db::rollback();
            throw new DbException();
        }
    }
}