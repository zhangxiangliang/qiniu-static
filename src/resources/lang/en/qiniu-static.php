<?php
return [
    'error' => [
        'path' => 'Place input file path',
        'exist' => 'Upload fail, filename is existed',
        'filename' => 'Place input file name',
        'fail' => 'Upload fail, filepath: :path',
        'code:631' => 'Bucket is error',
        'code:612' => 'Accesskey is error',
        'code:401' => 'Token or Secretkey is error',
    ],
    'info' => [
        'pushing' => 'Pushing...',
        'success' => '==>Push success, file path: :path, name: :name',
        'directory' => [
            'start' => '=>traversing :directory',
            'end' => '=>traversed :directory',
        ]
    ],
    'warn' => [
        'qiniu' => ':key is empty',
    ]
];
