<?php
/**
 * User: gl
 * Time: 9:53
 * 实现 json web token 类 用于用户验证
 */
namespace vendor\jwt;
use think\Config;
class Jwt{
    protected $key = '';
    protected $leeway = 0;
    protected $errMsg = '';
    protected $supported_algs = array(
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'RS256' => array('openssl', 'SHA256'),
    );
    public $timestamp = null;
    public function __construct($key,$leeway = 0){
        $this->key = $key;
        // $this->key = Config::get('jwt')['key'];
        $this->leeway = $leeway;
    }
    public function getErrMsg(){
        return $this->errMsg;
    }

    /**
     * 解密jwt
     * @param $jwt
     * @return bool|mixed
     */
    public function decode($jwt){
        $timestamp  = is_null($this->timestamp) ? time() : $this->timestamp;

        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            $this->errMsg = 'Wrong number of segments';
            return false;
        }

        list($headb64, $bodyb64, $cryptob64) = $tks;

        if(null === ($header = json_decode(base64_decode($headb64)))){
            $this->errMsg = 'Invalid header encoding';
            return false;
        }

        if(null === ($payload = json_decode(base64_decode($bodyb64)))){
            $this->errMsg = 'Invalid claims encoding';
            return false;
        }

        $sig = base64_decode($cryptob64);

        if (empty($header->alg)) {
            $this->errMsg = 'Empty algorithm';
            return false;
        }

        if(empty($this->supported_algs[$header->alg])){
            $this->errMsg = 'Algorithm not supported';
        }



        if (isset($payload->nbf) && $payload->nbf > ($timestamp + $this->leeway)) {
            $this->errMsg = 'Cannot handle token prior to'.date(DateTime::ISO8601, $payload->nbf);
            return false;
        }


        if (isset($payload->iat) && $payload->iat > ($timestamp + $this->leeway)) {
            $this->errMsg = 'Cannot handle token prior to'.date(DateTime::ISO8601, $payload->iat);
            return false;
        }

        if (isset($payload->exp) && ($timestamp - $this->leeway) >= $payload->exp) {
            $this->errMsg = 'Expired token';
            return false;
        }


        return (array)$payload;

    }

    /**
     * 在原来的jwt基础上添加数据
     * @param $jwt
     * @param array $data
     * @param string $alg
     * @param null $head
     * @return string
     */
    public function addData($jwt,$data = array(),$alg = 'HS256', $head = null){
        $payload = $this->decode($jwt);
        $payload = array_merge($payload,$data);


        return $this->encode($payload,$alg,$head);
    }

    /**
     * 移除jwt中的指定元素
     * @param $jwt
     * @param $key
     * @param string $alg
     * @param null $head
     * @return string
     */
    public function deleteData($jwt,$key,$alg = 'HS256', $head = null){
        $payload = $this->decode($jwt);
        unset($payload[$key]);

        return $this->encode($payload,$alg,$head);

    }

    /**
     * 生成json web token
     * @param $payload 载体
     * @param string $alg 加密方式 HS256
     * @param null $head 附加header信息
     * @return string
     */
    public function encode($payload,$alg = 'HS256', $head = null){
        $header = array('typ' => 'JWT', 'alg' => $alg);
        if ( isset($head) && is_array($head) ) {
            $header = array_merge($head, $header);
        }

        if(!isset($payload['iat'])){
            $payload['iat'] = time();
        }
        if(!isset($payload['exp'])){
            $payload['exp'] = time() + Config::get('jwt')['exp'];
        }


        $segments = array();
        $segments[] = base64_encode(json_encode($header));
        $segments[] = base64_encode(json_encode($payload));
        $signing_input = implode('.', $segments);
        $signature = $this->sign($signing_input, $this->key, $alg);
        $segments[] = base64_encode($signature);

        return implode('.',$segments);
    }





    /**
     * 加密函数
     * @param $msg
     * @param $key
     * @param string $alg
     * @return string
     * @throws Exception
     */
    public function sign($msg, $key, $alg = 'HS256'){
        if(empty($this->supported_algs[$alg])){
            $this->errMsg = 'Algorithm not supported';
            return false;
        }

        list($function, $algorithm) = $this->supported_algs[$alg];

        switch($function) {
            case 'hash_hmac':
                return hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm);
                if (!$success) {
                    $this->errMsg = 'OpenSSL unable to sign data';
                    return false;
                } else {
                    return $signature;
                }
        }
    }

    /**
     * 校验 json web token
     * @param $msg
     * @param $signature
     * @param $alg
     * @return bool
     */
    public function verify($msg, $signature,$alg){
        if(empty($this->supported_algs[$alg])){
            $this->errMsg = 'Algorithm not supported';
            return false;
        }

        list($function, $algorithm) = $this->supported_algs[$alg];

        switch($function) {
            case 'openssl':
                $success = openssl_verify($msg, $signature, $this->key, $algorithm);
                if (!$success) {
                    $this->errMsg = 'OpenSSL unable to verify data:'.openssl_error_string();
                } else {
                    return $signature;
                }
            case 'hash_hmac':
            default:
                $hash = hash_hmac($algorithm, $msg, $this->key, true);
                if (function_exists('hash_equals')) {
                    return hash_equals($signature, $hash);
                }
                $len = min($this->safeStrlen($signature), $this->safeStrlen($hash));

                $status = 0;
                for ($i = 0; $i < $len; $i++) {
                    $status |= (ord($signature[$i]) ^ ord($hash[$i]));
                }
                $status |= ($this->safeStrlen($signature) ^ $this->safeStrlen($hash));

                return ($status === 0);
        }


    }
    private function safeStrlen($str){
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }
}