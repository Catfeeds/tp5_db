<?php
/**
 * @Class: ExceptionHandler.php
 * @Description: 异常处理类 这里需要注意 应该继承 PHP自带的异常类Exception 而不是框架自带的异常类
 * @Author: gl
 * @Date: 2018/6/19
 */

namespace app\lib\exception;

// use Exception;//这里需要注意 应该继承 PHP自带的异常类Exception 而不是框架自带的异常类
// use think\Exception;
use think\LOG;
use think\exception\Handle;
use think\Request;
use app\lib\exception\BaseException;
class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
        //    如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            // 开则使用服务器调试的方法来排查错误，关则为客户端提供json体。
            if (config('app_debug')) {
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = '内部服务器错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }

        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url(),
        ];
        return json($result,$this->code);
    }

    /**
     * @description 在全局异常处理中加入日志
     * @param Exception $e
     */
    private function recordErrorLog(Exception $e)
    {
        LOG::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error'],
        ]);
        LOG::record($e->getMessage(),'error');
    }
}