<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_user_id'])) {
    echo "<script>location.replace('loginpage.php')</script>"; exit(0);
}
$is_admin = isset($_SESSION[$OJ_NAME . '_administrator']);
$edu_id = intval($_GET['id'] ?? 0);
if ($edu_id <= 0) { echo "<script>location.replace('edulist.php')</script>"; exit(0); }
$result = pdo_query("SELECT * FROM edu WHERE edu_id=? AND defunct='N'", $edu_id);
if (empty($result)) { echo "<script>alert('존재하지 않는 교안입니다.'); location.replace('edulist.php')</script>"; exit(0); }
$news = $result[0];
if ($is_admin && isset($_GET['action']) && $_GET['action'] === 'delete') {
    // 1) content에서 업로드 파일 경로 추출 후 실제 파일 삭제
    $content = $news['content'];
    $webRoot = dirname(__FILE__);
    // /upload/image/... 및 /upload/file/... 경로 모두 매칭
    if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $content, $matches)) {
        foreach ($matches[0] as $relPath) {
            $absPath = $webRoot . urldecode($relPath);
            if (is_file($absPath)) {
                @unlink($absPath);
            }
            // 빈 디렉토리도 정리
            $dir = dirname($absPath);
            if (is_dir($dir) && count(glob("$dir/*")) === 0) {
                @rmdir($dir);
            }
        }
    }
    // 2) DB에서 완전 삭제 (소프트 삭제 X)
    pdo_query("DELETE FROM edu WHERE edu_id=?", $edu_id);
    echo "<script>alert('삭제되었습니다.'); location.replace('edulist.php')</script>"; exit(0);
}
require("template/" . $OJ_TEMPLATE . "/eduview.php");
