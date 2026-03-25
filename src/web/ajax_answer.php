<?php
session_start();
$OJ_NAME = 'Codit';
$OJ_DATA = '/home/judge/data';

// 관리자 체크
if(!isset($_SESSION[$OJ_NAME.'_administrator'])) {
    die('관리자만 접근 가능합니다.');
}

$problem_id = intval($_POST['problem_id'] ?? 0);
$code = $_POST['answer_code'] ?? '';
$lang = $_POST['answer_lang'] ?? 'c';

if($problem_id <= 0 || empty(trim($code))) {
    die('문제 ID와 코드를 입력해주세요.');
}

// 허용 확장자
$allowed = ['c','cc','cpp','py','java'];
if(!in_array($lang, $allowed)) $lang = 'c';

$dir = "$OJ_DATA/$problem_id";
if(!is_dir($dir)) {
    die('문제 데이터 디렉토리가 없습니다.');
}

$filepath = "$dir/answer.$lang";
file_put_contents($filepath, $code);
chmod($filepath, 0640);

header("Location: problem.php?id=$problem_id");
exit;
