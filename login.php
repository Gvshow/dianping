<?php
header('Content-Type: application/json');

// 获取POST数据
$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'] ?? '';

// 验证密码是否为 235133
if ($password === '235133') {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?> 