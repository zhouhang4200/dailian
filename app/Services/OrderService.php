<?php

namespace App\Services;

use \Exception;
use App\Models\Server;
use App\Models\User;
use App\Models\Game;
use App\Models\Region;
use App\Models\AdminUser;
use App\Models\GameLevelingType;
use App\Models\GameLevelingOrder;
use App\Models\GameLevelingOrderLog;
use App\Models\GameLevelingOrderConsult;
use App\Models\GameLevelingOrderComplain;
use App\Models\GameLevelingOrderApplyComplete;
use App\Models\GameLevelingOrderPreviousStatus;
use Illuminate\Support\Facades\DB;
use App\Exceptions\OrderServiceException;

/**
 * 订单服务类
 * Class UserAssetServices
 */
class OrderService
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * 操作用户的父ID
     * @var object
     */
    private static $user = null;

    /**
     * 订单数据
     * @var object
     */
    private static $order = null;

    /**
     * 如果是下单则传空
     * @param int $userId
     * @param $gameLevelingOrderTradeNO
     * @param int $adminUserId
     * @return OrderService|null
     * @throws Exception
     */
    public static function init(int $userId, $gameLevelingOrderTradeNO = '', $adminUserId = 0)
    {
        if ($adminUserId != 0) {
            if (! $user = AdminUser::find($adminUserId)) {
                throw new OrderServiceException('管理员ID不存在');
            }
            self::$user = $user;
        } else {
            if (! $user = User::find($userId)) {
                throw new OrderServiceException('用户ID不存在');
            }
            self::$user = $user;
        }

        if ($order = GameLevelingOrder::getOrderByCondition(['trade_no' => $gameLevelingOrderTradeNO])->first()) {
            self::$order = $order;
        }

        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param int $gameId 游戏ID
     * @param int $regionId 区ID
     * @param int $serviceId 服ID
     * @param string $title 代练标题
     * @param string $gameAccount 游戏账号
     * @param string $gamePassword 游戏密码
     * @param string $gameRole 游戏角色
     * @param int $day 代练天
     * @param int $hour 代练小时
     * @param int $gameLevelingTypeId 代练类型
     * @param float $amount 金额
     * @param float $securityDeposit 安全保证金
     * @param float $efficiencyDeposit 效率保证金
     * @param string $explain 代练说明
     * @param string $requirement 代练要求
     * @param string $playerPhone 玩家手机
     * @param string $userQQ 发单商户QQ
     * @param string $takeOrderPassword 接单密码
     * @param string $foreignTradeNO 外部订单号
     * @param int $source 来源 1 web 平台下单 2 api 接口下单
     * @return mixed
     * @throws Exception
     */
    public function create(
        int $gameId,
        int $regionId,
        int $serviceId,
        string $title,
        string $gameAccount,
        string $gamePassword,
        string $gameRole,
        int $day,
        int $hour,
        int $gameLevelingTypeId,
        float $amount,
        float $securityDeposit,
        float $efficiencyDeposit,
        string $explain,
        string $requirement,
        string $playerPhone,
        string $userQQ,
        string $takeOrderPassword,
        string $foreignTradeNO = '',
        int $source = 1
    ){
        DB::beginTransaction();
        try {
            // 获取游戏 区 服 数据
            $game = Game::find($gameId);
            $region = Region::find($regionId);
            $service = Server::find($serviceId);
            $gameLevelingType = GameLevelingType::find($gameLevelingTypeId);

            // 生成交易订单号
            $tradeNO = generateOrderNo();
            // 创建订单
            $order = GameLevelingOrder::create([
                'trade_no' => $tradeNO,
                'foreign_trade_no' => $foreignTradeNO,
                'user_id' => self::$user->id,
                'username' => self::$user->name,
                'parent_user_id' => self::$user->parent_id,
                'parent_username' => optional(self::$user->parent)->name ?? self::$user->name,
                'order_type_id' => 1,
                'game_type_id' => $game->game_type_id,
                'game_class_id'=> $game->game_class_id,
                'game_id' => $game->id,
                'game_name' => $game->name,
                'region_id' => $region->id,
                'region_name' => $region->name,
                'server_id' => $service->id,
                'server_name' => $service->name,
                'game_leveling_type_id' => $gameLevelingType->id,
                'game_leveling_type_name' => $gameLevelingType->name,
                'title' => $title,
                'amount' => $amount,
                'game_account' => $gameAccount,
                'game_password' => $gamePassword,
                'game_role' => $gameRole,
                'day' => $day,
                'hour' => $hour,
                'security_deposit' => $securityDeposit,
                'efficiency_deposit' => $efficiencyDeposit,
                'explain' => $explain,
                'requirement' => $requirement,
                'take_order_password' => $takeOrderPassword,
                'player_phone' => $playerPhone,
                'user_qq' => $userQQ,
                'source' => $source,
            ]);
            // 写入订单日志
            GameLevelingOrderLog::store('发布', $tradeNO, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 发布了订单');
        } catch (Exception $exception) {
            throw new OrderServiceException($exception->getMessage(). $exception->getLine());
        }
        DB::commit();

        return $order;
    }

    /**
     * 接单
     * @return object
     * @throws Exception
     */
    public function take()
    {
        if (self::$order->status != 1) {
            throw new OrderServiceException('接单失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }

        DB::beginTransaction();
        try {
            // 如果存在保证金, 冻结接单方对应的保证金
            if (self::$order->security_deposit > 0) {
                UserAssetService::init(33, self::$user->id, self::$order->amount, self::$order->trade_no)->frozen();
            }
            if (self::$order->efficiency_deposit > 0) {
                UserAssetService::init(34, self::$user->id, self::$order->amount, self::$order->trade_no)->frozen();
            }
            // 冻结发单方对应订单金额
            UserAssetService::init(31, self::$order->user_id, self::$order->amount, self::$order->trade_no)->frozen();
            // 保存接单人ID修改订单状态
            self::$order->status = 2;
            self::$order->take_user_id = self::$user->id;
            self::$order->take_parent_user_id = self::$user->parent_id;
            self::$order->take_at = date('Y-m-d H:i:s');
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('接单', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [接单]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 删除订单
     * @return object
     * @throws Exception
     */
    public function delete()
    {
        if ( ! in_array(self::$order->status, [1, 12])) {
            throw new OrderServiceException('撤单失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('该订单不属于您,您无权撤单');
        }
        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 13;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('撤单', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [撤单]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 申请验收
     * @param array $image
     *
     * @return object
     * @throws \Exception
     */
    public function applyComplete($image = [])
    {
        if (self::$order->status != 2) {
            throw new OrderServiceException('申请验收失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }

        if (self::$order->take_parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是接单方无法申请验收');
        }

        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 3;
            self::$order->apply_complete_at = date('Y-m-d H:i:s');
            self::$order->save();
            // 记录验收记录
            $applyComplete = GameLevelingOrderApplyComplete::create([
                'game_leveling_order_trade_no' => self::$order->trade_no
            ]);
            // 存储验收图片
            $applyComplete->image()->createMany($image);
            // 写入订单日志
            GameLevelingOrderLog::store('申请验收', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [申请验收]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 取消验收
     * @return object
     * @throws Exception
     */
    public function cancelComplete()
    {
        if (self::$order->status != 3) {
            throw new OrderServiceException('取消验收失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->take_parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是接单方无法申请验收');
        }

        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 2;
            self::$order->save();
            // 删除上传的提交完成图片
            foreach (self::$order->applyComplete->image as $item) {
                try {
                    unlink(public_path($item->path));
                } catch (Exception $exception) {
                }
            }
            // 删除库中所有图片记录
            self::$order->applyComplete->image()->delete();
            // 删除所有相关的提交的完成记录
            self::$order->applyComplete()->delete();
            // 写入订单日志
            GameLevelingOrderLog::store('取消验收', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [取消验收]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 完成验收
     * @return object
     * @throws Exception
     */
    public function complete()
    {
        if (self::$order->status != 3) {
            throw new OrderServiceException('完成验收失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是发单方无法完成验收');
        }

        DB::beginTransaction();
        try {
            // 发单方从冻结支出代练费用
            UserAssetService::init(61, self::$orde->user_id, self::$orde->amount, self::$order->trade_no)->expendFromFrozen();
            // 接单方收入代练费用
            UserAssetService::init(61, self::$orde->take_user_id, self::$orde->amount, self::$order->trade_no)->income();
            // 如果存在保证金, 冻结接单方解冻保证金
            if (self::$order->security_deposit > 0) {
                UserAssetService::init(42, self::$user->take_user_id, self::$order->security_deposit, self::$order->trade_no)->unfrozen();
            }
            if (self::$order->efficiency_deposit > 0) {
                UserAssetService::init(43, self::$user->take_user_id, self::$order->efficiency_deposit, self::$order->trade_no)->unfrozen();
            }
            // 修改订单状态
            self::$order->status = 11;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('完成验收', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [完成验收]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 上架
     * @return object
     * @throws Exception
     */
    public function onSale()
    {
        if (self::$order->status != 12) {
            throw new OrderServiceException('上架失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是发单方无法上架该订单');
        }

        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 1;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('上架', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [上架]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 下架
     * @return object
     * @throws Exception
     */
    public function offSale()
    {
        if (self::$order->status != 1) {
            throw new OrderServiceException('下架失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是发单方无法下架该订单');
        }

        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 12;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('下架', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [下架]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 锁定
     * @return object
     * @throws Exception
     */
    public function lock()
    {
        // 待验收 与 异常时可以锁定
        if (! in_array(self::$order->status, [2, 3, 6])) {
            throw new OrderServiceException('锁定失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是发单方无法锁定该订单');
        }
        DB::beginTransaction();
        try {
            // 订录订单前一个状态
            GameLevelingOrderPreviousStatus::store(self::$order->status, self::$order->trade_no);
            // 修改订单状态
            self::$order->status = 7;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('锁定', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [锁定]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 取消锁定
     * @return object
     * @throws Exception
     */
    public function cancelLock()
    {
        // 锁定状态可以取消锁定
        if (self::$order->status != 7) {
            throw new OrderServiceException('取消锁定失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是发单方取消锁定该订单');
        }
        DB::beginTransaction();
        try {
            // 获取订单前一个状态
            $previousStatus = GameLevelingOrderPreviousStatus::getLatestBy(self::$order->trade_no);
            // 修改订单状态
            self::$order->status = $previousStatus;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('取消锁定', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [取消锁定]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 异常
     * @return object
     * @throws Exception
     */
    public function anomaly()
    {
        // 只有代练中订单可进行异常标记
        if (self::$order->status != 2) {
            throw new OrderServiceException('标记异常失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->take_parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是接单方无法进行异常操作');
        }
        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 6;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('提交异常', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [提交异常]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 取消异常
     * @return object
     * @throws Exception
     */
    public function cancelAnomaly()
    {
        // 只有异常订单可取消异常
        if (self::$order->status != 6) {
            throw new OrderServiceException('取消异常失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        if (self::$order->take_parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是接单方无法进行异常操作');
        }
        DB::beginTransaction();
        try {
            // 修改订单状态
            self::$order->status = 2;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('取消异常', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [取消异常]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 申请撤销
     * @param $amount
     * @param $securityDeposit
     * @param $efficiencyDeposit
     * @param $remark
     * @return object
     * @throws Exception
     */
    public function applyConsult($amount, $securityDeposit, $efficiencyDeposit, $remark)
    {
        // 状态为 代练中(2)  待收验(3) 异常(6) 锁定(7) 可申请撤销
        if ( ! in_array(self::$order->status, [2, 3, 6 ,7])) {
            throw new OrderServiceException('申请撤销失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        DB::beginTransaction();
        try {
            // 发起人
            $initiator = self::$user->parent_id == self::$order->parent_user_id ? 1 : 2;
            // 记录撤销数据
            GameLevelingOrderConsult::store(
                $initiator,
                self::$user->id,
                self::$user->parent_id,
                self::$order->trade_no,
                $amount,
                $securityDeposit,
                $efficiencyDeposit,
                1,
                $remark);
            // 记录订单前一个状态
            GameLevelingOrderPreviousStatus::store(self::$order->trade_no, self::$order->status);
            // 修改订单状态
            self::$order->status = 4;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('申请撤销', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [申请撤销]');
        } catch (Exception $exception) {
            throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 取消撤销
     * @return object
     * @throws Exception
     */
    public function cancelConsult()
    {
        // 状态为 撤销中 可取消撤销
        if (self::$order->status != 4) {
            throw new OrderServiceException('申请撤销失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        // 检测当前操作用户与发起用户是否是同一人
        if (self::$order->consult->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是该订单撤销发起人无法取消撤销');
        }
        DB::beginTransaction();
        try {
            // 记录撤销数据
            GameLevelingOrderConsult::where('game_leveling_order_trade_no', self::$order->trade_no)
                ->update(['status' => 2]);
            // 记录订单前一个状态
            $previousStatus = GameLevelingOrderPreviousStatus::getLatestBy(self::$order->trade_no);
            // 修改订单状态
            self::$order->status = $previousStatus;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('取消撤销', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [取消撤销]');
        } catch (Exception $exception) {
            throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 同意撤销
     * @return object
     * @throws Exception
     */
    public function agreeConsult()
    {
        // 状态为 撤销中 可取消撤销
        if (self::$order->status != 4) {
            throw new OrderServiceException('申请撤销失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        // 检测当前操作用户与发起用户是否是同一人
        if (self::$order->consult->parent_user_id == self::$user->parent_id) {
            throw new OrderServiceException('您不能同意自己发起的撤销');
        }
        DB::beginTransaction();
        try {
            // 记录撤销数据
            GameLevelingOrderConsult::where('game_leveling_order_trade_no', self::$order->trade_no)
                ->update(['status' => 3]);
            
            // 发单人 支出代练费
            $expend = 0;
            // 接单人 收入代练费
            $income = 0;
            // 发单人 解冻代练费
            $unfrozen = 0;
            // 接单 支出全保证金
            $securityDepositExpend = 0;
            // 发单人 收入安全保证金
            $securityDepositIncome = 0;
            // 接单人 解冻安全保证金
            $securityDepositUnfrozen = 0;
            // 接单人 支出效率保证金
            $efficiencyDepositExpend = 0;
            // 发单人 收入效率保证金
            $efficiencyDepositIncome= 0;
            // 接单人 解冻效率保证金
            $efficiencyDepositUnfrozen = 0;

            if (self::$order->amount == self::$order->consult->amount) { // 协商 代练费全额支出
                $expend = self::$order->amount;
                $income = self::$order->amount;
            } else if (self::$order->consult->amount > 0) { // 协商 代练费部分支出
                $expend = self::$order->consult->amount;
                $income = self::$order->consult->amount;
                $unfrozen = bcsub(self::$order->amount, self::$order->consult->amount);
            } else if (self::$order->consult->amount == 0) { // 协商 代练费不用支出
                $unfrozen = self::$order->amount;
            }

            if (self::$order->security_deposit == self::$order->consult->security_deposit) { // 协商 安全保证金全额支出
                $securityDepositIncome = self::$order->security_deposit;
                $securityDepositExpend= self::$order->security_deposit;
            } else if (self::$order->consult->security_deposit > 0) { // 协商 安全保证金部分支出
                $securityDepositIncome = self::$order->consult->security_deposit;
                $securityDepositExpend= self::$order->consult->security_deposit;
                $securityDepositUnfrozen = bcsub(self::$order->security_deposit, self::$order->consult->security_deposit);
            } else if (self::$order->consult->security_deposit == 0) { // 协商  安全保证金不用支出
                $securityDepositUnfrozen = self::$order->security_deposit;
            }

            if (self::$order->efficiency_deposit == self::$order->consult->efficiency_deposit) { // 协商 效率保证金全额支出
                $efficiencyDepositIncome = self::$order->security_deposit;
                $efficiencyDepositExpend = self::$order->security_deposit;
            } else if (self::$order->consult->efficiency_deposit > 0) { // 协商 效率保证金部分支出
                $efficiencyDepositIncome = self::$order->consult->efficiency_deposit;
                $efficiencyDepositExpend= self::$order->consult->efficiency_deposit;
                $efficiencyDepositUnfrozen = bcsub(self::$order->efficiency_deposit, self::$order->consult->efficiency_deposit);
            } else if (self::$order->consult->efficiency_deposit == 0) { // 协商  效率保证金不用支出
                $efficiencyDepositUnfrozen = self::$order->efficiency_deposit;
            }

            // 代练费
            if ($expend > 0) {
                UserAssetService::init(61, self::$order->user_id, $expend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($income > 0) {
                UserAssetService::init(51, self::$order->take_user_id, $income, self::$order->trade_no)->income();
            }
            if ($unfrozen > 0) {
                UserAssetService::init(41, self::$order->user_id, $unfrozen, self::$order->trade_no)->unfrozen();
            }
            // 效率保证金
            if ($efficiencyDepositExpend > 0) {
                UserAssetService::init(62, self::$order->take_user_id, $efficiencyDepositExpend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($efficiencyDepositIncome > 0) {
                UserAssetService::init(52, self::$order->user_id, $efficiencyDepositIncome, self::$order->trade_no)->income();
            }
            if ($efficiencyDepositUnfrozen > 0) {
                UserAssetService::init(42, self::$order->take_user_id, $efficiencyDepositUnfrozen, self::$order->trade_no)->unfrozen();
            }
            // 安全保证金
            if ($securityDepositExpend > 0) {
                UserAssetService::init(63, self::$order->take_user_id, $securityDepositExpend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($securityDepositIncome > 0) {
                UserAssetService::init(53, self::$order->user_id, $securityDepositExpend, self::$order->trade_no)->income();
            }
            if ($securityDepositUnfrozen > 0) {
                UserAssetService::init(43, self::$order->take_user_id, $securityDepositExpend, self::$order->trade_no)->unfrozen();
            }
            // 修改订单状态
            self::$order->status = 8;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('同意撤销', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [同意撤销]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 申请仲裁
     * @param $reason
     * @param array $image
     * @return object
     * @throws Exception
     */
    public function applyComplain($reason, $image = [])
    {
        // 状态为 代练中(2)  待收验(3) 异常(4)
        if ( ! in_array(self::$order->status, [2, 3, 4])) {
            throw new OrderServiceException('申请仲裁失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        DB::beginTransaction();
        try {
            // 发起人
            $initiator = self::$user->parent_id == self::$order->parent_user_id ? 1 : 2;
            // 记录仲裁数据
            $complain = GameLevelingOrderComplain::store(
                $initiator,
                self::$user->id,
                self::$user->parent_id,
                self::$order->trade_no,
                1,
                $reason);
            // 存储图片
            $complain->image()->createMany($image);
            // 记录订单前一个状态
            GameLevelingOrderPreviousStatus::store(self::$order->trade_no, self::$order->status);
            // 修改订单状态
            self::$order->status = 5;
            self::$order->save();
            // 写入订单日志
            GameLevelingOrderLog::store('申请仲裁', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [申请仲裁]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 取消仲裁
     * @return object
     * @throws Exception
     */
    public function cancelComplain()
    {
        // 状态为 撤销中 可取消撤销
        if (self::$order->status != 5) {
            throw new OrderServiceException('取消仲裁失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }
        // 检测当前操作用户与发起用户是否是同一人
        if (self::$order->complain->parent_user_id != self::$user->parent_id) {
            throw new OrderServiceException('您不是该订单仲裁发起人无法取消仲裁');
        }
        DB::beginTransaction();
        try {
            // 更新撤销数据
            $complain = GameLevelingOrderComplain::where('game_leveling_order_trade_no', self::$order->trade_no)->first();
            $complain->status = 2;
            $complain->save();
            // 删除上传的仲裁图片
            foreach ($complain->image as $item) {
                try {
                    unlink(public_path($item->path));
                } catch (Exception $exception) {

                }
            }
            $complain->image()->delete();
            // 删除上传的留言图片
            foreach (self::$order->message as $item) {
                try {
                    unlink(public_path($item->image[0]->path));
                } catch (Exception $exception) {

                }
                $item->image()->delete();
            }
            // 删除所有相关的留言
            self::$order->message()->delete();
            // 修改订单状态
            self::$order->status = GameLevelingOrderPreviousStatus::getLatestBy(self::$order->trade_no);;
            self::$order->save();

            // 写入订单日志
            GameLevelingOrderLog::store('取消仲裁', self::$order->trade_no, self::$user->id, self::$user->name, self::$user->parent_id, self::$user->name . ': 进行操作 [取消仲裁]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    /**
     * 仲裁 (客服操作)
     * @param $inputAmount
     * @param $inputSecurityDeposit
     * @param $inputEfficiencyDeposit
     * @param $inputResult
     * @return object
     * @throws Exception
     */
    public function arbitration($inputAmount, $inputSecurityDeposit, $inputEfficiencyDeposit, $inputResult)
    {
        // 状态为 仲裁中 可取消撤销
        if (self::$order->status != 5) {
            throw new OrderServiceException('仲裁失败,订单当前状态为: ' . self::$order->getStatusDescribe());
        }

        DB::beginTransaction();
        try {
            // 记录撤销数据
            GameLevelingOrderComplain::where('game_leveling_order_trade_no', self::$order->trade_no)
                ->update([
                    'status' => 3,
                    'amount' => $inputAmount,
                    'security_deposit' => $inputSecurityDeposit,
                    'efficiency_deposit' => $inputEfficiencyDeposit,
                    'result' => $inputResult,
                ]);

            // 发单人 支出代练费
            $expend = 0;
            // 接单人 收入代练费
            $income = 0;
            // 发单人 解冻代练费
            $unfrozen = 0;
            // 接单 支出全保证金
            $securityDepositExpend = 0;
            // 发单人 收入安全保证金
            $securityDepositIncome = 0;
            // 接单人 解冻安全保证金
            $securityDepositUnfrozen = 0;
            // 接单人 支出效率保证金
            $efficiencyDepositExpend = 0;
            // 发单人 收入效率保证金
            $efficiencyDepositIncome= 0;
            // 接单人 解冻效率保证金
            $efficiencyDepositUnfrozen = 0;

            if (self::$order->amount == $inputAmount) { // 仲裁 代练费全额支出
                $expend = self::$order->amount;
                $income = self::$order->amount;
            } else if ($inputAmount > 0) { // 仲裁 代练费部分支出
                $expend = $inputAmount;
                $income = $inputAmount;
                $unfrozen = bcsub(self::$order->amount, $inputAmount);
            } else if ($inputAmount == 0) { // 仲裁 代练费不用支出
                $unfrozen = $inputAmount;
            }

            if (self::$order->security_deposit == $inputSecurityDeposit) { // 协商 安全保证金全额支出
                $securityDepositIncome = self::$order->security_deposit;
                $securityDepositExpend= self::$order->security_deposit;
            } else if ($inputSecurityDeposit > 0) { // 协商 安全保证金部分支出
                $securityDepositIncome = $inputSecurityDeposit;
                $securityDepositExpend= $inputSecurityDeposit;
                $securityDepositUnfrozen = bcsub(self::$order->security_deposit, $inputSecurityDeposit);
            } else if ($inputSecurityDeposit == 0) { // 协商  安全保证金不用支出
                $securityDepositUnfrozen = self::$order->security_deposit;
            }

            if (self::$order->efficiency_deposit == $inputEfficiencyDeposit) { // 协商 效率保证金全额支出
                $efficiencyDepositIncome = self::$order->security_deposit;
                $efficiencyDepositExpend = self::$order->security_deposit;
            } else if ($inputEfficiencyDeposit > 0) { // 协商 效率保证金部分支出
                $efficiencyDepositIncome = $inputEfficiencyDeposit;
                $efficiencyDepositExpend= $inputEfficiencyDeposit;
                $efficiencyDepositUnfrozen = bcsub(self::$order->efficiency_deposit, $inputEfficiencyDeposit);
            } else if ($inputEfficiencyDeposit == 0) { // 协商  效率保证金不用支出
                $efficiencyDepositUnfrozen = self::$order->efficiency_deposit;
            }

            // 代练费
            if ($expend > 0) {
                UserAssetService::init(61, self::$order->user_id, $expend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($income > 0) {
                UserAssetService::init(51, self::$order->take_user_id, $income, self::$order->trade_no)->income();
            }
            if ($unfrozen > 0) {
                UserAssetService::init(41, self::$order->user_id, $unfrozen, self::$order->trade_no)->unfrozen();
            }
            // 效率保证金
            if ($efficiencyDepositExpend > 0) {
                UserAssetService::init(62, self::$order->take_user_id, $efficiencyDepositExpend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($efficiencyDepositIncome > 0) {
                UserAssetService::init(52, self::$order->user_id, $efficiencyDepositIncome, self::$order->trade_no)->income();
            }
            if ($efficiencyDepositUnfrozen > 0) {
                UserAssetService::init(42, self::$order->take_user_id, $efficiencyDepositUnfrozen, self::$order->trade_no)->unfrozen();
            }
            // 安全保证金
            if ($securityDepositExpend > 0) {
                UserAssetService::init(63, self::$order->take_user_id, $securityDepositExpend, self::$order->trade_no)->expendFromFrozen();
            }
            if ($securityDepositIncome > 0) {
                UserAssetService::init(53, self::$order->user_id, $securityDepositExpend, self::$order->trade_no)->income();
            }
            if ($securityDepositUnfrozen > 0) {
                UserAssetService::init(43, self::$order->take_user_id, $securityDepositExpend, self::$order->trade_no)->unfrozen();
            }
            // 修改订单状态
            self::$order->status = 9;
            self::$order->save();
            // 更新仲裁数据
            
            // 写入订单日志
            GameLevelingOrderLog::store('完成仲裁', self::$order->trade_no, 0, self::$user->name, 0, '客服: 进行操作 [完成仲裁]');
        } catch (Exception $exception) {
           throw new OrderServiceException($exception->getMessage());
        }
        DB::commit();
        return self::$order;
    }

    // 强制撤销 (客服操作)
    public function forceArbitration()
    {

    }

    public function addMoney()
    {

    }

    public function addTime()
    {

    }

    public function update()
    {

    }

    public function updateAccountPassword()
    {

    }
}

