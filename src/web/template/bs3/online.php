<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>접속자 현황 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.on-wrap { max-width: 1000px; margin: 32px auto; padding: 0 20px 60px; }
.on-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.on-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.on-count { background: #7c3aed; color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 14px; font-weight: 700; }
.on-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
  margin-bottom: 20px;
}
.on-card-head {
  background: #f8fafc;
  border-bottom: 1px solid #e5e9f0;
  padding: 14px 20px;
  font-size: 14px;
  font-weight: 700;
  color: #7c3aed;
}
.on-table { width: 100%; border-collapse: collapse; }
.on-table thead tr { background: #7c3aed; color: #fff; }
.on-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; text-align: left; white-space: nowrap; }
.on-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; word-break: break-all; }
.on-table tbody tr:hover { background: #f5f8ff; }
.on-table tbody tr:last-child td { border-bottom: none; }
.on-ip { font-family: monospace; font-size: 12px; color: #555; }
.on-ua { font-size: 11px; color: #999; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.on-time { white-space: nowrap; color: #888; font-size: 12px; }
.on-uri { font-size: 12px; color: #7c3aed; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
/* 관리자 검색 */
.on-search { padding: 14px 20px; border-top: 1px solid #f0f0f0; display: flex; gap: 8px; }
.on-search input { flex: 1; padding: 8px 14px; border: 1.5px solid #e0e4ea; border-radius: 7px; font-size: 13px; outline: none; }
.on-search input:focus { border-color: #7c3aed; }
.on-search button { padding: 8px 18px; background: #7c3aed; color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="on-wrap">
  <div class="on-header">
    <span style="font-size:24px">👥</span>
    <h2>접속자 현황</h2>
    <span class="on-count"><?php echo $on->get_num()?> 명</span>
  </div>

  <!-- 현재 접속자 테이블 -->
  <div class="on-card">
    <div class="on-card-head">🌐 현재 접속 중</div>
    <table class="on-table">
      <thead>
        <tr>
          <th>IP</th>
          <th>페이지</th>
          <th>접속시간</th>
          <th>브라우저</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($users as $u): if(!is_array($u)) continue; ?>
      <tr>
        <td><span class="on-ip"><?php echo htmlspecialchars($u['ip'])?></span></td>
        <td><span class="on-uri"><?php echo htmlspecialchars($u['uri'] ?? '')?></span></td>
        <td><span class="on-time"><?php echo sprintf("%d분 %d초", ($u['lastmove']-$u['firsttime'])/60, ($u['lastmove']-$u['firsttime'])%60)?></span></td>
        <td><span class="on-ua"><?php echo htmlspecialchars($u['ua'] ?? '')?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])): ?>
    <div class="on-search">
      <form style="display:flex;gap:8px;width:100%">
        <input type="text" name="search" placeholder="IP 검색...">
        <button type="submit">검색</button>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) && !empty($view_online)): ?>
  <!-- 관리자: 로그인 세션 목록 -->
  <div class="on-card">
    <div class="on-card-head">🔐 로그인 세션 (관리자)</div>
    <table class="on-table">
      <thead>
        <tr><th>UserID</th><th>IP</th><th>시간</th></tr>
      </thead>
      <tbody>
      <?php foreach($view_online as $row): ?>
      <tr>
        <?php foreach($row as $cell): ?>
        <td><?php echo htmlspecialchars($cell)?></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
