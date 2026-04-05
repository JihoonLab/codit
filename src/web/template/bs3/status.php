<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>도전현황 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Inter','Noto Sans KR',sans-serif; background: #f0f2f5; margin: 0; }

.st-wrap { max-width: 1100px; margin: 0 auto; padding: 32px 20px 48px; }
.st-header { margin-bottom: 24px; }
.st-header h2 { font-size: 24px; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.5px; }
.st-header h2 em { color: #7c3aed; font-style: normal; }

/* Filter */
.st-filter-card {
  background: #fff; border-radius: 16px; padding: 16px 24px; margin-bottom: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #e5e7eb;
}
.st-filter { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.st-filter .fg { display: flex; align-items: center; gap: 6px; }
.st-filter label { font-size: 12px; color: #9ca3af; font-weight: 700; margin: 0; white-space: nowrap; }
.st-filter input[type="text"], .st-filter select {
  padding: 7px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px;
  font-size: 13px; font-weight: 500; outline: none; background: #f9fafb; color: #374151; transition: all 0.2s;
}
.st-filter input[type="text"] { width: 80px; }
.st-filter input:focus, .st-filter select:focus { border-color: #7c3aed; background: #fff; box-shadow: 0 0 0 3px rgba(124,58,237,0.08); }
.st-filter .spacer { flex: 1; }
.st-filter-btn {
  padding: 8px 24px; background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(124,58,237,0.25);
}
.st-filter-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(124,58,237,0.35); }

/* Table */
.st-card {
  background: #fff; border-radius: 16px; overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
  border: 1px solid #e5e7eb;
}
.st-tbl { width: 100%; border-collapse: collapse; }
.st-tbl thead th {
  padding: 12px 12px; font-size: 11px; font-weight: 700; text-align: center;
  color: #fff; background: linear-gradient(135deg, #7c3aed, #6d28d9);
  letter-spacing: 0.5px; text-transform: uppercase; white-space: nowrap;
}
.st-tbl tbody tr {
  border-bottom: 1px solid #f3f4f6; transition: all 0.15s;
}
.st-tbl tbody tr:last-child { border-bottom: none; }
.st-tbl tbody tr:hover { background: #faf5ff; }
.st-tbl td {
  padding: 10px 12px; font-size: 13px; text-align: center;
  vertical-align: middle; color: #374151;
}

/* AC 행 */
.st-tbl tr.r-ac { background: #f0fdf4; }
.st-tbl tr.r-ac:hover { background: #dcfce7; }
/* WA 행 */
.st-tbl tr.r-wa { background: #fff; }

/* 채점번호 */
.td-sid { font-size: 12px; color: #c4b5fd; font-weight: 700; font-family: 'Inter', monospace; white-space: nowrap; }
.td-sid a { color: #9ca3af; text-decoration: none; font-size: 12px; }
.td-sid a:hover { color: #7c3aed; }
.td-sid a.text-danger { color: #fca5a5; }
.td-sid a.text-danger:hover { color: #ef4444; }

/* 사용자 */
.td-user { text-align: left !important; white-space: nowrap; }
.td-user a { color: #7c3aed; font-weight: 700; font-size: 13px; text-decoration: none; }
.td-user a:hover { text-decoration: underline; }
.td-user .nick { color: #94a3b8; font-size: 11px; font-weight: 500; margin-top: 1px; }

/* 문제번호 */
.td-prob a {
  display: inline-block; background: #f5f3ff; color: #7c3aed;
  font-weight: 800; font-size: 14px; padding: 3px 12px; border-radius: 6px;
  text-decoration: none; transition: all 0.15s;
}
.td-prob a:hover { background: #7c3aed; color: #fff; }

/* 결과 배지 */
.res-badge {
  display: inline-block; padding: 4px 12px; border-radius: 16px;
  font-size: 12px; font-weight: 700; text-decoration: none !important;
  white-space: nowrap; transition: transform 0.1s;
}
.res-badge:hover { transform: scale(1.05); }
.rb-ac { background: #dcfce7; color: #15803d; }
.rb-wa { background: #fee2e2; color: #dc2626; }
.rb-ce { background: #fef3c7; color: #b45309; }
.rb-tle { background: #dbeafe; color: #1d4ed8; }
.rb-mle { background: #e0e7ff; color: #4338ca; }
.rb-re { background: #ede9fe; color: #6d28d9; }
.rb-pe { background: #ffedd5; color: #c2410c; }
.rb-ole { background: #fce7f3; color: #be185d; }
.rb-pending { background: #f3f4f6; color: #9ca3af; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

/* 메모리/시간 */
.td-perf { font-size: 12px; color: #64748b; font-weight: 600; font-family: 'Inter', monospace; white-space: nowrap; }

/* 언어 */
.td-lang { font-size: 12px; color: #64748b; font-weight: 600; }
.td-lang a { color: #64748b; text-decoration: none; }
.td-lang a:hover { color: #7c3aed; }

/* 코드수정 */
.st-edit-btn {
  display: inline-flex; align-items: center; gap: 3px;
  padding: 4px 12px; background: #f5f3ff; color: #7c3aed !important;
  border: 1.5px solid #e9e5f5; border-radius: 6px;
  font-size: 11px; font-weight: 700; text-decoration: none !important;
  white-space: nowrap; transition: all 0.2s;
}
.st-edit-btn:hover { background: #7c3aed; color: #fff !important; border-color: #7c3aed; }

/* 시간 */
.td-date { font-size: 11px; color: #c4b5fd; font-weight: 500; font-family: 'Inter', monospace; white-space: nowrap; }

/* Pagination */
.st-page { display: flex; justify-content: center; gap: 6px; margin-top: 24px; }
.st-page a {
  padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
  border: 1.5px solid #e5e7eb; color: #6b7280; text-decoration: none;
  background: #fff; transition: all 0.15s;
}
.st-page a:hover { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }

.http_judge_form { display: inline; }
.http_judge_form .form-control { display: inline-block; width: auto; font-size: 12px; padding: 3px 6px; }

@media (max-width: 768px) {
  .st-wrap { padding: 16px 12px 32px; }
  .st-card { border-radius: 12px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
  .st-tbl { min-width: 700px; }
  .st-tbl th, .st-tbl td { padding: 8px 8px; }
}
@media (max-width: 480px) {
  .st-header h2 { font-size: 20px; }
  .st-filter { gap: 6px; }
  .st-filter input[type="text"] { width: 60px; font-size: 12px; }
}
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="st-wrap">
  <div class="st-header">
    <h2>📋 <em>도전</em>현황</h2>
  </div>

  <div class="st-filter-card">
    <form id="simform" class="st-filter" action="status.php" method="get">
      <?php if(isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
      <div class="fg"><label>문제</label><input type="text" name="problem_id" value="<?php echo htmlspecialchars($problem_id, ENT_QUOTES)?>" placeholder="번호"></div>
      <div class="fg"><label>사용자</label><input type="text" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES)?>" placeholder="ID"></div>
      <div class="fg"><label>언어</label>
        <select name="language"><option value="-1">전체</option><?php
          $selectedLang = isset($_GET['language']) ? intval($_GET['language']) : -1;
          $lang_count = count($language_ext); $langmask = $OJ_LANGMASK;
          $lang = (~((int)$langmask))&((1<<($lang_count))-1);
          for($i=0;$i<$lang_count;$i++){ if($lang&(1<<$i)) echo "<option value=$i ".($selectedLang==$i?'selected':'').">".$language_name[$i]."</option>"; }?>
        </select>
      </div>
      <div class="fg"><label>결과</label>
        <select name="jresult"><?php
          $jresult_get = isset($_GET['jresult']) ? intval($_GET['jresult']) : -1;
          if($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
          echo "<option value='-1'".($jresult_get==-1?" selected":"").">전체</option>";
          for($j=0;$j<12;$j++){ $i=($j+4)%12; echo "<option value='$i'".($i==$jresult_get?" selected":"").">".$jresult[$i]."</option>"; }?>
        </select>
      </div>
      <div class="spacer"></div>
      <button type="submit" class="st-filter-btn">검색</button>
    </form>
  </div>

  <div class="st-card">
    <table class="st-tbl">
      <thead><tr>
        <th style="width:70px">#</th>
        <th style="text-align:left">사용자</th>
        <th style="width:60px">문제</th>
        <th style="width:100px">결과</th>
        <th style="width:80px">메모리</th>
        <th style="width:65px">시간</th>
        <th style="width:45px">언어</th>
        <th style="width:75px"></th>
        <th style="width:110px">제출시간</th>
      </tr></thead>
      <tbody>
      <?php
      $rbMap = array(
        4=>'ac', 5=>'pe', 6=>'wa', 7=>'tle', 8=>'mle', 9=>'ole', 10=>'re', 11=>'ce'
      );
      $rbLabel = array(
        0=>'대기', 1=>'대기', 2=>'채점중', 3=>'채점중',
        4=>'정답 ✓', 5=>'형식 오류', 6=>'오답 ✗', 7=>'시간 초과',
        8=>'메모리 초과', 9=>'출력 초과', 10=>'런타임 에러', 11=>'컴파일 에러'
      );

      foreach($view_status as $row):
        $c_sid=$row[0]; $c_user=$row[1]; $c_nick=isset($row['nick'])?$row['nick']:'';
        $c_prob=$row[2]; $c_result=$row[3]; $c_mem=$row[4]; $c_time=$row[5];
        $c_lang=$row[6]; $c_edit=isset($row[7])?$row[7]:''; $c_date=isset($row[8])?$row[8]:'';

        // 결과코드 추출
        preg_match('/result=(\d+)/', $c_result, $rm);
        $rc = isset($rm[1]) ? intval($rm[1]) : 2;
        if($rc>11) $rc=2;
        $rbCls = isset($rbMap[$rc]) ? $rbMap[$rc] : 'pending';
        $rbTxt = isset($rbLabel[$rc]) ? $rbLabel[$rc] : '채점중';

        // 결과 링크
        $rLink=''; if(preg_match('/href=([^\s>]+)/', $c_result, $lm)) $rLink=$lm[1];

        // SID 숫자 + 관리자 액션
        $sidNum=''; $adminHtml='';
        if(preg_match('/^(\d+)(.*)$/s', $c_sid, $sm)){ $sidNum=$sm[1]; $adminHtml=trim($sm[2]); }
        else $sidNum=strip_tags($c_sid);

        $nickText = strip_tags($c_nick);
        $trClass = ($rc==4) ? 'r-ac' : (($rc==6)?'r-wa':'');
      ?>
      <tr class="<?php echo $trClass?>">
        <td class="td-sid">#<?php echo $sidNum?> <?php echo $adminHtml?></td>
        <td class="td-user">
          <?php echo $c_user?>
          <?php if($nickText):?><div class="nick"><?php echo htmlspecialchars($nickText)?></div><?php endif;?>
        </td>
        <td class="td-prob"><?php echo $c_prob?></td>
        <td>
          <?php if($rLink):?>
          <a href="<?php echo $rLink?>" class="res-badge rb-<?php echo $rbCls?>"><?php echo $rbTxt?></a>
          <?php else:?>
          <span class="res-badge rb-<?php echo $rbCls?>"><?php echo $rbTxt?></span>
          <?php endif;?>
        </td>
        <td class="td-perf"><?php echo strip_tags($c_mem)?></td>
        <td class="td-perf"><?php echo strip_tags($c_time)?></td>
        <td class="td-lang"><?php echo $c_lang?></td>
        <td><?php if(!empty(trim(strip_tags($c_edit)))) echo $c_edit;?></td>
        <td class="td-date"><?php echo $c_date?></td>
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
