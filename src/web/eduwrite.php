<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_administrator'])) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.replace('edulist.php')</script>"; exit(0);
}
$edu_id = intval($_GET['id'] ?? 0);
$news = ['title' => '', 'content' => ''];
if ($edu_id > 0) {
    $result = pdo_query("SELECT * FROM edu WHERE edu_id=?", $edu_id);
    if (!empty($result)) $news = $result[0];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION[$OJ_NAME . '_user_id'];
    if ($title === '' || $content === '') { $error = "제목과 내용을 입력해주세요."; }
    else {
        // 수정 시 이전 content에 있던 파일 중 새 content에 없는 파일 삭제
        if ($edu_id > 0 && !empty($news['content'])) {
            $webRoot = dirname(__FILE__);
            $oldFiles = [];
            $newFiles = [];
            if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $news['content'], $m)) {
                $oldFiles = $m[0];
            }
            if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $content, $m)) {
                $newFiles = $m[0];
            }
            // 이전에는 있었지만 새 내용에는 없는 파일 → 삭제
            $removed = array_diff($oldFiles, $newFiles);
            foreach ($removed as $relPath) {
                $absPath = $webRoot . urldecode($relPath);
                if (is_file($absPath)) {
                    @unlink($absPath);
                }
                $dir = dirname($absPath);
                if (is_dir($dir) && count(glob("$dir/*")) === 0) {
                    @rmdir($dir);
                }
            }
        }

        if ($edu_id > 0) {
            pdo_query("UPDATE edu SET title=?, content=?, time=NOW() WHERE edu_id=?", $title, $content, $edu_id);
        } else {
            pdo_query("INSERT INTO edu (user_id, title, content, time, importance, defunct) VALUES (?, ?, ?, NOW(), 0, 'N')", $user_id, $title, $content);
        }
        echo "<script>location.replace('edulist.php')</script>"; exit(0);
    }
}
require("template/" . $OJ_TEMPLATE . "/eduwrite.php");
