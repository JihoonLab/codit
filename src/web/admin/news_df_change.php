<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
$id=intval($_GET['id']);
$sql="SELECT `defunct`, `content` FROM `news` WHERE `news_id`=?";
$result=pdo_query($sql,$id);
if (empty($result)) { echo "No such news!"; exit(0); }
$row=$result[0];
$defunct=$row['defunct'];

if ($defunct=='N'){
    // 활성→삭제: 업로드 파일 정리 후 완전 삭제
    $content = $row['content'];
    $webRoot = realpath(dirname(__FILE__) . '/..');
    if (preg_match_all('#/upload/(?:image|file)/[^"\'<>\s]+#', $content, $matches)) {
        foreach ($matches[0] as $relPath) {
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
    pdo_query("DELETE FROM news WHERE news_id=?", $id);
}else{
    // 비활성→활성 복원
    pdo_query("UPDATE news SET defunct='N' WHERE news_id=?", $id);
}
?>
<script language=javascript>
    history.go(-1);
</script>
