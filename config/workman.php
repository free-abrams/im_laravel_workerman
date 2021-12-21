<?php

return [

    'default' => env('WM_NAME', 'wss'),
    'context'=>array(
        'ssl' => array(
            // 请使用绝对路径
            'local_cert'                 => env('WM_PUBLIC', '/usr/local/nginx/conf/ssl/service.bdmall68.com.crt'), // 也可以是crt文件
            'local_pk'                   => env('WM_KEY', '/usr/local/nginx/conf/ssl/service.bdmall68.com.key'),
            'verify_peer'               => false,
        )
    ),
    'run_port' => env('WM_RUN_PORT',6688),
    'reg_port' => env('WM_REG_PORT',1688),
];
