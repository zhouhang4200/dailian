<?php

namespace App\Models;

use ScoutElastic\Searchable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 游戏代练订单模型
 * 存在标识接单方与发单方的地方 (1 代表发单方 2 代练接单方)
 * Class GameLevelingOrder
 * @package App\Models
 */
class GameLevelingOrder extends Model
{
    use Searchable;

    protected $indexConfigurator = GameLevelingOrderIndexConfigurator::class;

    protected $searchRules = [
        //
    ];

    /**
     * 定义ElasticSearch 字段类型
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                "analyzer" => "ik_smart",
                "search_analyzer" => "ik_smart",

            ],
            'explain' => [
                'type' => 'text',
                "analyzer" => "ik_smart",
                "search_analyzer" => "ik_smart",
            ],
            'requirement' => [
                'type' => 'text',
                "analyzer" => "ik_smart",
                "search_analyzer" => "ik_smart",
            ],
            'top_at' => [
                'type' => 'date',
                "format" =>  "YYYY-MM-dd HH:mm:ss",
                "store" => true,
            ],
            'created_at' => [
                'type' => 'date',
                "format" =>  "YYYY-MM-dd HH:mm:ss",
                "store" => true,
            ],
            'updated_at' => [
                'type' => 'date',
                "format" =>  "YYYY-MM-dd HH:mm:ss",
                "store" => true,
            ],
        ]
    ];

    public $fillable = [
        'source',
        'trade_no',
        'foreign_trade_no',
        'user_id',
        'username',
        'parent_user_id',
        'parent_username',
        'parent_user_qq',
        'parent_user_phone',
        'order_type_id',
        'game_type_id',
        'game_class_id',
        'game_id',
        'game_name',
        'region_id',
        'region_name',
        'server_id',
        'server_name',
        'game_leveling_type_id',
        'game_leveling_type_name',
        'title',
        'amount',
        'game_account',
        'game_password',
        'game_role',
        'security_deposit',
        'efficiency_deposit',
        'explain',
        'requirement',
        'take_order_password',
        'player_phone',
        'player_qq',
        'user_qq',
        'user_phone',
        'day',
        'hour',
        'complete_at',
        'status',
        'top',
        'top_at',
    ];

    /**
     * 订单状态说明
     * @var array
     */
    public static $statusDescribe = [
        1 => '未接单',
        2 => '代练中',
        3 => '待验收',
        4 => '撤销中',
        5 => '仲裁中',
        6 => '异常',
        7 => '锁定',
        8 => '已撤销',
        9 => '已仲裁',
        10 => '已结算',
        11 => '强制撤销',
        12 => '已下架',
        13 => '已撤单',
    ];

