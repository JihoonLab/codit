<?php
require("admin-header.php");
require_once("../include/set_get_key.php");
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>
<title>User List</title>
<hr>
<center><h3><?php echo $MSG_USER."-".$MSG_LIST?></h3></center>
<div class='' style="overflow:auto">
<?php
$sql = "select COUNT('user_id') AS ids FROM `users`";
$result = pdo_query($sql);
$row = $result[0];
$ids = intval($row['ids']);
$idsperpage = 25;
$pages = intval(ceil($ids/$idsperpage));
if(isset($_GET['page'])){ 
  $page = intval($_GET['page']);
}else{ 
  $page = 1;
}
$pagesperframe = 5;
$frame = intval(ceil($page/$pagesperframe));
$spage = ($frame-1)*$pagesperframe+1;
$epage = min($spage+$pagesperframe-1, $pages);
$sid = ($page-1)*$idsperpage;
$sql = "";
$gkeyword="";
$trash="";
if(isset($_GET['keyword']) && $_GET['keyword']!=""){
  $gkeyword = $_GET['keyword'];
  $keyword = "%$gkeyword%";
  $sql = "select `user_id`,`nick`,`student_no`,`accesstime`,`reg_time`,`school`,`defunct`,`ai_group` FROM `users` WHERE (user_id LIKE ?) OR (nick LIKE ?) OR (school LIKE ?) ORDER BY `user_id` DESC";
  $result = pdo_query($sql,$keyword,$keyword,$keyword);
}else if(isset($_GET['trash'])){
  $trash="&trash";
  $sql = "select `user_id`,`nick`,`student_no`,`accesstime`,`reg_time`,`school`,`defunct`,`ai_group` FROM `users` where defunct='Y' ORDER BY `accesstime` DESC LIMIT $sid, $idsperpage";
  $result = pdo_query($sql);
}else{
  $sql = "select `user_id`,`nick`,`student_no`,`accesstime`,`reg_time`,`school`,`defunct`,`ai_group` FROM `users` where defunct='N' ORDER BY `accesstime` DESC LIMIT $sid, $idsperpage";
  $result = pdo_query($sql);
}
?>

<center>
<form action=user_list.php class="form-search form-inline">
  <input type="text" name="keyword"  value="<?php echo htmlentities($gkeyword,ENT_QUOTES) ?>"  class="form-control search-query" placeholder="<?php echo $MSG_USER_ID.', '.$MSG_NICK.', '.$MSG_SCHOOL?>">
  <button type="submit" class="form-control"><?php echo $MSG_SEARCH?></button>
  <a href="user_list.php?trash" title="<?php echo $MSG_VIEW_DISABLED_USER?>" ><i class='icon large trash color grey' ></i></a>
</form>
</center>

<center>
  <table width=100% border=1 style="text-align:center;" class="ui striped aligned table">
<thead>
    <tr>
      <th><?php echo $MSG_USER_ID?></th>
      <th><?php echo $MSG_NICK?></th>
      <th><?php echo $MSG_SCHOOL?></th>
      <th>번호</th>
      <th>AI분반</th>
      <th><?php echo $MSG_LAST_LOGIN?></th>
      <th><?php echo $MSG_REGISTER?></th>
      <th><?php echo $MSG_STATUS?></th>
      <th><?php echo $MSG_ADMIN ?></th>
      <th><?php echo $MSG_SETPASSWORD?></th>
      <th><?php echo $MSG_PRIVILEGE."-".$MSG_ADD ?></th>
    </tr>
</thead>

    <?php
    foreach($result as $row){
      echo "<tr>";
        echo "<td><a href='../userinfo.php?user=".htmlentities(urlencode($row['user_id']))."'>".$row['user_id']."</a></td>";
        if($row['nick']=="") $row['nick']="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<td><span fd='nick' user_id='".$row['user_id']."'>".$row['nick']."</span></td>";
        if($row['school']=="") $row['school']="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<td><span fd='school' user_id='".$row['user_id']."'>".$row['school']."</span></td>";
        $sno = $row['student_no'] ?? '';
        if($sno=="") $sno="&nbsp;";
        echo "<td><span fd='student_no' user_id='".$row['user_id']."'>".$sno."</span></td>";
        $aig = intval($row['ai_group'] ?? 0);
        $aig_labels = ['', 'AI-1', 'AI-2', 'AI-3'];
        $aig_colors = ['#9ca3af', '#22c55e', '#3b82f6', '#f59e0b'];
        echo "<td><select class='ai-group-sel' data-uid='".$row['user_id']."' style='border:none;font-weight:700;color:".$aig_colors[$aig].";background:transparent;cursor:pointer;font-size:12px;'>";
        echo "<option value='0'".($aig==0?" selected":"")." style='color:#9ca3af'>미배정</option>";
        echo "<option value='1'".($aig==1?" selected":"")." style='color:#22c55e'>AI-1 박지훈</option>";
        echo "<option value='2'".($aig==2?" selected":"")." style='color:#3b82f6'>AI-2 박지훈</option>";
        echo "<option value='3'".($aig==3?" selected":"")." style='color:#f59e0b'>AI-3 안예찬</option>";
        echo "</select></td>";
        echo "<td>".$row['accesstime']."</td>";
        echo "<td>".$row['reg_time']."</td>";

        echo "<td>".($row['defunct']=="N"?"<span class=green >$MSG_NORMAL</span>":"<span class=red>$MSG_DELETED</span>")."</td>";
      if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) && $row['user_id']!=$_SESSION[$OJ_NAME."_user_id"]){
        echo "<td><a href='#' onclick=\"if(confirm('".$row['user_id']." 계정을 완전히 삭제합니다. 복구할 수 없습니다. 정말 삭제하시겠습니까?')){location.href='user_df_change.php?cid=".$row['user_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."';}return false;\">".
           "<span class='label label-danger'>삭제</span>"
            ."</a></td>";
      }else{
      	   echo "<td>&nbsp;</td>";
      }
        echo "<td><a class='label label-warning' href='#' onclick=\"if(confirm('".$row['user_id']." 비밀번호를 123456으로 초기화합니다.')){resetPw('".$row['user_id']."');}return false;\">".$MSG_RESET."</a></td>";
        echo "<td><a class='label label-success' href=privilege_add.php?uid=".$row['user_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".$MSG_ADD."</a></td>";
      echo "</tr>";
    } ?>
  </table>
