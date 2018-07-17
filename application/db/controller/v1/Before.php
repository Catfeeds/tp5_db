<?php
/**
 * @Class: Before.php
 * @Description: Before.php
 * @Author: gl
 * @Email:898148191@qq.com
 * @Date: 2018/7/17
 */

namespace app\db\controller\v1;


use app\db\controller\BaseController;

class Before extends BaseController
{
    /**
     * @var array  前置操作 在执行second之前先执行first 一般用于权限验证
     */
    protected $beforeActionList = [
        'first' => ['only' => 'second'],
    ];

    protected function first()
    {
        echo '首先要经过我，才能执行其他操作';
    }

    public function second()
    {
        echo 'second';
    }
}
