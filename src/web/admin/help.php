<?php require_once("admin-header.php");
  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
$sql="select avg(usedtime) delay from (select judgetime-in_date usedtime from solution where result >=4 order by solution_id desc limit 10) c";
$delay=pdo_query($sql);
$delay_val = isset($delay[0][0]) ? round($delay[0][0],1) : 0;
$cpu_load = sys_getloadavg()[0];

$prob_cnt = pdo_query("select count(*) from problem")[0][0];
$user_cnt = pdo_query("select count(*) from users")[0][0];
$sol_cnt = pdo_query("select count(*) from solution")[0][0];
$pending_cnt = 0;
$pr = pdo_query("SELECT COUNT(*) FROM users WHERE defunct='Y'");
if($pr && count($pr)>0) $pending_cnt = intval($pr[0][0]);

$free_mem = '';
$free_disk = '';
if(function_exists('system')){
  ob_start(); system("free -h|grep Mem|awk '{print $7\"/\"$2}'"); $free_mem = trim(ob_get_clean());
  ob_start(); system("df -h|grep '/dev/'|grep -v 'shm'|awk '{print $4 \"/\" $2}'|head -1"); $free_disk = trim(ob_get_clean());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - <?php echo $OJ_NAME?></title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
* { margin:0; padding:0; box-sizing:border-box; }
/* Reset main site CSS overrides */
.navbar, .codit-global-footer, .jumbotron, #header, .cn-topbar, .cn-nav { display:none !important; }
a { color:inherit; text-decoration:none; }
a:hover, a:focus { text-decoration:none; color:inherit; }
body {
  font-family:'Inter','Noto Sans KR',sans-serif !important;
  background:#100c1f !important;
  color:#c4c0d4 !important;
  min-height:100vh !important;
  padding:28px !important;
  margin:0 !important;
}
::-webkit-scrollbar { width:6px; }
::-webkit-scrollbar-track { background:transparent; }
::-webkit-scrollbar-thumb { background:rgba(124,58,237,0.3); border-radius:4px; }

.dash-header {
  margin-bottom:28px;
}
.dash-header h1 {
  font-size:28px; font-weight:900; letter-spacing:-0.5px;
  background:linear-gradient(135deg,#c4b5fd,#a78bfa,#7c3aed);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.dash-header p { font-size:13px; color:rgba(255,255,255,0.3); margin-top:4px; }

/* Stat cards row */
.stat-row {
  display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));
  gap:14px; margin-bottom:28px;
}
.stat-card {
  background:rgba(255,255,255,0.04) !important;
  border:1px solid rgba(255,255,255,0.06) !important;
  border-radius:16px !important; padding:20px !important;
  transition:all .2s;
}
.stat-card:hover {
  border-color:rgba(124,58,237,0.3);
  background:rgba(124,58,237,0.06);
  transform:translateY(-2px);
}
.stat-card .sc-icon {
  font-size:24px; margin-bottom:10px;
}
.stat-card .sc-value {
  font-size:26px; font-weight:800; color:#fff;
}
.stat-card .sc-label {
  font-size:12px; font-weight:600; color:rgba(255,255,255,0.35);
  margin-top:2px; text-transform:uppercase; letter-spacing:0.5px;
}
.stat-card.warn .sc-value { color:#f59e0b; }
.stat-card.good .sc-value { color:#22c55e; }
.stat-card.purple .sc-value { color:#a78bfa; }
.stat-card.red .sc-value { color:#ef4444; }

/* Quick actions grid */
.section-title {
  font-size:16px; font-weight:800; color:rgba(255,255,255,0.6);
  margin-bottom:14px; display:flex; align-items:center; gap:8px;
}
.section-title .st-icon { font-size:18px; }

.action-grid {
  display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));
  gap:10px; margin-bottom:28px;
}
.action-card {
  display:flex !important; align-items:center; gap:12px;
  padding:14px 16px !important; border-radius:12px !important;
  background:rgba(255,255,255,0.03) !important;
  border:1px solid rgba(255,255,255,0.06) !important;
  text-decoration:none !important; color:#c4c0d4 !important;
  transition:all .2s; cursor:pointer;
}
.action-card:hover {
  background:rgba(124,58,237,0.1) !important;
  border-color:rgba(124,58,237,0.25) !important;
  color:#e0d4fc !important;
  transform:translateY(-1px);
}
.action-card .ac-icon {
  width:40px; height:40px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  font-size:18px; flex-shrink:0;
}
.ac-blue .ac-icon { background:rgba(59,130,246,0.15); }
.ac-green .ac-icon { background:rgba(34,197,94,0.15); }
.ac-yellow .ac-icon { background:rgba(245,158,11,0.15); }
.ac-red .ac-icon { background:rgba(239,68,68,0.15); }
.ac-purple .ac-icon { background:rgba(124,58,237,0.15); }
.action-card .ac-text { font-size:13px; font-weight:600; }
.action-card .ac-desc { font-size:11px; color:rgba(255,255,255,0.25); margin-top:2px; font-weight:400; }

/* System monitor */
.sys-monitor {
  background:rgba(255,255,255,0.03);
  border:1px solid rgba(255,255,255,0.06);
  border-radius:16px; padding:20px; margin-bottom:28px;
}
.sys-monitor iframe {
  width:100%; height:200px; border:none; border-radius:10px;
  background:rgba(0,0,0,0.3);
}
.sys-toggle {
  display:inline-flex; align-items:center; gap:6px;
  padding:8px 16px; border-radius:8px; border:none;
  background:rgba(124,58,237,0.15); color:#a78bfa;
  font-size:12px; font-weight:700; cursor:pointer;
  transition:all .15s; font-family:inherit;
}
.sys-toggle:hover { background:rgba(124,58,237,0.25); }
</style>
</head>
<body>

<div class="dash-header">
  <h1>Dashboard</h1>
  <p><?php echo $OJ_NAME?> Admin Panel</p>
</div>

<!-- Stats -->
<div class="stat-row">
  <div class="stat-card purple">
    <div class="sc-icon">📝</div>
    <div class="sc-value"><?php echo number_format($prob_cnt)?></div>
    <div class="sc-label">Problems</div>
  </div>
  <div class="stat-card good">
    <div class="sc-icon">👤</div>
    <div class="sc-value"><?php echo number_format($user_cnt)?></div>
    <div class="sc-label">Users</div>
  </div>
  <div class="stat-card">
    <div class="sc-icon">📊</div>
    <div class="sc-value"><?php echo number_format($sol_cnt)?></div>
    <div class="sc-label">Submissions</div>
  </div>
  <?php if($pending_cnt > 0): ?>
  <div class="stat-card red">
    <div class="sc-icon">⏳</div>
    <div class="sc-value"><?php echo $pending_cnt?></div>
    <div class="sc-label">Pending Approvals</div>
  </div>
  <?php endif; ?>
  <div class="stat-card <?php echo $delay_val > 5 ? 'warn' : 'good'?>">
    <div class="sc-icon">⚡</div>
    <div class="sc-value"><?php echo $delay_val?>s</div>
    <div class="sc-label">Judge Delay</div>
  </div>
  <div class="stat-card <?php echo $cpu_load > 2 ? 'warn' : 'good'?>">
    <div class="sc-icon">🖥️</div>
    <div class="sc-value"><?php echo round($cpu_load,1)?></div>
    <div class="sc-label">CPU Load</div>
  </div>
  <?php if($free_mem): ?>
  <div class="stat-card">
    <div class="sc-icon">💾</div>
    <div class="sc-value"><?php echo $free_mem?></div>
    <div class="sc-label">Free Memory</div>
  </div>
  <?php endif; ?>
  <?php if($free_disk): ?>
  <div class="stat-card">
    <div class="sc-icon">💿</div>
    <div class="sc-value"><?php echo $free_disk?></div>
    <div class="sc-label">Free Disk</div>
  </div>
  <?php endif; ?>
</div>

<!-- System Monitor -->
<div class="sys-monitor" id="sysmon" style="display:none">
  <iframe src="watch.php?notext"></iframe>
</div>
<div style="margin-bottom:28px">
  <button class="sys-toggle" onclick="var m=document.getElementById('sysmon');m.style.display=m.style.display==='none'?'block':'none'">
    📡 System Monitor 토글
  </button>
</div>

<!-- Quick Actions: 공지 -->
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
<div class="section-title"><span class="st-icon">📢</span> 공지 관리</div>
<div class="action-grid">
  <a class="action-card ac-blue" href="setmsg.php" target="main">
    <div class="ac-icon">📌</div>
    <div><div class="ac-text">공지 설정</div><div class="ac-desc">상단 띠배너 메시지</div></div>
  </a>
  <a class="action-card ac-blue" href="news_list.php" target="main">
    <div class="ac-icon">📋</div>
    <div><div class="ac-text">공지 목록</div><div class="ac-desc">공지사항 관리</div></div>
  </a>
  <a class="action-card ac-blue" href="news_add_page.php" target="main">
    <div class="ac-icon">✏️</div>
    <div><div class="ac-text">공지 추가</div><div class="ac-desc">새 공지사항 작성</div></div>
  </a>
</div>
<?php }?>

<!-- Quick Actions: 사용자 -->
<div class="section-title"><span class="st-icon">👤</span> 사용자 관리</div>
<div class="action-grid">
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
  <a class="action-card ac-purple" href="user_list.php" target="main">
    <div class="ac-icon">📋</div>
    <div><div class="ac-text">사용자 목록</div><div class="ac-desc">전체 사용자 조회/관리</div></div>
  </a>
  <?php }?>
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <a class="action-card ac-purple" href="user_add.php" target="main">
    <div class="ac-icon">➕</div>
    <div><div class="ac-text">사용자 추가</div><div class="ac-desc">새 사용자 등록</div></div>
  </a>
  <a class="action-card ac-purple" href="user_approve.php" target="main">
    <div class="ac-icon">✅</div>
    <div><div class="ac-text">가입 승인<?php if($pending_cnt>0) echo " <span style='color:#ef4444'>($pending_cnt)</span>"; ?></div><div class="ac-desc">회원가입 승인 대기</div></div>
  </a>
  <?php }?>
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
  <a class="action-card ac-purple" href="changepass.php" target="main">
    <div class="ac-icon">🔑</div>
    <div><div class="ac-text">비밀번호 변경</div><div class="ac-desc">사용자 비밀번호 재설정</div></div>
  </a>
  <?php }?>
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <a class="action-card ac-purple" href="privilege_list.php" target="main">
    <div class="ac-icon">🛡️</div>
    <div><div class="ac-text">권한 목록</div><div class="ac-desc">권한 현황 조회</div></div>
  </a>
  <?php }?>
