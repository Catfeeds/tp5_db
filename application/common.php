<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串，$id多个验证码标识


define('ROW', 10);
/**
 * Created by PhpStorm.
 * User: tangzuqiang
 * Date: 2017/5/13 0013
 * Time: 8:11
 */
/**
 * 获取IP地址位置
 */
function getIPLoc_sina($queryIP)
{
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $queryIP;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_ENCODING, 'utf8');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
    $location = curl_exec($ch);
    $location = json_decode($location);
    curl_close($ch);
    $loc = "";
    if ($location === FALSE)
        return "";
    if (empty($location->desc)) {
        $loc = $location->province . $location->city . $location->district . $location->isp;
    } else {
        $loc = $location->desc;
    }
    return $loc;
}

/***
 * 生成随机字符串
 * @param int $length
 * @return string
 */
function getRandStr($length = 8, $UID)
{
    $code_arr = M('code')->where(['UID' => $UID])->find();
    if ($code_arr) {
        return $code_arr['Code'];
    }
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];

    }
    $password = THINK_START_TIME . $password;
    $C = new \Home\Model\CodeModel();
    $c = [];
    $c['UID'] = $UID;
    $c['Code'] = $password;
    $C->addedit($c);
    return $password;
}

/***
 * 写入数据日志
 *  msg 写入信息，数组将转换成json
 *
 */
function dblog($msg = '', $url = false)
{
    $data['url'] = $url ? $url : url();
    $data['msg'] = is_string($msg) ? $msg : json_encode($msg, JSON_UNESCAPED_UNICODE);
    db('log')->insert($data);
}

/**
 * 无限极分类
 * @param        $classify_old 需分类数组
 * @param string $id 唯一id名称
 * @param string $pname 父id键名称
 * @param int $pid 父id，默认0为顶级父id
 * @return array|bool
 */

define('NOW_TIME', $_SERVER['REQUEST_TIME']);
function toClass($classify_o, $id = 'id', $pname = 'pid', $pid = 0)
{
    $num = 0;
    $classify = [];
    $classify_o_copy = $classify_o;
    foreach ($classify_o as $k => $v) {
        if ($v[$pname] == $pid) {
            $num++;
            array_push($classify, $v);
            unset($classify_o_copy[$k]);
        }
    }
    if ($num == 0) {
        return false;
    }
    foreach ($classify as $k => $v) {
        $a = toClass($classify_o_copy, $id, $pname, $v[$id]);
        if (!$a) {
            continue;
        }
        $classify[$k]['children'] = $a;
    }
    return $classify;
}

/***
 * @param $phone
 * @param string $content
 * @param string $template
 * @return array
 * 发送短信
 */
function sendMessage($phone, $content, $template)
{
    $uid = 'yiyunhao';//ftds
    $content = urlencode($content);
    $pwd = 'e76309e0c6ff4dd045589a2891a790e9';//e76309e0c6ff4dd045589a2891a790e9 ..2347a648f7d0d1329c5bd793a3c12384
    $url = "http://api.sms.cn/sms/?ac=send&uid={$uid}&pwd={$pwd}&mobile={$phone}&content={$content}&template={$template}";
    $r = doPost($url);
    return $r;
}

/* 生成验证密码 */
function makepwd($pwd, $key = '20160121sPenCeR_(>~axM8^OW5@6h{`0SyJ.*jLt#|dZ)4Bsf%=e}9R_')
{
    // return '' === $pwd ? '' : md5(sha1($pwd) . $key);
    return '' === $pwd ? '' : md5($pwd);
}

/***
 * GET提交
 */
function doGet($url)
{
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    //打印获得的数据
    return $output;
}
/***
 * 去除输入框中的前后空
 *
 * @param  array $data
 * return array
 */
function dislodge($data)
{
    foreach ($data as &$value) {
        if (!is_array($value)) {
            $value = trim($value);
        }
    }
    return $data;
}

function doPost($url, $body = [], $header = array(), $type = "POST")
{
    //1.创建一个curl资源
    $ch = curl_init();
    //2.设置URL和相应的选项
    curl_setopt($ch, CURLOPT_URL, $url);//设置url
    //1)设置请求头
    array_push($header, 'Accept:application/json');
    array_push($header, 'Accept-Charset:utf-8');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    //设置发起连接前的等待时间，如果设置为0，则无限等待。
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //2)设置提交方式
    switch ($type) {
        case "GET":
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
        case "POST":
            curl_setopt($ch, CURLOPT_POST, true);
            break;
        case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请求。这对于执行"DELETE" 或者其他更隐蔽的HTT
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
        case "DELETE":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
    }
    //3)设备请求体
    if (count($body) > 0) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));//全部数据使用HTTP协议中的"POST"操作来发送。
    }
    //设置请求头
    if (count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    //4)"User-Agent: "头的字符串。
    curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)'); // 模拟用户使用的浏览器
    //5.抓取URL并把它传递给浏览器
    $res = curl_exec($ch);
    $encode = mb_detect_encoding($res, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
    if ($encode != 'UTF-8') {
        $res = iconv('GBK', "UTF-8", $res);
    }
    //$res = mb_detect_encoding($res,'UTF-8',$encode);
    $result = json_decode($res, true);
    //4.关闭curl资源，并且释放系统资源
    curl_close($ch);
    if (empty($result))
        return $res;
    else
        return $result;
}

