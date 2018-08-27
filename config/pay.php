<?php

return [
    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => env('ALI_APP_ID', '2016091700532764'),
        // 支付宝异步通知地址
        'notify_url' => '',
        // 支付成功后同步通知地址
        'return_url' => '',
        // 阿里公共密钥，验证签名时使用
        'ali_public_key' => env('ALI_PUBLIC_KEY', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxSlXGMx+r/qhxpm3KSRHRKoql7U04oChnLjUs0LPL2Al7SxK5FERKNlqEdpOO7yRFnjhoKD+m/9zVotjDO/06Vq53yPKRq8IhAlo2+0wY54E3RBl0euJXGyrwJg5SUAmWun1bqIvpfQZT1vL4j/0NEeGnHI1nDfCyBRRUk18xxYcGO+o8bruzcbhzV3eY7MHuFgxNWOYLXXGtGdx8iDr2swaL3r0nZlG5qC6kI1Vv71wcJByFFUXfXOdmljN+w8jI0t/+jUGYre11Cp8cWFD7TjMC1WiSL2APZOfXZH5i1exIBXht2Fnoz3pvkk3FXDtGhYrUuJ4HrYjLljGimbf6QIDAQAB'),
        // 自己的私钥，签名时使用
        'private_key' => env('ALI_PRIVATE_KEY', 'MIIEpQIBAAKCAQEA1bhbDcwE2GFYiyS6R3IdvRVbBwaQOUOKlIdgIXjRsdDHQD3/9MXFg/lRtrXx6lQpTN4k94FfGV8R14t8fFklFF2XXTKqpv2Bv0xlFc2CnecHZBQ4XE3OBCIa64GIZlCmf3roo9Sfv35/SsuLHe4oZsgYKTOMnEQkvh5sjVeHUAE0Q/8bOu6qWdvX4NKlXwG3h7p6mcE2TEhm8MT/gBwscPZ6OsUvIq0lwEpo1Prf3JI06WYqBx/wXCJp/Xmk3ERWdaSIlUHi/tqjXFA/opvLbm2tfvvNKOidNR+odqt/AM20GhajkpH0KVHMrHDS4DBQdS75JaQap+M5j7GEQ918uwIDAQABAoIBAQDUQy+b+l9tkPFh8O5Z+0rx+v3XcntXhG9kqTeexuJtmo/qjClkr/BF7R9tOjO0qjYCtDc+4uzSxAtyaoUO83LSUUn+NE4tYGa22mcjSmNJ9KTwjuyTMGtGy6C0+ofAJqXWwvm/jXPDux0t6g6XgWv6+QyWDy6+HSfo80qY7q/jkh0wm1yrq6BWQZsZ9QCQQk60mdM8ev9/qoK+LTDzrkdMXOefD13eCTGOmOHdzPUFIb0wcOw/OuV3xset6f5ewlCmRGJGbA+45JuLFK2ZC4tkjicDZp5HqkyrWguycWAbNJ9YolyNT2/AjIlWCTtFlu0Cd8qgUaKAYPgAcvxf8I5xAoGBAPfVfSMlZmHmKGHuNDLonPuy6VyTjwgzQRET6Kf6alh6n2oE0YmPLbyC8joW//2IooAJ8s0zVuMgW4MABSqwg9paSsVBiGTwVfz+yZmUEawVy3iRjvaTukUbI0C1dPFJzp+CCq4NRCCAqmDg+0iOEZytYv8GWRVZ/KV10OC9h/jfAoGBANzDHNCTq744/z6eqhQ068wsYSMYujslGFkXzRfT2sRObFFBTnDUMxKms0sCi4N6mk/HJOdzI4grR+pMho9j97z1wTsLfU8PsFwUaw9QMVxEq+I3xr2HP32ju6vtfqC7jEgKa4LNxhgf8veGUoYByIsTfQuDe06bmHei3lCnB4ulAoGBANe0oBsEBU12NnWuDw60OVY1TTl/UQGRIrPLgTcbFI54A+IR2ZKB5QvraQq4VAyGbPFGN2b0pU5ALoYhcV3Ljqdw6R0wwZchO6Zc2HjsUElYGuWQwz7IK1rUmOVMSkFsvQNF+yescjxb4G87wlpPFPMqUjyneMfFr8kPd0L0k7E/AoGBANCt5wkK7ieMPUr2NJORSuvfb5r7ft71u8RMCWTisEMsVpugfgONsxGIXqcjKroIJOIQv0PBxO6s6vgBipj0yHPoYtXrkHIxxGXmLyV0ipRLnabjToxN0qhzI63re+14G6zlnKcy2HRpjwSIBm4CNY2xyFwf12mBD2jXngrjOPC5AoGAZb7z7NDqewqof944/bhYd6gN60yJfmgsQ417Vy7vNp4jnHupIY3McioqFvmfzDxbVHYrlE4ajCmGmBZR/dG2W0WCgfTwbAqp9TD80ObAO3JOSvoNA5rH70t5GjD7ect/mBNlKBn3r6yFmX1yO2cuPtMdm9fgNQFaMOjkKcJYTM8='),
        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/alipay.log'),
        //     'level' => 'debug'
        ],
        // optional，设置此参数，将进入沙箱模式
         'mode' => 'dev',
    ],

    'wechat' => [
        // 公众号 APPID
        'app_id' => env('WECHAT_APP_ID', 'wxd741646d2b519c60'),
        // 小程序 APPID
        'miniapp_id' => env('WECHAT_MINIAPP_ID', 'wx2c4853cb397d357c'),
        // APP 引用的 appid
        'appid' => env('WECHAT_APPID', 'wx2c4853cb397d357c'),
        // 微信支付分配的微信商户号
        'mch_id' => env('WECHAT_MCH_ID', '1502886431'), // 1502886431
        // 微信支付异步通知地址
        'notify_url' => '',
        // 微信支付签名秘钥
        'key' => env('WECHAT_KEY', 'c27656886d284e71a3d87aa1fce03ed1'),
        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_client' => storage_path('app/wechat/apiclient_cert.pem'),
        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_key' => storage_path('app/wechat/apiclient_key.pem'),
        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/wechat.log'),
        //     'level' => 'debug'
        ],
        // optional
        // 'dev' 时为沙箱模式
        // 'hk' 时为东南亚节点
        // 'mode' => 'dev',
    ],
];
