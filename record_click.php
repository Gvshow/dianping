<?php
header('Content-Type: application/json');

// 获取POST数据
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && isset($data['action']) && $data['action'] === 'copy_click') {
    // 获取当前时间
    $timestamp = date('Y-m-d H:i:s');
    
    // 读取现有的点击次数
    $clickCount = 1; // 默认值为1
    if (file_exists('jl.txt')) {
        $lines = file('jl.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines) {
            $clickCount = count($lines) + 1;
        }
    }
    
    // 准备要写入的数据
    $log_entry = $timestamp . " - 第" . $clickCount . "次点击\n";
    
    // 写入数据到文件
    $result = file_put_contents('jl.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
    if ($result === false) {
        echo json_encode(['status' => 'error', 'message' => '无法写入日志']);
    } else {
        echo json_encode(['status' => 'success', 'message' => '记录成功']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '无效的请求']);
}
?> 