<?php
// redis key 名配置文件,所有需要用的redis的地方key必须先在此文件定义,用config('redis_key.xxx')调用,方便统一管理

return [
    // 订单相关
    'order' => [
        'quantity' => 'order:quantity:', // 用于生成唯一交易订单号
    ]
];
