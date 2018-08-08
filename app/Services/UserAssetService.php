<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserAssetFlow;
use App\Models\BalanceWithdraw;
use App\Models\RealNameCertification;
use App\Exceptions\UserAssetServiceException;
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
    private static  $instance = null;

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
    private  static $userId = 0;

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
     * @param string $remark 备注
     * @return UserAssetService|null
     * @throws UserAssetServiceException
     */
    public static function init(int $subType, int $userId, float $amount, string $tradeNO, string $remark = null)
    {
        if (!$user = User::find($userId)) {
            throw new UserAssetServiceException('用户ID不合法');
        }
        if ($amount <= 0) {
            throw new UserAssetServiceException('金额不合法');
        }
        if (! $tradeNO) {
            throw new UserAssetServiceException('交易单号不合法');
        }
        if (! in_array($subType, array_flip(config('user_asset.sub_type')))) {
            throw new UserAssetServiceException('子类型不存在');
        }

        self::$type    = (int) substr(trim($subType), 0, 1);
        self::$subType = (int) $subType;
        self::$amount  = $amount;
        self::$tradeNO = $tradeNO;
        self::$remark  = config('user_asset.sub_type')[self::$subType];
        self::$userId  = $user->parent_id;

        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 充值
     * @return bool
     * @throws UserAssetServiceException
     */
    public function recharge()
    {
        if (self::$type != 1) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), $userAsset->frozen);
            // 更新用户余额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 提现
     * @throws UserAssetServiceException
     */
    public function withdraw()
    {
        if (self::$type != 3) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 检测余额是否够本次提现
            if ($userAsset->balance < self::$amount) {
                throw new UserAssetServiceException('您的余额不够,请调整提现金额');
            }
            // 获取用户认证信息
            $realNameCertification = RealNameCertification::where('user_id', self::$userId)->first();
            if (! $realNameCertification) {
                throw new UserAssetServiceException('您的账号没有进行实名认证无法进行提现');
            }
            // 写流水
            $this->flow(bcsub($userAsset->balance, self::$amount), bcadd($userAsset->frozen, self::$amount));
            // 生成提现单
            BalanceWithdraw::create([
                'user_id' => self::$userId,
                'trade_no' => self::$tradeNO,
                'amount' => self::$amount,
                'real_name' => $realNameCertification->real_name,
                'bank_card' => $realNameCertification->bank_card,
                'bank_name'  => $realNameCertification->bank_name,
                'status'  => 1,
            ]);
            // 更新用户余额与冻结金额
            $userAsset->balance = bcsub($userAsset->balance, self::$amount);
            $userAsset->frozen = bcadd($userAsset->frozen, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 冻结
     * @return bool
     * @throws UserAssetServiceException
     */
    public function frozen()
    {
        if (self::$type != 3) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }
        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 检测余额是否够本次提现
            if ($userAsset->balance < self::$amount) {
                throw new UserAssetServiceException('您的余额不够');
            }
            // 写流水
            $this->flow(bcsub($userAsset->balance, self::$amount), bcadd($userAsset->frozen, self::$amount));
            // 更新用户余额与冻结金额
            $userAsset->balance = bcsub($userAsset->balance, self::$amount);
            $userAsset->frozen = bcadd($userAsset->frozen, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage() . self::$userId);
        }
        DB::commit();
        return true;
    }

    /**
     * 解冻
     * @return bool
     * @throws UserAssetServiceException
     */
    public function unfrozen()
    {
        if (self::$type != 4) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 检测余额是否够本次提现
            if ($userAsset->frozen < self::$amount) {
                throw new UserAssetServiceException('您的余额不够本次解冻');
            }

            // 检测用户相关冻结订单号总金额与需要解冻金额是否相符
            $frozen = UserAssetFlow::where('user_id', self::$userId)
                ->where('trade_no', self::$tradeNO)->where('type', 3)->first();

            if (is_null($frozen)) {
                throw new UserAssetServiceException('不存在相关的冻结记录');
            }

            if ($frozen->amount < self::$amount) {
                throw new UserAssetServiceException('解冻金额大于冻结金额');
            }

            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), bcsub($userAsset->frozen, self::$amount));

            // 更新用户余额与冻结金额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->frozen = bcsub($userAsset->frozen, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage() . $UserAssetServiceException->getLine());
        }
        DB::commit();
        return true;
    }

    /**
     * 从余额支出
     * @return bool
     * @throws UserAssetServiceException
     */
    public function expendFromBalance()
    {
        if (self::$type != 6) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 检测余额是否够本次支出
            if ($userAsset->balance < self::$amount) {
                throw new UserAssetServiceException('您的余额不够');
            }

            // 写流水
            $this->flow(bcsub($userAsset->balance, self::$amount), $userAsset->frozen);

            // 更新用户余额
            $userAsset->balance = bcsub($userAsset->balance, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 从冻结支出
     * @return bool
     * @throws UserAssetServiceException
     */
    public function expendFromFrozen()
    {
        if (self::$type != 6) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }

        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 检测冻结余额是否够本次支出
            if ($userAsset->frozen < self::$amount) {
                throw new UserAssetServiceException('冻结余额不够支出');
            }

            // 写流水
            $this->flow($userAsset->balance,  bcsub($userAsset->frozen, self::$amount));

            // 更新用户冻结余额
            $userAsset->frozen = bcsub($userAsset->frozen, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 收入
     * @return bool
     * @throws UserAssetServiceException
     */
    public function income()
    {
        if (self::$type != 5) {
            throw new UserAssetServiceException('请检查传入的子类型是否正确');
        }
        DB::beginTransaction();
        try {
            $userAsset = UserAsset::where('user_id', self::$userId)->lockForUpdate()->first();
            // 写流水
            $this->flow(bcadd($userAsset->balance, self::$amount), $userAsset->fronzen);

            // 更新用户余额
            $userAsset->balance = bcadd($userAsset->balance, self::$amount);
            $userAsset->save();
        } catch (Exception $exception) {
            throw new UserAssetServiceException($exception->getMessage());
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
        UserAssetFlow::create([
            'user_id' => self::$userId,
            'type' => self::$type,
            'sub_type' => self::$subType,
            'trade_no' => self::$tradeNO,
            'amount' => self::$amount,
            'balance' => $balance,
            'frozen' => $frozen,
            'date' => date('Y-m-d'),
            'remark' => self::$remark,
        ]);
    }
}

