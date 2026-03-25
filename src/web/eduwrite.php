<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_administrator'])) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.replace('edulist.php')</script>"; exit(0);
}
$edu_id = intval($_GET['id'] ?? 0);
$news = ['title' => '', 'content' => '', 'tag' => ''];
if ($edu_id > 0) {
    $result = pdo_query("SELECT * FROM edu WHERE edu_id=?", $edu_id);
    if (!empty($result)) $news = $result[0];
}

// 기존 태그 목록
$existing_tags = [];
$tag_rows = pdo_query("SELECT DISTINCT tag FROM edu WHERE defunct='N' AND tag!='' ORDER BY tag");
foreach($tag_rows as $r) $existing_tags[] = $r['tag'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $user_id = $_SESSION[$OJ_NAME . '_user_id'];
    if ($title === '' || $content === '') { $error = "제목과 내용을 입력해주세요."; }
    else if ($tag === '') { $error = "태그(과목)를 선택해주세요."; }
    else {
        // 태그 접두사 자동 추가: [정보] 1차시... 형태
        $prefix = '[' . $tag . '] ';
        // 기존 접두사 제거 후 재적용 (태그 변경 대응)
        $title = preg_replace('/^\[.*?\]\s*/', '', $title);
        $title = $prefix . $title;

        if ($edu_id > 0 && !empty($news['content'])) {
            $webRoot = dirname(__FILE__);
            $oldFiles = []; $newFiles = [];
            if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $news['content'], $m)) $oldFiles = $m[0];
            if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $content, $m)) $newFiles = $m[0];
            $removed = array_diff($oldFiles, $newFiles);
            foreach ($removed as $relPath) {
                $absPath = $webRoot . urldecode($relPath);
                if (is_file($absPath)) @unlink($absPath);
                $dir = dirname($absPath);
                if (is_dir($dir) && count(glob("$dir/*")) === 0) @rmdir($dir);
            }
        }
        if ($edu_id > 0) {
            pdo_query("UPDATE edu SET title=?, tag=?, content=?, time=NOW() WHERE edu_id=?", $title, $tag, $content, $edu_id);
        } else {
            pdo_query("INSERT INTO edu (user_id, title, tag, content, time, importance, defunct) VALUES (?, ?, ?, ?, NOW(), 0, 'N')", $user_id, $title, $tag, $content);
        }
        echo "<script>location.replace('edulist.php')</script>"; exit(0);
    }
}
require("template/" . $OJ_TEMPLATE . "/eduwrite.php");
