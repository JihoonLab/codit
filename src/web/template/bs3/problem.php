<?php $class_param = isset($_GET["class_id"]) ? "&class_id=".intval($_GET["class_id"]) : ""; ?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>
                <?php echo $row['title']." - ".$MSG_PROBLEM." - ".$OJ_NAME; ?>
        </title>

        <?php include("template/$OJ_TEMPLATE/css.php");?>

        <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php if (isset($OJ_MATHJAX)&&$OJ_MATHJAX){?>
<script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
  };
</script>
<script id="MathJax-script" async src="template/<?php echo $OJ_TEMPLATE?>/tex-chtml.js"></script>
<style>.jumbotron1{ font-size: 18px; }</style>
<?php } ?>

<style>
/* ── 문제 페이지 전용 스타일 ── */
.prob-header {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  padding: 28px 32px 20px;
  margin-bottom: 16px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  text-align: center;
}

.prob-title {
  font-size: 32px;
  font-weight: 900;
  color: #1a1a2e;
  margin: 0 0 10px;
  letter-spacing: -0.5px;
  line-height: 1.3;
}

.prob-lang-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 700;
  vertical-align: middle;
  margin-left: 8px;
  position: relative;
  top: -2px;
}
.prob-lang-badge.python { background: #fef3c7; color: #92400e; }
.prob-lang-badge.c { background: #e0e7ff; color: #3730a3; }

.prob-credits {
  font-size: 13px;
  color: #aaa;
  margin-bottom: 14px;
}

.prob-meta {
  display: inline-flex;
  gap: 24px;
  background: #f5f8ff;
  border-radius: 8px;
  padding: 12px 28px;
  margin-bottom: 20px;
  font-size: 15px;
  color: #555;
}

.prob-meta span { display: flex; align-items: center; gap: 5px; }
.prob-meta strong { color: #7c3aed; font-weight: 700; }

/* CodeUp 스타일 액션 버튼 */
.prob-actions {
  display: flex;
  gap: 8px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 4px;
}

.prob-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 24px;
  border-radius: 7px;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none !important;
  border: none;
  cursor: pointer;
  transition: all 0.15s;
  white-space: nowrap;
}

.prob-btn-submit  { background: #e74c3c; color: #fff !important; }
.prob-btn-submit:hover  { background: #c0392b; color: #fff !important; }

.prob-btn-rank    { background: #7c3aed; color: #fff !important; }
.prob-btn-rank:hover    { background: #6d28d9; color: #fff !important; }

.prob-btn-status  { background: #27ae60; color: #fff !important; }
.prob-btn-status:hover  { background: #1e9450; color: #fff !important; }

.prob-btn-stat    { background: #f39c12; color: #fff !important; }
.prob-btn-stat:hover    { background: #d68910; color: #fff !important; }

.prob-btn-off     { background: #8e44ad; color: #fff !important; }
.prob-btn-off:hover     { background: #7d3c98; color: #fff !important; }

.prob-btn-edit    { background: #16a085; color: #fff !important; }
.prob-btn-edit:hover    { background: #138d75; color: #fff !important; }

.prob-btn-list    { background: #555; color: #fff !important; }
.prob-btn-list:hover    { background: #333; color: #fff !important; }


/* 섹션 패널 */
.prob-section {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 10px;
  margin-bottom: 14px;
  overflow: hidden;
  box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.prob-section-head {
  background: #f8fafc;
  border-bottom: 1px solid #e5e9f0;
  padding: 14px 24px;
  font-size: 16px;
  font-weight: 700;
  color: #7c3aed;
  display: flex;
  align-items: center;
  gap: 8px;
}

.prob-section-body {
  padding: 22px 26px;
  font-size: 16px;
  line-height: 1.9;
  color: #333;
}

.prob-section-body pre {
  background: #1e1e2e !important;
  color: #e0e0e0 !important;
  border: 1px solid #333 !important;
  border-radius: 12px;
  padding: 20px 24px;
  font-size: 15px;
  line-height: 1.9;
  font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
  overflow-x: auto;
  margin: 12px 0;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  tab-size: 4;
}

.prob-section-body pre code {
  background: none !important;
  color: #e0e0e0 !important;
  padding: 0;
  font-size: inherit;
  border: none !important;
  border-radius: 0;
  font-weight: 400;
}

.prob-section-body code {
  background: #f0edff;
  color: #7c3aed;
  padding: 2px 8px;
  border-radius: 5px;
  font-size: 14px;
  font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
  font-weight: 600;
}

.prob-section-body .img-right {
  text-align: left;
  margin: 10px 0;
}

/* IO 예시 2단 레이아웃 */
.io-grid .io-label {
  font-size: 15px;
  font-weight: 700;
  color: #666;
  margin-bottom: 10px;
  letter-spacing: 0.5px;
}
.io-grid pre {
  font-size: 16px !important;
  padding: 18px 22px !important;
  line-height: 1.8 !important;
}
.io-copy-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  margin-top: 8px;
  padding: 6px 14px;
  font-size: 13px;
  font-weight: 600;
  color: #7c3aed;
  background: #f5f3ff;
  border: 1px solid #e0d5ff;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}
.io-copy-btn:hover {
  background: #ede9fe;
  border-color: #c4b5fd;
  color: #6d28d9;
  text-decoration: none;
}
.io-copy-btn.copied {
  background: #ecfdf5;
  border-color: #a7f3d0;
  color: #059669;
}
.io-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
@media(max-width:600px){
  .io-grid { grid-template-columns: 1fr; }
  .prob-card { margin: 12px 8px; border-radius: 12px; }
  .prob-title-area { padding: 20px 16px; }
  .prob-title-area h2 { font-size: 20px; }
  .prob-meta { gap: 12px; font-size: 12px; }
  .prob-actions { gap: 6px; }
  .prob-btn { padding: 8px 14px; font-size: 13px; gap: 4px; }
  .prob-section { padding: 18px 16px; }
  .prob-section h3 { font-size: 15px; }
  .prob-footer { padding: 12px; gap: 6px; }
  .prob-footer .prob-btn { flex: 1; justify-content: center; }
}

/* 하단 액션 */
.prob-footer {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 10px;
  padding: 16px;
  text-align: center;
  margin-top: 8px;
  display: flex;
  gap: 8px;
  justify-content: center;
  flex-wrap: wrap;
}


/* 정답률 바 */
.prob-rate-wrap {
  display: flex; align-items: center; justify-content: center;
  gap: 14px; margin: 16px 0 6px; padding: 0 20px;
}
.prob-rate-bar {
  width: 200px; height: 8px; border-radius: 4px;
  background: #f3f4f6; overflow: hidden; flex-shrink: 0;
}
.prob-rate-fill {
  height: 100%; border-radius: 4px;
  background: linear-gradient(90deg, #c4b5fd, #7c3aed);
  transition: width 0.6s ease;
}
.prob-rate-text {
  font-size: 14px; font-weight: 800; color: #7c3aed; white-space: nowrap;
}
.prob-rate-label {
  font-size: 12px; color: #9ca3af; font-weight: 600;
}

/* 이전/다음 문제 네비게이션 */
.prob-nav {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 16px;
}
.prob-nav-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 10px 20px; border-radius: 10px;
  font-size: 13px; font-weight: 700;
  text-decoration: none !important;
  background: #fff; color: #6b7280 !important;
  border: 1.5px solid #e5e7eb;
  transition: all 0.2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.prob-nav-btn:hover {
  border-color: #7c3aed; color: #7c3aed !important;
  background: #faf5ff;
  transform: translateX(2px);
  box-shadow: 0 2px 8px rgba(124,58,237,0.1);
}
.prob-nav-btn.prev:hover { transform: translateX(-2px); }
.prob-nav-btn .nav-arrow { font-size: 16px; }
.prob-nav-btn .nav-pid { font-size: 11px; color: #b0b5bf; font-weight: 600; }
.prob-nav-center {
  font-size: 13px; font-weight: 700; color: #9ca3af;
  display: flex; align-items: center; gap: 6px;
}
.prob-nav-center a {
  color: #7c3aed; text-decoration: none; font-weight: 700;
}
.prob-nav-center a:hover { text-decoration: underline; }


/* 관리자: 전체 풀이 */
.admin-solutions {
  margin-top: 16px; padding-top: 16px;
  border-top: 2px dashed #ede9fe;
}
.admin-solutions-label {
  font-size: 14px; font-weight: 800; color: #7c3aed;
  margin-bottom: 12px; display: flex; align-items: center; gap: 6px;
}
.admin-solutions-label small { font-size: 11px; color: #9ca3af; font-weight: 600; }
.admin-sol-table {
  width: 100%; border-collapse: collapse; font-size: 13px;
}
.admin-sol-table thead tr { background: #f9fafb; }
.admin-sol-table th {
  padding: 8px 12px; font-size: 11px; font-weight: 700;
  color: #9ca3af; text-align: center; text-transform: uppercase;
  letter-spacing: 0.3px; border-bottom: 2px solid #f3f4f6;
}
.admin-sol-table td {
  padding: 8px 12px; text-align: center; border-bottom: 1px solid #f5f6f8;
  color: #374151;
}
.admin-sol-table tbody tr { transition: background 0.1s; cursor: pointer; }
.admin-sol-table tbody tr:hover { background: #faf5ff; }
.admin-sol-table td a { color: #7c3aed; text-decoration: none; font-weight: 600; }
.admin-sol-table td a:hover { text-decoration: underline; }
.admin-sol-table .sol-uid { font-weight: 700; color: #1f2937; }
.admin-sol-table .sol-lang-badge {
  display: inline-block; padding: 2px 8px; border-radius: 4px;
  font-size: 11px; font-weight: 700; background: #f3f0ff; color: #7c3aed;
}
.admin-sol-scroll { max-height: 400px; overflow-y: auto; border-radius: 8px; border: 1px solid #e5e7eb; }

/* 스크롤바 커스텀 */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #d0d5dd; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #aab0bc; }

/* ── 내 풀이 섹션 ── */
.my-solutions-wrap {
  margin-top: 12px;
  padding-top: 14px;
  border-top: 1px dashed #e5e9f0;
}
.my-solutions-label {
  font-size: 13px;
  font-weight: 700;
  color: #6b7280;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 5px;
  justify-content: center;
}
.my-sol-btns {
  display: flex;
  gap: 6px;
  justify-content: center;
  flex-wrap: wrap;
}
.my-sol-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 7px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none !important;
  cursor: pointer;
  border: 2px solid #10b981;
  background: linear-gradient(135deg, #ecfdf5, #d1fae5);
  color: #059669 !important;
  transition: all 0.2s ease;
  position: relative;
  overflow: hidden;
}
.my-sol-btn:hover {
  background: linear-gradient(135deg, #059669, #10b981);
  color: #fff !important;
  transform: translateY(-1px);
  box-shadow: 0 3px 12px rgba(16,185,129,0.3);
}
.my-sol-btn.active {
  background: linear-gradient(135deg, #059669, #10b981);
  color: #fff !important;
  box-shadow: 0 3px 12px rgba(16,185,129,0.3);
}
.my-sol-btn .sol-lang {
  font-size: 11px;
  opacity: 0.8;
}
.my-sol-btn .sol-date {
  font-size: 11px;
  opacity: 0.7;
}

/* 모범 풀이 (관리자) 버튼 */
.answer-sol-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 7px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none !important;
  cursor: pointer;
  border: 2px solid #3b82f6;
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  color: #2563eb !important;
  transition: all 0.2s ease;
  position: relative;
  overflow: hidden;
}
.answer-sol-btn:hover {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #fff !important;
  transform: translateY(-1px);
  box-shadow: 0 3px 12px rgba(59,130,246,0.3);
}
.answer-sol-btn.active {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #fff !important;
  box-shadow: 0 3px 12px rgba(59,130,246,0.3);
}
.answer-sol-btn .sol-lang {
  font-size: 11px;
  opacity: 0.8;
}

/* 코드 뷰어 */
.sol-code-viewer {
  margin-top: 14px;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e5e9f0;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  animation: solCodeSlide 0.3s ease;
  display: none;
}
@keyframes solCodeSlide {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}
.sol-code-header {
  background: linear-gradient(135deg, #059669, #10b981);
  color: #fff;
  padding: 12px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 13px;
  font-weight: 600;
}
.sol-code-header .sol-info {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}
.sol-code-header .sol-tag {
  background: rgba(255,255,255,0.2);
  padding: 3px 10px;
  border-radius: 10px;
  font-size: 11px;
  font-weight: 600;
}
.sol-code-close {
  background: rgba(255,255,255,0.2);
  border: none;
  color: #fff;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s;
}
.sol-code-close:hover {
  background: rgba(255,255,255,0.35);
}
.sol-code-body {
  background: #f8f9fc;
  padding: 20px;
  max-height: 400px;
  overflow: auto;
  border-top: 1px solid #e5e9f0;
  text-align: left;
}
.sol-code-body pre {
  margin: 0;
  color: #333;
  font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
  font-size: 13px;
  line-height: 1.7;
  white-space: pre;
  word-break: normal;
  overflow-x: auto;
  tab-size: 4;
  background: #fff;
  padding: 16px 20px;
  border-radius: 8px;
  border: 1px solid #e5e9f0;
  text-align: left;
}
.sol-code-body::-webkit-scrollbar { width: 6px; height: 6px; }
.sol-code-body::-webkit-scrollbar-track { background: #f8f9fc; }
.sol-code-body::-webkit-scrollbar-thumb { background: #d0d5dd; border-radius: 3px; }
.sol-code-copy {
  background: rgba(255,255,255,0.2);
  border: none;
  color: #fff;
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 11px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.15s;
}
.sol-code-copy:hover { background: rgba(255,255,255,0.35); }

</style>
</head>

<body>
<div id="main" class="container">
  <?php include("template/$OJ_TEMPLATE/nav.php");?>

  <!-- ── 문제 상태 배너 ── -->
  <?php
  if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])) {
    $cur_user = $_SESSION[$OJ_NAME.'_'.'user_id'];
    $pid_check = $row['problem_id'];
    $banner_class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
    if($banner_class_id > 0) {
      // 수업에서 접속: 해당 수업 내 제출만 확인
      $ac_check = pdo_query("SELECT COUNT(1) as cnt FROM solution WHERE user_id=? AND problem_id=? AND result=4 AND class_id=?", $cur_user, $pid_check, $banner_class_id);
      $has_ac = ($ac_check && $ac_check[0]['cnt'] > 0);
      $sub_check = pdo_query("SELECT COUNT(1) as cnt FROM solution WHERE user_id=? AND problem_id=? AND class_id=?", $cur_user, $pid_check, $banner_class_id);
      $has_sub = ($sub_check && $sub_check[0]['cnt'] > 0);
    } else {
      // 문제 탭에서 접속: 전체 제출 확인
      $ac_check = pdo_query("SELECT COUNT(1) as cnt FROM solution WHERE user_id=? AND problem_id=? AND result=4", $cur_user, $pid_check);
      $has_ac = ($ac_check && $ac_check[0]['cnt'] > 0);
      $sub_check = pdo_query("SELECT COUNT(1) as cnt FROM solution WHERE user_id=? AND problem_id=?", $cur_user, $pid_check);
      $has_sub = ($sub_check && $sub_check[0]['cnt'] > 0);
    }
    
    if($has_ac) {
      echo '<div id="solve-banner" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);border:1px solid #6ee7b7;border-radius:10px;padding:12px 20px;margin-bottom:12px;display:flex;align-items:center;gap:10px;">';
      echo '<span style="font-size:24px;">🎉</span><span style="font-size:15px;font-weight:800;color:#059669;">해결한 문제</span><span style="font-size:13px;color:#047857;">이 문제를 정답 처리했습니다.</span></div>';
    } else if($has_sub) {
      echo '<div id="solve-banner" style="background:linear-gradient(135deg,#fee2e2,#fecaca);border:1px solid #fca5a5;border-radius:10px;padding:12px 20px;margin-bottom:12px;display:flex;align-items:center;gap:10px;">';
      echo '<span style="font-size:24px;">❌</span><span style="font-size:15px;font-weight:800;color:#dc2626;">틀린 문제</span><span style="font-size:13px;color:#b91c1c;">다시 도전해 보세요!</span></div>';
    } else {
      echo '<div id="solve-banner" style="background:linear-gradient(135deg,#f3f4f6,#e5e7eb);border:1px solid #d1d5db;border-radius:10px;padding:12px 20px;margin-bottom:12px;display:flex;align-items:center;gap:10px;">';
      echo '<span style="font-size:24px;">📝</span><span style="font-size:15px;font-weight:800;color:#6b7280;">미해결 문제</span><span style="font-size:13px;color:#9ca3af;">아직 제출하지 않은 문제입니다.</span></div>';
    }
  }
  ?>

  <!-- ── 문제 헤더 ── -->
  <div class="prob-header">
    <?php
    if($pr_flag) {
      $pid_num = intval($id);
      $lang_badge = '';
      if($pid_num >= 1000 && $pid_num < 9000) $lang_badge = "<span class='prob-lang-badge python'>Python</span>";
      elseif($pid_num < 1000) $lang_badge = "<span class='prob-lang-badge c'>C</span>";
      echo "<div class='prob-title'>$id: ".htmlspecialchars($row['title'])."$lang_badge</div>";
    } else {
      $id = $row['problem_id'];
      $pid_num = intval($id);
      $lang_badge = '';
      if($pid_num >= 1000 && $pid_num < 9000) $lang_badge = "<span class='prob-lang-badge python'>Python</span>";
      elseif($pid_num < 1000) $lang_badge = "<span class='prob-lang-badge c'>C</span>";
      echo "<div class='prob-title'>$MSG_PROBLEM ".$PID[$pid].": ".htmlspecialchars($row['title'])."$lang_badge</div>";
    }
    ?>
    <?php if(!empty($row["credits"])): ?><div class="prob-credits"><?php echo $MSG_Creator.": ".$row["credits"]; ?></div><?php endif; ?>

    <div class="prob-meta">
      <span>⏱ <strong><?php echo $row['time_limit']?> sec</strong> 시간제한<span fd='time_limit' pid='<?php echo $row['problem_id']?>' style='display:none'><?php echo $row['time_limit']?></span></span>
      <span>💾 <strong><?php echo $row['memory_limit']?> MiB</strong> 메모리제한</span>
      <span>⚙️ <strong><?php echo array('일반 채점','스페셜 저지','실시간 채점')[$row['spj']]?></strong></span>
    </div>

    <div class="prob-actions">
      <?php
      /* 코드 제출 */
      if($pr_flag) {
        echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?id=$id$class_param' onclick='transform(); return false;'>✏️ 코드 제출</a>";
      } else {
        if($contest_is_over)
          echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?id=$id$class_param' onclick='transform(); return false;'>✏️ 코드 제출</a>";
        else
          echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' onclick='transform(); return false;'>✏️ 코드 제출</a>";
        echo "<a class='prob-btn prob-btn-list' href='contest.php?cid=$cid'>📋 문제 목록</a>";
      }

      if(!(isset($OJ_OI_MODE)&&$OJ_OI_MODE)) {
        if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])) {
          $my_uid_btn = $_SESSION[$OJ_NAME.'_'.'user_id'];
          $my_sub_count = pdo_query("SELECT COUNT(*) as cnt FROM solution WHERE user_id=? AND problem_id=?", $my_uid_btn, $row['problem_id']);
          $my_cnt = $my_sub_count[0]['cnt'] ?? 0;
          echo "<a class='prob-btn prob-btn-status' href='status.php?problem_id=".$row['problem_id']."&user_id=".$my_uid_btn.$class_param."'>📋 내 제출 (".$my_cnt.")</a>";
        }
      }

      if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
        echo "<a class='prob-btn prob-btn-rank' href='status.php?problem_id=".$row['problem_id']."'>📊 전체 제출</a>";
      }

      if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'."p".$row['problem_id']])) {
        require_once("include/set_get_key.php");
        echo "<a class='prob-btn prob-btn-edit' href='admin/problem_edit.php?id=$id&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."'>✏️ EDIT</a>";
        echo "<a class='prob-btn prob-btn-edit' href='javascript:phpfm(".$row['problem_id'].")'>📁 TESTDATA</a>";
        if(isset($used_in_contests) && count($used_in_contests)>0 && (!isset($cid) || $cid==0)) {
          echo "<br><small style='color:#aaa;margin-top:8px;display:block'>$MSG_PROBLEM_USED_IN: ";
          foreach($used_in_contests as $contests) {
            echo "<a class='label label-warning' href='contest.php?cid=".$contests[0]."'>".$contests[1]."</a> ";
          }
          echo "</small>";
        }
      }
      ?>
    </div>

    <!-- 정답률 -->
    <?php
      $rate = ($row['submit'] > 0) ? round(($row['accepted'] / $row['submit']) * 100, 1) : 0;
    ?>
    <div class="prob-rate-wrap">
      <span class="prob-rate-label">정답률</span>
      <div class="prob-rate-bar"><div class="prob-rate-fill" style="width:<?php echo $rate?>%"></div></div>
      <span class="prob-rate-text"><?php echo $rate?>%</span>
      <span class="prob-rate-label">(<?php echo $row['accepted']?>/<?php echo $row['submit']?>)</span>
    </div>

    <!-- 내 풀이 + 모범 풀이 -->
    <?php
      $is_admin = isset($_SESSION[$OJ_NAME.'_'.'administrator']);
      $has_my_solutions = false;
      $my_solutions = [];
      $lang_names = array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Scheme","Clang","Clang++","Lua","JavaScript","Go","SQL","Fortran","Matlab","Cobol","R","Scratch3","Cangjie");

      if(isset($_SESSION[$OJ_NAME.'_'.'user_id']) && $pr_flag && !isset($_GET['class_id'])) {
        $my_uid_sol = $_SESSION[$OJ_NAME.'_'.'user_id'];
        $my_pid_sol = $row['problem_id'];
        $my_solutions = pdo_query("SELECT s.solution_id, s.language, s.in_date, s.time, s.memory, LENGTH(sc.source) as code_len FROM solution s LEFT JOIN source_code_user sc ON s.solution_id=sc.solution_id WHERE s.user_id=? AND s.problem_id=? AND s.result=4 ORDER BY s.in_date DESC LIMIT 3", $my_uid_sol, $my_pid_sol);
        if($my_solutions && count($my_solutions) > 0) $has_my_solutions = true;
      }

      // 관리자: 답안 코드 준비
      $answer_files = [];
      if($is_admin) {
        $prob_id = $row['problem_id'];
        $answer_dir = "$OJ_DATA/$prob_id";
        $exts = ['c','cc','cpp','py','java'];
        foreach($exts as $ext) {
          $f = "$answer_dir/answer.$ext";
          if(file_exists($f)) $answer_files[$ext] = file_get_contents($f);
        }
      }

      // 내 풀이가 있거나 관리자면 섹션 표시
      if($has_my_solutions || ($is_admin && !empty($answer_files))):
    ?>
    <div class="my-solutions-wrap">
      <div class="my-solutions-label">✅ 내 정답 풀이</div>
      <div class="my-sol-btns">
        <?php if($is_admin && !empty($answer_files)): ?>
          <?php foreach($answer_files as $ext => $code):
            $lang_label = ['c'=>'C','cc'=>'C++','cpp'=>'C++','py'=>'Python','java'=>'Java'][$ext] ?? $ext;
          ?>
          <button class="answer-sol-btn" onclick="toggleAnswerCode('<?php echo $ext?>', this)" title="모범 답안 (<?php echo $lang_label?>)">
            <span>모범 풀이</span>
          </button>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if($has_my_solutions): ?>
          <?php foreach($my_solutions as $si => $sol): ?>
          <button class="my-sol-btn" onclick="toggleSolution(<?php echo $sol['solution_id']?>, this)" title="<?php echo $sol['in_date']?>">
            <span>풀이<?php echo $si+1?></span>
            <span class="sol-date"><?php echo substr($sol['in_date'], 5, 11)?></span>
          </button>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div id="sol-code-viewer" class="sol-code-viewer"></div>
    </div>

    <?php /* 답안 코드 데이터를 JS에 전달 */ ?>
    <?php if($is_admin && !empty($answer_files)): ?>
    <script>
    var answerCodes = {};
    <?php foreach($answer_files as $ext => $code): ?>
    answerCodes['<?php echo $ext?>'] = <?php echo json_encode($code)?>;
    <?php endforeach; ?>
    </script>
    <?php endif; ?>

    <?php endif; ?>

  </div>

  <?php echo "<!--StartMarkForVirtualJudge-->"; ?>

  <!-- ── 문제 본문 섹션들 ── -->
  <div class="prob-section">
    <div class="prob-section-head">📄 <?php echo $MSG_Description?></div>
    <div id="description" class="prob-section-body content"><?php echo bbcode_to_html($row['description'])?></div>
  </div>

  <?php if($row['input']): ?>
  <div class="prob-section">
    <div class="prob-section-head">⌨️ <?php echo $MSG_Input?></div>
    <div id="input" class="prob-section-body content"><?php echo bbcode_to_html($row['input'])?></div>
  </div>
  <?php endif; ?>

  <?php if($row['output']): ?>
  <div class="prob-section">
    <div class="prob-section-head">🖨️ <?php echo $MSG_Output?></div>
    <div id="output" class="prob-section-body content"><?php echo bbcode_to_html($row['output'])?></div>
  </div>
  <?php endif; ?>

  <?php
  $sinput=str_replace("<","&lt;",$row['sample_input']);
  $sinput=str_replace(">","&gt;",$sinput);
  $soutput=str_replace("<","&lt;",$row['sample_output']);
  $soutput=str_replace(">","&gt;",$soutput);
  if(strlen($sinput) || strlen($soutput)):
  ?>
  <div class="prob-section">
    <div class="prob-section-head">🔁 예제</div>
    <div class="prob-section-body">
      <div class="io-grid">
        <div>
          <div class="io-label">입력 예시</div>
          <?php if(strlen(trim($sinput))): ?>
          <pre><span id="sampleinput" class="sampledata"><?php echo $sinput?></span></pre>
          <a class="io-copy-btn" href="javascript:void(0)" onclick="copyIO(this, 'sampleinput')">📋 복사</a>
          <?php else: ?>
          <div style="color:#aaa;font-size:14px;padding:14px 0;font-style:italic;">입력 없음</div>
          <?php endif; ?>
        </div>
        <?php if(strlen($soutput)): ?>
        <div>
          <div class="io-label">출력 예시</div>
          <pre><span id="sampleoutput" class="sampledata"><?php echo $soutput?></span></pre>
          <a class="io-copy-btn" href="javascript:void(0)" onclick="copyIO(this, 'sampleoutput')">📋 복사</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if($row['hint']): ?>
  <div class="prob-section">
    <div class="prob-section-head">💡 <?php echo $MSG_HINT?></div>
    <div id="hint" class="prob-section-body content hint"><?php echo bbcode_to_html($row['hint'])?></div>
  </div>
  <?php endif; ?>

  <?php if($pr_flag): ?>
  <div class="prob-section">
    <div class="prob-section-head">🏷️ <?php echo $MSG_SOURCE?></div>
    <div fd="source" style="word-wrap:break-word;" pid="<?php echo $row['problem_id']?>" class="prob-section-body content">
      <?php
      $cats=explode(" ",$row['source']);
      foreach($cats as $cat){
        $hash_num=hexdec(substr(md5($cat),0,7));
        $label_theme=$color_theme[$hash_num%count($color_theme)];
        if($label_theme=="") $label_theme="default";
        echo "<a class='label label-$label_theme' style='display:inline-block;margin:2px' href='problemset.php?search=".urlencode(htmlentities($cat,ENT_QUOTES,'utf-8'))."'>".htmlentities($cat,ENT_QUOTES,'utf-8')."</a>&nbsp;";
      }
      ?>
    </div>
  </div>
  <?php endif; ?>

  <?php echo "<!--EndMarkForVirtualJudge-->"; ?>

  <!-- 하단 버튼 -->
  <div class="prob-footer">
    <?php
    if($pr_flag)
      echo "<a class='prob-btn prob-btn-submit' href='submitpage.php?id=$id$class_param' onclick='transform(); return false;'>✏️ 코드 제출</a>";
    else
      echo "<a class='prob-btn prob-btn-submit' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' onclick='transform(); return false;'>✏️ 코드 제출</a>";
    ?>
  </div>

</div><!-- /container -->

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function copyIO(btn, id) {
  var text = document.getElementById(id).textContent;
  navigator.clipboard.writeText(text).then(function() {
    btn.innerHTML = "✅ 복사됨";
    btn.classList.add("copied");
    setTimeout(function() {
      btn.innerHTML = "📋 복사";
      btn.classList.remove("copied");
    }, 1500);
  }).catch(function() {
    CopyToClipboard($("#" + id).text());
  });
}

function phpfm(pid){
  $.post("admin/phpfm.php",{'frame':3,'pid':pid,'pass':''},function(data,status){
    if(status=="success") document.location.href="admin/phpfm.php?frame=3&pid="+pid;
  });
}
function selectOne(num,answer){
  let editor=$("iframe")[0].contentWindow.$("#source");
  let rep=editor.text().replace(new RegExp(num+".*"),num+" "+answer);
  editor.text(rep);
}
function selectMulti(num,answer){
  let editor=$("iframe")[0].contentWindow.$("#source");
  let rep=editor.text().replace(new RegExp(num+".*"),num+" "+answer);
  editor.text(rep);
}
var currentSolId = null;
function toggleSolution(solId, btn) {
  var viewer = document.getElementById('sol-code-viewer');
  // If clicking same button, close
  if(currentSolId === solId && viewer.style.display !== 'none') {
    viewer.style.display = 'none';
    btn.classList.remove('active');
    currentSolId = null;
    return;
  }
  // Remove active from all buttons
  document.querySelectorAll('.my-sol-btn, .answer-sol-btn').forEach(function(b){ b.classList.remove('active'); });
  currentAnswerExt = null;
  btn.classList.add('active');
  currentSolId = solId;
  
  viewer.style.display = 'block';
  viewer.innerHTML = '<div class="sol-code-header"><div class="sol-info"><span>⏳ 코드 불러오는 중...</span></div></div><div class="sol-code-body"><pre style="color:#888;">로딩 중...</pre></div>';
  viewer.style.animation = 'none';
  viewer.offsetHeight; // trigger reflow
  viewer.style.animation = 'solCodeSlide 0.3s ease';
  
  // AJAX fetch source code
  $.ajax({
    url: 'ajax_solution.php?id=' + solId,
    dataType: 'json',
    success: function(res) {
      if(res.error) {
        viewer.innerHTML = '<div class="sol-code-header"><div class="sol-info"><span>⚠️ ' + res.error + '</span></div><button class="sol-code-close" onclick="closeSolViewer()">✕</button></div>';
        return;
      }
      var lang = res.language || '';
      var time = res.time || 0;
      var mem = res.memory || 0;
      var code = res.source || '';
      code = code.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
      
      viewer.innerHTML = '<div class="sol-code-header">'
        + '<div class="sol-info">'
        + '<span>📝 풀이 #' + solId + '</span>'
        + '<span class="sol-tag">' + lang + '</span>'
        + '<span class="sol-tag">⏱ ' + time + 'ms</span>'
        + '<span class="sol-tag">💾 ' + mem + 'KB</span>'
        + '</div>'
        + '<div style="display:flex;gap:6px;align-items:center;">'
        + '<button class="sol-code-copy" onclick="copySolCode()">📋 복사</button>'
        + '<button class="sol-code-close" onclick="closeSolViewer()">✕</button>'
        + '</div>'
        + '</div>'
        + '<div class="sol-code-body"><pre id="sol-code-text">' + code + '</pre></div>';
    },
    error: function(xhr, status, err) {
      viewer.innerHTML = '<div class="sol-code-header"><div class="sol-info"><span>⚠️ 서버 오류: ' + (err||status) + '</span></div><button class="sol-code-close" onclick="closeSolViewer()">✕</button></div>';
    }
  });
}

function closeSolViewer() {
  var viewer = document.getElementById('sol-code-viewer');
  viewer.style.display = 'none';
  document.querySelectorAll('.my-sol-btn, .answer-sol-btn').forEach(function(b){ b.classList.remove('active'); });
  currentSolId = null;
  currentAnswerExt = null;
}

var currentAnswerExt = null;
function toggleAnswerCode(ext, btn) {
  var viewer = document.getElementById('sol-code-viewer');
  // 같은 버튼 클릭 시 닫기
  if(currentAnswerExt === ext && viewer.style.display !== 'none') {
    viewer.style.display = 'none';
    btn.classList.remove('active');
    currentAnswerExt = null;
    return;
  }
  // 모든 버튼 비활성화
  document.querySelectorAll('.my-sol-btn, .answer-sol-btn').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  currentAnswerExt = ext;
  currentSolId = null;

  var code = (typeof answerCodes !== 'undefined' && answerCodes[ext]) ? answerCodes[ext] : '';
  code = code.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  var langMap = {'c':'C','cc':'C++','cpp':'C++','py':'Python','java':'Java'};
  var lang = langMap[ext] || ext;

  viewer.style.display = 'block';
  viewer.style.animation = 'none';
  viewer.offsetHeight;
  viewer.style.animation = 'solCodeSlide 0.3s ease';

  viewer.innerHTML = '<div class="sol-code-header" style="background:linear-gradient(135deg,#2563eb,#3b82f6)">'
    + '<div class="sol-info">'
    + '<span>📘 모범 풀이</span>'
    + '<span class="sol-tag">' + lang + '</span>'
    + '</div>'
    + '<div style="display:flex;gap:6px;align-items:center;">'
    + '<button class="sol-code-copy" onclick="copySolCode()">📋 복사</button>'
    + '<button class="sol-code-close" onclick="closeSolViewer()">✕</button>'
    + '</div>'
    + '</div>'
    + '<div class="sol-code-body"><pre id="sol-code-text">' + code + '</pre></div>';
}

function copySolCode() {
  var codeEl = document.getElementById('sol-code-text');
  if(codeEl) {
    var text = codeEl.textContent;
    if(navigator.clipboard) {
      navigator.clipboard.writeText(text).then(function(){
        var btn = document.querySelector('.sol-code-copy');
        btn.textContent = '✅ 복사됨!';
        setTimeout(function(){ btn.textContent = '📋 복사'; }, 1500);
      });
    } else {
      // fallback
      var ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      var btn = document.querySelector('.sol-code-copy');
      btn.textContent = '✅ 복사됨!';
      setTimeout(function(){ btn.textContent = '📋 복사'; }, 1500);
    }
  }
}

$(document).ready(function(){
  $("#creator").load("problem-ajax.php?pid=<?php echo $id?>");
  <?php if(isset($OJ_MARKDOWN)&&$OJ_MARKDOWN){ ?>
  $(".md").each(function(){ $(this).html(marked.parse($(this).html())); });
  <?php } ?>
  $('span[class=auto_select]').each(function(){
    let i=1,start=0,raw=$(this).html(),options=['A','B','C','D'];
    while(start>=0){
      start=raw.indexOf(i+".",start);
      if(start<0) break;
      let end=start,type="radio";
      for(let j=0;j<4;j++){
        let option=options[j];
        end=raw.indexOf(option+".",start);
        if(j==0&&raw.substring(start,end).indexOf("多选")>0) type="checkbox";
        if(end<0) break;
        let disp="<input type=\""+type+"\" name=\""+i+"\" value=\""+option+"\" />"+option+".";
        raw=raw.substring(0,end-1)+disp+raw.substring(end+2);
        start+=disp.length;
      }
      start=end+1; i++;
    }
    $(this).html(raw);
  });
  $('input[type="radio"]').click(function(){
    if($(this).is(':checked')) selectOne($(this).attr("name"),$(this).val());
  });
  $('input[type="checkbox"]').click(function(){
    let num=$(this).attr("name"),answer="";
    $("input[type=checkbox][name="+num+"]").each(function(){
      if($(this).is(':checked')) answer+=$(this).val();
    });
    selectMulti(num,answer);
  });
  // transform()은 코드제출 버튼 클릭 시 호출
});
function CopyToClipboard(input){
  if(window.clipboardData){ window.clipboardData.setData("Text",input); }
  else {
    var el=document.createElement("pre");
    el.style.cssText="position:absolute;left:-10000px;top:-10000px";
    el.textContent=input; el.contentEditable=true;
    document.body.appendChild(el);
    var r=document.createRange(); r.selectNodeContents(el);
    var s=window.getSelection(); s.removeAllRanges(); s.addRange(r);
    try { document.execCommand("copy"); alert("채점 데이터를 복사했습니다."); }
    catch(e){ alert("복사할 수 없습니다."); }
    document.body.removeChild(el);
  }
}
function transform(){
  if(document.getElementById('submitPage')) return; // 이미 열려있으면 무시
  var submitBtn = document.getElementById('submit');
  if(!submitBtn) return;
  var submitURL = submitBtn.href;
  // sid 전달 제거 - 항상 기본 템플릿으로 시작
  var main = document.getElementById('main');

  if(window.innerWidth < 768){
    // 모바일: 문제 아래에 에디터 추가
    var wrap = document.createElement('div');
    wrap.id = 'submitPage';
    wrap.style.cssText = 'margin-top:16px;animation:slideUp 0.3s ease;';
    wrap.innerHTML = '<div style="text-align:right;margin-bottom:8px;"><button onclick="closeEditor()" style="background:#ef4444;color:#fff;border:none;border-radius:6px;padding:6px 16px;font-size:13px;font-weight:700;cursor:pointer;">✕ 닫기</button></div>'
      + '<iframe id="ansFrame" src="'+submitURL+'&spa&_t='+Date.now()+'" style="width:100%;height:'+Math.max(window.innerHeight*0.7,500)+'px;border:none;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.08);"></iframe>';
    main.appendChild(wrap);
    setTimeout(function(){ wrap.scrollIntoView({behavior:'smooth'}); }, 300);
  } else {
    // 데스크탑: 좌우 분할 (애니메이션)
    main.className = ''; main.style.cssText = 'display:flex;gap:0;width:100%;max-width:100%;padding:8px 12px;margin:0;transition:all 0.3s ease;';
    
    // 문제 영역 래퍼
    var probWrap = document.createElement('div');
    probWrap.id = 'probWrap';
    probWrap.style.cssText = 'flex:0 0 45%;max-width:45%;overflow-y:auto;max-height:calc(100vh - 60px);padding-right:8px;transition:all 0.3s ease;';
    while(main.firstChild){
      probWrap.appendChild(main.firstChild);
    }
    main.appendChild(probWrap);

    // 에디터 영역 (오른쪽에서 슬라이드)
    var editorWrap = document.createElement('div');
    editorWrap.id = 'submitPage';
    editorWrap.style.cssText = 'flex:0 0 55%;max-width:55%;position:sticky;top:60px;height:calc(100vh - 60px);min-width:0;transform:translateX(100%);opacity:0;transition:all 0.35s ease;';
    
    // 닫기 버튼
    var closeBtn = '<div style="text-align:right;padding:6px 6px 8px 0;"><button onclick="closeEditor()" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:13px;font-weight:700;cursor:pointer;transition:all 0.15s;box-shadow:0 2px 8px rgba(239,68,68,0.3);" onmouseover="this.style.background=\'#dc2626\';this.style.boxShadow=\'0 2px 12px rgba(239,68,68,0.5)\'" onmouseout="this.style.background=\'#ef4444\';this.style.boxShadow=\'0 2px 8px rgba(239,68,68,0.3)\'">✕ 에디터 닫기</button></div>';
    
    editorWrap.innerHTML = closeBtn + '<iframe src="'+submitURL+'&spa&_t='+Date.now()+'" style="width:100%;height:calc(100% - 36px);border:none;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.1);background:#fff;"></iframe>';
    main.appendChild(editorWrap);

    // 애니메이션 트리거
    requestAnimationFrame(function(){
      requestAnimationFrame(function(){
        editorWrap.style.transform = 'translateX(0)';
        editorWrap.style.opacity = '1';
      });
    });
  }

  // 코드 제출 버튼 숨기기
  submitBtn.style.display = 'none';
  var footers = document.querySelectorAll('.prob-footer');
  footers.forEach(function(f){ f.style.display = 'none'; });
  <?php if($row['spj']>1){ ?>
  window.setTimeout(function(){
    try{ $("iframe")[0].contentWindow.$("#TestRun").remove(); }catch(e){}
  },1000);
  <?php }?>
}

function closeEditor(){
  var submitPage = document.getElementById('submitPage');
  var probWrap = document.getElementById('probWrap');
  var main = document.getElementById('main');
  var submitBtn = document.getElementById('submit');

  if(window.innerWidth < 768){
    // 모바일: 에디터 제거
    if(submitPage) submitPage.remove();
  } else {
    // 데스크탑: 애니메이션 후 원복
    if(submitPage){
      submitPage.style.transform = 'translateX(100%)';
      submitPage.style.opacity = '0';
    }
    setTimeout(function(){
      if(submitPage) submitPage.remove();
      if(probWrap){
        // probWrap의 자식들을 main으로 복원
        while(probWrap.firstChild){
          main.appendChild(probWrap.firstChild);
        }
        probWrap.remove();
      }
      main.className = 'container'; main.style.cssText = '';
    }, 350);
  }

  // 버튼 다시 보이기
  if(submitBtn) submitBtn.style.display = '';
  var footers = document.querySelectorAll('.prob-footer');
  footers.forEach(function(f){ f.style.display = ''; });
}
</script>
</body>
</html>
