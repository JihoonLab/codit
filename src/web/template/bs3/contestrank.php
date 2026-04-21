<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>순위표 - <?php echo htmlspecialchars($view_title)?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
    .cr-wrap { max-width: 1200px; margin: 32px auto; padding: 0 20px 60px; }

    /* 미니 헤더 */
    .cr-topbar {
      background: #fff; border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      padding: 16px 24px; margin-bottom: 20px;
      display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    }
    .cr-topbar-title { font-size: 16px; font-weight: 900; color: #7c3aed; flex: 1; }
    .cr-topbar-clock { font-size: 13px; font-weight: 700; color: #e74c3c; background: #fff5f5; border: 1px solid #fcc; border-radius: 6px; padding: 4px 12px; }
    .cr-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .cr-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
    .cr-btn:hover { text-decoration: none; }
    .cr-btn-blue { background: #7c3aed; color: #fff !important; }
    .cr-btn-blue:hover { background: #6d28d9; }
    .cr-btn-gray { background: #f0f3f7; color: #555 !important; }
    .cr-btn-gray:hover { background: #e2e8f0; }
    .cr-btn-active { background: #e8f0fe; color: #7c3aed !important; border: 1.5px solid #7c3aed; }

    /* 순위 테이블 카드 */
    .cr-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; }
    .cr-card-header { background: #f8fafc; border-bottom: 2px solid #7c3aed; padding: 13px 20px; font-size: 15px; font-weight: 700; color: #7c3aed; display: flex; align-items: center; justify-content: space-between; }
    .cr-card-header a { font-size: 12px; font-weight: 600; color: #aaa; text-decoration: none; }
    .cr-card-header a:hover { color: #7c3aed; }

    .cr-table-wrap { overflow-x: auto; }
    .cr-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .cr-table thead tr { background: #7c3aed; }
    .cr-table thead th { padding: 11px 12px; color: #fff; font-weight: 600; text-align: center; white-space: nowrap; }
    .cr-table thead th.th-left { text-align: left; }
    .cr-table tbody tr { border-bottom: 1px solid #f0f3f7; transition: background 0.1s; }
    .cr-table tbody tr:last-child { border-bottom: none; }
    .cr-table tbody tr:hover { background: #f5f8ff; }
    .cr-table td { padding: 10px 12px; text-align: center; vertical-align: middle; white-space: nowrap; }
    .cr-table td.td-left { text-align: left; }
    .cr-table td a { color: #7c3aed; text-decoration: none; }
    .cr-table td a:hover { text-decoration: underline; }

    .rank-num { font-size: 15px; font-weight: 900; color: #888; }
    .rank-medal { font-size: 20px; }
    .solved-count { font-size: 16px; font-weight: 900; color: #7c3aed; }
    .penalty-time { color: #888; font-size: 12px; }

    /* 문제별 셀 — 퍼스트블러드/맞음/틀림 + 제출횟수별 채도 */
    .p-cell { border-radius: 8px; padding: 6px 8px; font-size: 12px; font-weight: 700; text-align: center; line-height: 1.4; }

    /* 퍼스트 블러드: 금색 */
    .p-first { background: linear-gradient(135deg, #fef3c7, #fcd34d); color: #78350f; border: 1.5px solid #f59e0b; box-shadow: 0 0 8px rgba(245,158,11,.3); font-weight: 900; }

    /* 맞은 문제: 진한 초록 — 채도 4단계 */
    .p-ac        { background: #86efac; color: #052e16; }
    .p-ac.tries-2 { background: #4ade80; color: #052e16; }
    .p-ac.tries-3 { background: #22c55e; color: #fff; }
    .p-ac.tries-4 { background: #16a34a; color: #fff; }

    /* 틀린 문제: 진한 빨강 — 채도 4단계 */
    .p-wa        { background: #fca5a5; color: #7f1d1d; }
    .p-wa.tries-2 { background: #f87171; color: #450a0a; }
    .p-wa.tries-3 { background: #ef4444; color: #fff; }
    .p-wa.tries-4 { background: #dc2626; color: #fff; }

    .p-empty { color: #d1d5db; font-size: 12px; }

    .user-nick { font-weight: 700; color: #222; }
    .user-id { font-size: 11px; color: #aaa; }

    /* ═══ 수행평가 채점 카드 (관리자 전용) ═══ */
    .cr-grading-card {
      background: #fff;
      border: 1.5px solid #fed7aa;
      border-radius: 16px;
      margin-bottom: 16px;
      overflow: hidden;
      box-shadow: 0 2px 14px rgba(249,115,22,0.08);
    }
    .cr-grading-head {
      display: flex; align-items: center; gap: 14px;
      padding: 16px 22px;
      background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
      border-bottom: 1px solid #fed7aa;
      flex-wrap: wrap;
    }
    .cr-grading-head-icon {
      display: inline-flex; align-items: center; justify-content: center;
      width: 44px; height: 44px;
      background: linear-gradient(135deg, #fb923c, #f97316);
      border-radius: 12px;
      font-size: 22px;
      box-shadow: 0 2px 6px rgba(249,115,22,0.25);
    }
    .cr-grading-head-text { flex: 1; min-width: 160px; }
    .cr-grading-head-title {
      font-size: 16px; font-weight: 800; color: #9a3412; letter-spacing: -0.3px;
    }
    .cr-grading-head-sub {
      font-size: 12.5px; color: #7c2d12; font-weight: 500;
      margin-top: 3px;
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .cr-grading-head-sub .cr-subject-chip {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(255,255,255,0.85); backdrop-filter: blur(4px);
      color: #c2410c; font-size: 12px; font-weight: 700;
      padding: 4px 11px; border-radius: 100px;
      border: 1px solid #fed7aa;
    }
    .cr-grading-head-sub .cr-subject-chip::before { content: '📘'; }
    .cr-grading-head-meta { font-size: 12.5px; color: #7c2d12; }
    .cr-grading-head-meta strong { color: #9a3412; font-weight: 800; font-size: 14px; margin: 0 2px; }

    /* 점수 스케일 (8단계 카드 그리드) */
    .cr-grade-scale {
      display: grid;
      grid-template-columns: repeat(8, 1fr);
      gap: 8px;
      padding: 16px 22px;
    }
    @media (max-width: 900px) {
      .cr-grade-scale { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 480px) {
      .cr-grade-scale { grid-template-columns: repeat(2, 1fr); }
    }
    .cr-scale-item {
      display: flex; flex-direction: column; align-items: center;
      padding: 12px 8px 10px;
      background: #fff;
      border: 1.5px solid #f3e8cf;
      border-radius: 12px;
      transition: all 0.15s;
      cursor: default;
      position: relative;
    }
    .cr-scale-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(249,115,22,0.15);
    }
    .cr-scale-item .cr-scale-range {
      font-size: 11px; font-weight: 700;
      color: #78350f; opacity: 0.85;
      letter-spacing: 0.2px;
      margin-bottom: 6px;
    }
    .cr-scale-item .cr-scale-score {
      font-size: 22px; font-weight: 900;
      color: #9a3412;
      line-height: 1;
      font-variant-numeric: tabular-nums;
      display: inline-flex; align-items: baseline;
    }
    .cr-scale-item .cr-scale-score small {
      font-size: 11px; font-weight: 700;
      color: #c2410c; opacity: 0.75;
      margin-left: 2px;
    }

    /* 등급별 색상 강조 */
    .cr-scale-item.tier-s { background: linear-gradient(180deg, #fef3c7 0%, #fff 100%); border-color: #f59e0b; }
    .cr-scale-item.tier-s .cr-scale-range { color: #78350f; }
    .cr-scale-item.tier-s .cr-scale-score { color: #b45309; }
    .cr-scale-item.tier-s::before {
      content: '👑';
      position: absolute; top: -8px; right: -4px;
      font-size: 14px;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }
    .cr-scale-item.tier-a { background: linear-gradient(180deg, #dcfce7 0%, #fff 100%); border-color: #86efac; }
    .cr-scale-item.tier-a .cr-scale-range { color: #14532d; }
    .cr-scale-item.tier-a .cr-scale-score { color: #16a34a; }
    .cr-scale-item.tier-b { background: linear-gradient(180deg, #ecfccb 0%, #fff 100%); border-color: #bef264; }
    .cr-scale-item.tier-b .cr-scale-range { color: #365314; }
    .cr-scale-item.tier-b .cr-scale-score { color: #65a30d; }
    .cr-scale-item.tier-c { background: linear-gradient(180deg, #fef9c3 0%, #fff 100%); border-color: #fde047; }
    .cr-scale-item.tier-c .cr-scale-range { color: #713f12; }
    .cr-scale-item.tier-c .cr-scale-score { color: #a16207; }
    .cr-scale-item.tier-d { background: linear-gradient(180deg, #ffedd5 0%, #fff 100%); border-color: #fdba74; }
    .cr-scale-item.tier-d .cr-scale-range { color: #7c2d12; }
    .cr-scale-item.tier-d .cr-scale-score { color: #c2410c; }
    .cr-scale-item.tier-e { background: linear-gradient(180deg, #fee2e2 0%, #fff 100%); border-color: #fca5a5; }
    .cr-scale-item.tier-e .cr-scale-range { color: #7f1d1d; }
    .cr-scale-item.tier-e .cr-scale-score { color: #dc2626; }

    /* 점수 컬럼 */
    .cr-table thead th.th-score { background: linear-gradient(135deg, #f97316, #ea580c); }
    .exam-score {
      display: inline-flex;
      align-items: baseline;
      justify-content: center;
      gap: 2px;
      padding: 4px 11px;
      border-radius: 7px;
      font-weight: 900;
      font-size: 15px;
      font-variant-numeric: tabular-nums;
      min-width: 52px;
    }
    .exam-score small { font-size: 10.5px; font-weight: 600; opacity: 0.7; }
    .exam-score.sc-perfect { background: linear-gradient(135deg, #fef3c7, #fcd34d); color: #78350f; border: 1.5px solid #f59e0b; }
    .exam-score.sc-high    { background: #dcfce7; color: #14532d; border: 1px solid #86efac; }
    .exam-score.sc-mid     { background: #fef9c3; color: #713f12; border: 1px solid #fde047; }
    .exam-score.sc-below   { background: #ffedd5; color: #7c2d12; border: 1px solid #fdba74; }
    .exam-score.sc-low     { background: #fee2e2; color: #7f1d1d; border: 1px solid #fca5a5; }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="cr-wrap">
  <!-- 상단 바 -->
  <div class="cr-topbar">
    <div class="cr-topbar-title">🏆 <?php echo htmlspecialchars($view_title)?> — 순위표</div>
    <div class="cr-topbar-clock">🕐 <span id="nowdate"><?php echo date("Y-m-d H:i:s")?></span></div>
    <div class="cr-actions">
      <a href="contest.php?cid=<?php echo $cid?>" class="cr-btn cr-btn-gray">📋 문제</a>
      <a href="status.php?cid=<?php echo $cid?>" class="cr-btn cr-btn-gray">📊 제출현황</a>
      <a href="contestrank.php?cid=<?php echo $cid?>" class="cr-btn cr-btn-active">🏆 순위표</a>
      <a href="conteststatistics.php?cid=<?php echo $cid?>" class="cr-btn cr-btn-gray">📈 통계</a>
      <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])): ?>
      <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $cid?>" class="cr-btn" style="background:#7c3aed;color:#fff">⚙️ 수정</a>
      <?php endif; ?>
    </div>
  </div>

  <?php $is_grading_admin = isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']); ?>

  <?php if($is_grading_admin): ?>
  <!-- 관리자 전용: 수행평가 채점 카드 -->
  <div class="cr-grading-card">
    <div class="cr-grading-head">
      <div class="cr-grading-head-icon">📝</div>
      <div class="cr-grading-head-text">
        <div class="cr-grading-head-title">수행평가 채점</div>
        <div class="cr-grading-head-sub">
          <span class="cr-subject-chip"><?php echo htmlspecialchars($exam_subject_lbl)?></span>
          <span class="cr-grading-head-meta">만점 <strong><?php echo $exam_max_score?>점</strong></span>
          <span class="cr-grading-head-meta">· 전체 <strong><?php echo $pid_cnt?>문제</strong></span>
        </div>
      </div>
    </div>
    <div class="cr-grade-scale">
      <?php
        $grade_rows = ($exam_max_score==20)
          ? [['90% 이상',20,'s'],['70~90%',18,'a'],['60~70%',16,'b'],['50~60%',14,'c'],['40~50%',12,'d'],['30~40%',10,'d'],['20~30%',8,'e'],['20% 미만',6,'e']]
          : [['90% 이상',40,'s'],['70~90%',36,'a'],['60~70%',32,'b'],['50~60%',28,'c'],['40~50%',24,'d'],['30~40%',20,'d'],['20~30%',16,'e'],['20% 미만',12,'e']];
        foreach($grade_rows as $gr):
      ?>
      <div class="cr-scale-item tier-<?php echo $gr[2]?>">
        <div class="cr-scale-range"><?php echo $gr[0]?></div>
        <div class="cr-scale-score"><?php echo $gr[1]?><small>점</small></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- 순위 테이블 -->
  <div class="cr-card">
    <div class="cr-card-header">
      <span>참가자 <?php echo $user_cnt?>명</span>
      <a href="contestrank.xls.php?cid=<?php echo $cid?>">⬇️ 엑셀 다운로드</a>
    </div>
    <div class="cr-table-wrap">
      <table class="cr-table" id="rank">
        <thead>
          <tr>
            <th style="width:50px">순위</th>
            <th class="th-left">참가자</th>
            <th style="width:60px">정답</th>
            <th style="width:80px">패널티</th>
            <?php if($is_grading_admin): ?>
            <th style="width:80px" class="th-score">수행평가<br>점수</th>
            <?php endif; ?>
            <?php for($i=0; $i<$pid_cnt; $i++):
              if(time() < $end_time):
                echo "<th><a href='problem.php?cid=$cid&pid=$i' style='color:#fff'>$PID[$i]</a></th>";
              else:
                $sql = "SELECT cp.problem_id FROM contest_problem cp WHERE cp.contest_id=? AND cp.num=?";
                $tr = pdo_query($sql, $cid, $i);
                $tpid = isset($tr[0][0]) ? $tr[0][0] : 0;
                echo "<th>$PID[$i]</th>";
              endif;
            endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $rank = 1;
          for($i=0; $i<$user_cnt; $i++):
            $uuid = $U[$i]->user_id;
            $unick = htmlspecialchars($U[$i]->nick ?: $uuid);
            $isMe = isset($_GET['user_id']) && $uuid == $_GET['user_id'];
          ?>
          <tr <?php echo $isMe ? 'style="background:#fffbe6"' : ''?>>
            <td>
              <?php if($U[$i]->nick[0] == '*'): ?>
                <span class="rank-num">*</span>
              <?php elseif($rank == 1): ?>
                <span class="rank-medal">🥇</span><?php $rank++; ?>
              <?php elseif($rank == 2): ?>
                <span class="rank-medal">🥈</span><?php $rank++; ?>
              <?php elseif($rank == 3): ?>
                <span class="rank-medal">🥉</span><?php $rank++; ?>
              <?php else: ?>
                <span class="rank-num"><?php echo $rank++?></span>
              <?php endif; ?>
            </td>
            <td class="td-left">
              <a href="userinfo.php?user=<?php echo urlencode($uuid)?>" class="user-nick"><?php echo $unick?></a>
              <div class="user-id">@<?php echo htmlspecialchars($uuid)?></div>
            </td>
            <td><span class="solved-count"><?php echo $U[$i]->solved?></span></td>
            <td><span class="penalty-time"><?php echo sec2str($U[$i]->time)?></span></td>
            <?php if($is_grading_admin):
              $_exam_score = calc_exam_score($U[$i]->solved, $pid_cnt, $exam_max_score);
              $_exam_rate  = $pid_cnt > 0 ? $U[$i]->solved / $pid_cnt : 0;
              $_score_cls = 'sc-low';
              if ($_exam_rate >= 0.9) $_score_cls = 'sc-perfect';
              elseif ($_exam_rate >= 0.7) $_score_cls = 'sc-high';
              elseif ($_exam_rate >= 0.5) $_score_cls = 'sc-mid';
              elseif ($_exam_rate >= 0.3) $_score_cls = 'sc-below';
            ?>
            <td><span class="exam-score <?php echo $_score_cls?>"><?php echo $_exam_score?><small>/<?php echo $exam_max_score?></small></span></td>
            <?php endif; ?>
            <?php for($j=0; $j<$pid_cnt; $j++):
              $is_ac  = isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j] > 0;
              $is_wa  = isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j] > 0;
              $is_first = isset($first_blood[$j]) && $uuid == $first_blood[$j];
            ?>
            <td>
              <?php if($is_ac && $is_first):
                $wa_n = $is_wa ? $U[$i]->p_wa_num[$j] : 0;
              ?>
                <div class="p-cell p-first">⚡ <?php echo sec2str($U[$i]->p_ac_sec[$j])?><?php if($wa_n > 0) echo "<br>(-".$wa_n.")"; ?></div>
              <?php elseif($is_ac):
                $wa_n = $is_wa ? $U[$i]->p_wa_num[$j] : 0;
                $tries_cls = '';
                if($wa_n >= 4) $tries_cls = ' tries-4';
                elseif($wa_n >= 3) $tries_cls = ' tries-3';
                elseif($wa_n >= 2) $tries_cls = ' tries-2';
              ?>
                <div class="p-cell p-ac<?php echo $tries_cls?>"><?php echo sec2str($U[$i]->p_ac_sec[$j])?><?php if($wa_n > 0) echo "<br>(-".$wa_n.")"; ?></div>
              <?php elseif($is_wa):
                $wa_n = $U[$i]->p_wa_num[$j];
                $tries_cls = '';
                if($wa_n >= 4) $tries_cls = ' tries-4';
                elseif($wa_n >= 3) $tries_cls = ' tries-3';
                elseif($wa_n >= 2) $tries_cls = ' tries-2';
              ?>
                <div class="p-cell p-wa<?php echo $tries_cls?>">(-<?php echo $wa_n?>)</div>
              <?php else: ?>
                <span class="p-empty">—</span>
              <?php endif; ?>
            </td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if(!empty($absent)): ?>
  <div style="background:#fff;border-radius:10px;padding:16px 20px;margin-top:16px;box-shadow:0 2px 8px rgba(0,0,0,0.06)">
    <div style="font-size:13px;font-weight:700;color:#e74c3c;margin-bottom:8px">미제출 참가자</div>
    <?php foreach($absent as $a): $uid = htmlspecialchars($a['user_id']); ?>
    <a href="userinfo.php?user=<?php echo $uid?>" style="display:inline-block;margin:3px;padding:3px 10px;background:#fee2e2;color:#991b1b;border-radius:5px;font-size:12px;text-decoration:none"><?php echo $uid?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime() - new Date().getTime();
function clock() {
  var x = new Date(new Date().getTime() + diff);
  var pad = n => n>=10?n:'0'+n;
  document.getElementById('nowdate').textContent =
    x.getFullYear()+'-'+pad(x.getMonth()+1)+'-'+pad(x.getDate())+' '+pad(x.getHours())+':'+pad(x.getMinutes())+':'+pad(x.getSeconds());
  setTimeout(clock, 1000);
}
clock();
setTimeout(() => location.href = '/contestrank.php?cid=<?php echo $cid?>', 60000);
</script>
</body>
</html>
