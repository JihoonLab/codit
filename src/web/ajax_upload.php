<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 세션에서 관리자 확인 (OJ_NAME은 Codit)
$OJ_NAME = 'Codit';
if (!isset($_SESSION[$OJ_NAME.'_administrator'])) {
    die(json_encode(['error'=>1,'message'=>'로그인이 필요합니다.'], JSON_UNESCAPED_UNICODE));
}

if (empty($_FILES['file'])) {
    die(json_encode(['error'=>1,'message'=>'파일이 없습니다.'], JSON_UNESCAPED_UNICODE));
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(['error'=>1,'message'=>'업로드 오류'], JSON_UNESCAPED_UNICODE));
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['pdf','ppt','pptx','doc','docx','xls','xlsx','hwp','hwpx','zip','rar','txt'];
if (!in_array($ext, $allowed)) {
    die(json_encode(['error'=>1,'message'=>'허용되지 않는 파일 형식'], JSON_UNESCAPED_UNICODE));
}

$ymd = date('Ymd');
$saveDir = __DIR__ . '/upload/file/' . $ymd;
if (!is_dir($saveDir)) mkdir($saveDir, 0755, true);

$newName = date('YmdHis') . '_' . rand(10000, 99999) . '.' . $ext;
$savePath = $saveDir . '/' . $newName;

if (move_uploaded_file($file['tmp_name'], $savePath)) {
    chmod($savePath, 0644);
    // 수동으로 JSON 문자열 생성 (인코딩 문제 완전 회피)
    $url = '/upload/file/' . $ymd . '/' . $newName;
    $origName = $file['name'];
    die('{"error":0,"url":"'.$url.'","name":"'.addslashes($origName).'"}');
} else {
    die(json_encode(['error'=>1,'message'=>'파일 저장 실패'], JSON_UNESCAPED_UNICODE));
}
