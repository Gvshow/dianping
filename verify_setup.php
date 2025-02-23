<?php
// 设置安全配置
define('HASH_SALT', 'YaS7#mK9$pL2@qN4'); // 加盐值
define('MAX_LOGIN_ATTEMPTS', 5); // 最大登录尝试次数
define('LOCKOUT_TIME', 1800); // 锁定时间（秒）
define('PASSWORD_HASH', password_hash('qq235133' . HASH_SALT, PASSWORD_BCRYPT)); // 存储加密后的密码

// 创建一个用于检查设置的文件
if (!file_exists('texts.json')) {
    file_put_contents('texts.json', json_encode([]));
}

$permissions = substr(sprintf('%o', fileperms('texts.json')), -4);
echo "texts.json permissions: " . $permissions . "\n";

if (is_writable('texts.json')) {
    echo "texts.json is writable\n";
} else {
    echo "Warning: texts.json is not writable\n";
}
?> 