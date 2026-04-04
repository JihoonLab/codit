<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
$user_id=$_GET['cid'];
if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])) exit();
if($_SESSION[$OJ_NAME."_user_id"]==$user_id){
        echo "Can't delete yourself!";
        exit(0);
}
$sql="select `defunct` FROM `users` WHERE `user_id`=?";
$result=pdo_query($sql,$user_id);
$num=count($result);
if ($num<1){
        echo "No Such User!";
        require_once("../oj-footer.php");
        exit(0);
}

// 하드 삭제: 관련 데이터 모두 삭제
$sql="DELETE FROM `solution` WHERE `user_id`=?";
pdo_query($sql,$user_id);
$sql="DELETE FROM `source_code` WHERE `solution_id` NOT IN (SELECT `solution_id` FROM `solution`)";
pdo_query($sql);
$sql="DELETE FROM `users` WHERE `user_id`=?";
pdo_query($sql,$user_id);
?>

<script language=javascript>
        history.go(-1);
</script>
