<?php
  require_once("../include/db_info.inc.php");
  require_once("../lang/$OJ_LANG.php");
  require_once("../include/const.inc.php");

  require_once("admin-header.php");
  if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
  include_once("kindeditor.php") ;
?>
<body>
<?php
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
  $langmask=0;
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

  $cid=intval($_POST['cid']);

  if(!(isset($_SESSION[$OJ_NAME.'_'."m$cid"])||isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))) exit();

  $description = str_replace("<p>", "", $description);
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44;", $description);
  //echo "$subnet[$contest_type]";

  $sql = "UPDATE `contest` SET `title`=?,`description`=?,`start_time`=?,`end_time`=?,`private`=?,`langmask`=?,`password`=?,subnet=?,contest_type=?,exam_max_score=? WHERE `contest_id`=?";
  //echo $sql;
  pdo_query($sql,$title,$description,$starttime,$endtime,$private,$langmask,$password,$subnet,$contest_type,$exam_max_score,$cid);

  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=?";
  pdo_query($sql,$cid);
  $plist=trim($_POST['cproblem']);
  $pieces = explode(',', $plist);

  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    
    $plist="";
    pdo_query("update solution set num=-1 where contest_id=?",$cid);
    $num=0;
    for($i=0; $i<count($pieces); $i++){
      $sql="select problem_id from problem where problem_id=?";
      $pid=intval($pieces[$i]);
      $has=pdo_query($sql,$pid);
      if(count($has) > 0) {
         if($plist) $plist.=",";
         $plist.=intval($pieces[$i]);
         pdo_query($sql_1,$cid,$pieces[$i],$num);
	 $sql="UPDATE `contest_problem` SET `c_accepted`=(select count(1) FROM `solution` WHERE `problem_id`=? and contest_id=? AND `result`=4) WHERE `problem_id`=? and contest_id=?";
	 pdo_query($sql,$pid,$cid,$pid,$cid);
	 $sql="UPDATE `contest_problem` SET `c_submit`=(select count(1) FROM `solution` WHERE `problem_id`=? and contest_id=?) WHERE `problem_id`=? and contest_id=?";
	 pdo_query($sql,$pid,$cid,$pid,$cid);
      	 $sql_2 = "update solution set num=? where contest_id=? and problem_id=?;";
      	 pdo_query($sql_2,$num,$cid,$pid);
         $num++;
      }else{
         print("Problem not exists:".$pieces[$i]."<br>\n");
      }
    }

    // [2026-04-20] 대회 수정 시 문제 자동 공개(defunct='N') 처리 제거.
    // 숨김 문제도 contest_problem 매핑으로 대회 참가자가 접근 가능하므로 UPDATE 불필요.
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql,"c$cid");
  $pieces = explode("\n", trim($_POST['ulist']));
  $pieces = array_unique($pieces);
  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES (?,?)";
    for($i=0; $i<count($pieces); $i++){
      pdo_query($sql_1,trim($pieces[$i]),"c$cid") ;
    }
  }

  echo "<script>window.location.href=\"contest_list.php\";</script>";
  exit();
}else{
  $cid = intval($_GET['cid']);
  $sql = "select * FROM `contest` WHERE `contest_id`=?";
  $result = pdo_query($sql,$cid);

  if(count($result)!=1){
    echo "No such Contest!";
    exit(0);
  }

  $row = $result[0];
  $starttime = $row['start_time'];
  $endtime = $row['end_time'];
  $private = $row['private'];
  $password = $row['password'];
  $langmask = $row['langmask'];
  $subnet= $row['subnet'];
  $contest_type= $row['contest_type'];
  $description = $row['description'];
  $title = htmlentities($row['title'],ENT_QUOTES,"UTF-8");
  // [수행평가] $row가 이후 loop에서 재사용되므로 따로 보관
  $exam_max_score_stored = isset($row['exam_max_score']) ? intval($row['exam_max_score']) : 0;

  $plist = "";
  $sql = "select `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
  $result=pdo_query($sql,$cid);

  foreach($result as $row){
    if($plist) $plist .= ",";
    $plist.=$row[0];
  }

  $ulist = "";
  $sql = "select `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
  $result = pdo_query($sql,"c$cid");

  foreach($result as $row){
    if($ulist) $ulist .= "\n";
    $ulist .= $row[0];
  } 
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;600;700;800;900&display=swap');
:root {
  --cta-primary: #7c3aed; --cta-primary-dark: #6d28d9; --cta-primary-darker: #5b21b6;
  --cta-primary-light: #a78bfa; --cta-primary-softer: #ede9fe; --cta-primary-bg: #f3f0ff;
  --cta-danger: #ef4444; --cta-danger-bg: #fef2f2; --cta-danger-border: #fecaca;
  --cta-warning: #f59e0b; --cta-warning-bg: #fffbeb;
  --cta-success: #10b981; --cta-success-bg: #ecfdf5;
  --cta-surface: #fff; --cta-surface-sub: #faf9fd;
  --cta-border: #e5e0f0; --cta-border-hover: #c4b5fd;
  --cta-text: #1a1a2e; --cta-text-sub: #4b5563; --cta-text-mute: #9ca3af;
  --cta-divider: #f0ecf9;
  --cta-radius: 12px; --cta-radius-lg: 16px;
  --cta-ease: cubic-bezier(.4, 0, .2, 1);
  --cta-ease-spring: cubic-bezier(.34, 1.56, .64, 1);
}
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
.cta-section-title { font-size:15px; font-weight:800; color:#7c3aed; letter-spacing:-0.2px; margin-bottom:16px; display:flex; align-items:center; gap:6px; line-height:1.3; }
.cta-row { display:flex; gap:16px; margin-bottom:18px; flex-wrap:wrap; }
.cta-field { flex:1; min-width:0; }
.cta-field label { display:block; font-size:13px; font-weight:700; color:#4b5563; margin-bottom:8px; letter-spacing:0.1px; line-height:1.3; }
.cta-field input[type=text], .cta-field input[type=date], .cta-field input[type=password], .cta-field input[type=number], .cta-field textarea {
  width:100%; padding:13px 16px; border:1.5px solid #e5e0f0; border-radius:12px; font-size:15px; line-height:1.4; color:#1a1a2e; background:#faf9fd; transition:all .2s; font-family:inherit; box-sizing:border-box; height:48px;
}
.cta-field textarea { height:auto; min-height:100px; resize:vertical; font-family:'Consolas', 'Monaco', monospace; font-size:14px; line-height:1.6; }
.cta-field input:focus, .cta-field select:focus, .cta-field textarea:focus { outline:none; border-color:#7c3aed; background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,0.10); }
.cta-field select, select.cta-sel {
  display:block !important; width:100% !important;
  padding:13px 40px 13px 16px !important;
  font-size:15px !important; line-height:1.4 !important; color:#1a1a2e !important;
  background:#faf9fd url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path fill='%237c3aed' d='M6 8L0 0h12z'/></svg>") no-repeat right 14px center !important;
  background-size:12px 8px !important;
  border:1.5px solid #e5e0f0 !important; border-radius:12px !important;
  height:48px !important; font-family:'Noto Sans KR', 'Malgun Gothic', sans-serif !important;
  font-weight:500 !important; box-sizing:border-box !important;
  appearance:none !important; -webkit-appearance:none !important; -moz-appearance:none !important;
  cursor:pointer !important; transition:all .2s !important;
}
.cta-field select:hover, select.cta-sel:hover { border-color:#c4b5fd !important; }
.cta-field.medium { max-width:220px; }
.cta-field .hint { font-size:12.5px; color:#9ca3af; margin-top:6px; line-height:1.5; }
.cta-time-group { display:flex; gap:8px; align-items:center; }
.cta-time-group input[type=date] { flex:1; }
.cta-time-group input[type=number] { width:72px; text-align:center; padding:13px 8px; }
.cta-time-group span { color:#9ca3af; font-weight:700; font-size:14px; }
.cta-checkbox-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:10px; }
.cta-checkbox-item { display:flex; align-items:flex-start; gap:12px; padding:16px 18px; background:#faf9fd; border:1.5px solid #e5e0f0; border-radius:12px; font-size:14px; color:#374151; cursor:pointer; transition:all .15s; font-weight:500; }
.cta-checkbox-item:hover { border-color:#c4b5fd; background:#f5f3ff; }
.cta-checkbox-item input[type=checkbox] { accent-color:#7c3aed; width:18px; height:18px; margin:0; margin-top:1px; cursor:pointer; flex-shrink:0; }
.cta-checkbox-item.checked { background:#f3f0ff; border-color:#7c3aed; }
.cta-chk-content { display:flex; flex-direction:column; gap:4px; flex:1; min-width:0; }
.cta-chk-label { font-weight:700; font-size:14px; color:#1a1a2e; line-height:1.35; }
.cta-chk-desc { font-size:12.5px; color:#9ca3af; line-height:1.5; font-weight:400; }
.cta-checkbox-item.checked .cta-chk-label { color:#6d28d9; }
.cta-checkbox-item.checked .cta-chk-desc  { color:#8b5cf6; }
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
.cta-lang-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:10px; }
.cta-lang-item { display:flex; align-items:center; justify-content:center; gap:8px; padding:14px 16px; background:#faf9fd; border:1.5px solid #e5e0f0; border-radius:12px; font-size:14px; color:#64748b; cursor:pointer; transition:all .15s; font-weight:700; user-select:none; min-height:50px; }
.cta-lang-item input { display:none; }
.cta-lang-item:hover { background:#f5f3ff; border-color:#c4b5fd; color:#7c3aed; }
.cta-lang-item.active { background:linear-gradient(135deg, #7c3aed, #6d28d9); color:#fff; border-color:#7c3aed; box-shadow:0 4px 12px rgba(124,58,237,0.25); transform:translateY(-1px); }
.cta-exam-score { background:linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border:1.5px solid #fdba74; padding:20px 22px; border-radius:14px; margin-top:6px; }
.cta-exam-score label { color:#9a3412; font-size:14px; font-weight:800; display:flex; align-items:center; gap:6px; margin-bottom:10px; line-height:1.3; }
.cta-exam-score select { background:#fff !important; border-color:#fdba74 !important; }
.cta-exam-score select:focus { border-color:#f97316 !important; box-shadow:0 0 0 3px rgba(249,115,22,0.12) !important; }
.cta-exam-score .hint { color:#c2410c; font-weight:500; font-size:12.5px; margin-top:8px; }
.cta-btn { padding:14px 36px; border-radius:12px; font-size:15px; font-weight:700; cursor:pointer; border:none; font-family:inherit; transition:all .25s var(--cta-ease); text-decoration:none; display:inline-flex; align-items:center; gap:8px; line-height:1.3; }
.cta-btn-cancel { background:#fff; color:#6b7280; border:1.5px solid #e5e7eb; }
.cta-btn-cancel:hover { background:#f9fafb; color:var(--cta-text); border-color:#d1d5db; }
.cta-btn-submit { background:linear-gradient(135deg, var(--cta-primary), var(--cta-primary-dark)); color:#fff; box-shadow:0 4px 14px rgba(124,58,237,0.30); }
.cta-btn-submit:hover { background:linear-gradient(135deg, var(--cta-primary-dark), var(--cta-primary-darker)); transform:translateY(-1px); box-shadow:0 8px 24px rgba(124,58,237,0.40); }

/* Sticky Footer */
.cta-sticky-footer { position:sticky; bottom:12px; z-index:50; background:rgba(255,255,255,0.85); backdrop-filter:blur(12px) saturate(180%); -webkit-backdrop-filter:blur(12px) saturate(180%); border:1px solid var(--cta-divider); border-radius:14px; padding:14px 20px; margin-top:28px; display:flex; gap:12px; justify-content:space-between; align-items:center; box-shadow:0 -2px 20px rgba(0,0,0,0.06); }
.cta-sticky-hint { font-size:12.5px; color:var(--cta-text-mute); }
.cta-sticky-btns { display:flex; gap:10px; }

/* 제한옵션 프리셋 */
.cta-lock-preset { display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap; padding:12px 14px; background:var(--cta-surface-sub); border-radius:var(--cta-radius); border:1px solid var(--cta-divider); }
.cta-lock-preset-label { font-size:12px; font-weight:700; color:var(--cta-text-sub); align-self:center; margin-right:4px; }
.cta-lock-preset-btn { padding:8px 16px; background:#fff; border:1.5px solid var(--cta-border); border-radius:10px; font-size:13px; font-weight:700; color:var(--cta-text-sub); cursor:pointer; transition:all .2s var(--cta-ease); font-family:inherit; display:inline-flex; align-items:center; gap:5px; position:relative; }
.cta-lock-preset-btn:hover { border-color:var(--cta-border-hover); color:var(--cta-primary); transform:translateY(-1px); }
.cta-lock-preset-btn::after { content:attr(data-tip); position:absolute; top:100%; left:50%; transform:translateX(-50%) translateY(8px); background:#1a1a2e; color:#fff; padding:10px 14px; border-radius:10px; font-size:12px; font-weight:500; white-space:nowrap; opacity:0; pointer-events:none; transition:opacity .2s, transform .2s; z-index:100; box-shadow:0 8px 24px rgba(0,0,0,0.15); }
.cta-lock-preset-btn::before { content:''; position:absolute; top:100%; left:50%; transform:translateX(-50%) translateY(4px); border:6px solid transparent; border-bottom-color:#1a1a2e; opacity:0; pointer-events:none; transition:opacity .2s; z-index:101; }
.cta-lock-preset-btn:hover::after { opacity:1; transform:translateX(-50%) translateY(12px); }
.cta-lock-preset-btn:hover::before { opacity:1; }

/* 체크박스 그룹 */
.cta-lock-group { margin-bottom:14px; }
.cta-lock-group:last-child { margin-bottom:0; }
.cta-lock-group-label { display:flex; align-items:center; gap:8px; font-size:12px; font-weight:800; color:var(--cta-text-sub); letter-spacing:0.3px; margin-bottom:8px; padding-left:2px; text-transform:uppercase; }
.cta-lock-group-label::after { content:''; flex:1; height:1px; background:linear-gradient(90deg, var(--cta-divider), transparent); }
.cta-lock-group.group-special .cta-lock-group-label { color:#c2410c; }

/* 기본 뱃지 */
.cta-chk-badge { display:inline-flex; align-items:center; padding:1px 7px; margin-left:6px; background:linear-gradient(135deg, #fef3c7, #fcd34d); color:#78350f; border-radius:100px; font-size:10px; font-weight:800; letter-spacing:0.3px; vertical-align:middle; }

/* 위험 경고 */
.cta-warn-badge { display:none; margin-top:6px; padding:8px 12px 8px 14px; background:linear-gradient(135deg, #fef2f2, #fee2e2); border:1px solid #fca5a5; border-radius:10px; color:#991b1b; font-size:12px; font-weight:600; line-height:1.5; }
.cta-warn-badge.show { display:block; animation:warnPulse .4s var(--cta-ease); }
@keyframes warnPulse { 0%{transform:scale(.9);opacity:0} 60%{transform:scale(1.02)} 100%{transform:scale(1);opacity:1} }
.cta-warn-badge strong { color:#7f1d1d; }

/* 팁 아코디언 */
.cta-tip-guide { margin-top:16px; background:linear-gradient(135deg, #faf5ff, #f5f3ff); border:1px solid #ddd6fe; border-radius:var(--cta-radius); overflow:hidden; }
.cta-tip-guide summary { padding:14px 18px; cursor:pointer; font-size:13px; font-weight:700; color:var(--cta-primary-dark); list-style:none; display:flex; align-items:center; gap:8px; transition:background .2s; user-select:none; }
.cta-tip-guide summary::-webkit-details-marker { display:none; }
.cta-tip-guide summary:hover { background:rgba(124,58,237,0.08); }
.cta-tip-guide summary .tip-caret { margin-left:auto; transition:transform .3s var(--cta-ease-spring); font-size:10px; }
.cta-tip-guide[open] summary .tip-caret { transform:rotate(180deg); }
.cta-tip-body { padding:6px 14px 14px; background:#fff; border-top:1px solid #ddd6fe; display:grid; grid-template-columns:repeat(2, 1fr); gap:8px; }
.cta-tip-case { padding:12px 14px; background:var(--cta-surface-sub); border-radius:10px; border:1px solid var(--cta-divider); transition:all .2s; }
.cta-tip-case:hover { border-color:var(--cta-primary-light); transform:translateY(-1px); }
.cta-tip-case h5 { margin:0 0 4px; font-size:13px; font-weight:800; color:var(--cta-text); line-height:1.3; }
.cta-tip-case p { margin:0; font-size:11.5px; color:var(--cta-text-sub); line-height:1.55; }
@media(max-width:700px) { .cta-tip-body { grid-template-columns:1fr; } }

/* 비밀번호 조건부 */
.cta-field-pw.disabled input { background:#f3f4f6 !important; color:#9ca3af !important; border-style:dashed !important; cursor:not-allowed !important; }
.cta-field-pw.disabled label { opacity:0.5; }

/* 고급 설정 토글 */
.cta-advanced-toggle { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:transparent; border:1.5px dashed var(--cta-border); border-radius:10px; font-size:12.5px; font-weight:600; color:var(--cta-text-mute); cursor:pointer; transition:all .2s var(--cta-ease); font-family:inherit; margin-top:12px; }
.cta-advanced-toggle:hover { border-color:var(--cta-primary-light); color:var(--cta-primary); background:var(--cta-primary-bg); }
.cta-advanced-toggle .caret { transition:transform .3s var(--cta-ease-spring); display:inline-block; }
.cta-advanced-toggle.open .caret { transform:rotate(180deg); }
.cta-advanced { max-height:0; overflow:hidden; transition:max-height .4s var(--cta-ease), margin-top .3s var(--cta-ease); margin-top:0; }
.cta-advanced.open { max-height:400px; margin-top:14px; }
.cta-advanced-inner { padding:16px; background:var(--cta-surface-sub); border-radius:var(--cta-radius); border:1px solid var(--cta-divider); }

/* 언어 배지 */
.cta-lang-item { position:relative; overflow:hidden; }
.cta-lang-item::before { content:''; position:absolute; top:10px; right:10px; width:8px; height:8px; border-radius:50%; transition:transform .2s var(--cta-ease); }
.cta-lang-item[data-lang-id="0"]::before { background:#3730a3; }
.cta-lang-item[data-lang-id="1"]::before { background:#6d28d9; }
.cta-lang-item[data-lang-id="6"]::before { background:#f59e0b; }
.cta-lang-item.active::before { background:rgba(255,255,255,0.9) !important; }

/* 선택문제 태그 제목 */
.sel-tag.with-title { padding-left:12px; padding-right:10px; }
.sel-tag .sel-pid { font-weight:900; color:var(--cta-primary-dark); }
.sel-tag .sel-title { font-weight:500; color:var(--cta-text-sub); max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
@media(max-width:860px) { .cta-card { padding:32px 24px; } .cta-checkbox-grid { grid-template-columns:1fr; } .cta-lang-grid { grid-template-columns:repeat(3, 1fr); } .cta-head h2 { font-size:24px; } }
@media(max-width:600px) { .cta-wrap { padding:0 16px; } .cta-card { padding:24px 18px; border-radius:14px; } .cta-lang-grid { grid-template-columns:repeat(2, 1fr); } .cta-head h2 { font-size:22px; } .cta-head-icon { font-size:26px; } }
</style>

<div class="cta-wrap">
  <div class="cta-card">
    <div class="cta-head">
      <span class="cta-head-icon">✏️</span>
      <h2><em>대회</em> 수정 <span style="font-size:14px;color:#9ca3af;font-weight:600">#<?php echo $cid?></span></h2>
    </div>
    <div class="cta-sub">대회 설정을 수정합니다.</div>

    <form method="POST" id="ctaForm">
      <?php require_once("../include/set_post_key.php");?>
      <input type="hidden" name="cid" value="<?php echo $cid?>">

      <!-- ═══ 기본 정보 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">📝 기본 정보</div>
        <div class="cta-row">
          <div class="cta-field">
            <label>대회 제목</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES)?>">
          </div>
        </div>
        <div class="cta-row">
          <div class="cta-field">
            <label>📅 시작</label>
            <div class="cta-time-group">
              <input type="date" name="startdate" value="<?php echo substr($starttime,0,10)?>">
              <input type="number" name="shour" min="0" max="23" value="<?php echo substr($starttime,11,2)?>"><span>시</span>
              <input type="number" name="sminute" min="0" max="59" value="<?php echo substr($starttime,14,2)?>"><span>분</span>
            </div>
          </div>
          <div class="cta-field">
            <label>🏁 종료</label>
            <div class="cta-time-group">
              <input type="date" name="enddate" value="<?php echo substr($endtime,0,10)?>">
              <input type="number" name="ehour" min="0" max="23" value="<?php echo substr($endtime,11,2)?>"><span>시</span>
              <input type="number" name="eminute" min="0" max="59" value="<?php echo substr($endtime,14,2)?>"><span>분</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══ 공개 설정 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">🔒 공개 설정</div>
        <div class="cta-row">
          <div class="cta-field medium">
            <label>공개 여부</label>
            <select id="f-private" name="private" class="cta-sel" onchange="ctaTogglePrivate()">
              <option value="0" <?php echo $private=='0'?'selected':''?>>공개</option>
              <option value="1" <?php echo $private=='1'?'selected':''?>>비공개 (비밀번호)</option>
            </select>
          </div>
          <div class="cta-field medium cta-field-pw" id="f-pw-wrap">
            <label>비밀번호</label>
            <input type="text" id="f-password" name="password" value="<?php echo htmlentities($password, ENT_QUOTES, 'utf-8')?>" placeholder="예: 1234">
          </div>
        </div>

        <div class="cta-exam-score">
          <label>📝 수행평가 배점</label>
          <?php $exam_ms_cur = isset($exam_max_score_stored) ? intval($exam_max_score_stored) : 0; ?>
          <select name="exam_max_score" class="cta-sel">
            <option value="0"  <?php echo $exam_ms_cur==0  ? 'selected' : ''?>>자동 감지 (문제 ID로 판단)</option>
            <option value="20" <?php echo $exam_ms_cur==20 ? 'selected' : ''?>>2학년 정보 (C) — 20점 만점</option>
            <option value="40" <?php echo $exam_ms_cur==40 ? 'selected' : ''?>>3학년 인공지능기초 (Python) — 40점 만점</option>
          </select>
          <div class="hint">선택 시 순위표/통계에 자동 점수가 해당 기준으로 표시됩니다.</div>
        </div>

        <button type="button" class="cta-advanced-toggle <?php echo trim($subnet)?'open':'' ?>" id="cta-adv-toggle" onclick="ctaToggleAdvanced()">
          ⚙️ 고급 설정 <span class="caret">▼</span>
        </button>
        <div class="cta-advanced <?php echo trim($subnet)?'open':'' ?>" id="cta-advanced">
          <div class="cta-advanced-inner">
            <div class="cta-field">
              <label>🌐 접속 IP 대역 (subnet)</label>
              <input type="text" name="subnet" value="<?php echo htmlentities($subnet)?>" placeholder="예: 192.168.1.0/24">
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
          <input type="hidden" id="plist" name="cproblem" value="<?php echo htmlspecialchars($plist, ENT_QUOTES)?>">
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
        <div class="cta-section-title">💻 허용 언어 <span style="color:#e74c3c;font-weight:700">*</span></div>
        <div class="cta-lang-grid">
          <?php
          $lang_count = count($language_ext);
          $lang = (~((int)$langmask))&((1<<$lang_count)-1);
          for($i=0; $i<$lang_count; $i++){
            if((1<<$i) & $OJ_LANGMASK) continue;
            $checked = ($lang & (1<<$i)) ? 'checked' : '';
            $active = $checked ? 'active' : '';
            echo "<label class='cta-lang-item $active' data-lang-id='$i'>";
            echo "<input type='checkbox' class='lang-chk' name='lang[]' value='$i' $checked onchange='this.parentNode.classList.toggle(\"active\",this.checked)'>";
            echo "<span>".$language_name[$i]."</span>";
            echo "</label>";
          }
          ?>
        </div>
        <div class="hint" style="margin-top:8px;font-size:12px;color:#9ca3af">⚠️ 최소 1개 이상의 언어를 선택해야 합니다.</div>
      </div>

      <!-- ═══ 제한 플래그 ═══ -->
      <div class="cta-section">
        <div class="cta-section-title">🚫 대회 제한 옵션</div>

        <div class="cta-lock-preset">
          <span class="cta-lock-preset-label">빠른 설정:</span>
          <button type="button" class="cta-lock-preset-btn" data-tip="✔ 5개 체크 · 수행평가 최적 (bits 0·1·2·3·6)" onclick="ctaApplyLockPreset('exam')">📝 수행평가</button>
          <button type="button" class="cta-lock-preset-btn" data-tip="✔ 3개 체크 · 최소 보안 (bits 0·1·3)" onclick="ctaApplyLockPreset('contest')">🏆 일반 대회</button>
          <button type="button" class="cta-lock-preset-btn" data-tip="✔ 모두 해제 · 결과 즉시 확인 가능" onclick="ctaApplyLockPreset('practice')">🔓 자유 연습</button>
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
        $recommended_bits = (1<<0) | (1<<1) | (1<<2) | (1<<3) | (1<<6); // 수행평가 권장
        $cta_lock_groups = [
          'security' => ['🛡️ 기본 보안 (자주 사용)', [0, 3, 5], ''],
          'access'   => ['🚷 접근 제한',             [1, 6, 8], ''],
          'ranking'  => ['📊 랭킹 정책',             [2],       ''],
          'special'  => ['⚠️ 특수 모드 (신중히)',   [4, 7],    'group-special'],
        ];
        foreach ($cta_lock_groups as $gkey => $ginfo) {
          list($glabel, $gbits, $gclass) = $ginfo;
          echo "<div class='cta-lock-group $gclass'>";
          echo "<div class='cta-lock-group-label'>$glabel</div>";
          echo "<div class='cta-checkbox-grid'>";
          foreach ($gbits as $bit) {
            list($label, $desc) = $cta_locks_data[$bit];
            $is_default = ($recommended_bits & (1<<$bit)) !== 0;
            $checked = ($contest_type & (1<<$bit)) ? 'checked' : '';
            $active = $checked ? 'checked' : '';
            $badge = $is_default ? "<span class='cta-chk-badge'>기본</span>" : '';
            echo "<label class='cta-checkbox-item $active' data-bit='$bit'>";
            echo "<input type='checkbox' class='lock-chk' name='contest_type[]' value='$bit' $checked onchange='this.parentNode.classList.toggle(\"checked\",this.checked); ctaCheckDangerBits();'>";
            echo "<div class='cta-chk-content'>";
            echo "<div class='cta-chk-label'>".htmlspecialchars($label, ENT_QUOTES, 'UTF-8').$badge."</div>";
            echo "<div class='cta-chk-desc'>".htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')."</div>";
            echo "</div></label>";
          }
          echo "</div></div>";
        }
        ?>

        <div class="cta-warn-badge" id="cta-warn-bit7">
          ⚠️ <strong>"마지막 제출만 채점"</strong>이 켜져 있어요. 수행평가에서는 학생이 AC 후 실수로 WA 제출 시 점수가 날아갑니다.
        </div>
        <div class="cta-warn-badge" id="cta-warn-bit4">
          ⚠️ <strong>"대회 종료 전까지 결과 숨김"</strong>이 켜져 있어요. 학생이 본인 AC 여부를 모르므로 수행평가엔 비추천합니다.
        </div>

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
              <p>최소 3개만 (이전 코드·다운로드·타인 숨김). 실시간 결과 확인 가능.</p>
            </div>
            <div class="cta-tip-case">
              <h5>🎯 정보올림피아드 모의</h5>
              <p>기본 5개 + "결과 숨김" + "diff 숨김" 추가. NOIP 방식.</p>
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
          <textarea class="kindeditor" name="description" rows="8"><?php echo htmlentities($description, ENT_QUOTES, 'UTF-8')?></textarea>
        </div>
      </div>

      <!-- 참가자 목록은 제거됨 -->
      <input type="hidden" name="ulist" value="">

      <div class="cta-sticky-footer">
        <div class="cta-sticky-hint">💡 <strong>Tip:</strong> 프리셋 버튼으로 빠르게 설정하세요</div>
        <div class="cta-sticky-btns">
          <a href="contest_list.php" class="cta-btn cta-btn-cancel">← 취소</a>
          <button type="submit" name="submit" class="cta-btn cta-btn-submit">💾 저장</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
// ─── 비밀번호 조건부 ───
function ctaTogglePrivate() {
  var priv = document.getElementById('f-private').value;
  var pwWrap = document.getElementById('f-pw-wrap');
  var pwInput = document.getElementById('f-password');
  if (priv === '1') {
    pwWrap.classList.remove('disabled');
    pwInput.disabled = false;
  } else {
    pwWrap.classList.add('disabled');
    pwInput.disabled = true;
  }
}
ctaTogglePrivate();

// ─── 고급 설정 토글 ───
function ctaToggleAdvanced() {
  var adv = document.getElementById('cta-advanced');
  var btn = document.getElementById('cta-adv-toggle');
  adv.classList.toggle('open');
  btn.classList.toggle('open');
}

// ─── 제한옵션 프리셋 ───
function ctaApplyLockPreset(preset) {
  var presets = { 'exam':[0,1,2,3,6], 'contest':[0,1,3], 'practice':[] };
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
var ctaProbTitles = {};
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
// Edit mode: pre-select existing problems
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

// 수정 전 검증: 언어 필수
document.getElementById('ctaForm').addEventListener('submit', function(e){
  var langChecked = document.querySelectorAll('.lang-chk:checked').length;
  if (langChecked === 0) {
    e.preventDefault();
    alert('⚠️ 최소 1개 이상의 허용 언어를 선택해주세요.');
    document.querySelector('.cta-lang-grid').scrollIntoView({behavior:'smooth', block:'center'});
    return false;
  }
});
</script>
</body>
</html>


