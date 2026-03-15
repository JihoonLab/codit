<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($view_title)?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

    .ct-wrap { max-width: 1000px; margin: 32px auto; padding: 0 20px 60px; }

    /* 헤더 카드 */
    .ct-header {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
      overflow: hidden;
      margin-bottom: 20px;
    }
    .ct-header-top {
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      padding: 28px 32px 24px;
      color: #fff;
    }
    .ct-header-top .ct-id {
      font-size: 12px; font-weight: 600; opacity: 0.7;
      text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;
    }
    .ct-header-top h1 {
      font-size: 24px; font-weight: 900; margin: 0 0 10px; line-height: 1.3;
    }
    .ct-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
    .ct-badge {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 12px; border-radius: 20px;
      font-size: 12px; font-weight: 700;
    }
    .badge-running  { background: rgba(255,255,255,0.25); color: #fff; border: 1px solid rgba(255,255,255,0.4); }
    .badge-ended    { background: rgba(0,0,0,0.2); color: rgba(255,255,255,0.7); }
    .badge-upcoming { background: rgba(39,174,96,0.3); color: #a8ffcc; border: 1px solid rgba(39,174,96,0.4); }
    .badge-public   { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.85); }
    .badge-private  { background: rgba(231,76,60,0.3); color: #ffb3b3; border: 1px solid rgba(231,76,60,0.4); }

    /* 대회 정보 */
    .ct-info-body { padding: 20px 32px 24px; }
    .ct-desc {
      font-size: 14px; color: #555; line-height: 1.7;
      margin-bottom: 20px; padding-bottom: 16px;
      border-bottom: 1px solid #f0f3f7;
    }
    .ct-desc:empty { display: none; }
    .ct-meta-grid {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
      margin-bottom: 20px;
    }
    @media(max-width:600px) { .ct-meta-grid { grid-template-columns: 1fr 1fr; } }
    .ct-meta-item {
      background: #f8fafc; border-radius: 10px; padding: 12px 16px;
    }
    .ct-meta-item .m-label { font-size: 11px; color: #aaa; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .ct-meta-item .m-val { font-size: 14px; font-weight: 700; color: #222; }
    .ct-meta-item .m-val.running { color: #e74c3c; }
    .ct-meta-item .m-val.ended   { color: #aaa; }
    .ct-meta-item .m-val.upcoming{ color: #27ae60; }

    /* 서버 시간 */
    .ct-clock {
      display: inline-flex; align-items: center; gap: 8px;
      background: #fff5f5; border: 1px solid #fcc; border-radius: 8px;
      padding: 6px 14px; font-size: 13px; font-weight: 700; color: #e74c3c;
      margin-bottom: 20px;
    }

    /* 액션 버튼 */
    .ct-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .ct-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 18px; border-radius: 8px;
      font-size: 13px; font-weight: 700; text-decoration: none;
      transition: background 0.15s, transform 0.1s;
    }
    .ct-btn:hover { text-decoration: none; transform: translateY(-1px); }
    .ct-btn-primary { background: #7c3aed; color: #fff !important; }
    .ct-btn-primary:hover { background: #6d28d9; }
    .ct-btn-gray { background: #f0f3f7; color: #555 !important; }
    .ct-btn-gray:hover { background: #e2e8f0; }
    .ct-btn-admin { background: #7c3aed; color: #fff !important; }
    .ct-btn-admin:hover { background: #6d28d9; }

    /* 문제 테이블 */
    .ct-table-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.07);
      overflow: hidden;
    }
    .ct-table-header {
      background: #f8fafc; border-bottom: 2px solid #7c3aed;
      padding: 14px 24px; font-size: 15px; font-weight: 700; color: #7c3aed;
    }
    .ct-table { width: 100%; border-collapse: collapse; }
    .ct-table thead tr { background: #7c3aed; }
    .ct-table thead th {
      padding: 12px 16px; font-size: 13px; font-weight: 600;
      color: #fff; text-align: center;
    }
    .ct-table thead th.th-title { text-align: left; }
    .ct-table tbody tr { border-bottom: 1px solid #f0f3f7; transition: background 0.1s; }
    .ct-table tbody tr:last-child { border-bottom: none; }
    .ct-table tbody tr:hover { background: #f5f8ff; }
    .ct-table td { padding: 13px 16px; font-size: 14px; text-align: center; vertical-align: middle; }
    .ct-table td.td-title { text-align: left; }
    .ct-table td a { color: #7c3aed; text-decoration: none; font-weight: 500; }
    .ct-table td a:hover { text-decoration: underline; }
    .ct-table .pid-badge {
      display: inline-block; padding: 3px 10px;
      background: #e8f0fe; color: #7c3aed;
      border-radius: 5px; font-weight: 700; font-size: 13px;
    }
    .ct-table .ac-num { color: #27ae60; font-weight: 700; }
    .ct-table .sub-num { color: #888; }
    .ct-table .solved-mark { font-size: 16px; }
    /* check_ac() 반환 label 스타일 */
    .label { display:inline-block; padding:3px 10px; border-radius:5px; font-size:12px; font-weight:700; }
    .label-success { background:#d1fae5; color:#059669; }
    .label-danger  { background:#fee2e2; color:#dc2626; }
    .label-default { background:#f1f5f9; color:#64748b; }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="ct-wrap">
<?php if(isset($cid)): ?>

  <!-- 대회 헤더 -->
  <div class="ct-header">
    <div class="ct-header-top">
      <div class="ct-id">대회 #<?php echo $view_cid?></div>
      <h1><?php echo htmlspecialchars($view_title)?></h1>
      <div class="ct-badges">
        <?php if($now > $end_time): ?>
          <span class="ct-badge badge-ended">🏁 종료</span>
        <?php elseif($now < $start_time): ?>
          <span class="ct-badge badge-upcoming">⏳ 예정</span>
        <?php else: ?>
          <span class="ct-badge badge-running">🔴 진행 중</span>
        <?php endif; ?>
        <?php if($view_private == '0'): ?>
          <span class="ct-badge badge-public">🌐 공개</span>
        <?php else: ?>
          <span class="ct-badge badge-private">🔒 비공개</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="ct-info-body">
      <?php if(!empty($view_description)): ?>
      <div class="ct-desc"><?php echo $view_description?></div>
      <?php endif; ?>

      <!-- 시간 정보 -->
      <div class="ct-meta-grid">
        <div class="ct-meta-item">
          <div class="m-label">시작 시간</div>
          <div class="m-val"><?php echo $view_start_time?></div>
        </div>
        <div class="ct-meta-item">
          <div class="m-label">종료 시간</div>
          <div class="m-val"><?php echo $view_end_time?></div>
        </div>
        <div class="ct-meta-item">
          <div class="m-label">
            <?php if($now > $end_time): ?>종료까지
            <?php elseif($now < $start_time): ?>시작까지
            <?php else: ?>남은 시간<?php endif; ?>
          </div>
          <div class="m-val <?php echo $now>$end_time?'ended':($now<$start_time?'upcoming':'running')?>">
            <?php if($now > $end_time): ?>종료됨
            <?php elseif($now < $start_time): ?><?php echo formatTimeLength($start_time - $now)?>
            <?php else: ?><span id="timeleft"><?php echo formatTimeLength($end_time - $now)?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- 서버 시간 -->
      <div class="ct-clock">
        🕐 서버 시간: <span id="nowdate"><?php echo date("Y-m-d H:i:s")?></span>
      </div>

      <!-- 액션 버튼 -->
      <div class="ct-actions">
        <a href="contest.php?cid=<?php echo $cid?>" class="ct-btn ct-btn-primary">📋 문제</a>
        <a href="status.php?cid=<?php echo $view_cid?>" class="ct-btn ct-btn-gray">📊 제출현황</a>
        <a href="contestrank.php?cid=<?php echo $view_cid?>" class="ct-btn ct-btn-gray">🏆 순위표</a>
        <a href="conteststatistics.php?cid=<?php echo $view_cid?>" class="ct-btn ct-btn-gray">📈 통계</a>
        <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])): ?>
        <a href="suspect_list.php?cid=<?php echo $view_cid?>" class="ct-btn ct-btn-admin">🔍 IP 확인</a>
        <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $view_cid?>" class="ct-btn ct-btn-admin">⚙️ 수정</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- 문제 목록 -->
  <div class="ct-table-card">
    <div class="ct-table-header">📋 문제 목록</div>
    <table class="ct-table">
      <thead>
        <tr>
          <th style="width:50px">상태</th>
          <th style="width:80px">번호</th>
          <th class="th-title">문제 제목</th>
          <th style="width:80px">정답</th>
          <th style="width:80px">제출</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($view_problemset as $row): ?>
        <tr>
          <td><span class="solved-mark"><?php echo $row[0]?></span></td>
          <td><span class="pid-badge"><?php echo $row[1]?></span></td>
          <td class="td-title"><?php echo $row[2]?></td>
          <td class="ac-num"><?php echo isset($row[4]) ? $row[4] : '-'?></td>
          <td class="sub-num"><?php echo isset($row[5]) ? $row[5] : '-'?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?php endif; ?>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime() - new Date().getTime();
function clock() {
  var x = new Date(new Date().getTime() + diff);
  var pad = function(n){ return n>=10?n:'0'+n; };
  var str = x.getFullYear()+'-'+pad(x.getMonth()+1)+'-'+pad(x.getDate())+' '+pad(x.getHours())+':'+pad(x.getMinutes())+':'+pad(x.getSeconds());
  document.getElementById('nowdate').textContent = str;
  <?php if($now < $end_time && $now >= $start_time): ?>
  var left = Math.max(0, <?php echo $end_time?> - Math.floor((new Date().getTime()+diff)/1000));
  var h = Math.floor(left/3600), m = Math.floor((left%3600)/60), s = left%60;
  var pad2 = function(n){ return n>=10?n:'0'+n; };
  var el = document.getElementById('timeleft');
  if(el) el.textContent = h+'시간 '+pad2(m)+'분 '+pad2(s)+'초';
  <?php endif; ?>
  setTimeout(clock, 1000);
}
clock();
</script>
</body>
</html>
