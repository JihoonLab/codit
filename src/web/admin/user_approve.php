<?php
/**
 * 사용자 승인 관리 (관리자 전용)
 * - defunct='Y' 인 미승인 사용자 목록
 * - 승인(defunct='N') / 거절(삭제) 처리
 * - 일괄 승인/거절 지원
 */
chdir(dirname(__FILE__) . '/..');
require_once('include/db_info.inc.php');
require_once('include/my_func.inc.php');
require_once('include/setlang.php');

// 관리자 권한 체크
if (!isset($_SESSION[$OJ_NAME . '_administrator'])) {
    echo "<script>alert('관리자 권한이 필요합니다.');location.href='../loginpage.php';</script>";
    exit;
}

$message = '';
$msg_type = '';

// POST 액션 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $user_ids = $_POST['user_ids'] ?? [];

    if (!empty($user_ids) && is_array($user_ids)) {
        // sanitize user_ids
        $user_ids = array_map('trim', $user_ids);
        $user_ids = array_filter($user_ids, function($id) { return $id !== ''; });

        if (!empty($user_ids)) {
            $placeholders = implode(',', array_fill(0, count($user_ids), '?'));

            if ($action === 'approve') {
                $sql = "UPDATE users SET defunct='N' WHERE user_id IN ($placeholders) AND defunct='Y'";
                pdo_query($sql, ...$user_ids);
                $message = count($user_ids) . '명의 사용자를 승인했습니다.';
                $msg_type = 'success';
            } elseif ($action === 'reject') {
                $sql = "DELETE FROM users WHERE user_id IN ($placeholders) AND defunct='Y'";
                pdo_query($sql, ...$user_ids);
                $message = count($user_ids) . '명의 사용자를 거절(삭제)했습니다.';
                $msg_type = 'danger';
            }
        }
    } else {
        $message = '선택된 사용자가 없습니다.';
        $msg_type = 'warning';
    }
}

// 미승인 사용자 목록 조회
$pending_users = pdo_query("SELECT user_id, nick, email, school, reg_time, ip FROM users WHERE defunct='Y' ORDER BY reg_time DESC");
$pending_count = count($pending_users);

require("template/$OJ_TEMPLATE/user_approve.php");
