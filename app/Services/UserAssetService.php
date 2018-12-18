<?php

namespace App\Services;

use App\Models\PlatformAsset;
use Exception;
use App\Exceptions\UnknownException;
use App\Exceptions\UserAssetException;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserAssetFlow;
use App\Models\BalanceWithdraw;
use App\Models\RealNameCertification;
use Illuminate\Support\Facades\DB;

/**
 * 用户资产服务类
 * Class UserAssetServices
 */
class UserAssetService
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var int
     */
    private static $type = 0;

    /**
     * @var int
     */
    private static $subType = 0;

    /**
     * 发单人ID
     * @var int
     */
    private static $userId = 0;

    /**
     * @var int
     */
    private static $amount = 0;

    /**
     * @var int
     */
    private static $tradeNO = 0;

    /**
     * @var null
     */
    private static $remark = null;

    /**
     * @param int $subType 子类型
     * @param int $userId 用户ID
     * @param float $amount 金额
     * @param string $tradeNO 交易单号
     * @param string|null $remark 备注
     * @return UserAssetService|null
     * @throws UserAssetException
     */
    public static function init(int $subType, int $userId, float $amount, string $tradeNO, string $remark = null)
    {
        if (!$user = User::find($userId)) {
            throw new UserAssetException('用户ID不合法', 4003);
        }
        if ($amount <= 0) {
            throw new UserAssetException('金额不合法', 4004);
        }
        if (!$tradeNO) {
            throw new UserAssetException('交易单号不合法', 4005);
        }
        if (!in_array($subType, array_flip(config('user_asset.sub_type')))) {
            throw new UserAssetException('子类型不存在', 4006);
        }

        self::$type = (int)substr(trim($subType), 0, 1);
        self::$subType = (int)$subType;
        self::$amount = $amount;
        self::$tradeNO = $tradeNO;
        self::$remark = config('user_asset.sub_type')[self::$subType];
        self::$userId = $user->parent_id;

        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 充值
     * @return bool
     * @throws UserAssetException
     * @throws Exception
     */
    public function recharge()
    {
        if (self::$type != 1) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), $userAsset->frozen);
            // 更新用户余额 与总充值金额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->total_recharge = bcadd($userAsset->total_recharge, self::$amount);
            $userAsset->save();
            // 更新平台余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->balance = bcadd($platformAsset->balance, self::$amount);
            $platformAsset->total_recharge = bcadd($platformAsset->total_recharge, self::$amount);
            $platformAsset->save();
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 同意提现
     * @return bool
     * @throws UnknownException
     * @throws UserAssetException
     */
    public function agreeWithdraw()
    {
        if (self::$type != 2) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();

        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
        // 检测冻结余额是否够本次支出

        if ($userAsset->frozen < self::$amount) {
            throw new UserAssetException('冻结金额不够本次提现', 4008);
        }

        try {
            // 写流水
            $this->flow($userAsset->balance, bcsub($userAsset->frozen, self::$amount));
            // 更新用户冻结余额
            $userAsset->frozen = bcsub($userAsset->frozen, self::$amount);
            $userAsset->total_withdraw = bcadd($userAsset->total_withdraw, self::$amount);
            $userAsset->save();
            // 更新平台余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->frozen = bcsub($platformAsset->frozen, self::$amount);
            $platformAsset->total_withdraw = bcadd($platformAsset->total_withdraw, self::$amount);
            $platformAsset->save();
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 冻结
     * @return bool
     * @throws UserAssetException
     * @throws Exception
     */
    public function frozen()
    {
        if (self::$type != 3) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }
        DB::beginTransaction();

        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();

        // 检测余额是否够本次提现
        if ($userAsset->balance < self::$amount) {
            throw new UserAssetException('您的余额不够', 4001);
        }

        try {
            // 写流水
            $this->flow(bcsub($userAsset->balance, self::$amount), bcadd($userAsset->frozen, self::$amount));
            // 更新用户余额与冻结金额
            $userAsset->balance = bcsub($userAsset->balance, self::$amount);
            $userAsset->frozen = bcadd($userAsset->frozen, self::$amount);
            $userAsset->save();
            // 更新平台余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->balance = bcsub($platformAsset->balance, self::$amount);
            $platformAsset->frozen = bcadd($platformAsset->frozen, self::$amount);
            $platformAsset->save();
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 解冻
     * @return bool
     * @throws UserAssetException
     * @throws Exception
     */
    public function unfrozen()
    {
        if (self::$type != 4) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();

        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();

        // 检测用户相关冻结订单号总金额与需要解冻金额是否相符
        $frozen = UserAssetFlow::where('user_id', self::$userId)
            ->where('trade_no', self::$tradeNO)->where('type', 3)->sum('amount');

        if (is_null($frozen) || empty($frozen)) {
            throw new UserAssetException('不存在相关的冻结记录', 4009);
        }

        if (-$frozen < self::$amount) {
            throw new UserAssetException('解冻金额大于冻结金额', 4010);
        }

        try {
            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), bcsub($userAsset->frozen, self::$amount));
            // 更新用户余额与冻结金额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->frozen = bcsub($userAsset->frozen, self::$amount);
            $userAsset->save();

            // 更新平台余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->balance = bcadd($platformAsset->balance, self::$amount);
            $platformAsset->frozen = bcsub($platformAsset->frozen, self::$amount);
            $platformAsset->save();
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage() . $exception->getLine());
        }
        DB::commit();
        return true;
    }

    /**
     * 从余额支出
     * @return bool
     * @throws UserAssetException
     * @throws Exception
     */
    public function expendFromBalance()
    {
        if (self::$type != 6) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();
        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
        // 检测余额是否够本次支出
        if ($userAsset->balance < self::$amount) {
            throw new UserAssetException('您的余额不够', 4001);
        }

        try {
            // 写流水
            $this->flow(bcsub($userAsset->balance, self::$amount), $userAsset->frozen);
            // 更新用户余额
            $userAsset->balance = bcsub($userAsset->balance, self::$amount);
            $userAsset->total_expend = bcadd($userAsset->total_expend, self::$amount);
            $userAsset->save();

            // 更新平台资金
            $platformAsset = PlatformAsset::first();
            $platformAsset->balance = bcsub($platformAsset->balance, self::$amount);
            $platformAsset->save();

            // 如果支出的子类型在配置文件中则需要更新平台资产表的收入
            if (in_array(self::$subType, config('platform_asset.income'))) {
                // 更新平台余额
                $platformAsset = PlatformAsset::first();
                $platformAsset->total_income = bcadd($platformAsset->total_income, self::$amount);
                $platformAsset->save();
            }
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 从冻结支出
     * @return bool
     * @throws UnknownException
     * @throws UserAssetException
     * @throws UserAssetException
     */
    public function expendFromFrozen()
    {
        if (self::$type != 6) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();

        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
        // 检测冻结余额是否够本次支出

        if ($userAsset->frozen < self::$amount) {
            throw new UserAssetException('冻结余额不够支出', 4008);
        }

        try {
            // 写流水
            $this->flow($userAsset->balance, bcsub($userAsset->frozen, self::$amount));

            // 更新用户冻结余额
            $userAsset->frozen = bcsub($userAsset->frozen, self::$amount);
            $userAsset->total_expend = bcadd($userAsset->total_expend, self::$amount);
            $userAsset->save();

            // 更新平台冻结与余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->frozen = bcsub($platformAsset->frozen, self::$amount);
            $platformAsset->save();

            // 如果支出的子类型在配置文件中则需要更新平台资产表的收入
            if (in_array(self::$subType, config('platform_asset.income'))) {
                // 更新平台余额
                $platformAsset = PlatformAsset::first();
                $platformAsset->total_income = bcadd($platformAsset->total_income, self::$amount);
                $platformAsset->save();
            }
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 收入
     * @return bool
     * @throws UserAssetException
     * @throws Exception
     */
    public function income()
    {
        if (self::$type != 5) {
            throw new UserAssetException('请检查传入的子类型是否正确', 4007);
        }

        DB::beginTransaction();

        $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();

        try {
            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), $userAsset->frozen);

            // 更新用户余额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->total_income = bcadd($userAsset->total_income, self::$amount);
            $userAsset->save();

            // 更新平台冻结与余额
            $platformAsset = PlatformAsset::first();
            $platformAsset->balance = bcadd($platformAsset->balance, self::$amount);
            $platformAsset->save();

            // 如果用户收入的的子类型,在平台资产配置文件支出类型中则需要更新平台资产表的支出
            if (in_array(self::$subType, config('platform_asset.expend'))) {
                // 更新平台余额
                $platformAsset = PlatformAsset::first();
                $platformAsset->total_expend = bcadd($platformAsset->total_expend, self::$amount);
                $platformAsset->save();
            }
        } catch (Exception $exception) {
            throw new UnknownException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 写资金流水
     * @param $balance
     * @param $frozen
     */
    private function flow($balance, $frozen)
    {
        $data = [
            'user_id' => self::$userId,
            'type' => self::$type,
            'sub_type' => self::$subType,
            'trade_no' => self::$tradeNO,
            'amount' => self::$amount,
            'balance' => $balance,
            'frozen' => $frozen,
            'date' => date('Y-m-d'),
            'remark' => self::$remark,
        ];

        if (in_array(self::$type, [2, 3, 6])) {
            $data['amount'] = -self::$amount;
        }

        UserAssetFlow::create($data);
    }
}

