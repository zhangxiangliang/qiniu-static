<?php

return [
    'error' => [
        'path' => '请输入文件路径',
        'exist' => '改文件名已存在，请重新选择文件名，或者使用 --force 操作',
        'filename' => '请输入文件名',
        'fail' => '上传失败, 文件路径: :path'
    ],
    'info' => [
        'pushing' => '正在上传...',
        'success' => '==>上传成功, 文件路径: :path, 七牛云文件名: :name',
        'directory' => [
            'start' => '=>正在遍历目录 :directory',
            'end' => '=>结束遍历目录 :directory',
        ]
    ]
];
