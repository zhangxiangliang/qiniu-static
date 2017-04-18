# 七牛云 - 静态文件自动上传
## Qiniu - Static File Upload

## 安装
* 添加 `composer require zhangxiangliang/qiniu-static` 到项目。
* 添加 `Zhangxiangliang\QiniuStatic\QiniuStaticServiceProvider::class` 到 `config/app.php`。
* 运行 `php artisan vendor:publish` 来加载需要的配置文件。

## 配置
* 配置文件在 `config/qiniu-static.php`，如果找不到该文件请确定是否有执行过 `php artisan vendor:publish`。
```
return [
    'log' => storage_path('logs/qiniu-static.log'), // log 记录最后一次更新的时间

    'public' => [
        public_path(),
    ], // public 用来设置需要上传的文件目录

    'qiniu' => [
        'access_key' => env('QN_ACCESS_KEY', ''),
        'secret_key' => env('QN_SECRET_KEY', ''),
        'bucket'     => env('QN_BUCKET', ''),
        'url'        => env('QN_URL', ''),
    ], // qiniu 七牛云的配置，需要根据自己的情况来配置

    'extension' => [
        'img' => ['bmp','png','gif','jpeg','jpg'],
        'js'  => ['js'],
        'css' => ['css']
    ], // extension 拓展名，用于过滤需要上传的文件，可以自行添加类型。例如 'txt' => ['txt']
];

```
* 支持本地化，根据 `config/app.php` 中的语言进行提示。

## 使用
### 上传文件

使用方法：
```
php artisan qiniu-static:push [--type] [--path] [--force] [--basepath]
```

操作：
```
--type => 需要上传的文件类型，可选择的类型见配置文件中的 `extension`，默认为配置的所有拓展。
--path => 文件路径，可以选择需要上传的文件目录，也可以在配置文件中 `public` 里设置，默认为配置中的设置。
--force => 强制上传文件，默认下为上传`上一次上传时的时间`后的文件。
--basepath => 上传文件名的前缀例如 `--basepath dist/` 则上传的文件名为 `dist/` 加上 原文件名 组成，默认为上传原文件名。
```

### 上传单一文件

使用方法：
```
php artisan qiniu-static:file [path] [filename]
```

参数：
```
path => 需要上传的文件路径（暂时只能上传项目内的文件）
filename => 上传后的文件名
```

## 帮助函数
### qiniu_asset()
用于将 `资源引用` 转为 `七牛云` 中的 CDN路径。
```
<script src={{qiniu_asset('dist/app.css')}}></script>
```
