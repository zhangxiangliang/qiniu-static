<?php

namespace Zhangxiangliang\QiniuStatic;

use Illuminate\Support\ServiceProvider;

class QiniuStaticServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->qiniuStaticCommands();
        $this->qiniuStaticPublishes();
        $this->qiniuStaticHelpers();
        $this->qiniuStaticCheckConfig();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function qiniuStaticCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Zhangxiangliang\QiniuStatic\Commands\PushCommand::class,
                \Zhangxiangliang\QiniuStatic\Commands\FileCommand::class,
            ]);
        }
    }

    private function qiniuStaticPublishes()
    {
        $this->publishes([
            __DIR__ . '/cache' => base_path('storage/logs/'),
            __DIR__ . '/config/qiniu-static.php' => config_path('qiniu-static.php'),
            __DIR__ . '/resources/lang/' => base_path('resources/lang/'),
        ]);
    }

    private function qiniuStaticHelpers()
    {
        require __DIR__ . '/helpers.php';
    }

    private function qiniuStaticCheckConfig()
    {
        $qiniu = config('qiniu-static.qiniu');
        foreach ($qiniu as $key => $value) {
            if($value == null) {
                QiniuStatic::output(trans('qiniu-static.warn.qiniu', compact('key')), 'comment');
            }
        }
    }
}