</center>

<?php
if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
{
  echo "<div style='display:inline;'>";
  echo "<nav class='center'>";
  echo "<ul class='pagination pagination-sm'>";
  echo "<li class='page-item'><a href='user_list.php?page=".(strval(1))."$trash'>&lt;&lt;</a></li>";
  echo "<li class='page-item'><a href='user_list.php?page=".($page==1?strval(1):strval($page-1))."$trash'>&lt;</a></li>";
  for($i=$spage; $i<=$epage; $i++){
    echo "<li class='".($page==$i?"active ":"")."page-item'><a title='go to page' href='user_list.php?page=".$i."$trash'>".$i."</a></li>";
  }
  echo "<li class='page-item'><a href='user_list.php?page=".($page==$pages?strval($page):strval($page+1))."$trash'>&gt;</a></li>";
  echo "<li class='page-item'><a href='user_list.php?page=".(strval($pages))."$trash'>&gt;&gt;</a></li>";
  echo "</ul>";
  echo "</nav>";
  echo "</div>";
}
?>

</div>
<script>
function admin_mod(){
        $("span[fd=school]").each(function(){
                let sp=$(this);
                let user_id=$(this).attr('user_id');
                $(this).dblclick(function(){
                        let school=sp.text();
                        sp.html("<form onsubmit='return false;'><input type=hidden name='m' value='user_update_school'><input type='hidden' name='user_id' value='"+user_id+"'><input type='text' name='school' value='"+school+"' selected='true' class='input-large' size=20 ></form>");
                        let ipt=sp.find("input[name=school]");
                        ipt.focus();
                        ipt[0].select();
                        sp.find("input").change(function(){
                                let newschool=sp.find("input[name=school]").val();
                                $.post("ajax.php",sp.find("form").serialize()).done(function(){
                                        console.log("new school"+newschool);
                                        sp.html(newschool);
                                });

                        });
                });
        });

        $("span[fd=nick]").each(function(){
                let sp=$(this);
                let user_id=$(this).attr('user_id');
                $(this).dblclick(function(){
                        let nick=sp.text();
                        console.log("user_id:"+user_id+"  nick:"+nick);
                        sp.html("<form onsubmit='return false;'><input type=hidden name='m' value='user_update_nick'><input type='hidden' name='user_id' value='"+user_id+"'><input type='text' name='nick' value='"+nick+"' selected='true' class='input-mini' size=2 ></form>");
                        let ipt=sp.find("input[name=nick]");
                        ipt.focus();
                        ipt[0].select();
                        sp.find("input").change(function(){
                                let newnick=sp.find("input[name=nick]").val();
                                $.post("ajax.php",sp.find("form").serialize()).done(function(){
                                        console.log("new nick:"+newnick);
                                        sp.html(newnick);
                                });

                        });
                });


        });

}
function resetPw(uid){
        $.post("ajax.php", {m:'reset_password', user_id:uid}).done(function(resp){
                if(resp==='OK'){
                        alert(uid+' 비밀번호가 123456으로 초기화되었습니다.');
                }else{
                        alert('초기화 실패: '+resp);
                }
        }).fail(function(){
                alert('서버 오류가 발생했습니다.');
        });
}
$(document).ready(function(){
        admin_mod();
        // AI분반 드롭다운 변경 이벤트
        $(document).on('change', '.ai-group-sel', function(){
                var sel = $(this);
                var uid = sel.data('uid');
                var val = sel.val();
                var colors = ['#9ca3af', '#22c55e', '#3b82f6', '#f59e0b'];
                $.post('ajax.php', {m:'user_update_ai_group', user_id:uid, ai_group:val}).done(function(resp){
                        sel.css('color', colors[val]);
                });
        });
});

</script>
