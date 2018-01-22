<?php
/**
 * Created by PhpStorm.
 * Shejimoshi_celue.php
 * Author: gl
 * Date: 2018/1/16
 * Description:
 */

namespace app\db\controller;

//php设计模式之责任链模式
// 领班
class Foreman{
    // 自己的等级
    private $level=1;
    // 上级
    protected $superior='Director';
    public function process($level){
        if($this->level>=$level){
            // 自己能处理问题的级别大于等于当前事情级别，就自己处理
            echo '领班处理'.PHP_EOL;
        }else{
            (new $this->superior)->process($level);
        }
    }
}

// 主管
class Director{
    // 自己的等级
    private $level=2;
    // 上级
    protected $superior='Manager';
    public function process($level){
        if($this->level>=$level){
            echo '主管处理'.PHP_EOL;
        }else{
            (new $this->superior)->process($level);
        }
    }
}

// 经理
class Manager{
    // 自己的等级
    private $level=3;
    // 上级
    protected $superior='TopManager';
    public function process($level){
        if($this->level>=$level){
            echo '经理处理'.PHP_EOL;
        }else{
            (new $this->superior)->process($level);
        }
    }
}

// 总经理
class TopManager{
    // 自己的等级
    private $level=4;
    // 上级
    protected $superior='President';
    public function process($level){
        if($this->level>=$level){
            echo '总经理处理'.PHP_EOL;
        }else{
            (new $this->superior)->process($level);
        }
    }
}

// 董事长
class President{
    // 自己的等级
    private $level=5;
    public function process($level){
        echo '董事长处理'.PHP_EOL;
    }
}

// 责任链模式处理问题
$level=rand(1,5);
print('问题级别：'.$level);
$foreman=new Foreman();
$foreman->process($level);