/* 生产验证码
$len 验证码的长度
*/
function randnum($len = 4)
{
    $num = '0123456789';
    $str = "";
    for ($i = 0; $i < $len; $i++) {
        $str .= substr($num, mt_rand(0, strlen($num) - 1), 1);
    }
    return $str;
}

/* phpmail发送邮件 */
function phpsendmail($mailcode, $email = '290847350@qq.com', $type = 0, $msg = false)
{
    vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer();
    // $mail->SMTPDebug = 3; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.qq.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = '290847350@qq.com'; // SMTP username
    $mail->Password = 'iqsghrmbzjnicajj'; // SMTP password
    // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25; // TCP port to connect to
    $mail->setFrom('290847350@qq.com', '教育之窗'); //
    $mail->addAddress($email, '尊敬的客户'); // 收件人邮箱 // Add a recipient
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = '教育之窗'; // 邮箱标题
    $mail->Body
        = <<<tang
<style>
.mmsgLetter	{ width:580px;margin:0 auto;padding:10px;color:#333;background:#fff;border:0px solid #aaa;border:1px solid #aaa\9;border-radius:5px;-webkit-box-shadow:3px 3px 10px #999;-moz-box-shadow:3px 3px 10px #999;box-shadow:3px 3px 10px #999;font-family:Verdana, sans-serif; }
.mmsgLetter a:link,.mmsgLetter a:visited{color:#407700; }
.mmsgLetterContent {text-align:left;padding:30px;font-size:14px;line-height:1.5; }
.mmsgLetterContent h3{ color:#000;font-size:20px;font-weight:bold; margin:20px 0 20px;border-top:2px solid #eee;padding:20px 0 0 0;font-family:"微软雅黑","黑体", "Lucida Grande", Verdana, sans-serif;}
.mmsgLetterContent p{margin:20px 0;padding:0; }
.mmsgLetterContent .salutation { font-weight:bold;}
.mmsgLetterHeader{	height:23px; }
</style>
<div style="background-color:#d0d0d0;text-align:center;padding:40px;">
	<div style="width:580px;margin:0 auto;padding:10px;color:#333;background-color:#fff;border:0px solid #aaa;border-radius:5px;-webkit-box-shadow:3px 3px 10px #999;-moz-box-shadow:3px 3px 10px #999;box-shadow:3px 3px 10px #999;font-family:Verdana, sans-serif; " class="mmsgLetter">
		<div style="height:23px;" class="mmsgLetterHeader"></div>
		<div style="text-align:left;padding:30px;font-size:14px;line-height:1.5;" class="mmsgLetterContent">
			<div>
				<p style="font-weight:bold;" class="salutation">Hi,<span id="mailUserName">%s</span>：</p>
				<p>教育之窗正在发送%s验证码，邮件地址为<a href="mailto:%s" target="_blank">%s</a></p>
				<p>
					如果这是你的操作，请将此验证码(<a>%s</a>)输入到手机上完成邮箱注册<br>
					如果你没有操作注册此邮箱，请忽略此邮件。
				</p>
			</div>
		</div>
	</div>
</div>;//html内容
tang;
    $mail->Body = sprintf($mail->Body, $type, $email, $email, $email, $mailcode);
    if ($msg) {
        $mail->Body = '自动处理订单错误以下是错误的内容：请手工修改：' . $msg;
    }
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        return 'Error:' . $mail->ErrorInfo;
    } else {
        return $mailcode;
    }
}

/* 阿里云oss上传文件 */
//include('/data/source/Alioos/alioss.php');
/**
 * 上传文件到oss并删除本地文件
 * @param string $path 文件路径
 * @return bollear 是否上传
 */
function oss_upload_form($key = '')
{
    vendor('Oss/autoload');
    // 获取配置项
    if ($key) {
        $files = $_FILES[$key];
        //    $file = $_FILES[$key]['tmp_name'];
    } else {
        $files = current($_FILES);
    }
    $file = $files['tmp_name'];
    $bucket = 'ziqiangkeji';
    if (file_exists($file)) {
        // $type = strstr($file, '.');
        $type = strstr($files['name'], '.');
        $file_name = NOW_TIME . rand(1111, 9999) . $type;
        // 实例化oss类
        $config = array(
            'KEY_ID' => 'LTAIrrvJE9cEcu9l', // 阿里云oss key_id
            'KEY_SECRET' => 'zQDdb8Udmt9TKQAQpID49ueHY5rGgj', // 阿里云oss key_secret
            'END_POINT' => 'http://oss-cn-shanghai.aliyuncs.com', // 阿里云oss endpoint
            'BUCKET' => 'ziqiangkeji'// bucken 名称
        );
        $oss = new \OSS\OssClient($config['KEY_ID'], $config['KEY_SECRET'], $config['END_POINT']);
        try {
            $oss->uploadFile($bucket, 'images/' . $file_name, $file);
            unlink($file);
            $oss_path = 'http://ziqiangkeji.oss-cn-shanghai.aliyuncs.com/images/';//oss图片前缀
            return $oss_path . $file_name;
            // 上传成功，自己编码
            // 这里可以删除上传到本地的文件。unlink（$file）；
        } catch (OssException $e) {
            // 上传失败，自己编码
            printf($e->getMessage() . "\n");
            return 'error上传到阿里云服务器失败';
        }
    } else {
        return 'error本地文件不存在';
    }
}

/***
 * 上传oss文件
 * @param $file
 * @return string
 */

function oss_upload_file($file)
{
    // 获取配置项
    $bucket = 'sbswz';
    if (file_exists($file)) {
        // $type = strstr($file, '.');
        $type = strstr($file, '.');
        $file_name = NOW_TIME . rand(1111, 9999) . $type;
        // 实例化oss类
        $oss = new_oss();
        try {
            $oss->uploadFile($bucket, 'jy_img/' . $file_name, $file);
            unlink($file);
            return $file_name;
            // 上传成功，自己编码
            // 这里可以删除上传到本地的文件。unlink（$file）；
        } catch (OssException $e) {
            // 上传失败，自己编码
            printf($e->getMessage() . "\n");
            return 'error上传到阿里云服务器失败';
        }
    } else {
        return 'error本地文件不存在';
    }
}

/* 获取配置数据 */
function get_config($arr = array())
{
    $CONFIG = M('config')->where('is_del=0')->select();
    foreach ($CONFIG as $key => $value) {
        $arr[$value['key']] = $value['value'];
    }
    return $arr;
}

// 生成签名
function createSign($arr)
{
    if (key_exists('sign', $arr)) {
        unset($arr['sign']);
    }
    ksort($arr);
    $encrypt_str = '';
    foreach ($arr as $k => $v) {
        $encrypt_str .= $k . $v;
    }
    $encrypt_str = md5(C('sign_param') . md5($encrypt_str) . C('sign_param'));
    return $encrypt_str;
}

// 验证签名
function checkSign($arr)
{
    if (!key_exists('sign', $arr)) {
        return false;
    }
    $sign = $arr['sign'];
    $sign_t = createSign($arr);
    if ($sign === $sign_t) {
        return true;
    }
    return false;
}

/***
 * 导出execl
 *
 * @param $header 头部数组A1=>value
 * @param $data   数组数据普通
 * @param $field  a=>字段名
 */
function outExecl($data = null, $header = '')
{
    $objPHPExcel = new PHPExcel();
    /*以下是一些设置 ，什么作者  标题啊之类的*/
    $objPHPExcel->getProperties()->setCreator("转弯的阳光")
        ->setLastModifiedBy("转弯的阳光")
        ->setTitle("数据EXCEL导出")
        ->setSubject("数据EXCEL导出")
        ->setDescription("备份数据")
        ->setKeywords("excel")
        ->setCategory("result file");
    /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
    if ($header) {
        $k = 'A';
        foreach ($header as $key => $value) {
            $objPHPExcel->getActiveSheet()->getStyle($k . '1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue($k . '1', $value);
            $k++;
        }
    }
    $num = isset($header) ? 1 : 0;
    foreach ($data as $key => $v) {
        $num++;
        $k = 'A';
        foreach ($v as $vv) {
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue($k . $num, $vv)
                ->getColumnDimension($k)->setwidth(20);
            $objPHPExcel->getActiveSheet()->getStyle($k . $num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $k++;
        }
    }

    $objPHPExcel->getActiveSheet()->setTitle('小记者导出列表');
    //    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $name . '.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

//excel导入数据方法
function excel_import($filename, $exts = 'xls')
{
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
    //    import("Common.PHPExcel.PHPExcel" , '' , '.php');
    //创建PHPExcel对象，注意，不能少了\
    $PHPExcel = new \PHPExcel();
    //如果excel文件后缀名为.xls，导入这个类
    if ($exts == 'xls') {
        //        import("Common.PHPExcel.PHPExcel.Reader.Excel5" , '' , '.php');
        $PHPReader = new \PHPExcel_Reader_Excel5();
    } else if ($exts == 'xlsx') {
        //        import("Common.PHPExcel.phpexcel.Reader.Excel2007" , '' , '.php');
        $PHPReader = new \PHPExcel_Reader_Excel2007();
    }
    if (!file_exists($filename)) {
        return ['errMsg' => '文件不存在！'];
    }
    //载入文件
    $PHPExcel = $PHPReader->load($filename);
    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
    $currentSheet = $PHPExcel->getSheet(1);
    //获取总列数
    //    $allColumn = $currentSheet->getHighestColumn();
    $allColumn = 'N';
    //获取总行数
    $allRow = $currentSheet->getHighestRow();
    //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
    for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
        //从哪列开始，A表示第一列
        $temp = [];
        for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
            //数据坐标
            $address = $currentColumn . $currentRow;
            //读取到的数据，保存到数组$arr中
            $cell = $currentSheet->getCell($address)->getValue();
            if ($cell instanceof PHPExcel_RichText) {
                $cell = $cell->__toString();
            }
            $temp[] = $cell;
        }
        $data[] = $temp;
    }
    return $data;
}


/***
 * 处理小数
 * @param $money
 * @return float|int
 */
function jisuanxiaoshu($money)
{
    $money = $money * 100;
    $money = intval($money);
    $money = floor($money / 100);
    return $money;
}

function getPirewhere($ID, $member)
{
    $arr = [];
    $config = M('honorconfig')->where(['GoodsGrade' => 1])->find();
    // 获取直推人数
    $n = M('member')->where(['ReferenceMemberNumber' => $member])->count();
    //获取团队人数
    $map = [];
    $map['Path'] = ['exp', ' regexp ' . '\'' . $ID . '\''];
    $map['GoodsGrade'] = ['gt', 0];
    $n1 = M('member')->where($map)->count();
    //获取团队业绩
    $map = [];
    $map['me.Path'] = ['exp', ' regexp ' . '\'' . $ID . '\''];
    $map['me.GoodsGrade'] = ['gt', 0];
    $m1 = M('member me')
        ->where($map)
        ->field(['g.Money'])
        ->join('stock_goodsgradeconfig g ON me.GoodsGrade=g.GoodsGrade')
        ->sum('g.Money');
    if ($n < $config['Person1']) {
        $arr['PushNumber'] = 1;
    } else {
        $arr['PushNumber'] = 0;
    }
    if ($n1 < $config['person2']) {
        $arr['TeamNumber'] = 1;
    } else {
        $arr['TeamNumber'] = 0;
    }
    if ($m1 < $config['Money1']) {
        $arr['TeamEarnings'] = 1;
    } else {
        $arr['TeamEarnings'] = 0;
    }
    return $arr;
}

/***
 * 获取星级信息文本
 * @param $Grade
 * @return string
 */
function getGrade($Grade)
{
    switch ($Grade) {
        case 0:
            $s = '未认购';
            break;
        case 1:
            $s = '✯合伙人';
            break;
        case 2:
            $s = '✯✯合伙人';
            break;
        case 3:
            $s = '✯✯✯合伙人';
            break;
        case 4:
            $s = '✯✯✯✯合伙人';
            break;
        case 5:
            $s = '✯✯✯✯✯合伙人';
            break;
        case 6:
            $s = '股东';
            break;
        default:
            $s = '未知合伙人';
    }
    return $s;
}

/***
 * 计算分销奖人数
 * @param $n 代数
 * @param $member 当前会员编号
 * @return bool
 */
function zhituirenshu($n, $member)
{
    $n1 = M('member')->where(['ReferenceMemberNumber' => $member, 'GoodsGrade' => ['gt', 0]])->count();
    switch ($n) {
        case 0:
            $r = 1;
            break;
        case 1:
            $r = 1;
            break;
        case 2:
            $r = $n1 >= 3 ? 1 : 0;
            break;
        case 3:
            $r = $n1 >= 7 ? 1 : 0;
            break;
        default:
            $r = 0;
    }
    return $r;
}

/***
 * 读取数据结构
 */
function ReadDb()
{
    //要查询的数据库
    $dbname = C('DB_NAME');
    $tables = M()->query('SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE table_schema=' . '\'' . $dbname . '\'');
    $txt = '';
    foreach ($tables as $key => $value) {
        $txt .= '*```(' . $value['TABLE_NAME'] . ')  注释(' . $value['TABLE_COMMENT'] . ')' . "\r\n";
        $arr = M()->query('SELECT * FROM INFORMATION_SCHEMA.Columns WHERE table_name=' . '\'' . $value['TABLE_NAME'] . '\' AND table_schema=' . '\'' . $dbname . '\'');
        foreach ($arr as $v) {
            $txt .= '                                      ' . $v['COLUMN_NAME'] . '(' . $v['COLUMN_TYPE'] . ')      默认值:' . $v['COLUMN_DEFAULT'] . '   注释:' . $v['COLUMN_COMMENT'] . "\r\n";
        }
        // dump(M()->query(''));
        //$tablesmsg = M()->query('show columns from '.$value['Tables_in_zichan']);
        // dump($tablesmsg);
        $txt .= '*******************************************************************************' . "\r\n";
    }
    file_put_contents('./Updown/dbdate.txt', $txt);
    echo '数据库读取完成';
}

/**
 * 友好调试，默认中断
 */
if (!function_exists('dd')) {
    function dd($var, $isExit = false)
    {
        if (is_bool($var)) {
            var_dump($var);
        } else if (is_null($var)) {
            var_dump(NULL);
        } else {
            echo "<pre style='position:relative;z-index:1000;padding:5px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
        }
        if ($isExit) {
            exit();
        }
    }
}
/**
 * 友好调试，默认中断
 */
if (!function_exists('dd')) {
    function dd($data, $isexit = true)
    {
        $str = '<div style="clear: both;"><pre>';
        $str .= print_r($data, true);//以字符串方式输出
        $str .= '</pre></div>';
        echo $str;
        if ($isexit) {
            exit;
        }

    }
}
/**
 * 字符串加密
 * @param $string
 * @param $operation
 * @param string $key
 * @param int $expiry
 * @return bool|string
 */
function authcode($string, $operation, $key = '', $expiry = 0)
{
    $authkey = Ebh::app()->security['authkey'];
    $ckey_length = 4; // 随机密钥长度 取值 0-32;
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥
    $key = md5($key ? $key : $authkey);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}
/**
 * 生成随机大写字母字符串
 * @param $length
 * @return string
 */
if (!function_exists('getrandom')) {
    function getrandom($length)
    {
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= chr(mt_rand(65, 90));
        }
        return $hash;
    }
}

/**
 * json格式输出
 * @param number $code 状态标识 0 成功 1 失败
 * @param string $msg 输出消息
 * @param array $data 数组参数数组
 * @param string $exit 是否结束退出
 */
if (!function_exists('renderjson')) {
    function renderjson($code = 0, $msg = "", $data = array(), $exit = true)
    {
        $arr = array(
            'code' => (intval($code) === 0) ? 0 : intval($code),
            'msg' => $msg,
            'data' => $data
        );
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        if ($exit) {
            exit();
        }
    }
}
/**
 * 获取安全的html
 * @param $text
 * @param null $tags
 * @return mixed|string
 */
function h($text, $tags = null)
{
    $text = trim($text);
    //完全过滤注释
    $text = preg_replace('/<!--?.*-->/', '', $text);
    //完全过滤动态代码
    $text = preg_replace('/<\?|\?' . '>/', '', $text);
    //完全过滤js
    $text = preg_replace('/<script?.*\/script>/', '', $text);
    $text = str_replace('[', '&#091;', $text);
    $text = str_replace(']', '&#093;', $text);
    $text = str_replace('|', '&#124;', $text);
    //过滤换行符
    $text = preg_replace('/\r?\n/', '', $text);
    //br
    $text = preg_replace('/<br(\s*\/)?' . '>/i', '[br]', $text);
    $text = preg_replace('/<p(\s*\/)?' . '>/i', '[p]', $text);
    $text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);
    $text = str_replace('font', '{f{o{n{t{', $text);
    $text = str_replace('decoration', '{d{e{c{o{r{a{t{i{o{n{', $text);
    $text = str_replace('<strong>', '{s{t{r{o{n{g{', $text);
    $text = str_replace('</strong>', '}s{t{r{o{n{g{', $text);
    $text = str_replace('background-color', '{b{a{c{k{g{r{o{u{n{d{-{c{o{l{o{r', $text);
    //过滤危险的属性，如：过滤on事件lang js
    while (preg_match('/(<[^><]+)(on(?=[a-zA-Z])|lang|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1], $text);
    }
    while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . $mat[3], $text);
    }
    if (empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a|span|input|h1|h2|h3|h4|h5';
    }
    //允许的HTML标签
    $text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)?>/i', '[\1\2]', $text);
    $text = preg_replace('/<\/(' . $tags . ')>/Ui', '[/\1]', $text);
    //过滤多余html
    $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|pre)[^><]*>/i', '', $text);
    //过滤合法的html标签
    while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
    }
    //转换引号
    while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
    }
    //过滤错误的单个引号
    while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
    }
    //转换其它所有不合法的 < >
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('"', '&quot;', $text);
    //反转换
    $text = str_replace('[', '<', $text);
    $text = str_replace(']', '>', $text);
    $text = str_replace('&#091;', '[', $text);
    $text = str_replace('&#093;', ']', $text);
    $text = str_replace('|', '"', $text);
    //过滤多余空格
    $text = str_replace('  ', ' ', $text);
    $text = str_replace('{f{o{n{t{', 'font', $text);
    $text = str_replace('{s{t{r{o{n{g{', '<strong>', $text);
    $text = str_replace('}s{t{r{o{n{g{', '</strong>', $text);
    $text = str_replace('{d{e{c{o{r{a{t{i{o{n{', 'decoration', $text);
    $text = str_replace('{b{a{c{k{g{r{o{u{n{d{-{c{o{l{o{r', 'background-color', $text);
    //剔除class标签属性
    $text = preg_replace_callback('/<.*?(class\=([\'|\"])(.*?)(\2)).*?>/is', function ($grp) {
        return str_ireplace($grp[1], '', $grp[0]);
    }, $text);
    //抹去所有外链接
    $text = replace_Links($text);//可忽略
    return $text;
}

