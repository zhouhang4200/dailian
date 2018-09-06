<?php

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 生成订单号
 * @return string
 * @throws Exception
 */
function generateOrderNo()
{
    // 14位长度当前的时间 20150709105750
    $orderDate = date('YmdHis');
    // 今日订单数量
    $orderQuantity = cache()->increment(config('redis_key.order.quantity') . date('Ymd'));
    return $orderDate . str_pad($orderQuantity, 8, 0, STR_PAD_LEFT);
}

/**
 * 将秒转成: (天\小时\分\秒) 形式
 *
 * @param      $seconds
 * @param bool $showSeconds
 *
 * @return bool|string
 */
function sec2Time($seconds, $showSeconds = false)
{
    if (is_numeric($seconds)) {
        $value = array(
            'years' => 0, 'days' => 0, 'hours' => 0,
            'minutes' => 0, 'seconds' => 0,
        );
        if ($seconds >= 31556926) {
            $value['years'] = floor($seconds / 31556926);
            $seconds = ($seconds % 31556926);
        }
        if ($seconds >= 86400) {
            $value['days'] = floor($seconds / 86400);
            $seconds = ($seconds % 86400);
        }
        if ($seconds >= 3600) {
            $value['hours'] = floor($seconds / 3600);
            $seconds = ($seconds % 3600);
        }
        if ($seconds >= 60) {
            $value['minutes'] = floor($seconds / 60);
            $seconds = ($seconds % 60);
        }
        $value['seconds'] = floor($seconds);

        $t = '';
        if ($value['years'] > 0) {
            $t .= $value['years'] . '年';
        }
        if ($value['days'] > 0) {
            $t .= $value['days'] . '天';
        }
        if ($value['hours'] > 0) {
            $t .= $value['hours'] . '小时';
        }
        if ($value['minutes'] > 0) {
            $t .= $value['minutes'] . '分';
        }
        if ($value['seconds'] > 0 || $showSeconds) {
            $t .= $value['seconds'] . '秒';
        }
        Return $t;
    } else {
        return (bool)FALSE;
    }
}


if (!function_exists('hasEmployees')) {
    /**
     * 获取某个岗位有哪些员工
     * @param string $prefix
     * @return string
     */
    function hasEmployees($userRole)
    {
        $userNames = $userRole->users ? $userRole->users->pluck('name')->toArray() : '';

        if ($userNames) {
            return implode($userNames, '、');
        }
        return '';
    }
}

if(!function_exists('clientRSADecrypt')){
    /**
     * 前端传输数据解密
     * @param $hexEncryptData
     * @return mixed
     */
    function clientRSADecrypt($hexEncryptData)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCpaqa1W3o3nu1BbA33xmbCp52cxdpduvayixPGMYeF33ccAtpa
gdjToIo8f/bh5JGAIZIihOx/UPl7NtcqjZ0O6cG8EuoPJ1Gdo/Qe+uNtzSWmI/S1
IwDW0GAW5lTP1X8NO9u4NVxebXfr1be6xZpnluhEMp2SKQEZrA89dx/15wIDAQAB
AoGBAIYK8T3609dgMl4Z7W9GlhWbYxQgYybX/8rCSXH9zDl61pXeF/+WTwUaN2Wo
5aBTJWAYr7QKMciGO+5mNJXhmApjoP5edlqp86i4yErd3kukwaXgc6n3pmCsYR9C
TWYdD3X726DQt+5dee8Pw42RLfcvC/xGhuaPuEGBcp6eFRBxAkEA21VedrlJZovj
bx5UrcaGvxpgGy0B58nW/k83COQmo1w+CX+P4yekmsAgZyt1iRVRkoknEmld3rnD
/ubzaMXnjwJBAMW9CChee90mGtTyrvlUpOIv2pbSIARtR8duu/SzPBmWEbJttdRg
hZojWGP8DZowBOU30DqdvidcI2JhZUfEICkCQGFHZMVNerOjubTQBAiq85qQzS1g
cebnC5bxdVxZLJXp1I4L6Lp8G7KTIgwAJ3osXWibshulZf/h7n8A2daPaBsCQDp1
UycUH8xWipIwGPiPRJu2CAqUnnCQmirkmt6R6o+p5Rt6AcqCqpzSHDya9K6Dyb62
THI31lKuk6tvHdEks1kCQQCX5XtcAsLKa9Vd1BvZcNWLXYXCeJX3cOQg5obrXuNa
fgMCzgxMM0hmL1eC3kSxtd4z5gUAHLUxwuzrG+JroHpk
-----END RSA PRIVATE KEY-----";

        $encryptData = pack("H*", $hexEncryptData);
        openssl_private_decrypt($encryptData, $decryptData, $privateKey);
        return $decryptData;
    }
}

if (!function_exists('getIp')) {
    /**
     * @return string
     */
    function getIp()
    {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : null;
    }
}