    /**
     * 根据传入的条件获取订单
     * @param  integer $who 1 发单方 2 接单方 3 不限定
     * @param  array $condition 传入的过滤条件
     * @return $this|\Illuminate\Database\Eloquent\Builder|static
     */
    public static function getOrderByCondition($condition, $who = 3)
    {
        if ($who == 1) {
            $build = self::with('complain', 'consult')->where('parent_user_id', request()->user()->parent_id);
        } else if ($who == 2) {
            $build = self::with('complain', 'consult')->where('take_parent_user_id', request()->user()->parent_id)
                ->orderBy('id', 'desc');
        } else {
            $build = self::with('complain', 'consult');
        }

        if (isset($condition['parent_user_id']) && $condition['parent_user_id']) {
            $build->where('parent_user_id', $condition['parent_user_id']);
        }

        if (isset($condition['take_parent_user_id']) && $condition['take_parent_user_id']) {
            $build->where('take_parent_user_id', $condition['take_parent_user_id']);
        }

        if (isset($condition['status']) && $condition['status']) {
            $build->where('status', $condition['status']);
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $build->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['game_id']) && $condition['game_id']) {
            $build->where('game_id', $condition['game_id']);
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $build->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['start_time']) && $condition['start_time']) {
            $build->where('created_at', '>=',$condition['start_time']);
        }

        if (isset($condition['end_time']) && $condition['end_time']) {
            $build->where('created_at', '<=',$condition['end_time'] . ' 23:59:59');
        }

        return $build;
    }

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['parent_user_id']) && $condition['parent_user_id']) {
            $query->where('parent_user_id', $condition['parent_user_id']);
        }

        if (isset($condition['take_parent_user_id']) && $condition['take_parent_user_id']) {
            $query->where('take_parent_user_id', $condition['take_parent_user_id']);
        }

        if (isset($condition['game_leveling_type_id']) && $condition['game_leveling_type_id']) {
            $query->where('game_leveling_type_id', $condition['game_leveling_type_id']);
        }

        if (isset($condition['status']) && $condition['status']) {
            $query->where('status', $condition['status']);
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $query->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['game_id']) && $condition['game_id']) {
            $query->where('game_id', $condition['game_id']);
        }

        if (isset($condition['start_time']) && $condition['start_time']) {
            $query->where('created_at', '>=',$condition['start_time']);
        }

        if (isset($condition['end_time']) && $condition['end_time']) {
            $query->where('created_at', '<=',$condition['end_time'] . ' 23:59:59');
        }

        return $query;
    }

    /**
     * 搜索过滤
     * @param $condition
     * @return \ScoutElastic\Builders\FilterBuilder|\ScoutElastic\Builders\SearchBuilder
     */
    public static function searchCondition($condition)
    {
        $keyword = (isset($condition['keyword']) && ! empty($condition['keyword'])) ? $condition['keyword'] : '-1';

        $query = self::search($keyword);

        $where = [];

        $searchCondition = [
            'should' => [
                [
                    "bool" => [
                        "must" => [
                            [
                                "match" => [
                                    "status" => "1",
                                ],
                            ],
                            [
                                "match" => [
                                    "top" => "1",
                                ],
                            ]
                        ]
                    ]
                ],
                [
                    "bool" => [
                        "must" => []
                    ]
                ],
                [
                    "bool" =>  [
                        "should" =>  [
                            [
                                "bool" =>  [
                                    "must" =>  []
                                ]
                            ],
                            [
                                "bool" =>  [
                                    "must" =>  []
                                ]
                            ],
                            [
                                "bool" =>  [
                                    "must" =>  []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if (isset($condition['status']) && $condition['status']) {
            $where[] = [
                "match" => [
                    "status" => $condition['status'],
                ]
            ];
        }

        if (isset($condition['take_parent_user_id']) && $condition['take_parent_user_id']) {
            $where[] = [
                "match" => [
                    "take_parent_user_id" => $condition['take_parent_user_id'],
                ]
            ];
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $query->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['game_id']) && $condition['game_id']) {
            $where[] = [
                "match" => [
                    "game_id" => $condition['game_id'],
                ]
            ];
        }

        if (isset($condition['region_id']) && $condition['region_id']) {
            $where[] = [
                "match" => [
                    "region_id" => $condition['region_id'],
                ]
            ];
        }

        if (isset($condition['server_id']) && $condition['server_id']) {
            $where[] = [
                "match" => [
                    "server_id" => $condition['server_id'],
                ]
            ];
        }

        $filter = [];
        if (isset($condition['amount']) && $condition['amount'] == 1) {
            $filter = [
                'bool' => [
                    'must' =>  [
                        'range' => [
                            'amount' => [
                                'lt' => 10,
                            ]
                        ]
                    ]
                ]
            ];
        }

        if (isset($condition['amount']) && $condition['amount'] == 2) {
            $filter = [
                'bool' => [
                    'must' =>  [
                        'range' => [
                            'amount' => [
                                'gte' => 10,
                                'lte' => 100,
                            ]
                        ]
                    ]
                ]
            ];
        }

        if (isset($condition['amount']) && $condition['amount'] == 3) {
            $filter = [
                'bool' => [
                    'must' =>  [
                        'range' => [
                            'amount' => [
                                'gte' => 100,
                                'lte' => 200,
                            ]
                        ]
                ]
                ]
            ];
        }

        if (isset($condition['amount']) && $condition['amount'] == 4) {
            $filter = [
                'bool' => [
                    'must' =>  [
                        'range' => [
                            'amount' => [
                                'gt' => 200,
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($keyword == '-1') {
            $searchCondition['should'][1]['bool']['must'] = $where;
            $searchCondition['should'][1]['bool']['filter'] = $filter;
            unset($searchCondition['should'][2]);
        } else {
            $searchCondition['should'][2]['bool']['should'][0]['bool']['must'] = array_merge($where, [
                [
                    "match" => [
                        "trade_no" => $keyword,
                    ]
                ]
            ]);
            $searchCondition['should'][2]['bool']['should'][1]['bool']['must'] = array_merge($where, [
                [
                    "match" => [
                        "title" => $keyword,
                    ]
                ]
            ]);
            $searchCondition['should'][2]['bool']['should'][2]['bool']['must'] = array_merge($where, [
                [
                    "match" => [
                        "title" => $keyword,
                    ]
                ]
            ]);

            $searchCondition['should'][2]['bool']['should'][2]['bool']['filter'] = $filter;
            $searchCondition['should'][1] = $searchCondition['should'][2];
            unset($searchCondition['should'][2]);
        }

        // 查询
        $query->rule(function() use ($searchCondition) {
            return $searchCondition;
        });

        // 1 价格升序
        if (isset($condition['sort']) && $condition['sort'] == 1) {
            $query->orderBy('amount', 'asc');
        }
        // 2 价格降序
        if (isset($condition['sort']) && $condition['sort'] == 2) {
            $query->orderBy('amount', 'desc');
        }
        // 3 时间升序
        if (isset($condition['sort']) && $condition['sort'] == 3) {
            $query->orderBy('day', 'asc');
            $query->orderBy('hour', 'asc');
        }
        // 4 时间降序
        if (isset($condition['sort']) && $condition['sort'] == 4) {
            $query->orderBy('day', 'desc');
            $query->orderBy('hour', 'desc');
        }

        return $query;
    }



    /**
     * 获取订单状态
     * @return mixed
     */
    public function getStatusDescribe()
    {
        return self::$statusDescribe[$this->status];
    }

    /**
     *  获取代练剩余时间
     * @return bool|string
     */
    public function getRemainingTime()
    {
        if (! in_array($this->status, [8, 9, 10, 11, 13])) {
            return sec2Time(Carbon::parse($this->take_at)
                ->addDays($this->day)
                ->addHours($this->hours)
                ->diffInSeconds(Carbon::now()));
        } else {
            return '--';
        }
    }

    /**
     *  支付金额
     *  当前用户为发单用户:
     *      订单状态为正常结算则取订单表中的amount字段值
     *      订单状态为撤销完成/仲裁完成则取,撤销/仲裁表中的amount字段值
     *  当前用户为接单用户:
     *      订单状态为撤销完成/仲裁完成则取,安全保证金与效率保证金的和
     * @return int|string
     */
    public function getExpendAmount()
    {
        // 当前用户为发单用户 否则 就是接单用户
        if (request()->user()->parent_id == $this->parent_id) {
            if (optional($this->consult)->status && optional($this->consult)->status == 3) {
                return $this->consult->amount;
            } else if (optional($this->complain)->status && optional($this->complain)->status == 3) {
                return $this->complain->amount;
            } else if ($this->status == 10) {
                return $this->amount;
            } else {
                return 0;
            }
        } else {
            if (optional($this->consult)->status && optional($this->consult)->status == 3) {
                return bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit);
            } else if (optional($this->complain)->status && optional($this->complain)->status == 3) {
                return bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit);
            } else {
                return 0;
            }
        }
    }

    /**
     *  获取收入金额
     *
     *  当前用户为发单用户:
     *      订单状态为正常结算则为 0
     *      订单状态为撤销完成/仲裁完成则取,撤销/仲裁表中 保证金的和
     *  当前用户为接单用户:
     *      订单状态为撤销完成/仲裁完成则取,安全保证金与效率保证金的和
     * @return int|string
     */
    public function getIncomeAmount()
    {
        // 当前用户为发单用户 否则 就是接单用户
        if (request()->user()->parent_id == $this->parent_id) {
            if (optional($this->consult)->status && optional($this->consult)->status == 3) {
                return bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit);
            } else if (optional($this->complain)->status && optional($this->complain)->status == 3) {
                return bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit);
            } else {
                return 0;
            }
        } else {
            if (optional($this->consult)->status && optional($this->consult)->status == 3) {
                return $this->consult->amount;
            } else if (optional($this->complain)->status && optional($this->complain)->status == 3) {
                return $this->complain->amount;
            } else if ($this->status == 10) {
                return $this->amount;
            } else {
                return 0;
            }
        }
    }

    /**
     * 获取订单手续费
     * @return int|mixed
     */
    public function getPoundage()
    {
        if (optional($this->complain)->status && optional($this->complain)->status == 3) {
            return $this->poundage;
        } else {
            return 0;
        }
    }

    /**
     * 获取订单利润
     * @return int|string
     */
    public function getProfit()
    {
        return self::getIncomeAmount() - self::getExpendAmount() - self::getPoundage();
    }

    /**
     * 获取撤销发起人
     * @return int 0 不存在撤销 1 撤销发起人为 发单方
     */
    public function getConsultInitiator()
    {
        return (int) optional($this->consult)->initiator;
    }

    /**
     * 获取仲裁发起人
     * @return int 0 不存在仲裁 1 仲裁发起人为 发单方
     */
    public function getComplainInitiator()
    {
        return (int) optional($this->complain)->initiator;
    }

    /**
     * 获取订单撤销 描述
     * @return string
     */
    public function getConsultDescribe()
    {
        if (! is_null($this->consult) && optional($this->consult)->status != 2) {

            if ($this->consult->initiator == 1) { // 如果发起人为发单方

                // 当前用户父Id 等于撤销发起人
                if ($this->consult->parent_user_id == request()->user()->parent_id) {
                    return sprintf("您发起撤销, <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f, <br/> 原因: %s",
                        $this->consult->amount, 
                        bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit),
                        $this->consult->reason
                    );
                } else {
                    return sprintf("对方发起撤销, <br/> 对方支付代练费用 %.2f 元, 你方支付保证金 %.2f, <br/> 原因: %s",
                        $this->consult->amount, 
                        bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit),
                        $this->consult->reason
                    );
                }
            } else if ($this->consult->initiator == 2) {  // 如果发起人为接单方

                if ($this->consult->parent_user_id == request()->user()->parent_id) {
                    return sprintf("您发起撤销, <br/> 对方支付代练费用 %.2f 元, 你支付保证金 %.2f, <br/> 原因: %s",
                        $this->consult->amount, 
                        bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit),
                        $this->consult->reason
                    );
                } else {
                    return sprintf("对方发起撤销, <br/> 对方支付代练费用 %.2f 元, 您支付保证金 %.2f, <br/> 原因: %s",
                        $this->consult->amount, 
                        bcadd($this->consult->security_deposit, $this->consult->efficiency_deposit),
                        $this->consult->reason
                    );
                }
            }

        } else {
            return '';
        }
    }

    /**
     * 获取订单仲裁 描述
     * @return string
     */
    public function getComplainDescribe()
    {
        if (! is_null($this->complain) && $this->complain->status != 3) {
            // 当前用户父Id 等于仲裁发起人
            if ($this->complain->parent_user_id == request()->user()->parent_id) {
                return sprintf("你发起仲裁 <br/> 原因: %s",
                    $this->complain->reason
                );
            } else {
                return sprintf("对方发起仲裁 <br/> 原因: %s",
                    $this->complain->reason
                );
            }
        } else {
            return '';
        }
    }

    /**
     * 仲裁结果
     * @return string
     */
    public function getComplainResult()
    {
        if (! is_null($this->complain) && $this->complain->status == 2) {

            if ($this->complain->initiator == 1) { // 如果发起人为发单方

                // 当前用户父Id 等于仲裁发起人
                if ($this->complain->parent_user_id == request()->user()->parent_id) {
                    return sprintf("客服进行了【仲裁】  <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                        $this->complain->amount,
                        bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit),
                        $this->complain->reason
                    );
                } else {

                    return sprintf("客服进行了【仲裁】  <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                        $this->complain->amount,
                        bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit),
                        $this->complain->reason
                    );
                }
            } else if ($this->complain->initiator == 2) {  // 如果发起人为接单方
                // 客服进行了【仲裁】【你（对方）支出代练费1.0元，对方（你）支出保证金0.0元。仲裁说明：经查证，双方协商退单，已判定】
                if ($this->complain->parent_user_id == request()->user()->parent_id) {
                    return sprintf("客服进行了【仲裁】 <br/> 对方支付代练费用 %.2f 元, 你支付保证金 %.2f <br/> 仲裁说明： %s",
                        $this->complain->amount,
                        bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit),
                        $this->complain->reason
                    );
                } else {
                    return sprintf("客服进行了【仲裁】 <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                        $this->complain->amount,
                        bcadd($this->complain->security_deposit, $this->complain->efficiency_deposit),
                        $this->complain->reason
                    );
                }
            }
        } else {
            return '';
        }
    }

    /**
     * 提交验收时间
     * @return mixed|string
     */
    public function getApplyCompleteAtAttribute()
    {
        if ($this->status == 3) {
            return $this->attributes['apply_complete_at'];
        } else {
            return '';
        }
    }

    /**
     * 平分保证金
     * @param $tradeNO
     * @param $deposit
     * @return array
     */
    public static function deuceDeposit($tradeNO, $deposit)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNO])->first();

        $securityDeposit = 0;
        $efficiencyDeposit = 0;
        if ($deposit > $order->security_deposit) {
            $securityDeposit =  $order->security_deposit;
            $efficiencyDeposit =  bcsub($deposit, $order->security_deposit);
        } else if ($deposit < $order->security_deposit || $deposit == $order->security_deposit) {
            $securityDeposit = $deposit;
            $efficiencyDeposit = 0;
        }

        return  [
            'security_deposit' => $securityDeposit,
            'efficiency_deposit' => $efficiencyDeposit,
        ];
    }

    /**
     * 关联游戏
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }


    /**
     * 关联仲裁表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function complain()
    {
        return $this->hasOne(GameLevelingOrderComplain::class, 'game_leveling_order_trade_no', 'trade_no');
    }

    /**
     * 关联协商撤销表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function consult()
    {
        return $this->hasOne(GameLevelingOrderConsult::class, 'game_leveling_order_trade_no', 'trade_no');
    }

    /**
     * 关联留言表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message()
    {
        return $this->hasMany(GameLevelingOrderMessage::class, 'game_leveling_order_trade_no', 'trade_no');
    }

    /**
     * 申请验收记录
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function applyComplete()
    {
        return $this->hasOne(GameLevelingOrderApplyComplete::class, 'game_leveling_order_trade_no', 'trade_no');

    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))->toDateTimeString();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderStatistic()
    {
        return $this->hasOne(OrderStatistic::class, 'trade_no', 'trade_no');
    }
}
