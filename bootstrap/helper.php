<?php

/**
 * 生成订单号
 * @return string
 */
function generateOrderNo()
{
    // 14位长度当前的时间 20150709105750
    $orderDate = date('YmdHis');
    // 今日订单数量
    $orderQuantity = cache()->increment(config('redis_key.order.quantity') . date('Ymd'));
    return $orderDate . str_pad($orderQuantity, 8, 0, STR_PAD_LEFT);
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