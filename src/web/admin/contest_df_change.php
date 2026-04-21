<?php
/**
 * [2026-04-21 SAFE] 대회 defunct 토글 (공개 ↔ 숨김)
 * - 기존 버전은 defunct='N'일 때 완전 삭제했음 → 데이터 유실 위험
 * - 이제 순수 토글만 수행 (데이터는 절대 건드리지 않음)
 * - 영구 삭제는 contest_hard_delete.php 에서 이중 확인 후 처리
 */
require_once("admin-header.php");
require_once("../include/check_get_key.php");

$cid = intval($_GET['cid']);

if (!(isset($_SESSION[$OJ_NAME.'_'."m$cid"])
    || isset($_SESSION[$OJ_NAME.'_'.'administrator'])
    || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))) {
    exit();
}

$sql = "SELECT `defunct` FROM `contest` WHERE `contest_id`=?";
$result = pdo_query($sql, $cid);
if (count($result) < 1) {
    echo "No Such Contest!";
    exit(0);
}

$row = $result[0];
// 단순 토글: N ↔ Y (데이터 삭제 없음)
if ($row[0] == 'N') {
    pdo_query("UPDATE `contest` SET `defunct`='Y' WHERE `contest_id`=?", $cid);
} else {
    pdo_query("UPDATE `contest` SET `defunct`='N' WHERE `contest_id`=?", $cid);
}
?>
<script language=javascript>
    history.go(-1);
</script>
