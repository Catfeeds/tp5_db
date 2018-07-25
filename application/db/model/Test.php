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
    protected $visible = ['id','name','value'];


}