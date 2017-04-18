<?php

namespace Zhangxiangliang\QiniuStatic\Commands;

use Illuminate\Console\Command;
use Zhangxiangliang\QiniuStatic\QiniuStatic;

class FileCommand extends Command
{
    protected $pach = null;
    protected $filename = null;
    protected $signature = 'qiniu-static:file {path?} {filename?}';
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
    }

    private function checkArgument()
    {
        if(!$this->path) return $this->error('place input file path');
        if(!$this->filename) return $this->error('place input file name');
        return true;
    }

    private function push()
    {
        $this->info("Pushing...");
        (new QiniuStatic())->pushFile(base_path($this->path), $this->filename);
    }
}
