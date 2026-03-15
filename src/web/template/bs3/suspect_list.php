<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>IP 확인 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.sl-wrap { max-width: 1000px; margin: 32px auto; padding: 0 20px 60px; }
.sl-topbar { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.07); padding: 16px 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.sl-topbar-title { font-size: 16px; font-weight: 900; color: #7c3aed; flex: 1; }
.sl-topbar-clock { font-size: 13px; font-weight: 700; color: #e74c3c; background: #fff5f5; border: 1px solid #fcc; border-radius: 6px; padding: 4px 12px; }
.cs-actions { display: flex; gap: 6px; flex-wrap: wrap; }
.cs-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
.cs-btn:hover { text-decoration: none; }
.cs-btn-gray { background: #f0f3f7; color: #555 !important; }
.cs-btn-gray:hover { background: #e2e8f0; }
.sl-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; margin-bottom: 20px; }
.sl-card-head { background: #f8fafc; border-bottom: 1px solid #e5e9f0; padding: 14px 20px; font-size: 14px; font-weight: 700; color: #7c3aed; display: flex; align-items: center; gap: 8px; }
.sl-table { width: 100%; border-collapse: collapse; }
.sl-table thead tr { background: #7c3aed; color: #fff; }
.sl-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; text-align: left; }
.sl-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.sl-table tbody tr:hover { background: #faf5ff; }
.sl-table tbody tr:last-child td { border-bottom: none; }
.sl-table td a { color: #7c3aed; text-decoration: none; }
.sl-table td a:hover { text-decoration: underline; }
.sl-empty { text-align: center; padding: 28px; color: #aaa; font-size: 13px; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="sl-wrap">

  <!-- 대회 네비바 -->
  <div class="sl-topbar">
    <div class="sl-topbar-title">🔍 <?php echo htmlspecialchars($view_title)?> — IP 확인</div>
    <div class="sl-topbar-clock">🕐 <span id="nowdate"><?php echo date("Y-m-d H:i:s")?></span></div>
    <div class="cs-actions">
      <a href="contest.php?cid=<?php echo $view_cid?>" class="cs-btn cs-btn-gray">📋 문제</a>
      <a href="status.php?cid=<?php echo $view_cid?>" class="cs-btn cs-btn-gray">📊 제출현황</a>
      <a href="contestrank.php?cid=<?php echo $view_cid?>" class="cs-btn cs-btn-gray">🏆 순위표</a>
      <a href="conteststatistics.php?cid=<?php echo $view_cid?>" class="cs-btn cs-btn-gray">📈 통계</a>
    </div>
  </div>

  <!-- IP별 의심 목록 -->
  <div class="sl-card">
    <div class="sl-card-head">🌐 <?php echo $MSG_CONTEST_SUSPECT1 ?? 'IP별 중복 접속'?></div>
    <table class="sl-table">
      <thead><tr><th>IP 주소</th><th>사용자 ID</th><th>바로가기</th><th>시간</th><th>IP 사용 수</th></tr></thead>
      <tbody>
      <?php if(empty($result1)): ?>
        <tr><td colspan="5" class="sl-empty">의심 항목 없음</td></tr>
      <?php else: foreach($result1 as $row): ?>
      <tr>
        <td><code><?php echo htmlspecialchars($row['ip'])?></code></td>
        <td><?php echo htmlspecialchars($row['user_id'])?></td>
        <td>
          <a href="../userinfo.php?user=<?php echo $row['user_id']?>">프로필</a> /
          <a href="../status.php?cid=<?php echo $contest_id?>&user_id=<?php echo $row['user_id']?>">제출</a>
        </td>
        <td><?php echo $row['in_date']?></td>
        <td><?php echo $row['c']?></td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <!-- 사용자별 의심 목록 -->
  <div class="sl-card">
    <div class="sl-card-head">👤 <?php echo $MSG_CONTEST_SUSPECT2 ?? '사용자별 중복 IP'?></div>
    <table class="sl-table">
      <thead><tr><th>사용자 ID</th><th>바로가기</th><th>IP 주소</th><th>시간</th><th>IP 사용 수</th></tr></thead>
      <tbody>
      <?php if(empty($result2)): ?>
        <tr><td colspan="5" class="sl-empty">의심 항목 없음</td></tr>
      <?php else: foreach($result2 as $row): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['user_id'])?></td>
        <td>
          <a href="../userinfo.php?user=<?php echo $row['user_id']?>">프로필</a> /
          <a href="../status.php?cid=<?php echo $contest_id?>&user_id=<?php echo $row['user_id']?>">제출</a>
        </td>
        <td><code><?php echo $row['ip']?></code></td>
        <td><?php echo $row['time']?></td>
        <td><?php echo $row['c']?></td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>include/sortTable.js"></script>
<script>
var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime() - new Date().getTime();
function clock(){
  var x = new Date(new Date().getTime()+diff);
  var p = function(n){ return n>=10?n:'0'+n; };
  document.getElementById('nowdate').textContent =
    x.getFullYear()+'-'+p(x.getMonth()+1)+'-'+p(x.getDate())+' '+p(x.getHours())+':'+p(x.getMinutes())+':'+p(x.getSeconds());
  setTimeout(clock,1000);
}
clock();
</script>
</body>
</html>
