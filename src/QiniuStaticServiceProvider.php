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
            __DIR__ . '/config/qiniu-static.php' => config_path('qiniu-static.php')
        ]);
    }
}
