<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>제출현황 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
/* === Status Page === */
.st-wrap {
  max-width: 1200px;
  margin: 32px auto;
  padding: 0 20px;
  font-family: 'Noto Sans KR', -apple-system, BlinkMacSystemFont, sans-serif;
}
.st-title {
  font-size: 24px;
  font-weight: 700;
  color: #7c3aed;
  margin: 0 0 24px;
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Filter */
.st-filter-card {
  background: #fff;
  border-radius: 12px;
  padding: 16px 24px;
  margin-bottom: 20px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  border: 1px solid #e8ecf1;
}
.st-filter {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}
.st-filter .filter-group {
  display: flex;
  align-items: center;
  gap: 6px;
}
.st-filter label {
  font-size: 13px;
  color: #777;
  font-weight: 600;
  margin: 0;
  white-space: nowrap;
}
.st-filter input[type="text"],
.st-filter select {
  padding: 7px 12px;
  border: 1.5px solid #dce1e8;
  border-radius: 8px;
  font-size: 13px;
  outline: none;
  background: #f8f9fb;
  color: #333;
  transition: all 0.2s;
}
.st-filter input[type="text"] { width: 90px; }
.st-filter input[type="text"]:focus,
.st-filter select:focus {
  border-color: #7c3aed;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(26,111,196,0.1);
}
.st-filter .filter-spacer { flex: 1; }
.st-filter-btn {
  padding: 8px 24px;
  background: #7c3aed;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.st-filter-btn:hover { background: #1259a3; }

/* Table */
.st-table-wrap {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  border: 1px solid #e8ecf1;
}
.st-table {
  width: 100%;
  border-collapse: collapse;
}
.st-table thead th {
  padding: 13px 14px;
  font-size: 13px;
  font-weight: 700;
  text-align: center;
  white-space: nowrap;
  color: #fff;
  background: #7c3aed;
}
.st-table td {
  padding: 12px 14px;
  font-size: 13px;
  border-bottom: 1px solid #f0f2f5;
  text-align: center;
  vertical-align: middle;
  color: #444;
}
.st-table tbody tr {
  transition: background 0.15s;
}
.st-table tbody tr:hover {
  background: #f5f8fc;
}
.st-table tbody tr:last-child td {
  border-bottom: none;
}
.st-table td a {
  color: #7c3aed;
  text-decoration: none;
  font-weight: 500;
}
.st-table td a:hover {
  text-decoration: underline;
}

/* Result badges */
.st-table .label {
  display: inline-block !important;
  padding: 5px 12px !important;
  border-radius: 20px !important;
  font-size: 12px !important;
  font-weight: 600 !important;
  line-height: 1.3 !important;
  text-decoration: none !important;
  border: none !important;
}
.st-table a.label:hover { text-decoration: none !important; }
.st-table .label.label-success { background: #34c759 !important; color: #fff !important; }
.st-table .label.label-danger { background: #ff3b30 !important; color: #fff !important; }
.st-table .label.label-warning { background: #ff9500 !important; color: #fff !important; }
.st-table .label.label-info { background: #5ac8fa !important; color: #fff !important; }
.st-table .label.label-primary { background: #007aff !important; color: #fff !important; }
.st-table .label.gray,
.st-table .label.lable-gray { background: #8e8e93 !important; color: #fff !important; }

/* Edit button */
.st-edit-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 5px 14px;
  background: #f0f5ff;
  color: #7c3aed !important;
  border: 1.5px solid #d0e0f5;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none !important;
  white-space: nowrap;
  transition: all 0.2s;
}
.st-edit-btn:hover {
  background: #7c3aed;
  color: #fff !important;
  border-color: #7c3aed;
  text-decoration: none !important;
}

.td_result { position: relative; }

/* Pagination */
.st-page {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin: 24px 0;
}
.st-page a {
  padding: 8px 18px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  border: 1.5px solid #e0e5ec;
  color: #555;
  text-decoration: none;
  background: #fff;
  transition: all 0.2s;
}
.st-page a:hover {
  background: #7c3aed;
  color: #fff;
  border-color: #7c3aed;
}

@media (max-width: 768px) {
  .st-wrap { padding: 0 12px; margin: 16px auto; }
  .st-title { font-size: 20px; }
  .st-filter { gap: 8px; }
  .st-filter input[type="text"] { width: 70px; }
  .st-table-wrap { border-radius: 10px; overflow-x: auto; }
  .st-table th, .st-table td { padding: 10px 8px; font-size: 12px; }
  .st-edit-btn { padding: 4px 10px; font-size: 11px; }
}

.http_judge_form { display: inline; }
.http_judge_form .form-control { display: inline-block; width: auto; font-size: 12px; padding: 3px 6px; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="st-wrap">
  <h2 class="st-title"><span>📋</span> 제출현황</h2>

  <div class="st-filter-card">
    <form id="simform" class="st-filter" action="status.php" method="get">
      <?php if(isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
      <div class="filter-group">
        <label>문제</label>
        <input type="text" name="problem_id" value="<?php echo htmlspecialchars($problem_id, ENT_QUOTES)?>" placeholder="ID">
      </div>
      <div class="filter-group">
        <label>사용자</label>
        <input type="text" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES)?>" placeholder="ID">
      </div>
      <div class="filter-group">
        <label>언어</label>
        <select name="language">
          <option value="-1">전체</option>
          <?php
          $selectedLang = isset($_GET['language']) ? intval($_GET['language']) : -1;
          $lang_count = count($language_ext);
          $langmask = $OJ_LANGMASK;
          $lang = (~((int)$langmask))&((1<<($lang_count))-1);
          for($i=0;$i<$lang_count;$i++){
            if($lang&(1<<$i)) echo "<option value=$i ".($selectedLang==$i?'selected':'').">".$language_name[$i]."</option>";
          }?>
        </select>
      </div>
      <div class="filter-group">
        <label>결과</label>
        <select name="jresult">
          <?php
          $jresult_get = isset($_GET['jresult']) ? intval($_GET['jresult']) : -1;
          if($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
          echo "<option value='-1'".($jresult_get==-1?" selected":"").">전체</option>";
          for($j=0;$j<12;$j++){
            $i=($j+4)%12;
            echo "<option value='$i'".($i==$jresult_get?" selected":"").">".$jresult[$i]."</option>";
          }?>
        </select>
      </div>
      <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'source_browser'])):
        $showsim = isset($_GET['showsim']) ? intval($_GET['showsim']) : 0;?>
      <div class="filter-group">
        <label>SIM</label>
        <select name="showsim" onchange="document.getElementById('simform').submit()">
          <?php foreach([0,50,60,70,80,90,100] as $v) echo "<option value=$v".($showsim==$v?' selected':'').">".($v==0?'전체':$v)."</option>";?>
        </select>
      </div>
      <?php endif;?>
      <div class="filter-spacer"></div>
      <button type="submit" class="st-filter-btn">검색</button>
    </form>
  </div>

  <div class="st-table-wrap">
    <table class="st-table" id="result-tab">
      <thead>
        <tr>
          <th>#</th>
          <th>사용자</th>
          <th>이름</th>
          <th>문제</th>
          <th>결과</th>
          <th>메모리</th>
          <th>시간</th>
          <th>언어</th>
          <th></th>
          <th>제출시간</th>
          
        </tr>
      </thead>
      <tbody>
      <?php foreach($view_status as $row):
        $cells = array_values((array)$row);?>
      <tr>
        <?php foreach($cells as $i=>$cell):?>
        <td<?php if($i==4) echo " class='td_result'";?>><?php echo $cell?></td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
  </div>

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
</body>
</html>
