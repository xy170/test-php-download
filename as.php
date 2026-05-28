<?php

// $domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
// $time = date('Y-m-d H:i:s');

// $data = [
//     'domain' => $domain,
//     'time'   => $time
// ];

// $ch = curl_init('http://10.163.17.203/api/stat/collect');

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// curl_setopt($ch, CURLOPT_TIMEOUT, 5);

// $response = curl_exec($ch);
// curl_close($ch);
// // file_put_contents(__DIR__ . '/stat.log', "domain={$domain}, time={$time}\n", FILE_APPEND);
// // echo 'ok';
// return;



$domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
$time = date('Y-m-d H:i:s');

$data = [
    'domain' => $domain,
    'time'   => $time
];

$ch = curl_init('http://127.0.0.1/api/stat/collect');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

file_put_contents(__DIR__ . '/stat.log',
    "response={$response}, error={$error}\n",
    FILE_APPEND
);

return;
