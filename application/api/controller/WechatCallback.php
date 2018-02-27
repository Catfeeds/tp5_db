<?php
/**
  * 微信扩展类
  */
class WechatCallback
{
	private $_token = NULL;		//微信接口token
	private $_appID = NULL;		//微信接口appID 
	private $_appsecret = NULL; //微信接口appsecret
	private $_access_token = NULL;	//微信接口公众号的全局唯一票据，为了避免每次从微信接口取数据，设置此变量
	public function __construct() {
		$config = Ebh::app()->getConfig()->load('weixin');
		$this->_token 	= $config['token'];
		$this->_appID 	= $config['appID'];
		$this->_appsecret 	= $config['appsecret'];
		$this->_template = $config['template'];
	}
	/**
	*根据客户端code获取对应的openid，
	*主要用户微信菜单上的网页授权
	*微信点击菜单会传递code参数
	*@param string $code 网页授权code，每次点击菜单会传递此参数，且每次都会不同
	*@return string 返回 openid
	*/
	public function getopenidbycode($code) {
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->_appID.'&secret='.$this->_appsecret.'&code='.$code.'&grant_type=authorization_code';
		$tokenjson = $this->getHttpResponseGET($url);
		$tokenarr = json_decode($tokenjson);
		if(empty($tokenarr) || !empty($tokenarr->errcode) || empty($tokenarr->openid))
			return FALSE;
		$openid = $tokenarr->openid;
		return $openid;
	}

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
      	//extract post data
		if (!empty($postStr)){

              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
	/**
	*验证微信接口发送过来的消息是否合法
	*/
	public function checkSignature()
	{
		if(empty($_GET["signature"]) || empty($_GET["timestamp"]))
			return FALSE;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];

        $nonce = $_GET["nonce"];
		$tmpArr = array($this->_token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	/**
	*解析微信客户端提交的数据
	*/
	public function parsedata() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			return $postObj;
		}
		return FALSE;
	}
	/**
	*用户关注微信号事件处理
	*/
	public function subscribe($postobj) {
		$openid = $postobj->FromUserName.'';
		if(empty($openid))
			return FALSE;
		$userinfo = $this->getuserinfo($openid);
		$param = array(
			'openid'=>$openid,
			'nickname'=>$userinfo->nickname,
			'sex'=>$userinfo->sex,
			'province'=>$userinfo->province,
			'city'=>$userinfo->city,
			'country'=>$userinfo->country,
			'headimgurl'=>$userinfo->headimgurl,
			'status'=>1,
			'dateline'=>$userinfo->subscribe_time
			);
		$weixinmodel = Ebh::app()->model('Weixin');
		$result = $weixinmodel->insertWeixinInfo($param);
		//绑定后发送欢迎信息
		$openid = $postobj->FromUserName.'';
		
		//砍价活动暂时修改关注后消息推送  eker 2016年10月20日13:43:18
		$content = '您好，欢迎关注e板会(www.ebh.net)-----中国大型互联网知识商城的缔造者，全球领先的网络学校技术服务商。e板会网校砍价活动正式开始了！1、参与方式：进入活动页面，直接点击“我要参加”或右下角“我要报名”，即可发起本人的砍价活动；转发活动页面，邀请好友为您砍价；每位好友只能砍一次；砍至0元，即为砍价成功。2、助砍方法：分享朋友后，关注本公众号，点击菜单栏砍价进入页面即可帮助砍价！还在等什么，赶紧呼朋唤友来参加吧！';
		//$content = '您好，欢迎关注e板会，e板会网络学校运营平台(www.ebh.net)一直致力于打造“人人开网校，个个当校长”的中国梦。是运用云计算技术，推动教育变革和创新，构建网络化、数字化、个性化、终身化的教育体系，建设“人人皆学、处处能学、时时可学”的学习型社会生态圈大平台。';
		$this->sendmessagebyopenid($openid,$content);
	}
	public function about($postobj) {
		$openid = $postobj->FromUserName.'';
		$content = '“e板会”是浙江新盛蓝科技有限公司开发的一项以网络为基础，应用最新的通信技术和教学媒介，建立全球经验最丰富&学识最渊博的师资团队，汇聚全球最丰富教学资源的信息平台。实况课件在电子板书即时录播的“e板会”是全球领先的网络在线资源有偿分享增值服务平台。
		“e板会”平台凭借丰富的数据共享、清晰流畅的音视频效果赢得了教育行业的认可。在网络教学和网络培训中，体现了功能稳定、简单易用的优势，在处理多方多层次的音频、视频、数据等交互方面有着更多的技术处理手段和应用积累，为教育打造出了基于网络的互动课堂，使网络教育更加生动和具体。';
		$this->sendmessagebyopenid($openid,$content);
	}
	/**
	*取消关注事件
	*/
	public function unsubscribe($postobj) {
		$openid = $postobj->FromUserName.'';
		$setarr = array('status'=>0);
		$wherearr = array('openid'=>$openid);
		if(Ebh::app()->model('Weixin')->subscribeUpdate($status=0,$openid))
			return TRUE;
	}

