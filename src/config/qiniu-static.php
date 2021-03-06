<?php

return [
    'log' => storage_path('logs/qiniu-static.log'),
    'public' => [
        public_path(),
    ],
    'qiniu' => [
        'access_key' => env('QN_ACCESS_KEY', ''),
        'secret_key' => env('QN_SECRET_KEY', ''),
        'bucket'     => env('QN_BUCKET', ''),
        'url'        => env('QN_URL', ''),
    ],
    'extension' => [
        'img' => ['bmp','png','gif','jpeg','jpg'],
        'js'  => ['js'],
        'css' => ['css']
    ],
];
