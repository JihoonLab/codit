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
$rows = pdo_query("SELECT edu_id, title, user_id, time FROM edu WHERE defunct='N' ORDER BY importance DESC, time DESC LIMIT $per_page OFFSET $offset");
require("template/" . $OJ_TEMPLATE . "/edulist.php");
