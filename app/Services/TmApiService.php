<?php

namespace App\Services;

use DB;
use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Game;
use App\Models\Server;
use App\Models\Region;
use Illuminate\Http\UploadedFile;
use App\Models\GameLevelingType;
use App\Services\OrderService;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use App\Exceptions\Order\OrderTimeException;
use App\Exceptions\Order\OrderUserException;
use App\Exceptions\Order\OrderMoneyException;
use App\Exceptions\Order\OrderStatusException;
use App\Exceptions\Order\OrderPasswordException;
use App\Exceptions\Order\OrderAdminUserException;
use App\Exceptions\Order\OrderUnauthorizedException;

/**
 * 丸子调千手的操作类
 * Class QsTransmitterConrtoller
 * @package App\Services
 */
class TmApiService
{
    // 发单平台的app_id
    private static $appId = 'T8WsMDT4mJ5DxKJkf4fWVP5XYU00McJxxyAeoX4aPIy6jrWN70bmQltXfwof';
    // 发单平台的app_secret
    private static $appSecret = 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx';
    // 发单平台的地址
    private static $url = [
        'take' => 'http://www.test.com/api/partner/order/receive', // 接单
        'anomaly' => 'http://www.test.com/api/partner/order/abnormal', // 异常
        'cancelAnomaly' => 'http://www.test.com/api/partner/order/cancel-abnormal', // 取消异常
        'applyComplain' => 'http://www.test.com/api/partner/order/apply-arbitration', // 申请仲裁
        'cancelComplain' => 'http://www.test.com/api/partner/order/cancel-arbitration', // 取消仲裁
        'applyComplete' => 'http://www.test.com/api/partner/order/apply-complete', // 申请验收
        'applyConsult' => 'http://www.test.com/api/partner/order/revoke', // 申请协商
        'cancelConsult' => 'http://www.test.com/api/partner/order/cancel-revoke', // 取消协商
        'agreeConsult' => 'http://www.test.com/api/partner/order/agree-revoke', // 同意协商
        'refuseConsult' => 'http://www.test.com/api/partner/order/refuse-revoke', // 不同意协商
    ];

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
        return md5(rtrim($str,  '&') . static::$appSecret);
    }

    /**
     * 普通请求
     * @param $options
     * @param $url
     * @param string $method
     * @return mixed
     * @throws OrderServiceException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function normalRequest($options, $url, $method = 'POST')
    {
        try {
            $client = new Client();
            $response = $client->request($method, $url, [
                'form_params' => $options,
            ]);
            $result =  $response->getBody()->getContents();
            // 发送日志
//            myLog('qs-request-result', ['地址' => $url,'信息' => $options,'结果' => $result]);

            if (! isset($result) || empty($result)) {
                throw new OrderStatusException('请求返回数据不存在');
            }

            if (isset($result) && ! empty($result)) {
                $arrResult = json_decode($result, true);

                // 失败
                if (isset($arrResult) && is_array($arrResult) && count($arrResult) > 0) {
                    if (isset($arrResult['code']) && $arrResult['code'] != 1) {
                        $message = $arrResult['message'] ?? '发单器接口返回错误';

                        throw new OrderStatusException($message);
                    }
                }
            }
            return json_decode($result, true);
        } catch (OrderStatusException $e) {
//            myLog('tmapi-reback-request-error', ['方法' => '请求', '原因' => $e->getMessage()]);
            throw new OrderStatusException($e->getMessage());
        } catch (Exception $e) {
//            myLog('tmapi-local-request-error', ['方法' => '请求', '原因' => $e->getMessage()]);
            throw new Exception($e->getMessage());
        }
    }

    /**
     * multipart 发送
     * @param $options
     * @param $url
     * @param string $method
     * @return mixed
     * @throws OrderServiceException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function formDataRequest($options, $url, $method = 'POST')
    {
        try {
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
//            myLog('qs-request-result', ['地址' => $url,'信息' => $options,'结果' => $result]);

            if (! isset($result) || empty($result)) {
                throw new OrderStatusException('请求返回数据不存在');
            }

            if (isset($result) && ! empty($result)) {
                $arrResult = json_decode($result, true);

                // 失败
                if (isset($arrResult) && is_array($arrResult) && count($arrResult) > 0) {
                    if (isset($arrResult['code']) && $arrResult['code'] != 1) {
                        $message = $arrResult['message'] ?? '发单器接口返回错误';

                        throw new OrderStatusException($message);
                    }
                }
            }
            return json_decode($result, true);
        } catch (OrderStatusException $e) {
//            myLog('tmapi-reback-request-error', ['方法' => '请求', '原因' => $e->getMessage()]);
            throw new OrderStatusException($e->getMessage());
        } catch (Exception $e) {
//            myLog('tmapi-local-request-error', ['方法' => '请求', '原因' => $e->getMessage()]);
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 接单
     * @param GameLevelingOrder $order
     * @throws Exception
     */
    public static function take(GameLevelingOrder $order)
    {
        try {
            $user = User::find($order->take_parent_user_id);
            $options = [
                'order_no' => $order->trade_no,
                'app_id' => static::$appId,
                'timestamp' => time(),
                'hatchet_man_qq' => $user->qq ?? '0',
                'hatchet_man_phone' => $user->phone,
                'hatchet_man_name' => $user->name ?? '空',
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['take']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  异常
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function anomaly($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['anomaly']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 取消异常
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function cancelAnomaly($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['cancelAnomaly']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 申请仲裁
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function applyComplain($orderNo, $reason)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
                'content' => $reason,
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['applyComplain']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  取消仲裁
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function cancelComplain($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['cancelComplain']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  申请完成
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function applyComplete($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['applyComplete']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  申请协商
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function applyConsult($orderNo, $amount, $deposit, $reason)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
                'api_amount' => $amount,
                'api_deposit' => $deposit,
                'api_service' => 0,
                'content' => $reason,
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['applyConsult']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  取消协商
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function cancelConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['cancelConsult']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  同意协商
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function agreeConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'api_service' => 0,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['agreeConsult']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *  不同意协商
     * @param $orderNo
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function rejectConsult($orderNo)
    {
        try {
            $options = [
                'order_no' => $orderNo,
                'app_id' => static::$appId,
                'timestamp' => time(),
            ];
            $sign = static::getSign($options);
            $options['sign'] = $sign;
            // 发送
            static::normalRequest($options, static::$url['refuseConsult']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
