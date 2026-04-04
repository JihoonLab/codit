<?php
$OJ_CACHE_SHARE = false;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/my_func.inc.php");

if (
    (isset($OJ_EXAM_CONTEST_ID) && $OJ_EXAM_CONTEST_ID > 0) ||
    (isset($OJ_ON_SITE_CONTEST_ID) && $OJ_ON_SITE_CONTEST_ID > 0)
) {
    if (isset($_POST['ajax'])) { echo json_encode(['ok'=>false,'msg'=>'시험 중에는 수정할 수 없습니다.']); exit; }
    $view_errors = $MSG_MODIFY_NOT_ALLOWED_FOR_EXAM;
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit();
}

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    if (isset($_POST['ajax'])) { echo json_encode(['ok'=>false,'msg'=>'로그인이 필요합니다.']); exit; }
    $view_errors = "<a href=loginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit();
}

$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];
$action = $_POST['action'] ?? '';
header('Content-Type: application/json; charset=utf-8');

// === 프로필 수정 (비밀번호 불필요) ===
if ($action === 'profile') {
    $nick = trim($_POST['nick'] ?? '');
    $school = trim($_POST['school'] ?? '');
    $student_no = trim($_POST['student_no'] ?? '');

    if (mb_strlen($nick) > 100) { echo json_encode(['ok'=>false,'msg'=>'이름이 너무 깁니다.']); exit; }
    if ($nick === '') $nick = $user_id;
    if (mb_strlen($school) > 100) { echo json_encode(['ok'=>false,'msg'=>'학년/반 정보가 너무 깁니다.']); exit; }
    if (has_bad_words($nick)) { echo json_encode(['ok'=>false,'msg'=>'이름에 부적절한 단어가 포함되어 있습니다.']); exit; }
    if (has_bad_words($school)) { echo json_encode(['ok'=>false,'msg'=>'학년/반에 부적절한 단어가 포함되어 있습니다.']); exit; }

    $nick = htmlentities($nick, ENT_QUOTES, "UTF-8");
    $school = htmlentities($school, ENT_QUOTES, "UTF-8");
    $student_no = htmlentities($student_no, ENT_QUOTES, "UTF-8");

    $sql = "UPDATE `users` SET `nick`=?, `student_no`=?, `school`=? WHERE `user_id`=?";
    pdo_query($sql, $nick, $student_no, $school, $user_id);

    if ($nick !== '') {
        pdo_query("UPDATE solution SET nick=? WHERE user_id=?", $nick, $user_id);
    }

    $_SESSION[$OJ_NAME . '_nick'] = $nick;
    echo json_encode(['ok'=>true,'msg'=>'프로필이 저장되었습니다.']);
    exit;
}

// === 비밀번호 변경 (현재 비밀번호 필요) ===
if ($action === 'password') {
    $opassword = $_POST['opassword'] ?? '';
    $npassword = $_POST['npassword'] ?? '';
    $rptpassword = $_POST['rptpassword'] ?? '';

    if ($opassword === '') { echo json_encode(['ok'=>false,'msg'=>'현재 비밀번호를 입력해주세요.']); exit; }

    $sql = "SELECT `password` FROM `users` WHERE `user_id`=?";
    $result = pdo_query($sql, $user_id);
    if (empty($result) || !pwCheck($opassword, $result[0]['password'])) {
        echo json_encode(['ok'=>false,'msg'=>'현재 비밀번호가 일치하지 않습니다.']);
        exit;
    }

    if (strlen($npassword) < 32) { // MD5 해시는 32자 — 클라이언트에서 MD5 변환 전 길이 체크
        echo json_encode(['ok'=>false,'msg'=>'새 비밀번호는 6자 이상이어야 합니다.']);
        exit;
    }
    if ($npassword !== $rptpassword) {
        echo json_encode(['ok'=>false,'msg'=>'새 비밀번호가 일치하지 않습니다.']);
        exit;
    }

    $newHash = pwGen($npassword, True);
    pdo_query("UPDATE `users` SET `password`=? WHERE `user_id`=?", $newHash, $user_id);

    echo json_encode(['ok'=>true,'msg'=>'비밀번호가 변경되었습니다.']);
    exit;
}

echo json_encode(['ok'=>false,'msg'=>'잘못된 요청입니다.']);
?>
