<?php
header('Content-Type: application/json');

// 获取POST数据
$data = file_get_contents('php://input');
$texts = json_decode($data);

// 保存到文件
if (file_put_contents('texts.json', json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?> 