	/*
	 * 获取关注着用户信息
	 */
	public function getuserinfo($openid) {
		$access_token = $this->getAccess_token();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$userjson = $this->getHttpResponseGET($url);
		$userarr = json_decode($userjson);
		return $userarr;
	}
	

	
	    
	public function get_access_token($fresh = FALSE) {
		GLOBAL $_SGLOBAL;
		$access_token = $_SGLOBAL['cached']->get('access_token');
		if(!empty($access_token) && !$fresh)
			return $access_token;

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appID."&secret=".$this->_appsecret;
		$tokenjson = $this->getHttpResponseGET($url);
		$tokenarr = json_decode($tokenjson);
		$expire_time = $tokenarr->expires_in;
		$expire_time = intval($expire_time) - 1000;
		if($expire_time <= 0)
			$expire_time = 1000;
		$access_token = $tokenarr->access_token;
		$_SGLOBAL['cached']->set('access_token',$access_token,$expire_time);

		return $access_token;
	}
	/**
	*创建菜单
	*/
	public function cretemenu() {
		$access_token = $this->getAccess_token();
		//echo $access_token;exit;
		$menu = '{
			    "button": [
			        {
			            "type": "view",
			            "name": "家长绑定",
			            "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->_appID.'&redirect_uri=http%3A%2F%2Fwww.ebanhui.com%2Fwxbind.html%3Fcallback%3D1&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
			        },
			        {
			            "type": "view",
			            "name": "接收消息",
			            "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->_appID.'&redirect_uri=http%3A%2F%2Fwww.ebanhui.com%2Fwxbind%2Fwxlist.html&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
			        },
			        {
			            "type": "view",
			            "name": "查询分析",
			            "url": "http://www.ebanhui.com"
			        }
			    ]
			}';
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
		$this->getHttpResponsePOST($url,$menu);
	}

	public function vbind($postobj) {
		//$access_token = $this->get_access_token();
		$openid = $postobj->FromUserName.'';
		//$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;

		$content = '您好，欢迎关注e板会，请在微信菜单中选择绑定，绑定后即可享受更多的服务。';
		$this->sendmessagebyopenid($openid,$content);
	}
	//给客户端发微信
	public function sendmessagebyopenid($openid,$content,$resend = FALSE) {
		$access_token = $this->getAccess_token();
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
		$msg = '{
			"touser":"'.$openid.'",
			"msgtype":"text",
			"text":
			{
				 "content":"'.$content.'"
			}
		}';
		$result = $this->https_post($url,$msg);
		if(!empty($result)) {
			$returnarr = json_decode($result);
			if(!empty($returnarr) && !empty($returnarr->errcode) && $returnarr->errcode == '40001') {
				$access_token = $this->getAccess_token(TRUE);
				if(!$resend) {
					return $this->sendmessagebyopenid($touid,$content,TRUE);
				}
				return FALSE;
			}
		}
		return TRUE;
	}
	/**
	 * @desc 回复信息给公众平台
	 * @param $postobj object
	 * @return booean
	 */
	public function sendReplyToClassMsg($postobj){
		$openid = $postobj->FromUserName.'';
		$content = $postobj->Content.'';
		$weixinmodel = Ebh::app()->model('Weixin');
		$uidlist = $weixinmodel->getUidListByOpenid($openid);
		if(empty($uidlist))
			return FALSE;
		$classlists = array();	//所有的班级列表，如绑定多个账号，则可能有多个班级
		foreach($uidlist as $uiditem) {
			$classlist = $weixinmodel->getStudentClassList($uiditem['suid']);
			$classlists[$uiditem['suid']] = $classlist;
		}
		foreach($classlists as $uid=>$classlist) {
			foreach($classlist as $myclass) {
				$param = array('uid'=>$uid,'openid'=>$openid,'classid'=>$myclass['classid'],'msg'=>$content);
				$result = $weixinmodel->addReply($param);
			}
		}
		return FALSE;
	}


	function getHttpResponsePOST($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	function dopost($url,$data) {
		$header[]="Content-Type: text/xml; charset=utf-8";

	//	$header[]="User-Agent: nginx/1.0.0";
	//	$header[]="Host: 127.0.0.1";
		$header[]="Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2";
		$header[]="Connection: keep-alive";
		$header[]="Content-Length: ".strlen($data);

		//$url = "http://{$_SERVER['HTTP_HOST']}".dirname($_SERVER['PHP_SELF']).‘/response.php’;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
		curl_close($ch);
	}

	function https_post($url,$data)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
		   return FALSE;
		}
		curl_close($curl);
		return $result;
	}


	function getHttpResponseGET($url)
	{
		//if (ini_get("allow_url_fopen") == "1")
			//return file_get_contents($url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$result =  curl_exec($ch);
		curl_close($ch);

		return $result;
	}
	/*
	*获取微信接口全局 access_token
	*@param bool $fresh 是否直接从微信服务端重新取值
	*/
	public function getAccess_token($fresh = FALSE){
		$cache = Ebh::app()->getCache();
		if(!$fresh) {	//默认不刷新，则从缓存中取
			if(isset($this->_access_token))	
				return $this->_access_token;
			//从缓存中取
			$access_token = $cache->get('access_token');
			if(!empty($access_token)) {
				$this->_access_token = $access_token;
				return $access_token;
			}
		}
		//从微信服务接口中取
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appID."&secret=".$this->_appsecret;
		$tokenjson = $this->getHttpResponseGET($url);
		$tokenarr = json_decode($tokenjson);
		$expire_time = $tokenarr->expires_in;
		$expire_time = intval($expire_time) - 1000;
		if($expire_time <= 0)
			$expire_time = 1000;
		$access_token = $tokenarr->access_token;
		$cache->set('access_token',$access_token,$expire_time);
		$this->_access_token = $access_token;
		return $access_token;
	}
	//得到关注者的code
	public function getNoticeAppid(){
		$redirect_uri = urlencode("http://mp1.ebanhui.com/wxbind.html");
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->_appID."&redirect_uri=".$redirect_uri."&response_type=code&scope=SCOPE&state=ebanhui#wechat_redirect";
		return $tokenjson = $this->getHttpResponseGET($url);
	}
	//拉取用户信息
	public function getUserInfoAll($access_token,$openid){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		$tokenjson = $this->getHttpResponseGET($url);
		$tokenarr = json_decode($tokenjson);
		return $tokenarr;
	}

	//给客户端发微信
	public function sendMessageByOpenidWithTpl($openid,$data,$resend = FALSE,$template_id=1) {
		$access_token = $this->getAccess_token();
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		$templateKey = 'template_'.$template_id;
		if(empty($this->_template[$templateKey])){
			log_message($templateKey.'的模板不存在');
			return FALSE;
		}
		$header = array(
			"touser"=>$openid,
			"template_id"=>$this->_template[$templateKey],
			"topcolor"=>"#FF0000"
		);
		$msg = array_merge($header,$data);
		$msg = json_encode($msg);
		$result = $this->https_post($url,$msg);
		if(!empty($result)) {
			$returnarr = json_decode($result);
			if(!empty($returnarr) && !empty($returnarr->errcode) && $returnarr->errcode == '40001') {
				$access_token = $this->getAccess_token(TRUE);
				if(!$resend) {
					return $this->sendMessageByOpenidWithTpl($openid,$data,TRUE,$template_id);
				}
				return FALSE;
			}
		}
		return TRUE;
	}
	
	
	

	/******************************网页授权处理 start***********************************************************/
	
	/**
	 * 获取微信授权后code
	 * @return unknown
	 */
	public function getAuthCode($state=''){
	    if(empty($state)){
	        $state  = 'ebanhui';
	    }
	    $redirect_uri = urlencode("http://www.ebh.net/weixin/callback.html");
	    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->_appID."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
	    header("Location:$url");
	    //log_message("url:$url");
	    exit;
	    //echo $url;
	    //return $tokenjson = $this->getHttpResponseGET($url);
	}
	
	/**
	 * 获取网页授权后的token
	 */
	public function getAuthAccessToken($code){
	    //从code换token
	    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appID}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
	    $tokenjson = $this->getHttpResponseGET($url);
	    $tokenarr = json_decode($tokenjson,true);
	    //log_message($tokenarr['access_token']);
	   // var_dump($tokenarr);
	    if(empty($tokenarr) || !empty($tokenarr['errcode']) || empty($tokenarr['openid'])){
	        log_message('从code获取token出错,错误消息:'.$tokenjson.'请求url:'.$url);
	        return FALSE;
	    } 
	    return $tokenarr;
	}
	
	/**
	 * 获取授权用户信息
	 */
	public function getAuthUserInfo($code){
	    $token = $this->getAuthAccessToken($code);
	    if(!empty($token)){
	        $url= "https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$token['openid']}&lang=zh_CN";
	        $userjson = $this->getHttpResponseGET($url);
	        $userinfo = json_decode($userjson,true);
	        
	        if(empty($userinfo) || !empty($userinfo['errcode'])){
	            log_message('从code获取token后,获取用户信息出错,错误消息:'.$userjson.'请求url:'.$url);
	            return FALSE;
	        }
	        //var_dump($userinfo);
	        return $userinfo;
	    }else{
	        return false;   
	    }
	}
	

	
	/*******************************网页授权处理 end************************************************************/
	
}

?>