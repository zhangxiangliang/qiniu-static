<?php

namespace Zhangxiangliang\QiniuStatic\Commands;

use Illuminate\Console\Command;
use Zhangxiangliang\QiniuStatic\QiniuStatic;

class PushCommand extends Command
{
    protected $type = null;
    protected $force = null;
    protected $basepath = null;
    protected $path = null;

    protected $signature = 'qiniu-static:push {type?} {--path=} {--force} {--basepath=}';
    protected $description = 'Push static file to qiniu cdn, type value (all, css, img, js)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->type = $this->argument('type') ? : 'all';
        $this->force = $this->option('force');
        $this->basepath = $this->option('basepath');
        $this->path = $this->option('path');
        $this->push();
    }

    private function push()
    {
        $this->info("Pushing " . $this->type);
        (new QiniuStatic($this->basepath, $this->type, $this->force, $this->path))->pushFiles($this->type);
    }
}
