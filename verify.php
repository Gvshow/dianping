<?php
require_once 'verify_setup.php';
require_once 'security_headers.php';
session_start();

header('Content-Type: application/json');

// 获取客户端IP
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// 检查是否被锁定
function isLocked() {
    $ip = getClientIP();
    $lockFile = "login_attempts/{$ip}.txt";
    
    if (file_exists($lockFile)) {
        $data = json_decode(file_get_contents($lockFile), true);
        if ($data['attempts'] >= MAX_LOGIN_ATTEMPTS) {
            if (time() - $data['last_attempt'] < LOCKOUT_TIME) {
                return true;
            } else {
                unlink($lockFile); // 锁定时间已过，删除记录
            }
        }
    }
    return false;
}

// 记录登录尝试
function recordAttempt($success) {
    $ip = getClientIP();
    $lockDir = "login_attempts";
    if (!is_dir($lockDir)) {
        mkdir($lockDir, 0755, true);
    }
    
    $lockFile = "{$lockDir}/{$ip}.txt";
    $data = ['attempts' => 1, 'last_attempt' => time()];
    
    if (file_exists($lockFile)) {
        $data = json_decode(file_get_contents($lockFile), true);
        if (!$success) {
            $data['attempts']++;
        }
        $data['last_attempt'] = time();
    }
    
    file_put_contents($lockFile, json_encode($data));
}

// 记录错误日志
function logError($message) {
    require_once 'error_log.php';
    writeLog('login_error', [
        'ip' => getClientIP(),
        'time' => date('Y-m-d H:i:s'),
        'message' => $message
    ]);
}

// 验证密码
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $password = isset($input['password']) ? $input['password'] : '';
    
    if (isLocked()) {
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'message' => '登录尝试次数过多，请稍后再试'
        ]);
        exit;
    }
    
    if (password_verify($password . HASH_SALT, PASSWORD_HASH)) {
        // 登录成功
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_time'] = time();
        recordAttempt(true);
        echo json_encode(['success' => true]);
    } else {
        // 登录失败
        recordAttempt(false);
        logError('密码错误尝试');
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => '密码错误'
        ]);
    }
}
?> 