/**
 * 从一段文本中去除别的网站的a链接
 * @param $body
 * @param array $allow_urls
 * @return mixed
 */
function replace_Links(&$body, $allow_urls = array())
{
    if (empty($allow_urls)) {
        $allow_urls = array(
            'ebh.net',
            'ebanhui.com',
            'svnlan.com'
        );
    }
    $host_rule = join('|', $allow_urls);
    $host_rule = preg_replace("#[\n\r]#", '', $host_rule);
    $host_rule = str_replace('.', "\\.", $host_rule);
    $host_rule = str_replace('/', "\\/", $host_rule);
    $arr = '';
    preg_match_all("#<a([^>]*)>(.*)<\/a>#iU", $body, $arr);
    if (is_array($arr[0])) {
        $rparr = array();
        $tgarr = array();
        foreach ($arr[0] as $i => $v) {
            if ($host_rule != '' && preg_match('#' . $host_rule . '#i', $arr[1][$i])) {
                continue;
            } else {
                $rparr[] = $v;
                $tgarr[] = $arr[2][$i];
            }
        }
        if (!empty($rparr)) {
            $body = str_replace($rparr, $tgarr, $body);
        }
    }
    $arr = $rparr = $tgarr = '';
    return $body;
}

/**
 * 打印调试信息
 */
