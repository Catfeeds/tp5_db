<?php
/**
 * @Class: BaseService.php
 * @Description: 控制器
 * @Author: gl
 * @Date: 2018/7/4
 */

namespace app\db\service;


class Token
{
   public static function  grantToken(){
       $randChars = getRandChar();
       $timestamp = SYSTIME;
       $token_salt = config('setting.token_salt');
       return md5($randChars.$timestamp.$token_salt);
   }
}