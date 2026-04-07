<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($class['title'])?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.cv-wrap{max-width:1000px;margin:36px auto;padding:0 16px}
.cv-header{background:#7c3aed;color:#fff;border-radius:12px 12px 0 0;padding:28px 32px}
.cv-header h1{font-size:22px;font-weight:700;margin:0 0 6px}
.cv-header .meta{font-size:13px;opacity:.85}
.cv-body{background:#fff;border-radius:0 0 12px 12px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:32px;margin-bottom:28px}
.cv-content{font-size:15px;line-height:1.9;color:#333;margin-bottom:32px}
.cv-content img{max-width:100%;border-radius:8px;margin:8px 0}
.cv-section-title{font-size:16px;font-weight:700;color:#7c3aed;margin:0 0 14px;padding-bottom:8px;border-bottom:2px solid #e8f0fe}
.prob-list{list-style:none;margin:0 0 32px;padding:0}
.prob-item{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:8px;margin-bottom:8px;background:#f8faff;border:1px solid #e8f0fe;transition:background 0.1s}
.prob-item.solved{background:#e8f4fd;border-color:#90caf9}
.prob-item.solved .prob-num{background:#1a9fd4}
.prob-item.solved .prob-title{color:#1565c0;font-weight:700}
.prob-item.unsolved{background:#fff5f5;border-color:#fcc}
.prob-item.unsolved .prob-num{background:#e74c3c}
.prob-item.unsolved .prob-title{color:#c0392b}
.prob-status-badge{display:flex;align-items:center;gap:5px;font-size:14px;font-weight:700;margin-left:auto;white-space:nowrap;flex-shrink:0}
.prob-status-badge.solved{color:#1a9fd4}
.prob-status-badge.unsolved{color:#e74c3c}
.prob-item:hover{background:#e8f0fe}
.prob-num{width:28px;height:28px;background:#7c3aed;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0}
.prob-info{flex:1}
.prob-title{font-size:14px;font-weight:600;color:#7c3aed;text-decoration:none}
.prob-title:hover{text-decoration:underline}
.prob-id{font-size:12px;color:#aaa}
.prob-first{font-size:12px;color:#27ae60;margin-left:auto;white-space:nowrap}
.stat-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid #e5e9f0}
.stat-table{width:100%;border-collapse:collapse;min-width:500px}
.stat-table thead tr{background:#7c3aed;color:#fff}
.stat-table th{padding:11px 14px;font-size:12px;font-weight:600;text-align:center;white-space:nowrap}
.stat-table th.th-user{text-align:left;min-width:120px}
.stat-table td{padding:10px 14px;font-size:13px;border-bottom:1px solid #f0f0f0;text-align:center}
.stat-table td.td-user{text-align:left;font-weight:500;color:#333}
.stat-table tbody tr:hover{background:#f5f8ff}
.stat-table tbody tr:last-child td{border-bottom:none}
.ac{color:#27ae60;font-size:16px;font-weight:700}
.no{color:#ddd;font-size:16px}
.score{font-weight:700;color:#7c3aed}
.rank-badge{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;font-size:13px;font-weight:800;color:#fff}
.rank-1{background:linear-gradient(135deg,#fbbf24,#f59e0b);box-shadow:0 2px 6px rgba(245,158,11,.4)}
.rank-2{background:linear-gradient(135deg,#cbd5e1,#94a3b8);box-shadow:0 2px 6px rgba(148,163,184,.4)}
.rank-3{background:linear-gradient(135deg,#d97706,#b45309);box-shadow:0 2px 6px rgba(180,83,9,.3)}
.rank-4{background:#7c3aed;font-size:12px}
.rank-5{background:#7c3aed;font-size:12px}
.stat-empty{text-align:center;padding:32px;color:#aaa;font-size:14px}
.btn-row{display:flex;gap:10px;margin-bottom:24px}
.btn-back{background:#f0f0f0;color:#555;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;text-decoration:none}
.btn-back:hover{background:#e0e0e0}
.btn-edit{background:#7c3aed;color:#fff;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;text-decoration:none}
.btn-edit:hover{background:#6d28d9}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cv-wrap">
  <div class="btn-row">
    <a href="classop.php" class="btn-back">← 목록</a>
    <?php if($is_admin): ?><a href="classop.php?action=write&id=<?php echo $class['class_id']?>" class="btn-edit">✏️ 수정</a><?php endif; ?>
  </div>

  <div class="cv-header">
    <h1>📚 <?php echo htmlspecialchars($class['title'])?></h1>
    <?php if($class['description']): ?><div class="meta"><?php echo htmlspecialchars($class['description'])?></div><?php endif; ?>
  </div>

  <div class="cv-body">
    <!-- 수업 내용 -->
    <?php if($class['content']): ?>
    <div class="cv-content">
      <?php
        $content = $class['content'];
        if(strip_tags($content) === $content) {
          $content = htmlspecialchars($content, ENT_QUOTES);
          $content = preg_replace('/https?:\/\/\S+\.(jpg|jpeg|png|gif|webp)(\?\S*)?/i','<img src="$0" alt="">',$content);
          $content = nl2br($content);
        }
        echo $content;
      ?>
    </div>
    <?php endif; ?>

    <!-- 문제 목록 -->
    <?php if(!empty($problems)): ?>
    <div class="cv-section-title">📝 문제 목록 (<?php echo count($problems)?>문제)</div>
    <?php
      $my_solved = [];
      if(isset($_SESSION[$OJ_NAME.'_user_id']) && !empty($problems)) {
        $pids_str = implode(',', array_map(fn($p)=>intval($p['problem_id']), $problems));
        $class_time = $class['time'] ?? '2000-01-01 00:00:00';
        $my_ac = pdo_query("SELECT DISTINCT problem_id FROM solution WHERE user_id=? AND result=4 AND problem_id IN ($pids_str) AND class_id=?", $_SESSION[$OJ_NAME.'_user_id'], $class['class_id']);
        if($my_ac) foreach($my_ac as $r) $my_solved[$r['problem_id']] = true;
      }
    ?>
    <ul class="prob-list">
      <?php foreach($problems as $i => $p): ?>
      <?php $is_solved = isset($my_solved[$p['problem_id']]); ?>
      <?php $is_logged = isset($_SESSION[$OJ_NAME.'_user_id']); ?>
      <li class="prob-item <?php echo $is_logged ? ($is_solved?'solved':'unsolved') : ''?>">
        <div class="prob-num"><?php echo $i+1?></div>
        <div class="prob-info">
          <a href="problem.php?id=<?php echo $p['problem_id']?>&class_id=<?php echo $class['class_id']?>" class="prob-title"><?php echo htmlspecialchars($p['title'] ?? '제목 없음')?></a>
          <div class="prob-id">#<?php echo $p['problem_id']?></div>
        </div>
        <?php if($is_logged): ?>
          <?php if($is_solved): ?>
          <div class="prob-status-badge solved">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            해결
          </div>
          <?php else: ?>
          <div class="prob-status-badge unsolved">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            미해결
          </div>
          <?php endif; ?>
        <?php elseif(isset($first_map[$p['problem_id']])): ?>
        <div class="prob-first">🥇 <?php echo htmlspecialchars($first_map[$p['problem_id']]['user_id'])?> (<?php echo substr($first_map[$p['problem_id']]['time'],0,16)?>)</div>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <!-- 풀이 현황 -->
    <div class="cv-section-title">📊 학생별 풀이 현황 <?php if(!empty($students)): ?><span style="font-size:13px;font-weight:500;color:#999">(<?php echo count($students)?>명)</span><?php endif; ?></div>
    <?php if(empty($problems)): ?>
      <div class="stat-empty">등록된 문제가 없습니다.</div>
    <?php elseif(empty($students)): ?>
      <div class="stat-empty"><?php
        $class_tag = trim($class['tag'] ?? '');
        if(preg_match('/^AI-/', $class_tag)) echo '해당 AI분반에 배정된 학생이 없습니다.';
        else if($class_tag !== '') echo '해당 반에 등록된 학생이 없습니다.';
        else echo '아직 제출한 학생이 없습니다.';
      ?></div>
    <?php else: ?>
    <div class="stat-table-wrap">
      <table class="stat-table">
        <thead>
          <tr>
            <th class="th-user">학생</th>
            <?php foreach($problems as $i2 => $p): ?>
            <th><a href="problem.php?id=<?php echo $p['problem_id']?>&class_id=<?php echo $class['class_id']?>" style="color:#fff;text-decoration:none" title="<?php echo htmlspecialchars($p['title']??'')?>">
              <?php echo $p['problem_id']?>
            </a></th>
            <?php endforeach; ?>
            <th>풀이</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($students as $st): ?>
          <tr>
            <td class="td-user"><?php echo htmlspecialchars($st['user_id'])?> <span style="color:#aaa;font-size:12px">(<?php if(!empty($st['student_no'])) echo htmlspecialchars($st['student_no']).' '; ?><?php echo htmlspecialchars($st['nick'] ?? '')?>)</span></td>
            <?php $cnt = 0; foreach($problems as $p):
              $ok = isset($ac_map[$st['user_id']][$p['problem_id']]);
              if($ok) $cnt++;
              $rank = $rank_map[$st['user_id']][$p['problem_id']] ?? 0;
            ?>
            <td><?php if($rank >= 1 && $rank <= 5): ?>
              <span class="rank-badge rank-<?php echo $rank?>"><?php echo $rank?></span>
            <?php elseif($ok): ?>
              <span class="ac">✔</span>
            <?php else: ?>
              <span class="no">·</span>
            <?php endif; ?></td>
            <?php endforeach; ?>
            <td><span class="score"><?php echo $cnt?>/<?php echo count($problems)?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
