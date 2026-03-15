<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
$cid=intval($_GET['cid']);
if(!(isset($_SESSION[$OJ_NAME.'_'."m$cid"]) || isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))) exit();

$sql="SELECT `defunct` FROM `contest` WHERE `contest_id`=?";
$result=pdo_query($sql,$cid);
if (count($result)<1){
    echo "No Such Contest!";
    exit(0);
}
$row=$result[0];
if ($row[0]=='N'){
    // 활성→비활성 토글이면 완전 삭제
    pdo_query("DELETE FROM contest_problem WHERE contest_id=?", $cid);
    pdo_query("DELETE FROM contest WHERE contest_id=?", $cid);
}else{
    // 비활성→활성 복원
    pdo_query("UPDATE contest SET defunct='N' WHERE contest_id=?", $cid);
}
?>
<script language=javascript>
    history.go(-1);
</script>
