<?php
header('Content-Type: text/plain');

echo "系统检查开始...\n\n";

// 检查 PHP 版本
echo "PHP 版本: " . PHP_VERSION . "\n";

// 检查文件权限
$files = [
    'texts.json' => '666',
    'verify.php' => '644',
    'save_texts.php' => '644'
];

foreach ($files as $file => $required_permission) {
    $perms = substr(sprintf('%o', fileperms($file)), -3);
    echo "$file 权限: $perms (需要: $required_permission) - ";
    echo ($perms == $required_permission ? "正确\n" : "需要修改\n");
}

// 检查文件是否可写
echo "\ntexts.json 可写性: " . (is_writable('texts.json') ? "正常" : "异常") . "\n";

// 检查 PHP 设置
$settings = [
    'post_max_size',
    'upload_max_filesize',
    'max_execution_time'
];

echo "\nPHP 设置:\n";
foreach ($settings as $setting) {
    echo "$setting: " . ini_get($setting) . "\n";
}

echo "\n检查完成";
?> 