if (!function_exists('log_message')) {
    function log_message($msg, $level = 'error')
    {
        if (empty($msg)) {
            return false;
        }
        if (is_array($msg)) {
            $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);
        }
        $date = date('Y-m-d H:i:s');
        $title = $level . ' - ' . "$date -->\n";
        $content = $title . $msg . "\n\n";
        $savepath = LOG_PATH;
        $filename = $savepath . date('Y-m-d') . '.log';
        $fp = fopen($filename, 'a');
        flock($fp, LOCK_EX);
        fwrite($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
/**
 * 创建token
 * @return string
 */
function createToken()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    $token = uniqid(mt_rand(0, 1000000));
    $_SESSION['token'] = $token;
    return $token;
}

/**
 * 校验token
 * @param null $token
 * @return bool
 */
function checkToken($token = null)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (is_null($token)) return false;
    if (isset($_SESSION['token']) && $_SESSION['token'] == $token) {
        unset($_SESSION['token']);
        return true;
    } else {
        return false;
    }
}

//编码转换
function myiconv($str)
{
    global $_SC;
    if (EBH::app()->output['charset'] != 'utf-8') {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = myiconv($value);
            }
        } else {
            $encode = mb_detect_encoding($str, array('UTF-8', 'EUC-CN'));
            if ($_SC['db']['dbtype'] == 'mssql' && $encode != 'EUC-CN') {
                $str = iconv('UTF-8', 'GBK', $str);
            }
        }
    }
    return $str;
}