if (!function_exists('getLoginCity')) {
    /**
     * @param $ip
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function getLoginCity($ip)
    {
        try {
            $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;

            $client = new Client();

            $res = $client->request('GET', $url);
            $res = $res->getBody()->getContents();

            $res = json_decode($res);

            if (isset($res->code) && $res->code == 0) {
                return $res->data->city ? : null;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}

if (!function_exists('myLog')) {
    /**
     * 自定义日志写入
     * @param $fileName
     * @param array $data
     * @throws Exception
     */
    function myLog($fileName, $data = [])
    {
        if (php_sapi_name() == 'cli') {
            $fileName = $fileName . '-cli';
        }
        $log = new \Monolog\Logger($fileName);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $fileName . '-' . date('Y-m-d') .'.log'));
        $log->addInfo($fileName, $data);
    }
}
if (!function_exists('base64ToImg')) {

    /**
     * 将base64图片存为图片到resources 指定目录
     * @param $base64Str string
     * @param $path string 指定的目录
     * @return array
     */
    function base64ToImg($base64Str, $path)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Str, $result)) {
            $imgDir = '/resources/' . $path . '/';
            if (!is_dir(public_path($imgDir))) {
                mkdir(public_path($imgDir), 0777);
            }
            $imageName = uniqid() . '.png';
            $imgPath = $imgDir .  $imageName;
            if (file_put_contents(public_path($imgPath), base64_decode(str_replace($result[1], '', $base64Str)))) {
                return [
                    'mime_type' => 'image/png',
                    'name' => $imageName,
                    'path' => $imgPath,
                ];
            }
        }
    }
}
if (!function_exists('imageJsonToArr')) {

    /**
     * 将图片路径json 转 数组
     * @param $jsonImages
     * @return array
     */
    function imageJsonToArr($jsonImages)
    {
        $images = [];
        foreach (json_decode($jsonImages, true) as $image) {
            $images[] = [
                'path' => $image,
            ];
        }
        return $images;
    }
}

if (!function_exists('export')) {
    /**
     * 导出数据
     * @param $title
     * @param $name
     * @param $callback
     *
       export([
           'id',
           '名字',
        ], '财务订单导出', $query, function ($query, $out){
           $query->chunk(100, function ($items) use ($out) {

               foreach ($items as $item) {
                   $data = [
                       $item->id,
                       $item->name,
                   ];
                   fputcsv($out, $data);
               }
           });
       });

     */
    function export($title, $name, $query, $callback)
    {
        $response = new StreamedResponse(function () use ($title, $name, $query, $callback){
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF)); // 添加 BOM
            fputcsv($out, $title);

            $callback($query, $out);

            fclose($out);
        },200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' .  $name .   '.csv"',
        ]);
        $response->send();
    }
}

/**
 * 获取汉子首字母
 * @param $s0
 * @return null|string
 */
function getFirstChar($s0){
    $fChar = ord($s0{0});

    if($fChar >= ord("A") and $fChar <= ord("z") ) {
        return strtoupper($s0{0});
    }

    $s1 = getEncoding($s0, 'GB2312');
    $s2 = getEncoding($s1, 'UTF-8');
    if ($s2 == $s0) {
        $s = $s1;
    } else {
        $s = $s0;
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 and $asc <= -20284) return "a";
    if ($asc >= -20283 and $asc <= -19776) return "b";
    if ($asc >= -19775 and $asc <= -19219) return "c";
    if ($asc >= -19218 and $asc <= -18711) return "d";
    if ($asc >= -18710 and $asc <= -18527) return "e";
    if ($asc >= -18526 and $asc <= -18240) return "f";
    if ($asc >= -18239 and $asc <= -17923) return "g";
    if ($asc >= -17922 and $asc <= -17418) return "i";
    if ($asc >= -17417 and $asc <= -16475) return "j";
    if ($asc >= -16474 and $asc <= -16213) return "k";
    if ($asc >= -16212 and $asc <= -15641) return "l";
    if ($asc >= -15640 and $asc <= -15166) return "m";
    if ($asc >= -15165 and $asc <= -14923) return "n";
    if ($asc >= -14922 and $asc <= -14915) return "o";
    if ($asc >= -14914 and $asc <= -14631) return "p";
    if ($asc >= -14630 and $asc <= -14150) return "q";
    if ($asc >= -14149 and $asc <= -14091) return "r";
    if ($asc >= -14090 and $asc <= -13319) return "s";
    if ($asc >= -13318 and $asc <= -12839) return "s";
    if ($asc >= -12838 and $asc <= -12557) return "w";
    if ($asc >= -12556 and $asc <= -11848) return "w";
    if ($asc >= -11847 and $asc <= -11056) return "y";
    if ($asc >= -11055 and $asc <= -10247) return "z";
    return null;
}

/**
 * 自动检测内容编码进行转换
 * @param $data
 * @param $to
 * @return null|string|string[]
 */
function getEncoding($data, $to){
    $encodeArr =array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
    $encoded = mb_detect_encoding($data, $encodeArr);
    $data = mb_convert_encoding($data, $to, $encoded);
    return $data;
}

function pinyin($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getFirstChar($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}

function printSql($callback)
{
    \DB::connection()->enableQueryLog();
    call_user_func($callback);
    dd(\DB::getQueryLog());
}

/*
 * 文件转base64输出
 */
function fileToBase64($file){
    $base64File = '';
    if(file_exists($file)){
        $mimeType= mime_content_type($file);
        $base64Data = base64_encode(file_get_contents($file));
        $base64File = 'data:'.$mimeType.';base64,'.$base64Data;
        unlink($file);
    }
    return $base64File;
}

function randomNumber($count = 5) {
    $number = '';
    for ($i=0; $i < $count; $i ++) {
         $number .= random_int(0, 9);
    }
    return $number;
}


if (!function_exists('toPercent')) {

    /**
     * 转化为百分比
     * @param $jsonImages
     * @return array
     */
    function toPercent($a, $b)
    {
        if (! $b || ! is_numeric($a) || ! is_numeric($b)) {
            return "0%";
        } else {
            return (100*(bcdiv($a, $b, 4)+0))."%";
        }
    }
}