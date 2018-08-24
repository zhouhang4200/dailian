<?php

namespace App\Services;

/**
 * Class SmSApiService
 * @package App\Services
 */
class SmSApiService
{
    /**
     * @var string
     */
    private static $gateway = 'http://fulu.10690007.net';

    /**
     * 短信发送
     * @param int $type 1 验信验证码 2 营销短信
     * @param int $to 发送给谁(手机号)
     * @param string $content 发送内容
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function send($to, $content, $type = 1)
    {
        $user = $type == 1 ? '20156' : '20157';
        $password = $type == 1 ? 'gcsEc1sx' : 'zbXRhLhl';

        //预定义参数，参数说明见文档
        $spSc = "02"; // 服务代码
        $sa = "10";  // 源地址
        $dc = "15";

        //拼接URI
        $request = "/sms/mt";
        $request .= "?command=MT_REQUEST&spid=" . $user . "&spsc=" . $spSc . "&sppassword=" . $password;
        $request .= "&sa=" . $sa . "&da=86" . $to . "&dc=" . $dc . "&sm=";
        $request .= bin2hex(mb_convert_encoding($content, "GBK", "UTF-8"));//下发内容转换HEX编码
        $uri = self::$gateway . $request;

        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', $uri);
        $content =  $result->getBody()->getContents();

        if ((bool)strpos($content, "mterrcode=000")) {
            return true;
        }
        return false;
    }
}