//传入分类列表，处理出树形结构函数
function getTree($arr = array(), $upid = 0, $index = 0)
{
    $tree = array();
    foreach ($arr as $value) {
        if ($value['upid'] == $upid) {
            $value['name'] = str_repeat('┣━', $index) . $value['name'];
            $tree[] = $value;
            $tree = array_merge($tree, getTree($arr, $value['catid'], $index + 1));
        }
    }
    return $tree;
}

/**
 * 验证是不是微信浏览器
 * 访问
 * @return boolean
 */
function is_weixin1()
{
    if (!empty($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)) {
        return true;
    } else {
        return false;
    }
}

/*
 * 生成缩略图
 * param $imagepath:图片文件物理路径
 * param $size:要生成的缩略图尺寸,width_height格式，如160_120
 * param $quality: 图片缩略后的质量，默认75，如果想高质量，可以设置为100.
 * return : 缩略图路径，文件名上加上size，如给定 D:/2012/1.jpg 缩略图路径即为 D:/2012/1_160_120.jpg
 */
function thumb($imagepath, $size, $quality = 75)
{
    if (!is_file($imagepath)) {
        return '';
    }
    list($src_w, $src_h, $src_info) = getimagesize($imagepath);
    list($size_x, $size_y) = explode('_', $size);
    $filename = explode('.', $imagepath);
    $thumbpath = $filename[0] . '_' . $size . '.' . $filename[1];
    $proportion = $size_x / $size_y;
    $nproportion = $src_w / $src_h;
    $src_nw = $src_w;
    $src_nh = $src_h;
    if ($src_nw > $size_x || $src_nh > $size_y) {
        if ($nproportion > $proportion) {
            $src_nw = $size_x;
            $src_nh = $src_h / $src_w * $size_x;
        } else {
            $src_nw = $src_w / $src_h * $size_y;
            $src_nh = $size_y;
        }
    } else {
        return '';
    }
    switch ($src_info) {
        case 2:
            $createtype = 'imagecreatefromjpeg';
            $headertype = 'imagejpeg';
            break;
        case 1:
            $createtype = 'imagecreatefromgif';
            $headertype = 'imagegif';
            break;
        case 3:
            $createtype = 'imagecreatefrompng';
            $headertype = 'imagepng';
            break;
        default:
            $createtype = 'imagecreatefromjpeg';
            $headertype = 'imagejpeg';
            break;
    }
    $im = $createtype($imagepath);
    $im_p = imagecreatetruecolor($src_nw, $src_nh);
    if ($createtype == 'imagecreatefrompng') {
        imagesavealpha($im_p, true);
        imagealphablending($im_p, false);
        imagesavealpha($im_p, true);
    }
    imagecopyresampled($im_p, $im, 0, 0, 0, 0, $src_nw, $src_nh, $src_w, $src_h);
    if ($headertype == 'imagejpeg')
        $headertype($im_p, $thumbpath, $quality);
    else
        $headertype($im_p, $thumbpath);
    return $thumbpath;
}

