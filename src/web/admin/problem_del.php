<?php
 require_once("admin-header.php");
ini_set("display_errors","On");
require_once("../include/check_get_key.php");
$pid=intval($_GET['id']);
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'."p".$pid]) )){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

?> 
<?php
function recursiveDelete($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    recursiveDelete($path);
                } else {
                    unlink($path);
                }
            }
        }
        rmdir($dir);
    }
}

// 使用示例
  $id=intval($_GET['id']);
  if($id>0&&strlen($OJ_DATA)>8){
        $basedir = "$OJ_DATA/$id";
	if(strlen($basedir)>16&&$id>0){
			//system("rm -rf $basedir");
			recursiveDelete($basedir);
	}
        // 1. 해당 문제의 모든 solution_id 수집
        $sol_rows = pdo_query("SELECT solution_id FROM solution WHERE problem_id=?", $id);
        if(!empty($sol_rows)) {
          $sol_ids = array_column($sol_rows, 'solution_id');
          $sol_ids_str = implode(',', array_map('intval', $sol_ids));
          // 2. solution 연관 데이터 일괄 삭제
          pdo_query("DELETE FROM source_code WHERE solution_id IN ($sol_ids_str)");
          pdo_query("DELETE FROM source_code_user WHERE solution_id IN ($sol_ids_str)");
          pdo_query("DELETE FROM runtimeinfo WHERE solution_id IN ($sol_ids_str)");
          pdo_query("DELETE FROM compileinfo WHERE solution_id IN ($sol_ids_str)");
          pdo_query("DELETE FROM sim WHERE s_id IN ($sol_ids_str) OR s_id_2 IN ($sol_ids_str)");
          pdo_query("DELETE FROM solution_ai_answer WHERE solution_id IN ($sol_ids_str)");
          pdo_query("DELETE FROM custominput WHERE solution_id IN ($sol_ids_str)");
          // 3. solutions 삭제
          pdo_query("DELETE FROM solution WHERE problem_id=?", $id);
        }
        // 4. 문제 자체 삭제
        pdo_query("DELETE FROM problem WHERE problem_id=?", $id);
        // 5. 권한 삭제
        pdo_query("DELETE FROM privilege WHERE rightstr=?", "p$id");
        // 6. 수업/대회에서 문제 제거
        pdo_query("DELETE FROM class_problem WHERE problem_id=?", $id);
        pdo_query("DELETE FROM contest_problem WHERE problem_id=?", $id);
	  
        $sql="select max(problem_id) FROM `problem`" ;
        $result=pdo_query($sql);
        $row=$result[0];
        $max_id=$row[0];
        $max_id++;
        if($max_id<1000)$max_id=1000;
        
        $sql="ALTER TABLE problem AUTO_INCREMENT = $max_id";
        pdo_query($sql);
        ?>
        <script language=javascript>
                history.go(-1);
        </script>
<?php 
  }else{
  
  
  ?>
        <script language=javascript>
                alert("Nees enable system() in php.ini");
                history.go(-1);
        </script>
  <?php 
  
  }

?>
