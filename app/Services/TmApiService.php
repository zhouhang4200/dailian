<?php

namespace App\Services;

use DB;
use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\GameLevelingOrder;
use App\Exceptions\UnknownException;

/**
 * 丸子调天猫发单平台的操作类
 * Class TmApiService
 * @package App\Services
 */
class TmApiService
{
    /**
     * 制作签名
     * @param array $options
     * @return string
     */
    public static function getSign($options = [])
    {
        ksort($options);
        $str = '';
        foreach ($options as $key => $value) {
            $str .= $key . '=' . $value . '&';
        }
        return md5(rtrim($str,  '&') . config('tm.app_secret'));
    }

    /**
     * 普通请求
     * @param $options
     * @param $url
     * @param string $method
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public static function normalRequest($options, $url, $method = 'POST')
    {
        $client = new Client();
        $response = $client->request($method, $url, [
            'form_params' => $options,
        ]);
        $result =  $response->getBody()->getContents();

        myLog('tm-request', ['地址' => $url,'信息' => $options,'结果' => $result]);

        if (! isset($result) || empty($result)) {
            throw new Exception('请求返回数据不存在');
        }

        if (isset($result) && ! empty($result)) {
            $arrResult = json_decode($result, true);

            // 失败
            if (isset($arrResult) && is_array($arrResult) && count($arrResult) > 0) {
                if (isset($arrResult['code']) && $arrResult['code'] != 1) {
                    $message = $arrResult['message'] ?? '发单器接口返回错误';

                    throw new Exception($message);
                }
            }
        }
        return json_decode($result, true);

    }

    /**
     * multipart 发送
     * @param $options
     * @param $url
     * @param string $method
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public static function formDataRequest($options, $url, $method = 'POST')
    {

        $data = [];
        foreach ($options as $name => $value) {
            $data[$name]['name'] = $name;
            $data[$name]['contents'] = $value;
        }
        $client = new Client();
        $response = $client->request($method, $url, [
            'multipart' => $data,
        ]);
        $result = $response->getBody()->getContents();

        // 发送日志
        myLog('qs-request-result', ['地址' => $url,'信息' => $options,'结果' => $result]);

        if (! isset($result) || empty($result)) {
            throw new Exception('请求返回数据不存在');
        }

        if (isset($result) && ! empty($result)) {
            $arrResult = json_decode($result, true);

            // 失败
            if (isset($arrResult) && is_array($arrResult) && count($arrResult) > 0) {
                if (isset($arrResult['code']) && $arrResult['code'] != 1) {
                    $message = $arrResult['message'] ?? '发单器接口返回错误';
                    throw new Exception($message);
                }
            }
        }
        return json_decode($result, true);

    }

    /**
     * 接单
     * @param GameLevelingOrder $order
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function take(GameLevelingOrder $order)
    {
        try {
            $user = User::find($order->take_parent_user_id);
            $options = [
                'order_no' => $order->trade_no,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
                'hatchet_man_qq' => $user->qq ?? '0',
                'hatchet_man_phone' => $user->phone,
                'hatchet_man_name' => $user->name ?? '空',
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.take'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  异常
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function anomaly($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.anomaly'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * 取消异常
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function cancelAnomaly($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.cancel_anomaly'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * 申请仲裁
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function applyComplain($orderNo, $reason)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
                'content' => $reason,
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.apply_complain'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  取消仲裁
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function cancelComplain($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.cancel_complain'));
        } catch (TmApiException $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  申请完成
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function applyComplete($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.apply_complete'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  申请协商
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function applyConsult($orderNo, $amount, $deposit, $reason)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
                'api_amount' => $amount,
                'api_deposit' => $deposit,
                'api_service' => 0,
                'content' => $reason,
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.apply_consult'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  取消协商
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function cancelConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.cancel_consult'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  同意协商
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function agreeConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'api_service' => 0,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.agree_consult'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  不同意协商
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function rejectConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.refuse_consult'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  客服仲裁
     * @param $amount
     * @param $deposit
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function arbitration($orderNo, $amount, $deposit)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
                'api_amount' => $amount,
                'api_deposit' => $deposit,
                'api_service' => 0,
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.arbitration'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     *  取消验收
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws UnknownException
     */
    public static function cancelComplete($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => config('tm.app_id'),
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, config('tm.action.cancel_complete'));
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * @param $orderNo
     * @param $tradNo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function callback($orderNo, $tradNo)
    {
        $options = [
            'no' => $orderNo,
            'order_no' => $tradNo,
            'app_id' => config('tm.app_id'),
            'timestamp' => time()
        ];

        // 合成发单器的签名
        $options['sign'] = static::getSign($options);;

        $client = new Client();
        $response = $client->request('POST', config('tm.action.callback'), [
            'form_params' => $options,
            'body' => 'x-www-form-urlencoded',
        ]);
        $result = $response->getBody()->getContents();

        return json_decode($result, true);
    }

    /**
     * 解密数据
     * @param $encryptOrderData
     * @return mixed
     */
    public static function decryptOrderData($encryptOrderData)
    {
        $decryptData = openssl_decrypt($encryptOrderData, 'aes-128-cbc', config('tm.aes_key'), false, config('tm.aes_iv'));
        return json_decode($decryptData, true);
    }
}
