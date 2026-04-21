<?php
  require_once("../include/db_info.inc.php");
  require_once("../lang/$OJ_LANG.php");
  require_once("../include/const.inc.php");
  require_once("admin-header.php");
  header("Cache-control:private");
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
  include_once("kindeditor.php") ;
?>
<body>
<?php
$description = "";
if(isset($_POST['startdate'])){
  require_once("../include/check_post_key.php");

  $starttime = $_POST['startdate']." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
  $endtime = $_POST['enddate']." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
  //echo $starttime;
  //echo $endtime;

  $title = $_POST['title'];
  $private = $_POST['private'];
  $password = $_POST['password'];
  $description = $_POST['description'];
  
  if(false){
    $title = stripslashes($title);
    $private = stripslashes($private);
    $password = stripslashes($password);
    $description = stripslashes($description);
  }

  $lang = $_POST['lang'];
  $langmask = 0;
  foreach($lang as $t){
    $langmask += 1<<$t;
  } 

  $langmask = ((1<<count($language_ext))-1)&(~$langmask);
  //echo $langmask; 

  $subnet= $_POST['subnet'];
  $contest_types= $_POST['contest_type'];
  $contest_type=0;
  foreach($contest_types as $t){
    $contest_type |= 1<<$t;
  }
  // 수행평가 만점 설정 (0=자동감지, 20=2학년C, 40=3학년Python)
  $exam_max_score = isset($_POST['exam_max_score']) ? intval($_POST['exam_max_score']) : 0;
  if ($exam_max_score !== 0 && $exam_max_score !== 20 && $exam_max_score !== 40) $exam_max_score = 0;

  $sql = "INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`,subnet,contest_type,exam_max_score,`user_id`)
          VALUES(?,?,?,?,?,?,?,?,?,?,?)";

  $description = str_replace("<p>", "", $description);
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44; ", $description);
  $user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];
 // echo $sql.$title.$starttime.$endtime.$private.$langmask.$description.$password,$user_id;
  $cid = pdo_query($sql,$title,$starttime,$endtime,$private,$langmask,$description,$password,$subnet,$contest_type,$exam_max_score,$user_id) ;
  echo "Add Contest ".$cid;

  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
  $plist = trim($_POST['cproblem']);
  $pieces = explode(",",$plist );
  $pieces = array_unique($pieces);
  if(count($pieces)>0 && intval($pieces[0])>0){
     
     
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    $plist="";
    $pid=0;
    for($i=0; $i<count($pieces); $i++){
      $sql="select problem_id from problem where problem_id=?";
      $has=pdo_query($sql,$pieces[$i]);
      if(count($has) > 0) {
         if($plist) $plist.=",";
         $plist.=intval($pieces[$i]);
         pdo_query($sql_1,$cid,$pieces[$i],$pid);
         $pid++;
      }else{
         print("Problem not exists:".$pieces[$i]."<br>\n");
      }
    }
    //echo $sql_1;
    // [2026-04-20] 대회 생성 시 문제 자동 공개(defunct='N') 처리 제거.
    // 숨김 문제도 contest_problem 매핑으로 대회 참가자가 접근 가능하므로 UPDATE 불필요.
    // 문제 공개는 반드시 문제관리에서 명시적으로 수행할 것.
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql,"c$cid");

  $sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
  pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id'],"m$cid");

  $_SESSION[$OJ_NAME.'_'."m$cid"] = true;
  $pieces = explode("\n", trim($_POST['ulist']));

  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES (?,?)";
    for($i=0; $i<count($pieces); $i++){
      pdo_query($sql_1,trim($pieces[$i]),"c$cid") ;
    }
  }
  echo "<script>window.location.href=\"contest_list.php\";</script>";
}
else{
  if(isset($_GET['cid'])){
    $cid = intval($_GET['cid']);
    $sql = "select * FROM contest WHERE `contest_id`=?";
    $result = pdo_query($sql,$cid);
    $row = $result[0];
    $title = $row['title']."-Copy";

    $private = $row['private'];
    $langmask = $row['langmask'];
    $description = $row['description'];

    $plist = "";
    $sql = "select `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
    $result = pdo_query($sql,$cid);
    foreach($result as $row){
      if($plist) $plist = $plist.',';
      $plist = $plist.$row[0];
    }

    $ulist = "";
    $sql = "select `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
    $result = pdo_query($sql,"c$cid");

    foreach($result as $row){
      if($ulist) $ulist .= "\n";
      $ulist .= $row[0];
    }
  }
  else if(isset($_POST['problem2contest'])){
    $plist = "";
    
    sort($_POST['pid']);
    foreach($_POST['pid'] as $i){       
      if($plist)
      $plist.=','.intval($i);
      else
        $plist=$i;
    }
  $plist = trim($_POST['hlist']);
  $pieces = explode(",",$plist );
  $pieces = array_unique($pieces);
  if($pieces[0]=="") unset($pieces[0]);
  $plist=implode(",",$pieces);

  }else if(isset($_GET['spid'])){
    //require_once("../include/check_get_key.php");
    $spid = intval($_GET['spid']);

    $plist = "";
    $sql = "select `problem_id` FROM `problem` WHERE `problem_id`>=? ";
    $result = pdo_query($sql,$spid);
    foreach($result as $row){
      if($plist) $plist.=',';
      $plist.=$row[0];
    }
  }

?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;600;700;800;900&display=swap');
/* ═══ CSS Variables (Design Tokens) ═══ */
:root {
  --cta-primary: #7c3aed;
  --cta-primary-dark: #6d28d9;
  --cta-primary-darker: #5b21b6;
  --cta-primary-light: #a78bfa;
  --cta-primary-softer: #ede9fe;
  --cta-primary-bg: #f3f0ff;
  --cta-danger: #ef4444;
  --cta-danger-bg: #fef2f2;
  --cta-danger-border: #fecaca;
  --cta-warning: #f59e0b;
  --cta-warning-bg: #fffbeb;
  --cta-success: #10b981;
  --cta-success-bg: #ecfdf5;
  --cta-surface: #fff;
  --cta-surface-sub: #faf9fd;
  --cta-border: #e5e0f0;
  --cta-border-hover: #c4b5fd;
  --cta-text: #1a1a2e;
  --cta-text-sub: #4b5563;
  --cta-text-mute: #9ca3af;
  --cta-divider: #f0ecf9;
  --cta-radius: 12px;
  --cta-radius-lg: 16px;
  --cta-shadow-sm: 0 1px 3px rgba(16,24,40,.05);
  --cta-shadow: 0 4px 14px rgba(124,58,237,.12);
  --cta-shadow-lg: 0 12px 40px rgba(124,58,237,.15);
  --cta-ease: cubic-bezier(.4, 0, .2, 1);
  --cta-ease-spring: cubic-bezier(.34, 1.56, .64, 1);
}
/* 리셋: hoj.css 영향 제거 */
.cta-wrap, .cta-wrap * { box-sizing: border-box; }
.cta-wrap { max-width: 1080px; margin: 32px auto 100px; padding: 0 24px; font-family: 'Noto Sans KR', 'Malgun Gothic', sans-serif; font-size: 15px; line-height: 1.5; color: var(--cta-text); }
.cta-card { background:var(--cta-surface); border-radius:var(--cta-radius-lg); box-shadow:0 4px 32px rgba(0,0,0,.06); border:1px solid var(--cta-divider); padding:40px 44px; }
.cta-head { display:flex; align-items:center; gap:14px; margin-bottom:6px; }
.cta-head-icon { font-size:32px; line-height:1; }
.cta-head h2 { font-size:28px; font-weight:800; color:#1a1a2e; margin:0; letter-spacing:-0.5px; line-height:1.2; }
.cta-head h2 em { color:#7c3aed; font-style:normal; }
.cta-sub { font-size:14px; color:#9ca3af; margin-bottom:32px; line-height:1.5; }

.cta-section { border-top:1px solid #f0ecf9; padding-top:28px; margin-top:28px; }
.cta-section:first-of-type { border-top:none; padding-top:0; margin-top:0; }
.cta-section-title {
  font-size:15px; font-weight:800; color:#7c3aed;
  letter-spacing:-0.2px; margin-bottom:16px;
  display:flex; align-items:center; gap:6px; line-height:1.3;
}

.cta-row { display:flex; gap:16px; margin-bottom:18px; flex-wrap:wrap; }
.cta-field { flex:1; min-width:0; }
.cta-field label {
  display:block; font-size:13px; font-weight:700; color:#4b5563;
  margin-bottom:8px; letter-spacing:0.1px; line-height:1.3;
}
.cta-field input[type=text],
.cta-field input[type=date],
.cta-field input[type=password],
.cta-field input[type=number],
.cta-field textarea {
  width:100%; padding:13px 16px; border:1.5px solid #e5e0f0;
  border-radius:12px; font-size:15px; line-height:1.4; color:#1a1a2e;
  background:#faf9fd; transition:all .2s; font-family:inherit;
  box-sizing:border-box; height:48px;
}
.cta-field textarea { height:auto; min-height:100px; resize:vertical; font-family:'Consolas', 'Monaco', monospace; font-size:14px; line-height:1.6; }
.cta-field input:focus, .cta-field select:focus, .cta-field textarea:focus {
  outline:none; border-color:#7c3aed; background:#fff;
  box-shadow:0 0 0 3px rgba(124,58,237,0.10);
}
/* Custom Select (fix native dropdown text invisible bug with hoj.css) */
.cta-field select,
select.cta-sel {
  display:block !important;
  width:100% !important;
  padding:13px 40px 13px 16px !important;
  font-size:15px !important;
  line-height:1.4 !important;
  color:#1a1a2e !important;
  background:#faf9fd url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path fill='%237c3aed' d='M6 8L0 0h12z'/></svg>") no-repeat right 14px center !important;
  background-size:12px 8px !important;
  border:1.5px solid #e5e0f0 !important;
  border-radius:12px !important;
  height:48px !important;
  font-family:'Noto Sans KR', 'Malgun Gothic', sans-serif !important;
  font-weight:500 !important;
  box-sizing:border-box !important;
  appearance:none !important;
  -webkit-appearance:none !important;
  -moz-appearance:none !important;
  cursor:pointer !important;
  transition:all .2s !important;
}
.cta-field select:hover, select.cta-sel:hover { border-color:#c4b5fd !important; }
.cta-field.small { max-width:120px; }
.cta-field.medium { max-width:220px; }
.cta-field .hint { font-size:12.5px; color:#9ca3af; margin-top:6px; line-height:1.5; }

/* 시간 입력 그룹 */
.cta-time-group { display:flex; gap:8px; align-items:center; }
.cta-time-group input[type=date] { flex:1; }
.cta-time-group input[type=number] { width:72px; text-align:center; padding:13px 8px; }
.cta-time-group span { color:#9ca3af; font-weight:700; font-size:14px; }

/* 체크박스 그룹 (forbidden flags) */
.cta-checkbox-grid {
  display:grid; grid-template-columns:repeat(2, 1fr); gap:10px;
}
.cta-checkbox-item {
  display:flex; align-items:flex-start; gap:12px; padding:16px 18px;
  background:#faf9fd; border:1.5px solid #e5e0f0; border-radius:12px;
  font-size:14px; color:#374151; cursor:pointer; transition:all .15s;
  font-weight:500;
}
.cta-checkbox-item:hover { border-color:#c4b5fd; background:#f5f3ff; }
.cta-checkbox-item input[type=checkbox] {
  accent-color:#7c3aed; width:18px; height:18px; margin:0; margin-top:1px;
  cursor:pointer; flex-shrink:0;
}
.cta-checkbox-item.checked {
  background:#f3f0ff; border-color:#7c3aed;
}
.cta-chk-content { display:flex; flex-direction:column; gap:4px; flex:1; min-width:0; }
.cta-chk-label { font-weight:700; font-size:14px; color:#1a1a2e; line-height:1.35; }
.cta-chk-desc { font-size:12.5px; color:#9ca3af; line-height:1.5; font-weight:400; }
.cta-checkbox-item.checked .cta-chk-label { color:#6d28d9; }
.cta-checkbox-item.checked .cta-chk-desc  { color:#8b5cf6; }

/* Problem Picker */
.pp-search { display:flex; gap:10px; margin-bottom:10px; }
.pp-search input { flex:1; padding:12px 16px; border:1.5px solid #e5e0f0; border-radius:12px; font-size:14px; background:#faf9fd; font-family:inherit; box-sizing:border-box; height:46px; }
.pp-search input:focus { outline:none; border-color:#7c3aed; background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,0.10); }
.pp-toggle { padding:10px 20px; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; border:1.5px solid #e5e0f0; background:#faf9fd; color:#7c3aed; transition:all .15s; font-family:inherit; white-space:nowrap; height:46px; }
.pp-toggle:hover { background:#f0ecf9; border-color:#d4c8f0; }
.pp-toggle.active { background:#7c3aed; color:#fff; border-color:#7c3aed; }
.pp-lang-filter { display:flex; gap:8px; margin-bottom:10px; }
.pp-lang-btn { padding:8px 18px; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; border:1.5px solid #e5e0f0; background:#faf9fd; color:#888; transition:all .15s; font-family:inherit; }
.pp-lang-btn:hover { background:#f0ecf9; border-color:#d4c8f0; color:#7c3aed; }
.pp-lang-btn.active { background:#7c3aed; color:#fff; border-color:#7c3aed; }
.pp-list { display:none; max-height:380px; overflow-y:auto; border:1.5px solid #e5e0f0; border-radius:12px; background:#fff; margin-top:10px; }
.pp-list::-webkit-scrollbar { width:6px; }
.pp-list::-webkit-scrollbar-thumb { background:#d4c8f0; border-radius:4px; }
.pp-list table { width:100%; border-collapse:collapse; font-size:14px; }
.pp-list thead tr { background:#f8f6fd; position:sticky; top:0; z-index:1; }
.pp-list th { padding:11px 14px; font-weight:700; color:#666; font-size:13px; }
.pp-row { transition:background .1s; cursor:pointer; }
.pp-row:hover { background:#f8f6fd; }
.pp-row.checked { background:#f0ecf9; }
.pp-row td { padding:10px 14px; font-size:14px; }
.pp-chk { width:18px; height:18px; accent-color:#7c3aed; cursor:pointer; }
.sel-tags { display:flex; flex-wrap:wrap; gap:8px; min-height:32px; margin-top:10px; margin-bottom:6px; }
.sel-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 12px 6px 14px; border-radius:100px; font-size:13px; font-weight:700; background:linear-gradient(135deg,#f0ecf9,#e8e0f8); color:#7c3aed; border:1px solid #d4c8f0; animation:tagPop .2s ease; }
.sel-tag .tx { width:18px; height:18px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; cursor:pointer; background:rgba(124,58,237,.15); color:#7c3aed; transition:all .15s; line-height:1; }
.sel-tag .tx:hover { background:#7c3aed; color:#fff; }
@keyframes tagPop { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
.sel-count { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:100px; font-size:13px; font-weight:700; background:#7c3aed; color:#fff; }

/* 언어 선택 (language grid) */
.cta-lang-grid {
  display:grid; grid-template-columns:repeat(4, 1fr); gap:10px;
}
.cta-lang-item {
  display:flex; align-items:center; justify-content:center; gap:8px;
  padding:14px 16px; background:#faf9fd;
  border:1.5px solid #e5e0f0; border-radius:12px;
  font-size:14px; color:#64748b; cursor:pointer; transition:all .15s;
  font-weight:700; user-select:none; min-height:50px;
}
.cta-lang-item input { display:none; }
.cta-lang-item:hover { background:#f5f3ff; border-color:#c4b5fd; color:#7c3aed; }
.cta-lang-item.active {
  background:linear-gradient(135deg, #7c3aed, #6d28d9);
  color:#fff; border-color:#7c3aed;
  box-shadow:0 4px 12px rgba(124,58,237,0.25);
  transform:translateY(-1px);
}

/* 수행평가 배점 하이라이트 */
.cta-exam-score {
  background:linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  border:1.5px solid #fdba74;
  padding:20px 22px;
  border-radius:14px;
  margin-top:6px;
}
.cta-exam-score label {
  color:#9a3412; font-size:14px; font-weight:800;
  display:flex; align-items:center; gap:6px; margin-bottom:10px; line-height:1.3;
}
.cta-exam-score select {
  background:#fff !important; border-color:#fdba74 !important;
}
.cta-exam-score select:focus { border-color:#f97316 !important; box-shadow:0 0 0 3px rgba(249,115,22,0.12) !important; }
.cta-exam-score .hint { color:#c2410c; font-weight:500; font-size:12.5px; margin-top:8px; }

/* 문제 목록 미리보기 */
#ptitles { margin-top:8px; display:flex; flex-direction:column; gap:4px; }
#ptitles a {
  display:inline-flex; align-items:center; gap:6px;
  padding:4px 10px; background:#f3f0ff; border-radius:6px;
  font-size:12px; color:#7c3aed; text-decoration:none; font-weight:600;
}
#ptitles a:hover { background:#e9d5ff; }

/* 사용자 목록 */
.cta-user-top {
  display:flex; justify-content:space-between; align-items:center; gap:8px;
  margin-bottom:6px;
}
.cta-user-top select {
  width:auto; padding:6px 10px; font-size:12px; font-weight:600;
  border:1px solid #d1d5db; border-radius:6px; background:#fff; cursor:pointer;
}

/* 버튼 */
.cta-btn {
  padding:14px 36px; border-radius:12px; font-size:15px; font-weight:700;
  cursor:pointer; border:none; font-family:inherit; transition:all .25s var(--cta-ease);
  text-decoration:none; display:inline-flex; align-items:center; gap:8px;
  line-height:1.3;
}
.cta-btn-cancel {
  background:#fff; color:#6b7280; border:1.5px solid #e5e7eb;
}
.cta-btn-cancel:hover { background:#f9fafb; color:var(--cta-text); border-color:#d1d5db; }
.cta-btn-submit {
  background:linear-gradient(135deg, var(--cta-primary), var(--cta-primary-dark)); color:#fff;
  box-shadow:0 4px 14px rgba(124,58,237,0.30);
}
.cta-btn-submit:hover {
  background:linear-gradient(135deg, var(--cta-primary-dark), var(--cta-primary-darker));
  transform:translateY(-1px); box-shadow:0 8px 24px rgba(124,58,237,0.40);
}
.cta-btn-submit:active { transform:translateY(0); }

/* ═══ Sticky Footer ═══ */
.cta-sticky-footer {
  position: sticky; bottom: 12px; z-index: 50;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px) saturate(180%);
  -webkit-backdrop-filter: blur(12px) saturate(180%);
  border: 1px solid var(--cta-divider);
  border-radius: 14px;
  padding: 14px 20px;
  margin-top: 28px;
  display: flex; gap: 12px; justify-content: space-between; align-items: center;
  box-shadow: 0 -2px 20px rgba(0,0,0,0.06);
  animation: stickySlide .4s var(--cta-ease) backwards;
}
@keyframes stickySlide { from { transform: translateY(20px); opacity: 0; } }
.cta-sticky-hint { font-size: 12.5px; color: var(--cta-text-mute); display: flex; align-items: center; gap: 6px; }
.cta-sticky-hint kbd { padding: 2px 7px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 5px; font-size: 11px; font-family: monospace; }
.cta-sticky-btns { display: flex; gap: 10px; }

/* ═══ 시간 설정 (지속시간 프리셋) ═══ */
.cta-duration-row { display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-start; }
.cta-duration-col { flex: 1; min-width: 240px; }
.cta-preset-btns { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
.cta-preset-btn {
  padding: 8px 14px; background: var(--cta-surface-sub);
  border: 1.5px solid var(--cta-border); border-radius: 10px;
  font-size: 13px; font-weight: 700; color: var(--cta-text-sub);
  cursor: pointer; transition: all .2s var(--cta-ease); font-family: inherit;
}
.cta-preset-btn:hover { border-color: var(--cta-border-hover); color: var(--cta-primary); background: var(--cta-primary-bg); }
.cta-preset-btn.active {
  background: linear-gradient(135deg, var(--cta-primary), var(--cta-primary-dark));
  color: #fff; border-color: var(--cta-primary);
  box-shadow: 0 2px 8px rgba(124,58,237,.25);
}
.cta-end-preview {
  background: linear-gradient(135deg, var(--cta-primary-softer), #faf5ff);
  border: 1.5px dashed var(--cta-primary-light); border-radius: var(--cta-radius);
  padding: 14px 18px; margin-top: 10px;
  display: flex; align-items: center; gap: 10px;
}
.cta-end-preview-icon { font-size: 20px; }
.cta-end-preview-text { flex: 1; }
.cta-end-preview-label { font-size: 11.5px; color: var(--cta-primary-dark); font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; }
.cta-end-preview-value { font-size: 15px; font-weight: 800; color: var(--cta-text); font-variant-numeric: tabular-nums; margin-top: 2px; }

/* ═══ 제한옵션 프리셋 (수행평가/일반/자유) ═══ */
.cta-lock-preset {
  display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;
  padding: 12px 14px; background: var(--cta-surface-sub);
  border-radius: var(--cta-radius); border: 1px solid var(--cta-divider);
}
.cta-lock-preset-label {
  font-size: 12px; font-weight: 700; color: var(--cta-text-sub);
  align-self: center; margin-right: 4px; letter-spacing: 0.3px;
}
.cta-lock-preset-btn {
  padding: 8px 16px; background: #fff;
  border: 1.5px solid var(--cta-border); border-radius: 10px;
  font-size: 13px; font-weight: 700; color: var(--cta-text-sub);
  cursor: pointer; transition: all .2s var(--cta-ease); font-family: inherit;
  display: inline-flex; align-items: center; gap: 5px;
  position: relative;
}
.cta-lock-preset-btn:hover { border-color: var(--cta-border-hover); color: var(--cta-primary); transform: translateY(-1px); }
.cta-lock-preset-btn.preset-exam:hover { background: #fff7ed; border-color: #fdba74; color: #c2410c; }
.cta-lock-preset-btn.preset-contest:hover { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }
.cta-lock-preset-btn.preset-practice:hover { background: #f0fdf4; border-color: #86efac; color: #15803d; }

/* 프리셋 툴팁 */
.cta-lock-preset-btn::after {
  content: attr(data-tip);
  position: absolute; top: 100%; left: 50%;
  transform: translateX(-50%) translateY(8px);
  background: #1a1a2e; color: #fff;
  padding: 10px 14px; border-radius: 10px;
  font-size: 12px; font-weight: 500; line-height: 1.5;
  white-space: nowrap; letter-spacing: 0.2px;
  opacity: 0; pointer-events: none;
  transition: opacity .2s var(--cta-ease), transform .2s var(--cta-ease);
  z-index: 100; box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.cta-lock-preset-btn::before {
  content: ''; position: absolute; top: 100%; left: 50%;
  transform: translateX(-50%) translateY(4px);
  border: 6px solid transparent; border-bottom-color: #1a1a2e;
  opacity: 0; pointer-events: none;
  transition: opacity .2s var(--cta-ease);
  z-index: 101;
}
.cta-lock-preset-btn:hover::after {
  opacity: 1; transform: translateX(-50%) translateY(12px);
}
.cta-lock-preset-btn:hover::before { opacity: 1; }

/* ═══ 체크박스 그룹 레이블 ═══ */
.cta-lock-group { margin-bottom: 14px; }
.cta-lock-group:last-child { margin-bottom: 0; }
.cta-lock-group-label {
  display: flex; align-items: center; gap: 8px;
  font-size: 12px; font-weight: 800;
  color: var(--cta-text-sub); letter-spacing: 0.3px;
  margin-bottom: 8px; padding-left: 2px;
  text-transform: uppercase;
}
.cta-lock-group-label::after {
  content: ''; flex: 1; height: 1px;
  background: linear-gradient(90deg, var(--cta-divider), transparent);
}
.cta-lock-group.group-special .cta-lock-group-label { color: #c2410c; }

/* ═══ 기본값 배지 (수행평가 권장) ═══ */
.cta-chk-badge {
  display: inline-flex; align-items: center;
  padding: 1px 7px; margin-left: 6px;
  background: linear-gradient(135deg, #fef3c7, #fcd34d);
  color: #78350f; border-radius: 100px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.3px;
  vertical-align: middle;
}

/* ═══ 위험 경고 뱃지 (bit 4, 7 체크 시) ═══ */
.cta-warn-badge {
  display: none; margin-top: 6px;
  padding: 8px 12px 8px 14px;
  background: linear-gradient(135deg, #fef2f2, #fee2e2);
  border: 1px solid #fca5a5; border-radius: 10px;
  color: #991b1b; font-size: 12px; font-weight: 600;
  line-height: 1.5; position: relative;
}
.cta-warn-badge.show {
  display: block; animation: warnPulse .4s var(--cta-ease);
}
@keyframes warnPulse {
  0% { transform: scale(.9); opacity: 0; }
  60% { transform: scale(1.02); }
  100% { transform: scale(1); opacity: 1; }
}
.cta-warn-badge strong { color: #7f1d1d; }

/* ═══ 선생님 팁 아코디언 ═══ */
.cta-tip-guide {
  margin-top: 16px;
  background: linear-gradient(135deg, #faf5ff, #f5f3ff);
  border: 1px solid #ddd6fe; border-radius: var(--cta-radius);
  overflow: hidden;
}
.cta-tip-guide summary {
  padding: 14px 18px; cursor: pointer;
  font-size: 13px; font-weight: 700;
  color: var(--cta-primary-dark);
  list-style: none;
  display: flex; align-items: center; gap: 8px;
  transition: background .2s var(--cta-ease);
  user-select: none;
}
.cta-tip-guide summary::-webkit-details-marker { display: none; }
.cta-tip-guide summary:hover { background: rgba(124, 58, 237, 0.08); }
.cta-tip-guide summary .tip-caret {
  margin-left: auto;
  transition: transform .3s var(--cta-ease-spring);
  font-size: 10px;
}
.cta-tip-guide[open] summary .tip-caret { transform: rotate(180deg); }
.cta-tip-body {
  padding: 6px 14px 14px; background: #fff;
  border-top: 1px solid #ddd6fe;
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;
}
.cta-tip-case {
  padding: 12px 14px;
  background: var(--cta-surface-sub); border-radius: 10px;
  border: 1px solid var(--cta-divider);
  transition: all .2s var(--cta-ease);
}
.cta-tip-case:hover { border-color: var(--cta-primary-light); transform: translateY(-1px); }
.cta-tip-case h5 {
  margin: 0 0 4px; font-size: 13px; font-weight: 800;
  color: var(--cta-text); line-height: 1.3;
}
.cta-tip-case p {
  margin: 0; font-size: 11.5px; color: var(--cta-text-sub);
  line-height: 1.55;
}
@media(max-width: 700px) {
  .cta-tip-body { grid-template-columns: 1fr; }
}

/* ═══ 비밀번호 조건부 ═══ */
.cta-field-pw.disabled input {
  background: #f3f4f6 !important; color: #9ca3af !important;
  border-style: dashed !important; cursor: not-allowed !important;
}
.cta-field-pw.disabled label { opacity: 0.5; }
.cta-field-pw.required input { border-color: #fda4af !important; background: #fff1f2 !important; }
.cta-field-pw.required label::after {
  content: "필수"; margin-left: 6px; padding: 1px 7px;
  background: #fecaca; color: #991b1b; border-radius: 100px;
  font-size: 10px; font-weight: 800;
}

/* ═══ 고급 설정 토글 ═══ */
.cta-advanced-toggle {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 14px; background: transparent;
  border: 1.5px dashed var(--cta-border); border-radius: 10px;
  font-size: 12.5px; font-weight: 600; color: var(--cta-text-mute);
  cursor: pointer; transition: all .2s var(--cta-ease); font-family: inherit;
  margin-top: 12px;
}
.cta-advanced-toggle:hover { border-color: var(--cta-primary-light); color: var(--cta-primary); background: var(--cta-primary-bg); }
.cta-advanced-toggle .caret { transition: transform .3s var(--cta-ease-spring); display: inline-block; }
.cta-advanced-toggle.open .caret { transform: rotate(180deg); }
.cta-advanced {
  max-height: 0; overflow: hidden;
  transition: max-height .4s var(--cta-ease), margin-top .3s var(--cta-ease);
  margin-top: 0;
}
.cta-advanced.open { max-height: 400px; margin-top: 14px; }
.cta-advanced-inner {
  padding: 16px; background: var(--cta-surface-sub);
  border-radius: var(--cta-radius); border: 1px solid var(--cta-divider);
}

/* ═══ 언어 버튼 배지 ═══ */
.cta-lang-item { position: relative; overflow: hidden; }
.cta-lang-item::before {
  content: ''; position: absolute; top: 10px; right: 10px;
  width: 8px; height: 8px; border-radius: 50%;
  transition: transform .2s var(--cta-ease);
}
.cta-lang-item[data-lang-id="0"]::before { background: #3730a3; }
.cta-lang-item[data-lang-id="1"]::before { background: #6d28d9; }
.cta-lang-item[data-lang-id="6"]::before { background: #f59e0b; }
.cta-lang-item.active::before { background: rgba(255,255,255,0.9) !important; }

/* ═══ 선택된 문제 태그 (제목 포함) ═══ */
.sel-tag.with-title { padding-left: 12px; padding-right: 10px; }
.sel-tag .sel-pid { font-weight: 900; color: var(--cta-primary-dark); }
.sel-tag .sel-title { font-weight: 500; color: var(--cta-text-sub); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ═══ 실시간 Validation 메시지 ═══ */
.cta-field-err {
  display: none; padding: 8px 12px; margin-top: 6px;
  background: var(--cta-danger-bg); border: 1px solid var(--cta-danger-border);
  border-radius: 8px; font-size: 12.5px; color: var(--cta-danger);
  font-weight: 600; line-height: 1.4;
  animation: errShake .3s var(--cta-ease);
}
.cta-field-err.show { display: block; }
.cta-field-warn {
  display: none; padding: 8px 12px; margin-top: 6px;
  background: var(--cta-warning-bg); border: 1px solid #fcd34d;
  border-radius: 8px; font-size: 12.5px; color: #92400e;
  font-weight: 600; line-height: 1.4;
}
.cta-field-warn.show { display: block; animation: errShake .3s var(--cta-ease); }
@keyframes errShake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-4px)} 75%{transform:translateX(4px)} }
.cta-field input.invalid, .cta-field select.invalid {
  border-color: var(--cta-danger) !important;
  box-shadow: 0 0 0 3px rgba(239,68,68,0.10) !important;
}

/* ═══ 성공 토스트 ═══ */
#cta-toast {
  position: fixed; top: 24px; right: 24px; z-index: 9999;
  background: #fff; border: 1px solid var(--cta-success);
  border-left: 6px solid var(--cta-success); border-radius: 12px;
  padding: 16px 22px; min-width: 280px; max-width: 400px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15);
  display: none; animation: toastIn .4s var(--cta-ease-spring);
}
#cta-toast.show { display: block; }
@keyframes toastIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
#cta-toast .toast-title { font-weight: 800; color: var(--cta-text); margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
#cta-toast .toast-body { font-size: 13px; color: var(--cta-text-sub); }
#cta-toast .toast-actions { display: flex; gap: 8px; margin-top: 10px; }
#cta-toast .toast-actions a { padding: 6px 12px; background: var(--cta-success-bg); color: var(--cta-success); border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none; }

/* KindEditor 크기 업 */
.cta-field .ke-container { border-radius:12px !important; border:1.5px solid var(--cta-border) !important; }
.cta-field .ke-toolbar { border-radius:12px 12px 0 0 !important; }

@media(max-width:860px) {
  .cta-card { padding:32px 24px; }
  .cta-checkbox-grid { grid-template-columns:1fr; }
  .cta-lang-grid { grid-template-columns:repeat(3, 1fr); }
  .cta-head h2 { font-size:24px; }
  .cta-row { gap:12px; }
  .cta-duration-row { flex-direction: column; }
}
@media(max-width:600px) {
  .cta-wrap { padding:0 16px; }
  .cta-card { padding:24px 18px; border-radius:14px; }
  .cta-lang-grid { grid-template-columns:repeat(2, 1fr); }
  .cta-head h2 { font-size:22px; }
  .cta-head-icon { font-size:26px; }
  .cta-section-title { font-size:14px; }
  .cta-sticky-hint { display: none; }
}
</style>

<div class="cta-wrap">
  <div class="cta-card">
    <div class="cta-head">
      <span class="cta-head-icon">🎯</span>
      <h2><em>수행평가 / 대회</em> 만들기</h2>
    </div>
    <div class="cta-sub" id="cta-subtitle">기본 정보를 입력하고 수행평가 배점·문제·권한을 설정하세요.</div>

    <form method="POST" id="ctaForm">

      <!-- ═══ 기본 정보 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">📝 기본 정보</div>
        <div class="cta-row">
          <div class="cta-field">
            <label>대회 제목</label>
            <input type="text" id="f-title" name="title" value="<?php echo htmlspecialchars(isset($title)?$title:'',ENT_QUOTES)?>" placeholder="예: 2학년 정보 1차 수행평가" autocomplete="off">
            <div class="cta-field-err" id="err-title">대회 제목을 입력해주세요.</div>
          </div>
        </div>

        <!-- 시작 시간 + 지속시간 프리셋 -->
        <div class="cta-duration-row">
          <div class="cta-duration-col">
            <div class="cta-field">
              <label>📅 시작 시간</label>
              <div class="cta-time-group">
                <input type="date" id="f-startdate" name="startdate" value="<?php echo date('Y-m-d')?>">
                <input type="number" id="f-shour" name="shour" min="0" max="23" value="<?php echo date('H')?>"><span>시</span>
                <input type="number" id="f-sminute" name="sminute" min="0" max="59" value="00"><span>분</span>
              </div>
            </div>
          </div>
          <div class="cta-duration-col">
            <div class="cta-field">
              <label>⏱ 지속시간</label>
              <div class="cta-preset-btns">
                <button type="button" class="cta-preset-btn active" data-min="35" onclick="ctaSetDuration(35, this)">📝 수행평가 (35분)</button>
                <button type="button" class="cta-preset-btn" data-min="60" onclick="ctaSetDuration(60, this)">⏱ 1시간</button>
                <button type="button" class="cta-preset-btn" data-min="120" onclick="ctaSetDuration(120, this)">🏆 2시간</button>
                <button type="button" class="cta-preset-btn" data-min="-1" onclick="ctaSetDuration(-1, this)">📅 직접 설정</button>
              </div>
              <div class="cta-custom-time" id="f-custom-time" style="display:none;margin-top:10px">
                <div class="cta-time-group">
                  <input type="date" id="f-enddate-manual" value="<?php echo date('Y-m-d')?>">
                  <input type="number" id="f-ehour-manual" min="0" max="23" value="<?php echo (date('H')+1)%24?>"><span>시</span>
                  <input type="number" id="f-eminute-manual" min="0" max="59" value="00"><span>분</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 종료 시간 미리보기 -->
        <div class="cta-end-preview">
          <span class="cta-end-preview-icon">🏁</span>
          <div class="cta-end-preview-text">
            <div class="cta-end-preview-label">종료 시간 (자동 계산)</div>
            <div class="cta-end-preview-value" id="cta-end-display">—</div>
          </div>
        </div>
        <div class="cta-field-err" id="err-time">시작보다 빠른 종료 시간입니다.</div>

        <!-- 실제 POST 필드 (계산된 값) -->
        <input type="hidden" name="enddate" id="f-enddate">
        <input type="hidden" name="ehour" id="f-ehour">
        <input type="hidden" name="eminute" id="f-eminute">
      </div>

      <!-- ═══ 공개 설정 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">🔒 공개 설정</div>
        <div class="cta-row">
          <div class="cta-field medium">
            <label>공개 여부</label>
            <select id="f-private" name="private" class="cta-sel" onchange="ctaTogglePrivate()">
              <option value="0" <?php echo ($private??'1')=='0'?'selected':''?>>공개</option>
              <option value="1" <?php echo ($private??'1')=='1'?'selected':''?>>비공개 (비밀번호)</option>
            </select>
          </div>
          <div class="cta-field medium cta-field-pw" id="f-pw-wrap">
            <label>비밀번호</label>
            <input type="text" id="f-password" name="password" placeholder="예: 1234">
            <div class="cta-field-err" id="err-pw">비공개 대회는 비밀번호가 필요합니다.</div>
          </div>
        </div>

        <!-- 수행평가 배점 (강조) -->
        <div class="cta-exam-score">
          <label>📝 수행평가 배점</label>
          <select id="f-exam" name="exam_max_score" class="cta-sel" onchange="ctaUpdateSubjectHint()">
            <option value="0">자동 감지 (문제 ID로 판단)</option>
            <option value="20">2학년 정보 (C) — 20점 만점</option>
            <option value="40">3학년 인공지능기초 (Python) — 40점 만점</option>
          </select>
          <div class="hint">선택 시 순위표/통계에 자동 점수가 해당 기준으로 표시됩니다.</div>
        </div>

        <!-- 고급 설정 토글 -->
        <button type="button" class="cta-advanced-toggle" id="cta-adv-toggle" onclick="ctaToggleAdvanced()">
          ⚙️ 고급 설정 <span class="caret">▼</span>
        </button>
        <div class="cta-advanced" id="cta-advanced">
          <div class="cta-advanced-inner">
            <div class="cta-field">
              <label>🌐 접속 IP 대역 (subnet)</label>
              <input type="text" name="subnet" placeholder="예: 192.168.1.0/24 · 비워두면 전체 허용">
              <div class="hint" style="font-size:12px;color:var(--cta-text-mute);margin-top:6px">컴퓨터실 IP 대역만 허용하여 외부 접속을 차단합니다.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══ 문제 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">📚 문제 설정</div>
        <div class="cta-field">
          <label>📝 문제 선택</label>
          <input type="hidden" id="plist" name="cproblem" value="<?php echo htmlspecialchars(isset($plist)?$plist:'',ENT_QUOTES)?>">
          <div class="pp-search">
            <input type="text" id="pp-search-input" placeholder="문제 ID 또는 제목 검색...">
            <button type="button" class="pp-toggle" id="pp-toggle-btn" onclick="ctaToggleList()">📋 전체 목록</button>
          </div>
          <div class="pp-lang-filter">
            <button type="button" class="pp-lang-btn active" data-lang="all" onclick="ctaFilterLang(this)">전체</button>
            <button type="button" class="pp-lang-btn" data-lang="c" onclick="ctaFilterLang(this)">C언어</button>
            <button type="button" class="pp-lang-btn" data-lang="py" onclick="ctaFilterLang(this)">Python</button>
          </div>
          <div class="sel-tags" id="sel-tags"></div>
          <div class="pp-list" id="pp-list">
            <table>
              <thead>
                <tr>
                  <th style="width:40px;text-align:center"></th>
                  <th style="width:70px;text-align:center">ID</th>
                  <th style="text-align:left">제목</th>
                </tr>
              </thead>
              <tbody id="pp-tbody">
              <?php
                $all_probs = pdo_query("SELECT problem_id, title, defunct FROM problem ORDER BY problem_id ASC");
                if($all_probs) foreach($all_probs as $p):
                  $is_hidden = ($p['defunct'] === 'Y');
              ?>
                <tr class="pp-row<?php echo $is_hidden ? ' pp-hidden' : ''?>" data-pid="<?php echo $p['problem_id']?>" data-title="<?php echo htmlspecialchars(strtolower($p['title']), ENT_QUOTES)?>">
                  <td style="text-align:center"><input type="checkbox" class="pp-chk" value="<?php echo $p['problem_id']?>"></td>
                  <td style="text-align:center;font-weight:700;color:<?php echo $is_hidden ? '#94a3b8' : '#7c3aed'?>"><?php echo $p['problem_id']?></td>
                  <td>
                    <?php echo htmlspecialchars($p['title'])?>
                    <?php if($is_hidden): ?><span style="display:inline-block;margin-left:8px;padding:2px 8px;font-size:11px;font-weight:700;color:#64748b;background:#f1f5f9;border:1px solid #cbd5e1;border-radius:10px">숨김</span><?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ═══ 허용 언어 (필수) ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">💻 허용 언어 <span style="color:var(--cta-danger);font-weight:800">*</span></div>
        <div class="cta-lang-grid" id="f-lang-grid">
          <?php
          $lang_count = count($language_ext);
          for($i=0; $i<$lang_count; $i++){
            if((1<<$i) & $OJ_LANGMASK) continue;
            echo "<label class='cta-lang-item' data-lang-id='$i'>";
            echo "<input type='checkbox' class='lang-chk' name='lang[]' value='$i' onchange='ctaToggleLang(this)'>";
            echo "<span>".$language_name[$i]."</span>";
            echo "</label>";
          }
          ?>
        </div>
        <div class="cta-field-err" id="err-lang">⚠️ 최소 1개 이상의 언어를 선택해야 합니다.</div>
        <div class="hint" style="margin-top:8px;font-size:12.5px;color:var(--cta-text-mute)">💡 학생들이 제출할 수 있는 언어를 체크하세요.</div>
      </div>

      <!-- ═══ 제한 플래그 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">🚫 대회 제한 옵션</div>

        <!-- 프리셋 버튼 (툴팁 포함) -->
        <div class="cta-lock-preset">
          <span class="cta-lock-preset-label">빠른 설정:</span>
          <button type="button" class="cta-lock-preset-btn preset-exam" data-tip="✔ 5개 체크 · 수행평가 최적 (bits 0·1·2·3·6)" onclick="ctaApplyLockPreset('exam')">
            📝 수행평가
          </button>
          <button type="button" class="cta-lock-preset-btn preset-contest" data-tip="✔ 3개 체크 · 최소 보안 (bits 0·1·3)" onclick="ctaApplyLockPreset('contest')">
            🏆 일반 대회
          </button>
          <button type="button" class="cta-lock-preset-btn preset-practice" data-tip="✔ 모두 해제 · 결과 즉시 확인 가능" onclick="ctaApplyLockPreset('practice')">
            🔓 자유 연습
          </button>
        </div>

        <?php
        $cta_locks_data = [
          0 => ['🔒 이전 제출 코드 열람 금지',  '본인이 과거에 제출한 코드를 못 보게 막음 (복붙 방지)'],
          1 => ['📥 문제 다운로드 금지',         '문제 첨부파일 다운로드 버튼을 숨김'],
          2 => ['🏆 전체 랭킹에서 제외',         '대회 중엔 순위표가 숨겨지고, 사이트 전체 랭킹에도 반영되지 않음'],
          3 => ['👥 타인 제출 기록 숨기기',      '다른 학생의 제출·결과를 볼 수 없음'],
          4 => ['❓ 대회 종료 전까지 결과 숨김',  '제출 후 AC/WA 결과가 대회 끝날 때까지 안 보임'],
          5 => ['🔍 정답과 비교 결과 숨기기',    '틀렸을 때 내 출력 vs 정답 비교 화면을 감춤'],
          6 => ['⏰ 대회 종료 후 재도전 금지',   '대회 끝난 후에는 같은 문제를 다시 제출할 수 없음'],
          7 => ['📝 마지막 제출만 채점',         '가장 마지막에 제출한 것만 점수로 인정'],
          8 => ['🤖 AI 도움 기능 숨기기',        '문제 페이지의 AI 힌트 버튼을 숨김'],
        ];
        $default_bits = (1<<0) | (1<<1) | (1<<2) | (1<<3) | (1<<6); // 수행평가 기본값 (0,1,2,3,6)

        // 그룹화: 사용 빈도와 성격에 따라 재배치
        $cta_lock_groups = [
          'security' => ['🛡️ 기본 보안 (자주 사용)', [0, 3, 5], ''],
          'access'   => ['🚷 접근 제한',             [1, 6, 8], ''],
          'ranking'  => ['📊 랭킹 정책',             [2],       ''],
          'special'  => ['⚠️ 특수 모드 (신중히)',   [4, 7],    'group-special'],
        ];

        $render_lock = function($bit) use ($cta_locks_data, $default_bits) {
          list($label, $desc) = $cta_locks_data[$bit];
          $is_default = ($default_bits & (1<<$bit)) !== 0;
          $checked = $is_default ? 'checked' : '';
          $active = $is_default ? 'checked' : '';
          $badge = $is_default ? "<span class='cta-chk-badge'>기본</span>" : '';
          echo "<label class='cta-checkbox-item $active' data-bit='$bit'>";
          echo "<input type='checkbox' class='lock-chk' name='contest_type[]' value='$bit' $checked onchange='this.parentNode.classList.toggle(\"checked\",this.checked); ctaCheckDangerBits();'>";
          echo "<div class='cta-chk-content'>";
          echo "<div class='cta-chk-label'>".htmlspecialchars($label, ENT_QUOTES, 'UTF-8').$badge."</div>";
          echo "<div class='cta-chk-desc'>".htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')."</div>";
          echo "</div></label>";
        };

        foreach ($cta_lock_groups as $gkey => $ginfo) {
          list($glabel, $gbits, $gclass) = $ginfo;
          echo "<div class='cta-lock-group $gclass'>";
          echo "<div class='cta-lock-group-label'>$glabel</div>";
          echo "<div class='cta-checkbox-grid'>";
          foreach ($gbits as $bit) { $render_lock($bit); }
          echo "</div>";
          echo "</div>";
        }
        ?>

        <!-- 위험 조합 경고 (bit 4, 7 체크 시 동적 표시) -->
        <div class="cta-warn-badge" id="cta-warn-bit7">
          ⚠️ <strong>"마지막 제출만 채점"</strong>이 켜져 있어요. 수행평가에서는 학생이 AC 후 실수로 WA 제출 시 점수가 날아갑니다. 꼭 필요한 경우에만 체크하세요.
        </div>
        <div class="cta-warn-badge" id="cta-warn-bit4">
          ⚠️ <strong>"대회 종료 전까지 결과 숨김"</strong>이 켜져 있어요. 학생이 본인 AC 여부를 모르므로 수행평가엔 비추천합니다. (NOIP·정보올림피아드 방식)
        </div>

        <!-- 선생님 팁 아코디언 -->
        <details class="cta-tip-guide">
          <summary>
            <span>💡</span>
            <span>어떤 조합을 언제 쓰나요? (가이드)</span>
            <span class="tip-caret">▼</span>
          </summary>
          <div class="cta-tip-body">
            <div class="cta-tip-case">
              <h5>📝 수행평가 (내신 반영)</h5>
              <p>기본 5개 체크 — 소스 잠금·다운로드·랭킹 제외·타인 숨김·재도전 금지. <strong>추천</strong></p>
            </div>
            <div class="cta-tip-case">
              <h5>🏆 일반 대회 (연습 목적)</h5>
              <p>최소 3개만 (이전 코드·다운로드·타인 숨김). 학생들이 실시간 결과 확인 가능.</p>
            </div>
            <div class="cta-tip-case">
              <h5>🎯 정보올림피아드 모의</h5>
              <p>기본 5개 + "대회 종료 전까지 결과 숨김" + "diff 숨김" 추가. NOIP 방식.</p>
            </div>
            <div class="cta-tip-case">
              <h5>🔓 자유 연습 / 이벤트</h5>
              <p>모든 옵션 해제. 학생들이 서로 공유·토론하며 학습.</p>
            </div>
          </div>
        </details>
      </div>

      <!-- ═══ 설명 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">✍️ 대회 설명</div>
        <div class="cta-field">
          <textarea class="kindeditor" name="description" rows="8"><?php echo isset($description)?$description:""?></textarea>
        </div>
      </div>

      <!-- 참가자 목록은 제거됨 (비공개 대회도 비밀번호로 충분) -->
      <input type="hidden" name="ulist" value="">

      <!-- Sticky Footer -->
      <div class="cta-sticky-footer">
        <div class="cta-sticky-hint">
          <span>💡 <strong>Tip:</strong> 프리셋 버튼으로 빠르게 설정하세요</span>
        </div>
        <div class="cta-sticky-btns">
          <?php require_once("../include/set_post_key.php");?>
          <a href="contest_list.php" class="cta-btn cta-btn-cancel">← 취소</a>
          <button type="submit" name="submit" class="cta-btn cta-btn-submit">💾 대회 생성</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- 성공 토스트 -->
<div id="cta-toast">
  <div class="toast-title"><span>✅</span><span>생성 완료</span></div>
  <div class="toast-body" id="cta-toast-body">대회가 성공적으로 생성되었습니다.</div>
</div>

<script>
// ═══════════════════════════════════════════════════════════
// 🎯 Contest Add — Modern Form Handler
// ═══════════════════════════════════════════════════════════

// ─── 지속시간 프리셋 + 자동 종료 시간 계산 ───
var ctaDurationMin = 35; // 기본 35분
function ctaSetDuration(min, btn) {
  document.querySelectorAll('.cta-preset-btn').forEach(b=>b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  var custom = document.getElementById('f-custom-time');
  if (min === -1) {
    custom.style.display = 'block';
    ctaDurationMin = -1;
  } else {
    custom.style.display = 'none';
    ctaDurationMin = min;
  }
  ctaRecalcEnd();
}
function ctaRecalcEnd() {
  var sd = document.getElementById('f-startdate').value;
  var sh = parseInt(document.getElementById('f-shour').value) || 0;
  var sm = parseInt(document.getElementById('f-sminute').value) || 0;
  if (!sd) return;

  var endDate, eh, em;
  if (ctaDurationMin === -1) {
    endDate = document.getElementById('f-enddate-manual').value;
    eh = parseInt(document.getElementById('f-ehour-manual').value) || 0;
    em = parseInt(document.getElementById('f-eminute-manual').value) || 0;
  } else {
    var start = new Date(sd + 'T' + String(sh).padStart(2,'0') + ':' + String(sm).padStart(2,'0') + ':00');
    var end = new Date(start.getTime() + ctaDurationMin * 60 * 1000);
    var y = end.getFullYear();
    var mo = String(end.getMonth()+1).padStart(2,'0');
    var d = String(end.getDate()).padStart(2,'0');
    endDate = y + '-' + mo + '-' + d;
    eh = end.getHours();
    em = end.getMinutes();
  }

  // Hidden inputs 세팅 (실제 POST 값)
  document.getElementById('f-enddate').value = endDate;
  document.getElementById('f-ehour').value = eh;
  document.getElementById('f-eminute').value = em;

  // 미리보기
  var disp = document.getElementById('cta-end-display');
  if (endDate) {
    var pm = eh >= 12 ? '오후' : '오전';
    var h12 = eh % 12 || 12;
    disp.textContent = endDate + ' (' + pm + ' ' + h12 + '시 ' + String(em).padStart(2,'0') + '분)';
    // 유효성: end > start?
    var startTs = new Date(sd + 'T' + String(sh).padStart(2,'0') + ':' + String(sm).padStart(2,'0'));
    var endTs = new Date(endDate + 'T' + String(eh).padStart(2,'0') + ':' + String(em).padStart(2,'0'));
    var err = document.getElementById('err-time');
    if (endTs <= startTs) {
      err.classList.add('show');
    } else {
      err.classList.remove('show');
    }
  }
}
['f-startdate','f-shour','f-sminute','f-enddate-manual','f-ehour-manual','f-eminute-manual'].forEach(function(id){
  var el = document.getElementById(id);
  if (el) el.addEventListener('input', ctaRecalcEnd);
});
ctaRecalcEnd(); // 초기 계산

// ─── 공개여부 → 비밀번호 조건부 활성화 ───
function ctaTogglePrivate() {
  var priv = document.getElementById('f-private').value;
  var pwWrap = document.getElementById('f-pw-wrap');
  var pwInput = document.getElementById('f-password');
  if (priv === '1') {
    pwWrap.classList.remove('disabled');
    pwWrap.classList.add('required');
    pwInput.disabled = false;
    pwInput.placeholder = '예: 1234';
  } else {
    pwWrap.classList.remove('required');
    pwWrap.classList.add('disabled');
    pwInput.disabled = true;
    pwInput.value = '';
    pwInput.placeholder = '공개 대회는 불필요';
  }
  document.getElementById('err-pw').classList.remove('show');
}
ctaTogglePrivate();

// ─── 고급 설정 토글 ───
function ctaToggleAdvanced() {
  var adv = document.getElementById('cta-advanced');
  var btn = document.getElementById('cta-adv-toggle');
  adv.classList.toggle('open');
  btn.classList.toggle('open');
}

// ─── 허용 언어 토글 (시각 상태 + 에러 해제) ───
function ctaToggleLang(chk) {
  chk.parentNode.classList.toggle('active', chk.checked);
  if (document.querySelectorAll('.lang-chk:checked').length > 0) {
    document.getElementById('err-lang').classList.remove('show');
  }
}

// ─── 수행평가 배점 → 제목 placeholder + 서브타이틀 ───
function ctaUpdateSubjectHint() {
  var exam = document.getElementById('f-exam').value;
  var title = document.getElementById('f-title');
  var sub = document.getElementById('cta-subtitle');
  if (exam === '20') {
    title.placeholder = '예: 2학년 정보 1차 수행평가';
    sub.innerHTML = '📘 <strong>2학년 정보 (C)</strong> · 20점 만점으로 채점됩니다';
  } else if (exam === '40') {
    title.placeholder = '예: 3학년 인공지능기초 1차 수행평가';
    sub.innerHTML = '🐍 <strong>3학년 인공지능기초 (Python)</strong> · 40점 만점으로 채점됩니다';
  } else {
    title.placeholder = '예: 2학년 정보 1차 수행평가';
    sub.textContent = '기본 정보를 입력하고 수행평가 배점·문제·권한을 설정하세요.';
  }
}

// ─── 제한옵션 프리셋 ───
function ctaApplyLockPreset(preset) {
  var presets = {
    'exam':     [0,1,2,3,6],
    'contest':  [0,1,3],
    'practice': []
  };
  var bits = presets[preset] || [];
  document.querySelectorAll('.lock-chk').forEach(function(c){
    var bit = parseInt(c.value);
    c.checked = bits.indexOf(bit) !== -1;
    c.parentNode.classList.toggle('checked', c.checked);
  });
  ctaCheckDangerBits();
}

// ─── 위험 조합 경고 (bit 4, 7) ───
function ctaCheckDangerBits() {
  var b4 = document.querySelector('.lock-chk[value="4"]');
  var b7 = document.querySelector('.lock-chk[value="7"]');
  var w4 = document.getElementById('cta-warn-bit4');
  var w7 = document.getElementById('cta-warn-bit7');
  if (w4) w4.classList.toggle('show', b4 && b4.checked);
  if (w7) w7.classList.toggle('show', b7 && b7.checked);
}
ctaCheckDangerBits();

// ═══ Problem Picker ═══
var ctaCurrentLang = 'all';
var ctaProbTitles = {};  // pid → title 매핑
document.querySelectorAll('.pp-row').forEach(function(r){
  ctaProbTitles[r.dataset.pid] = r.querySelector('td:last-child').textContent.trim();
});

function ctaFilterLang(btn) {
  document.querySelectorAll('.pp-lang-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  ctaCurrentLang = btn.dataset.lang;
  ctaApplyFilters();
  document.getElementById('pp-list').style.display = 'block';
  document.getElementById('pp-toggle-btn').classList.add('active');
}
function ctaApplyFilters() {
  var q = document.getElementById('pp-search-input').value.toLowerCase();
  document.querySelectorAll('.pp-row').forEach(function(row){
    var pid = parseInt(row.dataset.pid);
    var title = row.dataset.title;
    var langOk = (ctaCurrentLang==='all') || (ctaCurrentLang==='c' && pid<1000) || (ctaCurrentLang==='py' && pid>=1000);
    var searchOk = !q || row.dataset.pid.indexOf(q)!==-1 || title.indexOf(q)!==-1;
    row.style.display = (langOk && searchOk) ? '' : 'none';
  });
}
function ctaToggleList() {
  var list = document.getElementById('pp-list');
  var btn = document.getElementById('pp-toggle-btn');
  if (list.style.display==='block') { list.style.display='none'; btn.classList.remove('active'); }
  else { list.style.display='block'; btn.classList.add('active'); }
}
document.getElementById('pp-search-input').addEventListener('input', function(){
  ctaApplyFilters();
  if(this.value){ document.getElementById('pp-list').style.display='block'; document.getElementById('pp-toggle-btn').classList.add('active'); }
});
document.querySelectorAll('.pp-row').forEach(function(row){
  row.addEventListener('click', function(e){
    if(e.target.type==='checkbox') return;
    var chk = this.querySelector('.pp-chk');
    chk.checked = !chk.checked;
    ctaRefreshSel();
  });
});
document.querySelectorAll('.pp-chk').forEach(function(chk){ chk.addEventListener('change', ctaRefreshSel); });

function ctaRefreshSel() {
  var checks = document.querySelectorAll('.pp-chk:checked');
  var ids = [];
  var html = '';
  checks.forEach(function(c){
    ids.push(c.value);
    var title = ctaProbTitles[c.value] || '';
    if (title.length > 24) title = title.substring(0, 22) + '…';
    html += '<span class="sel-tag with-title"><span class="sel-pid">#'+c.value+'</span> <span class="sel-title">'+title+'</span><span class="tx" onclick="ctaRmProb('+c.value+')">✕</span></span>';
  });
  document.getElementById('plist').value = ids.join(',');
  document.getElementById('sel-tags').innerHTML = ids.length ? '<span class="sel-count">📝 '+ids.length+'문제</span>' + html : '';
  document.querySelectorAll('.pp-row').forEach(function(r){
    r.classList.toggle('checked', r.querySelector('.pp-chk').checked);
  });
}
function ctaRmProb(pid) {
  var c = document.querySelector('.pp-chk[value="'+pid+'"]');
  if(c){ c.checked = false; ctaRefreshSel(); }
}
(function(){
  var cur = document.getElementById('plist').value;
  if(!cur) return;
  cur.split(',').forEach(function(id){
    id = id.trim();
    var c = document.querySelector('.pp-chk[value="'+id+'"]');
    if(c) c.checked = true;
  });
  ctaRefreshSel();
})();

// ═══ Submit 전 검증 + 실시간 피드백 ═══
document.getElementById('ctaForm').addEventListener('submit', function(e){
  var ok = true;

  // 제목
  var title = document.getElementById('f-title').value.trim();
  if (!title) {
    document.getElementById('err-title').classList.add('show');
    document.getElementById('f-title').classList.add('invalid');
    ok = false;
  }

  // 언어
  if (document.querySelectorAll('.lang-chk:checked').length === 0) {
    document.getElementById('err-lang').classList.add('show');
    ok = false;
  }

  // 비밀번호 (비공개 시 필수)
  if (document.getElementById('f-private').value === '1') {
    var pw = document.getElementById('f-password').value.trim();
    if (!pw) {
      document.getElementById('err-pw').classList.add('show');
      document.getElementById('f-password').classList.add('invalid');
      ok = false;
    }
  }

  // 시간 유효성
  if (document.getElementById('err-time').classList.contains('show')) {
    ok = false;
  }

  if (!ok) {
    e.preventDefault();
    // 첫 에러 지점으로 스크롤
    var firstErr = document.querySelector('.cta-field-err.show, .invalid');
    if (firstErr) firstErr.scrollIntoView({behavior:'smooth', block:'center'});
    return false;
  }

  // 문제 없을 때 한번 더 확인
  var pids = document.getElementById('plist').value.trim();
  if (!pids) {
    if (!confirm('❓ 문제가 하나도 선택되지 않았습니다. 그래도 생성할까요?')) {
      e.preventDefault();
      return false;
    }
  }
});

// 입력 시 실시간 에러 해제
document.getElementById('f-title').addEventListener('input', function(){
  if (this.value.trim()) {
    this.classList.remove('invalid');
    document.getElementById('err-title').classList.remove('show');
  }
});
document.getElementById('f-password').addEventListener('input', function(){
  if (this.value.trim()) {
    this.classList.remove('invalid');
    document.getElementById('err-pw').classList.remove('show');
  }
});

// Ctrl+Enter 단축키로 제출
document.addEventListener('keydown', function(e){
  if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
    document.getElementById('ctaForm').requestSubmit();
  }
});
</script>
<?php }

?>
</body>
</html>
