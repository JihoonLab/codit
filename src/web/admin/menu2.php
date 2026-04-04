<?php require_once("admin-header.php");
  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
  $path_fix="../";
  $OJ_TP=$OJ_TEMPLATE;
  $OJ_TEMPLATE="bs3";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $MSG_ADMIN?></title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
* { margin:0; padding:0; box-sizing:border-box; }
/* Reset main site CSS overrides */
.navbar, .codit-global-footer, .jumbotron, #header, .cn-topbar, .cn-nav { display:none !important; }
a { color:inherit; }
a:hover, a:focus { text-decoration:none; }
html, body {
  height:100% !important; overflow-x:hidden !important;
  font-family:'Inter','Noto Sans KR',sans-serif !important;
  background:#0f0b1e !important;
  color:#c4c0d4 !important;
  margin:0 !important; padding:0 !important;
}
::-webkit-scrollbar { width:4px; }
::-webkit-scrollbar-track { background:transparent; }
::-webkit-scrollbar-thumb { background:rgba(124,58,237,0.3); border-radius:4px; }

.sidebar {
  display:flex; flex-direction:column; min-height:100vh;
  padding:20px 16px;
}

/* Brand */
.sb-brand {
  text-align:center; padding:8px 0 24px; border-bottom:1px solid rgba(255,255,255,0.06);
  margin-bottom:20px;
}
.sb-brand .logo {
  font-size:26px; font-weight:900; letter-spacing:-1px;
  background:linear-gradient(135deg,#c4b5fd,#a78bfa,#7c3aed);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.sb-brand .sub {
  font-size:11px; color:rgba(255,255,255,0.3); margin-top:4px;
  font-weight:500; letter-spacing:1px; text-transform:uppercase;
}

/* Quick actions */
.sb-quick {
  display:flex; gap:6px; margin-bottom:22px;
}
.sb-quick a {
  flex:1; display:flex; align-items:center; justify-content:center; gap:6px;
  padding:11px 0; border-radius:10px; font-size:12.5px; font-weight:700;
  text-decoration:none; transition:all .2s;
  background:rgba(124,58,237,0.15); color:#c4b5fd; border:1px solid rgba(124,58,237,0.25);
}
.sb-quick a:hover {
  background:rgba(124,58,237,0.3); border-color:#7c3aed; color:#e0d4fc;
  transform:translateY(-1px);
}

/* Section */
.sb-section { margin-bottom:8px; }
.sb-section-head {
  display:flex; align-items:center; gap:8px;
  padding:11px 12px; border-radius:10px; cursor:pointer;
  font-size:13px; font-weight:800; color:rgba(255,255,255,0.55);
  letter-spacing:0.3px;
  transition:all .15s; user-select:none;
}
.sb-section-head:hover { color:rgba(255,255,255,0.8); background:rgba(255,255,255,0.04); }
.sb-section-head .icon { font-size:16px; width:22px; text-align:center; }
.sb-section-head .arrow {
  margin-left:auto; font-size:10px; transition:transform .2s; color:rgba(255,255,255,0.25);
}
.sb-section.open .sb-section-head .arrow { transform:rotate(90deg); color:#a78bfa; }
.sb-section.open .sb-section-head { color:#c4b5fd; }

.sb-links {
  overflow:hidden; max-height:0; transition:max-height .3s ease;
  padding-left:8px;
}
.sb-section.open .sb-links { max-height:500px; }

.sb-links a {
  display:flex; align-items:center; gap:9px;
  padding:10px 12px; margin:2px 0; border-radius:8px;
  font-size:13.5px; font-weight:600; color:rgba(255,255,255,0.5);
  text-decoration:none; transition:all .15s;
}
.sb-links a:hover {
  background:rgba(124,58,237,0.12); color:#e0d4fc;
}
.sb-links a .dot {
  width:5px; height:5px; border-radius:50%;
  background:rgba(255,255,255,0.2); flex-shrink:0;
  transition:background .15s;
}
.sb-links a:hover .dot { background:#a78bfa; }

/* Footer */
.sb-footer {
  margin-top:auto; padding-top:16px;
  border-top:1px solid rgba(255,255,255,0.06);
  text-align:center;
}
.sb-footer a {
  display:flex; align-items:center; justify-content:center; gap:6px;
  padding:11px; border-radius:10px; font-size:13px; font-weight:700;
  color:rgba(255,255,255,0.4); text-decoration:none; transition:all .15s;
}
.sb-footer a:hover { background:rgba(255,255,255,0.05); color:#e0d4fc; }
</style>
</head>
<body>
<div class="sidebar">

  <div class="sb-brand">
    <div class="logo"><?php echo $OJ_NAME?></div>
    <div class="sub">Admin Panel</div>
  </div>

  <div class="sb-quick">
    <a href="help.php" target="main">🏠 대시보드</a>
    <a href="../" target="_top">🌐 사이트 보기</a>
  </div>

  <!-- 공지 관리 -->
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <div class="sb-section open">
    <div class="sb-section-head" onclick="this.parentElement.classList.toggle('open')">
      <span class="icon">📢</span> <?php echo $MSG_NEWS?> 관리
      <span class="arrow">▶</span>
    </div>
    <div class="sb-links">
      <?php if ($OJ_TP=="bs3"){?>
      <a href="setmsg.php" target="main"><span class="dot"></span> 공지 설정</a>
      <?php }?>
      <a href="news_list.php" target="main"><span class="dot"></span> <?php echo $MSG_NEWS?> 목록</a>
      <a href="news_add_page.php" target="main"><span class="dot"></span> <?php echo $MSG_NEWS?> 추가</a>
    </div>
  </div>
  <?php }?>

  <!-- 사용자 관리 -->
  <div class="sb-section open">
    <div class="sb-section-head" onclick="this.parentElement.classList.toggle('open')">
      <span class="icon">👤</span> <?php echo $MSG_USER?> 관리
      <span class="arrow">▶</span>
    </div>
    <div class="sb-links">
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
      <a href="user_list.php" target="main"><span class="dot"></span> <?php echo $MSG_USER?> 목록</a>
      <?php }?>
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'user_adder'])){?>
      <a href="user_add.php" target="main"><span class="dot"></span> <?php echo $MSG_USER?> 추가</a>
      <a href="user_import.php" target="main"><span class="dot"></span> <?php echo $MSG_USER?> 가져오기</a>
      <?php }?>
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
      <a href="changepass.php" target="main"><span class="dot"></span> 비밀번호 변경</a>
      <?php }?>
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
      <a href="privilege_list.php" target="main"><span class="dot"></span> 권한 목록</a>
      <a href="privilege_add.php" target="main"><span class="dot"></span> 권한 추가</a>
      <a href="user_approve.php" target="main"><span class="dot"></span> 가입 승인</a>
      <?php }?>
    </div>
  </div>

  <!-- 문제 관리 -->
  <div class="sb-section open">
    <div class="sb-section-head" onclick="this.parentElement.classList.toggle('open')">
      <span class="icon">📝</span> <?php echo $MSG_PROBLEM?> 관리
      <span class="arrow">▶</span>
    </div>
    <div class="sb-links">
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])) {?>
      <a href="problem_list.php" target="main"><span class="dot"></span> <?php echo $MSG_PROBLEM?> 목록</a>
      <?php }?>
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])) {?>
      <a href="problem_add_page.php" target="main"><span class="dot"></span> <?php echo $MSG_PROBLEM?> 추가</a>
      <?php }?>
      <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_importer'])) {?>
      <a href="problem_import.php" target="main"><span class="dot"></span> <?php echo $MSG_PROBLEM?> 가져오기</a>
      <a href="problem_export.php" target="main"><span class="dot"></span> <?php echo $MSG_PROBLEM?> 내보내기</a>
      <?php }?>
    </div>
  </div>

  <!-- 수업/대회 관리 -->
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){?>
  <div class="sb-section">
    <div class="sb-section-head" onclick="this.parentElement.classList.toggle('open')">
      <span class="icon">🏆</span> <?php echo $MSG_CONTEST?> 관리
      <span class="arrow">▶</span>
    </div>
    <div class="sb-links">
      <a href="contest_list.php" target="main"><span class="dot"></span> <?php echo $MSG_CONTEST?> 목록</a>
      <a href="contest_add.php" target="main"><span class="dot"></span> <?php echo $MSG_CONTEST?> 추가</a>
      <a href="user_set_ip.php" target="main"><span class="dot"></span> 로그인 IP 설정</a>
      <a href="team_generate.php" target="main"><span class="dot"></span> 팀 계정 생성</a>
      <a href="team_generate2.php" target="main"><span class="dot"></span> 팀 계정 생성2</a>
      <a href="offline_import.php" target="main"><span class="dot"></span> 오프라인 가져오기</a>
    </div>
  </div>
  <?php }?>

  <!-- 시스템 관리 -->
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <div class="sb-section">
    <div class="sb-section-head" onclick="this.parentElement.classList.toggle('open')">
      <span class="icon">⚙️</span> <?php echo $MSG_SYSTEM?> 관리
      <span class="arrow">▶</span>
    </div>
    <div class="sb-links">
      <a href="rejudge.php" target="main"><span class="dot"></span> 재채점</a>
      <a href="source_give.php" target="main"><span class="dot"></span> 소스 공개</a>
      <a href="../online.php" target="main"><span class="dot"></span> 접속자 현황</a>
      <a href="update_db.php" target="main"><span class="dot"></span> DB 업데이트</a>
      <a href="backup.php" target="main"><span class="dot"></span> 백업</a>
      <a href="ranklist_export.php" target="main"><span class="dot"></span> 랭킹 내보내기</a>
    </div>
  </div>
  <?php }?>

  <div class="sb-footer">
    <a href="../logout.php" target="_top">🚪 로그아웃</a>
  </div>

</div>
</body>
</html>