/**
 * curl模拟post请求
 * @param $url
 * @param $data
 * @param bool $retJson
 * @param bool $setHeader
 * @return mixed
 */
function do_post($url, $data, $retJson = true, $setHeader = false)
{
    $auth = Ebh::app()->getInput()->cookie('auth');
    $uri = Ebh::app()->getUri();
    $domain = $uri->uri_domain();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if ($setHeader) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
    }
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIE, 'ebh_auth=' . urlencode($auth) . ';ebh_domain=' . $domain);
    $ret = curl_exec($ch);
    curl_close($ch);
    if ($retJson == false) {
        $ret = json_decode($ret);
    }
    return $ret;
}

/**
 * 判断是否手机浏览器
 * @return int
 */
function is_mobile()
{
    $check = false;
    // returns true if one of the specified mobile browsers is detected
    // 如果监测到是指定的浏览器之一则返回true
    $regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
    $regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match .= ")/i";
    // preg_match()方法功能为匹配字符，既第二个参数所含字符是否包含第一个参数所含字符，包含则返回1既true
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $check = preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
    }
    return $check;
}

/**
 * 获取客户端IP地址
 * @return string IP_ADDRESS
 */
function getip()
{
    $ip_address = '';
    if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        $ip_address = $_SERVER["HTTP_CLIENT_IP"];
    else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (!empty($_SERVER["REMOTE_ADDR"]))
        $ip_address = $_SERVER["REMOTE_ADDR"];
    else
        $ip_address = "127.0.0.1";
    $ip_address = preg_match('/[\d\.]{7,15}/', $ip_address, $matches) ? $matches[0] : '';
    return $ip_address;
}

