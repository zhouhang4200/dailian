<?php

return [
    'app_id' => env('TM_APP_ID', 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx'),

    'app_secret' => env('TM_APP_SECRET', 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx'),

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
    ],
];