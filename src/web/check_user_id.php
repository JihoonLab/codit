<?php
header('Content-Type: application/json; charset=utf-8');
require_once('./include/db_info.inc.php');

$user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';

if (mb_strlen($user_id) < 3) {
    echo json_encode(['status' => 'short']);
    exit;
}

$sql = "SELECT COUNT(*) FROM users WHERE user_id = ?";
$result = pdo_query($sql, $user_id);
$count = intval($result[0][0]);

if ($count > 0) {
    echo json_encode(['status' => 'exists']);
} else {
    echo json_encode(['status' => 'ok']);
}
