<?php
/**
 * @Class: Test.php
 * @Description: Test.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/20
 */

namespace app\db\model;



class Test extends BaseModel
{
    protected $autoWriteTimestamp = true;//是否需要自动写入时间戳
    protected $createTime = 'dateline';//添加时间戳
    protected $updateTime = 'updatetime';//添加时间戳
    protected $dateFormat = 'Ymd';//设置当前模型时间显示格式
    protected $hidden = ['updatetime','dateline'];
    protected $visible = ['id','name','value','total','hehe'];

    // 修改器的使用 设置不存在的属性 RealName 控制器取 real_name 如果是 Realname  则取 realname
    public function getRealNameAttr($value,$data){
        return $data['id']*$data['total'];
    }

}