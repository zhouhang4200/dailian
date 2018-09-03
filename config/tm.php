<?php

return [
    // 天猫发单器在丸子的账号ID
    'user_id' => env('TM_USER_ID', 1),
    // 天猫发单器给丸子分配的app_id
    'app_id' => env('TM_APP_ID', 'T8WsMDT4mJ5DxKJkf4fWVP5XYU00McJxxyAeoX4aPIy6jrWN70bmQltXfwof'),
    // 天猫发单器给丸子分配的app_secret
    'app_secret' => env('TM_APP_SECRET', 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx'),
    // 天猫发单器给的 aes_key
    'aes_key' => env('TM_AES_KEY', '335ss6s8m8e4f5a8e2e2ls5'),
    // 天猫发单器给的 aes_iv
    'aes_iv' => env('TM_AES_IV', '1234567891111152'),
    // 天猫发单器的接口地址
    'action' => [
        'take' =>  env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/receive', // 接单
        'anomaly' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/abnormal', // 异常
        'cancel_anomaly' =>  env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/cancel-abnormal', // 取消异常
        'apply_complain' =>  env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/apply-arbitration', // 申请仲裁
        'cancel_complain' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/cancel-arbitration', // 取消仲裁
        'apply_complete' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/apply-complete', // 申请验收
        'apply_consult' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/revoke', // 申请协商
        'cancel_consult' =>  env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/cancel-revoke', // 取消协商
        'agree_consult' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/agree-revoke', // 同意协商
        'refuse_consult' => env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/refuse-revoke', // 不同意协商
        'arbitration' =>  env('TM_DOMAIN', 'http://www.tm.com') .  '/api/partner/order/force-arbitration', // 客服仲裁
        'cancel_complete' => env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/cancel-complete', // 取消验收
        'callback' => env('TM_DOMAIN', 'http://www.tm.com') . '/api/partner/order/callback', // 取消验收
    ],
];