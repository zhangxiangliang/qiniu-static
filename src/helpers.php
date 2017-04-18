<?php
if (! function_exists('qiniu_asset')) {
    function qiniu_asset($key)
    {
        $baseurl = config('qiniu-static.qiniu.url');
        return $baseurl . $key;
    }
}
