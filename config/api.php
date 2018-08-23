<?php
    return [
        // 判断数据包中时间戳 与 服务器时间 差值大于此值时则表明请求无效
        'timestamp' => 120,

        // 响应状态码与说明
        'code' => [
            0 => [
                'code' => 0,
                'message' => '请求成功',
            ],
            // 公共
            1001 => [
                'code' => 1001,
                'message' => '参数缺失',
            ],
            1002 => [
                'code' => 1002,
                'message' => '签名错误',
            ],
            1003 => [
                'code' => 1003,
                'message' => '服务器异常',
            ],
            1004 => [
                'code' => 1004,
                'message' => '未授权访问',
            ],
            1005 => [
                'code' => 1005,
                'message' => '您没有该权限',
            ],
            // 登录注册模块
            2001 => [
                'code' => 2001,
                'message' => '密码错误',
            ],
            2002 => [
                'code' => 2002,
                'message' => '邮箱已存在',
            ],
            2003 => [
                'code' => 2003,
                'message' => '手机号已存在',
            ],
            2004 => [
                'code' => 2004,
                'message' => '请填写注册时候的手机号',
            ],
            2005 => [
                'code' => 2005,
                'message' => '原密码错误',
            ],
            2006 => [
                'code' => 2006,
                'message' => '用户不存在',
            ],
            // 个人中心
            3001 => [
                'code' => 3001,
                'message' => '上传图片类型不合法',
            ],
            3002 => [
                'code' => 3002,
                'message' => '上传图片为空',
            ],
            3003 => [
                'code' => 3003,
                'message' => '原支付密码错误',
            ],
            3004 => [
                'code' => 3004,
                'message' => '只有主账号才能申请实名认证',
            ],
            3005 => [
                'code' => 3005,
                'message' => '实名认证申请信息不存在',
            ],
            3006 => [
                'code' => 3006,
                'message' => '子账号不能查看主账号实名认证信息',
            ],
            3007 => [
                'code' => 3007,
                'message' => '支付密码错误',
            ],
            // 资金流水 您的账号余额不够
            4001 => [
                'code' => 4001,
                'message' => '您的账号余额不够',
            ],
            4002 => [
                'code' => 4002,
                'message' => '支付密码错误',
            ],
            // 公告帮助中心
            5001 => [
                'code' => 5001,
                'message' => '该公告或帮助不存在',
            ],
            // 订单
            7001 => [

            ]
        ],

    ];
