<?php

namespace Zhangxiangliang\QiniuStatic;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\UploadManager as QiniuUploadManager;
use Qiniu\Storage\BucketManager as QiniuBucketManager;
use Symfony\Component\Console\Output\ConsoleOutput;

class QiniuStatic
{
    private $qiniuAuth;
    private $uploadManager;
    private $bucketManager;
    private $token;

    private $beforeTime;
    private $type;
    private $logPath;
    private $force;
    private $directories;
    private $bucket;
    private $extension = [];

    public function __construct($basepath = false, $type = 'all', $force = false, $path = null)
    {
        $this->output = new ConsoleOutput();
        $this->initQiniuAuth();
        $this->initConfig();
        $this->uploadManager = new QiniuUploadManager();
        $this->bucketManager = new QiniuBucketManager($this->qiniuAuth);
        $this->beforeTime = $this->getBeforePushTime();
        $this->basepath = $basepath;
        $this->type = $type;
        $this->force = $force;
        $this->directories = $path ? [$path] : config('qiniu-static.public');
    }

    public function pushFiles($type)
    {
        foreach ($this->directories as $directory) {
            foreach(File::allFiles($directory) as $file) {
                $this->filterFiles($file);
            }
        }
        $this->logPushTime();
    }

    public function pushFile($filepath, $filename)
    {
        if($this->existFile($filename)) {
            $this->line('Upload fail, filename is existed', 'error');
            return;
        }
        list($ret, $err) = $this->uploadManager->putFile(
            $this->token, $this->addBasePath($filename), $filepath
        );
        $this->uploadMessage($err, $filepath, $this->addBasePath($filename));
    }

    private function addBasePath($filename)
    {
        return ($this->basepath ? : '') . $filename;
    }

    private function initConfig()
    {
        $this->logPath = config('qiniu-static.log');
        $this->bucket = config('qiniu-static.qiniu.bucket');
        $this->extension['img'] = config('qiniu-static.extension.img');
        $this->extension['js'] = config('qiniu-static.extension.js');
        $this->extension['css'] = config('qiniu-static.extension.css');
    }

    private function initQiniuAuth()
    {
        $accessKey = config('qiniu-static.qiniu.access_key');
        $secretKey = config('qiniu-static.qiniu.secret_key');
        $bucket = config('qiniu-static.qiniu.bucket');

        $this->qiniuAuth = new QiniuAuth($accessKey, $secretKey);
        $this->token = $this->qiniuAuth->uploadToken($bucket);
    }

    private function filterFiles($file)
    {
        if($file->getSize() == 0) return;
        if(!$this->extension($file)) return;
        if(!$this->needUploadOfTime($file) && !$this->force) return;

        $this->deleteFiles($file);
        $this->uploadFiles($file);
    }

    private function line($string, $style)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;
        $this->output->writeln($styled);
    }

    private function needUploadOfTime($file)
    {
        return Carbon::createFromTimestamp($file->getATime()) > $this->beforeTime
            || Carbon::createFromTimestamp($file->getCTime()) > $this->beforeTime
            || Carbon::createFromTimestamp($file->getMTime()) > $this->beforeTime
            || $this->beforeTime === false;
    }

    private function existFile($filename)
    {
        return $this->bucketManager->stat($this->bucket, $filename)[0] ? true : false;
    }

    private function uploadFiles($file)
    {
        $filename = $file->getFilename();
        list($ret, $err) = $this->uploadManager->putFile(
            $this->token, $this->addBasePath($filename), $file->getRealPath()
        );
        $this->uploadMessage($err, $file->getRealPath(), $this->addBasePath($filename));
    }

    private function deleteFiles($file)
    {
        $this->bucketManager->delete($this->bucket, $file->getFilename());
    }

    private function extension($file)
    {
        $extension = $this->type == 'all' ? array_merge(
            $this->extension['img'],
            $this->extension['js'],
            $this->extension['css']
        ) : config('qiniu-static.extension.' . $this->type);

        return in_array($file->getExtension(), $extension);
    }

    private function uploadMessage($err, $path, $name)
    {
        if($err !== null) $this->line('Upload fail, filepath: ' . $path, 'error');
        else $this->line('Push success, file path: ' . $path . ', name: ' . $name, 'info');
    }

    private function getBeforePushTime()
    {
        $before = File::get($this->logPath);
        if(!$before) return false;
        return $before;
    }

    private function logPushTime()
    {
        File::put($this->logPath, new Carbon());
    }
}
