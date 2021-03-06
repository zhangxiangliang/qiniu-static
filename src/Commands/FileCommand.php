<?php

namespace Zhangxiangliang\QiniuStatic\Commands;

use Illuminate\Console\Command;
use Zhangxiangliang\QiniuStatic\QiniuStatic;

class FileCommand extends Command
{
    protected $pach = null;
    protected $filename = null;
    protected $force = null;
    protected $signature = 'qiniu-static:file {path?} {filename?} {--force}';
    protected $description = 'Push and rename static file path to qiniu CDN.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->initArgument();
        if(!$this->checkArgument()) return;
        $this->push();
    }

    private function initArgument()
    {
        $this->path = $this->argument('path');
        $this->filename = $this->argument('filename');
        $this->force = $this->option('force');
    }

    private function checkArgument()
    {
        if(!$this->path) return $this->error(trans('qiniu-static.error.path'));
        if(!$this->filename) return $this->error(trans('qiniu-static.error.filename'));
        return true;
    }

    private function push()
    {
        $this->info(trans('qiniu-static.info.pushing'));
        (new QiniuStatic($this->force))->pushFile($this->path, $this->filename);
    }
}
