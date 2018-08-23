<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Exceptions\Order\OrderException;
use App\Exceptions\UserAsset\UserAssetException;
use App\Exceptions\UserAsset\UserAssetBalanceException;
use App\Http\Controllers\Controller;
use App\Services\OrderService;

/**
 * 订单操作
 * Class OrderOperationController
 * @package App\Http\Controllers\Api\V1
 */
class OrderOperationController extends Controller
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function take()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }

        try {
            OrderService::init(request()->user()->id, request('trade_no'))
                ->take(request('pay_password'), request('take_password'));
        } catch (UserAssetBalanceException $exception) {
            return response()->apiJson(7001);
        } catch (UserAssetException $exception) {
            return response()->apiJson(7001);
        } catch (OrderException $exception) {
            return response()->apiJson(7001);
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        return response()->apiJson(0);
    }
}
