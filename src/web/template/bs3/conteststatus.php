<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>제출현황 - <?php echo htmlspecialchars($view_title)?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.st-wrap { max-width: 1100px; margin: 32px auto; padding: 0 20px 60px; }

/* 대회 상단 네비바 */
.cs-topbar { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 16px 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.cs-topbar-title { font-size: 16px; font-weight: 900; color: #7c3aed; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cs-topbar-clock { font-size: 13px; font-weight: 700; color: #e74c3c; background: #fff5f5; border: 1px solid #fcc; border-radius: 6px; padding: 4px 12px; flex-shrink: 0; }
.cs-actions { display: flex; gap: 6px; flex-wrap: wrap; }
.cs-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
.cs-btn:hover { text-decoration: none; }
.cs-btn-gray { background: #f0f3f7; color: #555 !important; }
.cs-btn-gray:hover { background: #e2e8f0; }
.cs-btn-active { background: #e8f0fe; color: #7c3aed !important; border: 1.5px solid #7c3aed; }

/* 필터 */
.st-filter { background: #fff; border: 1px solid #e5e9f0; border-radius: 10px; padding: 16px 24px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
.st-filter label { font-size: 13px; color: #666; font-weight: 600; margin: 0; }
.st-filter input, .st-filter select { padding: 7px 12px; border: 1.5px solid #e0e4ea; border-radius: 7px; font-size: 13px; outline: none; min-width: 80px; }
.st-filter input:focus, .st-filter select:focus { border-color: #7c3aed; }
.st-filter button { padding: 8px 20px; background: #7c3aed; color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; }
.st-filter button:hover { background: #6d28d9; }

/* 테이블 */
.st-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.07); }
.st-table thead tr { background: #7c3aed; color: #fff; }
.st-table th { padding: 12px 14px; font-size: 13px; font-weight: 600; text-align: center; white-space: nowrap; }
.st-table td { padding: 11px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; text-align: center; vertical-align: middle; }
.st-table tbody tr:hover { background: #f5f8ff; }
.st-table tbody tr:last-child td { border-bottom: none; }
.st-table td a { color: #7c3aed; text-decoration: none; }
.st-table td a:hover { text-decoration: underline; }

/* 페이지네이션 */
.st-page { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
.st-page a { padding: 7px 16px; border-radius: 7px; font-size: 13px; border: 1px solid #e0e0e0; color: #555; text-decoration: none; }
.st-page a:hover { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="st-wrap">

  <!-- 대회 네비바 -->
  <div class="cs-topbar">
    <div class="cs-topbar-title">📊 <?php echo htmlspecialchars($view_title)?> — 제출현황</div>
    <div class="cs-topbar-clock">🕐 <span id="nowdate"><?php echo date("Y-m-d H:i:s")?></span></div>
    <div class="cs-actions">
      <a href="contest.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">📋 문제</a>
      <a href="status.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-active">📊 제출현황</a>
      <a href="contestrank.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">🏆 순위표</a>
      <a href="conteststatistics.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">📈 통계</a>
      <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])): ?>
      <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $cid?>" class="cs-btn" style="background:#7c3aed;color:#fff">⚙️ 수정</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- 필터 -->
  <form id="simform" class="st-filter" action="status.php" method="get">
    <input type="hidden" name="cid" value="<?php echo $cid?>">
    <label>문제ID</label>
    <input type="text" name="problem_id" value="<?php echo htmlspecialchars($problem_id, ENT_QUOTES)?>" style="width:80px">
    <label>사용자ID</label>
    <input type="text" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES)?>" style="width:100px">
    <label>언어</label>
    <select name="language">
      <option value="-1">All</option>
      <?php
      $selectedLang = isset($_GET['language']) ? intval($_GET['language']) : -1;
      $lang_count = count($language_ext);
      $langmask = $OJ_LANGMASK;
      $lang = (~((int)$langmask))&((1<<($lang_count))-1);
      for($i=0; $i<$lang_count; $i++){
        if($lang&(1<<$i)) echo "<option value=$i ".($selectedLang==$i?'selected':'').">".$language_name[$i]."</option>";
      }?>
    </select>
    <label>채점결과</label>
    <select name="jresult">
      <?php
      $jresult_get = isset($_GET['jresult']) ? intval($_GET['jresult']) : -1;
      if($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
      echo "<option value='-1'".($jresult_get==-1?" selected":"").">All</option>";
      for($j=0;$j<12;$j++){
        $i=($j+4)%12;
        echo "<option value='$i'".($i==$jresult_get?" selected":"").">".$jresult[$i]."</option>";
      }?>
    </select>
    <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'source_browser'])):
      $showsim = isset($_GET['showsim']) ? intval($_GET['showsim']) : 0;?>
    <label>SIM</label>
    <select name="showsim" onchange="document.getElementById('simform').submit()">
      <?php foreach([0,50,60,70,80,90,100] as $v) echo "<option value=$v".($showsim==$v?' selected':'').">".($v==0?'All':$v)."</option>";?>
    </select>
    <?php endif;?>
    <button type="submit">검색</button>
  </form>

  <!-- 테이블 -->
  <table class="st-table">
    <thead>
      <tr>
        <th>채점번호</th>
        <th>사용자ID</th>
        <th>이름</th>
        <th>문제ID</th>
        <th>채점결과</th>
        <th>메모리</th>
        <th>시간</th>
        <th>언어</th>
        <th>코드용량</th>
        <th>제출시간</th>
        <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])) echo "<th>채점서버</th>";?>
      </tr>
    </thead>
    <tbody>
    <?php foreach($view_status as $row):
      $cells = array_values((array)$row);?>
    <tr>
      <?php foreach($cells as $i=>$cell):?>
      <td><?php echo $cell?></td>
      <?php endforeach;?>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>

  <!-- 페이지네이션 -->
  <div class="st-page">
    <a href="status.php?<?php echo $str2?>">« 처음</a>
    <?php if(isset($_GET['prevtop'])): ?>
    <a href="status.php?<?php echo $str2?>&top=<?php echo intval($_GET['prevtop'])?>">‹ 이전</a>
    <?php else: ?>
    <a href="status.php?<?php echo $str2?>&top=<?php echo $top+50?>">‹ 이전</a>
    <?php endif;?>
    <a href="status.php?<?php echo $str2?>&top=<?php echo $bottom?>&prevtop=<?php echo $top?>">다음 ›</a>
  </div>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var judge_result=[<?php foreach($judge_result as $r) echo "'$r',";?>''];
var judge_color=[<?php foreach($judge_color as $r) echo "'$r',";?>''];
var oj_mark='<?php echo $OJ_MARK?>';
var user_id="<?php if(isset($_SESSION[$OJ_NAME."_user_id"])&&$OJ_FANCY_RESULT) echo $_SESSION[$OJ_NAME."_user_id"];?>";
</script>
<script>
$(document).ready(function(){
  $("[id^=del-]").click(function(){
    var num=this.id.slice(4);
    if(confirm("삭제하시겠습니까?")) location.href='status.php?command=del&sid='+num;
  });
});
</script>
<script src="<?php echo $OJ_CDN_URL?>template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js?v=0.41"></script>
<script>
var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime() - new Date().getTime();
function clock() {
  var x = new Date(new Date().getTime() + diff);
  var pad = n => n>=10?n:'0'+n;
  document.getElementById('nowdate').textContent =
    x.getFullYear()+'-'+pad(x.getMonth()+1)+'-'+pad(x.getDate())+' '+pad(x.getHours())+':'+pad(x.getMinutes())+':'+pad(x.getSeconds());
  setTimeout(clock, 1000);
}
clock();
</script>
</body>
</html>