/**
 * 获取客户端浏览器user_agent信息
 * @return string 返回user_agent信息
 */
function user_agent()
{
    $user_agent = '';
    $user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
    return $user_agent;
}

/**
 *获取客户端信息
 */
function getClient()
{
    $userAgent = user_agent();
    if (empty($userAgent))
        return FALSE;
    $userAgent = strtolower($userAgent);
    //处理系统信息
    $sys = 'other';
    $sysversion = '';
    $vendor = '';
    if (strpos($userAgent, 'ipad') !== FALSE) {
        $sys = 'iPad';
        if (preg_match('/cpu os ([\d_]+)/', $userAgent, $matchs)) {
            $sysversion = $matchs[1];
        }
    } else if (strpos($userAgent, 'iphone') !== FALSE) {
        $sys = 'iPhone';
        if (preg_match('/iphone os ([\d_]+)/', $userAgent, $matchs)) {
            $sysversion = $matchs[1];
        }
    } else if (strpos($userAgent, 'android') !== FALSE) {
        $sys = 'Android';
        if (preg_match('/android ([\d.]+)/', $userAgent, $matchs)) {
            $sysversion = $matchs[1];
        }
    } else if (strpos($userAgent, 'linux') !== FALSE) {
        $sys = 'Linux';
    } else if (strpos($userAgent, 'windows mobile') !== FALSE || strpos($userAgent, 'windows ce') !== FALSE) {
        $sys = 'Windows Mobile';
    } else if (strpos($userAgent, 'windows') !== FALSE) {    //windows 则设置版本
        if (strpos($userAgent, 'windows nt 5.0') !== FALSE || strpos($userAgent, 'windows 2000') !== FALSE) {
            $sys = 'Win2000';
        } else if (strpos($userAgent, 'windows nt 5.1') !== FALSE || strpos($userAgent, 'windows xp') !== FALSE) {
            $sys = 'WinXP';
        } else if (strpos($userAgent, 'windows nt 5.2') !== FALSE || strpos($userAgent, 'windows 2003') !== FALSE) {
            $sys = 'Win2003';
        } else if (strpos($userAgent, 'windows nt 6.0') !== FALSE || strpos($userAgent, 'windows Vista') !== FALSE) {
            $sys = 'WinVista';
        } else if (strpos($userAgent, 'windows nt 6.1') !== FALSE || strpos($userAgent, 'windows 7') !== FALSE) {
            $sys = 'Win7';
        } else if (strpos($userAgent, 'windows nt 6.2') !== FALSE || strpos($userAgent, 'windows 8') !== FALSE) {
            $sys = 'Win8';
        } else if (strpos($userAgent, 'windows nt 6.3') !== FALSE || strpos($userAgent, 'windows 8.1') !== FALSE) {
            $sys = 'Win8.1';
        } else if (strpos($userAgent, 'windows nt 10') !== FALSE || strpos($userAgent, 'windows 10') !== FALSE) {
            $sys = 'Win10';
        }
    } else if (strpos($userAgent, 'mac') !== FALSE) {
        $sys = 'Mac';
    } else if (strpos($userAgent, 'X11') !== FALSE) {
        $sys = 'Unix';
    }
    //处理浏览器厂家
    if (strpos($userAgent, 'ebhbrowser') !== FALSE) {
        $vendor = '直播客户端';
    } else if (strpos($userAgent, 'micromessenger') !== FALSE) {
        $vendor = '微信';
    } else if (strpos($userAgent, 'maxthon') !== FALSE) {
        $vendor = '遨游';
    } else if (strpos($userAgent, 'qqbrowser') !== FALSE) {
        $vendor = 'QQ';
    } else if (strpos($userAgent, 'metasr') !== FALSE) {
        $vendor = '搜狗';
    } else if (strpos($userAgent, 'lbbrowser') !== FALSE) {
        $vendor = '猎豹';
    } else if (strpos($userAgent, 'opr') !== FALSE || strpos($userAgent, 'opera') !== FALSE) {
        $vendor = '欧朋';
    } else if (strpos($userAgent, 'edge') !== FALSE) {
        $vendor = 'Edge';
    } else if (strpos($userAgent, 'bidubrowser') !== FALSE) {
        $vendor = '百度';
    } else if (strpos($userAgent, 'juzibrowser') !== FALSE) {
        $vendor = '桔子';
    } else if (strpos($userAgent, 'theworld') !== FALSE) {
        $vendor = '世界之窗';
    } else if (strpos($userAgent, 'firefox') !== FALSE) {
        $vendor = '火狐';
    } else if (strpos($userAgent, 'ubrowser') !== FALSE) {
        $vendor = 'UC';
    } else if (strpos($userAgent, 'chrome') !== FALSE) {
        $vendor = '谷歌';
    }//chrome放在最后
    //处理浏览器和版本信息
    $browser = '';
    $broversion = 0;
    if (preg_match('/ebhbrowser\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'ebhBrowser';
    } else if (preg_match('/bidubrowser\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'BIDUBrowser';
    } else if (preg_match('/juzibrowser\/([\d.]+)/', $userAgent, $matchs)) {//没有版本,ie
        $broversion = $matchs[1];
        $browser = 'juzibrowser';
    } else if (preg_match('/lbbrowser\/([\d.]+)/', $userAgent, $matchs)) {//没有版本,chrome
        $broversion = $matchs[1];
        $browser = 'lbbrowser';
    } else if (preg_match('/ubrowser\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'ubrowser';
    } else if (preg_match('/theworld ([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'theworld';
    } else if (preg_match('/micromessenger\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'micromessenger';
    } else if (preg_match('/edge\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Edge';
    } else if (preg_match('/maxthon\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'maxthon';
    } else if (preg_match('/qqbrowser\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'qqbrowser';
    } else if (preg_match('/metasr ([\d.]+)/', $userAgent, $matchs)) {//版本与关于里不太符合,1.0
        $broversion = $matchs[1];
        $browser = 'metasr';
    } else if (preg_match('/trident\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = intval($matchs[1]);
        $browser = 'IE';
        $broversion = $broversion + 4;
    } else if (preg_match('/rv:([\d.]+)\) like gecko/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'IE';
    } else if (preg_match('/msie ([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'IE';
    } else if (preg_match('/firefox\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Firefox';
    } else if (preg_match('/opera.([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Opera';
    } else if (preg_match('/opr\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Opera';
    } else if (preg_match('/chrome\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Chrome';
    } else if (preg_match('/safari\/([\d.]+)/', $userAgent, $matchs)) {
        $broversion = $matchs[1];
        $browser = 'Safari';
    }
    $ip = getip();

    $client = array('system' => $sys, 'systemversion' => $sysversion, 'browser' => $browser, 'broversion' => $broversion, 'vendor' => $vendor, 'ip' => $ip);
    return $client;
}
/**
 * 隐藏名字第二个字(中英文) 如：张*丰
 * @param $name
 * @return string
 */
function hidename($name)
{
    $strlen = mb_strlen($name, 'utf-8');
    $firstStr = mb_substr($name, 0, 1, 'utf-8');
    $lastStr = mb_substr($name, 2, $strlen - 2, 'utf-8');
    $name = $firstStr . '*' . $lastStr;
    return $name;
}
