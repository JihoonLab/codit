<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_user_id'])) {
    echo "<script>location.replace('loginpage.php')</script>"; exit(0);
}
$is_admin = isset($_SESSION[$OJ_NAME . '_administrator']);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;
$total_rows = pdo_query("SELECT COUNT(*) cnt FROM edu WHERE defunct='N'");
$total = $total_rows[0]['cnt'] ?? 0;
$total_pages = max(1, ceil($total / $per_page));
$rows = pdo_query("SELECT edu_id, title, tag, user_id, time FROM edu WHERE defunct='N' ORDER BY importance DESC, time ASC LIMIT $per_page OFFSET $offset");

// 태그 목록 수집 (정보 → 인공지능기초 순서 고정, 나머지는 가나다순)
$priority_tags = ['정보', '인공지능기초'];
$all_tags = [];
$all_rows = pdo_query("SELECT DISTINCT tag FROM edu WHERE defunct='N' AND tag!='' ORDER BY tag");
$db_tags = [];
foreach($all_rows as $r) $db_tags[] = $r['tag'];
// 우선순위 태그 먼저
foreach($priority_tags as $pt) {
    if(in_array($pt, $db_tags)) $all_tags[] = $pt;
}
// 나머지 태그
foreach($db_tags as $dt) {
    if(!in_array($dt, $priority_tags)) $all_tags[] = $dt;
}

require("template/" . $OJ_TEMPLATE . "/edulist.php");