</div>

<!-- Quick Actions: 문제 -->
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){?>
<div class="section-title"><span class="st-icon">📝</span> 문제 관리</div>
<div class="action-grid">
  <a class="action-card ac-green" href="problem_list.php" target="main">
    <div class="ac-icon">📋</div>
    <div><div class="ac-text">문제 목록</div><div class="ac-desc">전체 문제 조회/편집</div></div>
  </a>
  <a class="action-card ac-green" href="problem_add_page.php" target="main">
    <div class="ac-icon">➕</div>
    <div><div class="ac-text">문제 추가</div><div class="ac-desc">새 문제 출제</div></div>
  </a>
  <a class="action-card ac-green" href="problem_import.php" target="main">
    <div class="ac-icon">📥</div>
    <div><div class="ac-text">문제 가져오기</div><div class="ac-desc">외부 문제 파일 가져오기</div></div>
  </a>
  <a class="action-card ac-green" href="problem_export.php" target="main">
    <div class="ac-icon">📤</div>
    <div><div class="ac-text">문제 내보내기</div><div class="ac-desc">문제 파일 내보내기</div></div>
  </a>
</div>
<?php }?>

<!-- Quick Actions: 수업/대회 -->
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){?>
<div class="section-title"><span class="st-icon">🏆</span> 수업/대회 관리</div>
<div class="action-grid">
  <a class="action-card ac-yellow" href="contest_list.php" target="main">
    <div class="ac-icon">📋</div>
    <div><div class="ac-text">수업/대회 목록</div><div class="ac-desc">전체 수업·대회 관리</div></div>
  </a>
  <a class="action-card ac-yellow" href="contest_add.php" target="main">
    <div class="ac-icon">➕</div>
    <div><div class="ac-text">수업/대회 추가</div><div class="ac-desc">새 수업·대회 생성</div></div>
  </a>
  <a class="action-card ac-yellow" href="team_generate.php" target="main">
    <div class="ac-icon">👥</div>
    <div><div class="ac-text">팀 계정 생성</div><div class="ac-desc">대회용 팀 계정 일괄 생성</div></div>
  </a>
</div>
<?php }?>

<!-- Quick Actions: 시스템 -->
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
<div class="section-title"><span class="st-icon">⚙️</span> 시스템 관리</div>
<div class="action-grid">
  <a class="action-card ac-red" href="rejudge.php" target="main">
    <div class="ac-icon">🔄</div>
    <div><div class="ac-text">재채점</div><div class="ac-desc">문제/사용자별 재채점</div></div>
  </a>
  <a class="action-card ac-red" href="update_db.php" target="main">
    <div class="ac-icon">🗄️</div>
    <div><div class="ac-text">DB 업데이트</div><div class="ac-desc">데이터베이스 스키마 갱신</div></div>
  </a>
  <a class="action-card ac-red" href="backup.php" target="main">
    <div class="ac-icon">💾</div>
    <div><div class="ac-text">백업</div><div class="ac-desc">데이터베이스 백업</div></div>
  </a>
  <a class="action-card ac-red" href="ranklist_export.php" target="main">
    <div class="ac-icon">📊</div>
    <div><div class="ac-text">랭킹 내보내기</div><div class="ac-desc">CSV 형식 내보내기</div></div>
  </a>
</div>
<?php }?>

</body>
</html>
