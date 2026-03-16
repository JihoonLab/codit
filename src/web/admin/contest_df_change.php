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
    // 1. 대회 관련 solutions의 연관 데이터 삭제
    $sol_rows = pdo_query("SELECT solution_id FROM solution WHERE contest_id=?", $cid);
    if(!empty($sol_rows)) {
      $sol_ids = array_column($sol_rows, 'solution_id');
      $sol_ids_str = implode(',', array_map('intval', $sol_ids));
      pdo_query("DELETE FROM source_code WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM source_code_user WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM runtimeinfo WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM compileinfo WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM sim WHERE s_id IN ($sol_ids_str) OR s_id_2 IN ($sol_ids_str)");
      pdo_query("DELETE FROM solution_ai_answer WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM custominput WHERE solution_id IN ($sol_ids_str)");
      pdo_query("DELETE FROM solution WHERE contest_id=?", $cid);
    }
    // 2. 대회 관련 테이블 삭제
    pdo_query("DELETE FROM contest_problem WHERE contest_id=?", $cid);
    pdo_query("DELETE FROM balloon WHERE cid=?", $cid);
    pdo_query("DELETE FROM privilege WHERE rightstr=?", "m$cid");
    pdo_query("DELETE FROM contest WHERE contest_id=?", $cid);
}else{
    // 비활성→활성 복원
    pdo_query("UPDATE contest SET defunct='N' WHERE contest_id=?", $cid);
}
?>
<script language=javascript>
    history.go(-1);
</script>
