<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Exceptions\UserAssetException;
use App\Models\BalanceWithdraw;

/*
 * 用户余额服务类
 */
class UserBalanceService
{
    /**
     * 提现到支付宝账号
     * @param $amount
     * @param $user
     * @param $alipayAccount
     * @param $alipayName
     * @return BalanceWithdraw|\Illuminate\Database\Eloquent\Model
     * @throws UserAssetException
     * @throws UserException
     * @throws \Exception
     */
   public static function withdraw($amount, $user, $alipayAccount, $alipayName)
   {
       if (optional($user->parent->realNameCertification)->status != 2) {
           throw new UserException('您的账号没有进行实名认证/或实名认证没有通过,不能进行提现', 3005);
       }

       if (! \Hash::check(clientRSADecrypt(request('password')), $user->pay_password)) {
           throw new UserAssetException('支付密码错误', 4002);
       }

       // 计算手续费
       $poundage = bcmul($amount, bcdiv(\Setting::get('withdraw.rate'), 100, 2));
       // 实际到账金额
       $amount = bcsub($amount, $poundage);

       // 创建提现记录
       return BalanceWithdraw::create([
           'user_id' => $user->id,
           'trade_no' => generateOrderNo(),
           'real_amount' => $amount,
           'amount' => request('amount'),
           'poundage' => $poundage,
           'alipay_account' => $alipayAccount,
           'alipay_name' => $alipayName,
           'real_name' => auth()->user()->parent->realNameCertification->real_name,
           'bank_card' => auth()->user()->parent->realNameCertification->bank_card,
           'bank_name' => auth()->user()->parent->realNameCertification->bank_name,
           'status' => 1
       ]);
   }
}

