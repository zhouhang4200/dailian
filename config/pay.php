<?php

return [
    'alipay' => [
        # 支付宝分配的 APPID
        'app_id' => env('ALI_APP_ID', '2018030802336096'),
        # 支付宝异步通知地址
        'notify_url' => '',
        # 支付成功后同步通知地址
        'return_url' => '',
        # 阿里公共密钥，验证签名时使用
        'ali_public_key' => env('ALI_PUBLIC_KEY', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7Dq65aKIvY/hPxPBt+14lWv3Bt8GJMifOlqRnGb78Mx5oMv17o9iC7JLNV0HodrIsBYVpdJQoQiQ6rRe9wIdAV0OXUIir3fQxKo6whvCyGecnDQGYXpac7zBMFaoVa256q/uSTidFJIFnLvajJuRXwWZBYXujy+mZ9AUZbmNax8RDytqKEv6E+AUuPrwNMW7BkI4f67j0BEgc4qhas347fnQ1yxQ0bMp9C9NuKboWlFJ+bVz6SImdVcfwBnLQ+DyILwPcSiVoNySskJx6XyKAYzCDd04+dXHzuerAGBFp88gbOM+mTqgPOMYkvW2LhQdqkAw+btLJ+gdHcK8xUuXqQIDAQAB'),
        # 自己的私钥，签名时使用
        'private_key' => env('ALI_PRIVATE_KEY', "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCoixb88r3wnuU+cTifXpMPyVofN28z6ey8NTx7Jm6wOEhcJx4HZnZkNty9ggF4IsoGLmp25bOuBqowmC+yLnGkvLZ+XYYbodedgif8W5Pxlrt0SQH4N+k3a5qBJYD+zsMwTB/IVuwe6/VRa1uCzWUVZ2/9Pmj+J7EEmO3KJeFpGWD8Dmvv7Hw86tUhutMjFWVx3VDQ0M64ghZHJrfKFB23C1erARmA0j/NAh2ie8MRlXNojvrz3TSBXWwAEKgjdQJkkmmL1rPWPisARHfHzUdEdgjIlxzpT5YbIFB0BIlBhSO4LCLFggtT0+V0pfgy0t00Xc9t4UzwsK644wHixei5AgMBAAECggEBAKNag8JQBxYS+z9E/0s/n6t6TTXaRZZ8UC2uL1twyXFUa5WdeDZV8cN5hQLL4V6t4T6SeC+avEacQRFuPzQkXZL8MKhTPurDNrZ7cwPdQouxuyeepyEMF6bWTN6FGfxdK8NA1MEYtivWKGNseTpSlnljUqCRf7Ntht1c14PIqStxkd4vSor+BzxBiWfyFpkIG6hEMDYT7GG1/Hu1su5eD4TNaxXOCh4w11nqw/C9kij4CU8Zw/8E83Y36SgjMrDlgJ+VLxqVA/SgF31wFHPQK56zpeq3c2m7nyps0l4Gxy0pJK6q0WZUOEazVsS1LwWnI71LCRLpQg0CaiA8ab9o450CgYEA03OWSk/s45YbWwEYiGRYoLz5RQA9ZUlRag1ectMHUGnRlNxPG0K4RVvfqZamhZEht3bVQTgbRzFFvp562o/1/M88Iqj9VtXy5BJPZSlHZxrDhwwJ9JTDC/EZthYhlbBNtuEjaWpLzmz6Cyjie0N+4wYKBgy7P+42AqCDwhEsN7sCgYEAzA1LfjIR6LVIveYdvdmEDB4omG41Pw2jtmtnCkN6/6UCd0EzCQfevCBmOzR6/H/DGf6aGn1N6s0igEk8m//KD0emYVNmAptyrDVlXHA+oWVJv7229IUofgAkoB5bQkFADwUFjlQNM/yxU4XaNj6G0FMc1xhDdsqoZTU05SE/mBsCgYBcjcWE9YMNAxEqPkqMuM+KW+0H5Nj60qLONtfWjsQu0IKqE9yUZBngUhyOUKDG6gXGFj+18mcOPGT40MmZjVXwuIzr5ugs8C0G43TZJL5aRP68x+o7ojnVqkzExN8idC8wE0+6voo76rtv0w9/QMXzfRs6FqyHiS8e6z+A3J9sLQKBgA6ReG1XF848eygLsN3oFLRkxtMzMxwAVkrk2iNyc+qilk17WzBu3mkiCwp5EbrLSFunwMrZXWHBKZBtKiWdGokCvY8/TA4tmP9QhJ8X6HDPcXd+DPziMOTmD7da03ske3VXD3F88MBgbyyeZtjul+Nxu6JjuhVWHLW1GcSuHgM3AoGAbv+Y8qAEG1KwXrmVRJ7686w0uaIhAUeg1QbCM5TlYHiMLsiY5vUermBTgEDk9Ws6qqNyGb+96dHoNYQJsToMhEyWTahtoODzZZfRMuAcEJY9Kly5CAGQCuBGTA+g7plAltpju01Rk/j/3YucvkD7CKBNLQJkoK4Oxnc22Blb7NY="),

        # optional，设置此参数，将进入沙箱模式
//        'mode' => 'dev',
//
//        'log' => [
//            'file' => storage_path('logs/alipay.log'),
//           'level' => 'debug'
//        ],

    ],

    'wechat' => [
        // 公众号 APPID
        'app_id' => env('WECHAT_APP_ID', 'wxd741646d2b519c60'),
        // 小程序 APPID
        'miniapp_id' => env('WECHAT_MINIAPP_ID', 'wx2c4853cb397d357c'),
        // appsecret
        'app_secret' => env('APP_SECRET', '9fb4558377ce6620ca545067b82e4395'),
        // APP 引用的 appid
        'appid' => env('WECHAT_APPID', ''),
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
