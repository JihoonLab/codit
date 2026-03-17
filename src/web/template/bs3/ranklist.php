<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>랭킹 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Inter','Noto Sans KR',sans-serif; background: #f0f2f5; }
    .rl-wrap { max-width: 960px; margin: 0 auto; padding: 32px 20px 48px; }

    /* Header */
    .rl-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
    }
    .rl-header h2 { font-size: 24px; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.5px; }
    .rl-scope { display: flex; gap: 6px; }
    .rl-scope a {
      padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 700;
      border: 1.5px solid #e5e7eb; color: #6b7280; text-decoration: none;
      transition: all 0.15s;
    }
    .rl-scope a:hover { border-color: #7c3aed; color: #7c3aed; }
    .rl-scope a.active { background: #7c3aed; color: #fff; border-color: #7c3aed; }

    /* ===== Podium ===== */
    .podium-section {
      background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 30%, #ede9fe 60%, #faf5ff 100%);
      border-radius: 20px; padding: 36px 20px 24px; margin-bottom: 24px;
      position: relative; overflow: hidden;
      border: 1px solid rgba(124,58,237,0.08);
      box-shadow: 0 4px 24px rgba(124,58,237,0.06);
    }
    .podium-section::after {
      content: '';
      position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: shimmer 5s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes shimmer { 0%,100%{ left:-100%; } 50%{ left:140%; } }

    /* sparkles */
    .sparkles { position: absolute; inset: 0; pointer-events: none; }
    .sparkle {
      position: absolute; border-radius: 50%;
      background: rgba(251,191,36,0.35);
      animation: sparkleAnim ease-in-out infinite;
    }
    @keyframes sparkleAnim { 0%,100%{ transform:scale(0); opacity:0; } 50%{ transform:scale(1); opacity:1; } }

    .podium {
      display: flex; align-items: flex-end; justify-content: center;
      gap: 20px; position: relative; z-index: 1;
    }
    .podium-item {
      display: flex; flex-direction: column; align-items: center;
      text-decoration: none;
      transition: transform 0.3s cubic-bezier(.34,1.56,.64,1);
    }
    .podium-item:hover { transform: translateY(-6px) scale(1.02); }

    .podium-trophy {
      font-size: 36px; margin-bottom: 8px;
      filter: drop-shadow(0 3px 8px rgba(251,191,36,0.3));
      animation: trophyFloat 3s ease-in-out infinite;
    }
    .p-1 .podium-trophy { font-size: 52px; filter: drop-shadow(0 4px 12px rgba(251,191,36,0.4)); }
    @keyframes trophyFloat {
      0%,100% { transform: translateY(0) rotate(0deg); }
      25% { transform: translateY(-6px) rotate(-2deg); }
      75% { transform: translateY(-3px) rotate(2deg); }
    }
    .p-1 .podium-icon::after {
      content: ''; position: absolute; top: 50%; left: 50%;
      transform: translate(-50%,-50%);
      width: 80px; height: 80px; border-radius: 50%;
      background: radial-gradient(circle, rgba(251,191,36,0.15) 0%, transparent 70%);
      animation: glowP 2s ease-in-out infinite; z-index: -1;
    }
    @keyframes glowP { 0%,100%{ transform:translate(-50%,-50%) scale(1); opacity:.5; } 50%{ transform:translate(-50%,-50%) scale(1.4); opacity:1; } }
    .podium-icon { position: relative; }

    .podium-pedestal {
      border-radius: 14px; padding: 16px 12px 14px;
      display: flex; flex-direction: column; align-items: center;
      backdrop-filter: blur(8px);
    }
    .p-1 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(254,243,199,0.6));
      border: 1.5px solid rgba(251,191,36,0.25);
      box-shadow: 0 6px 24px rgba(251,191,36,0.12);
      min-width: 160px; min-height: 120px; justify-content: center;
    }
    .p-2 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(243,244,246,0.6));
      border: 1.5px solid rgba(156,163,175,0.2);
      box-shadow: 0 4px 16px rgba(0,0,0,0.04);
      min-width: 140px; min-height: 95px; justify-content: center;
    }
    .p-3 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(254,243,199,0.3));
      border: 1.5px solid rgba(217,119,6,0.15);
      box-shadow: 0 4px 16px rgba(217,119,6,0.06);
      min-width: 140px; min-height: 85px; justify-content: center;
    }
    .podium-badge {
      font-size: 10px; font-weight: 800; padding: 3px 12px;
      border-radius: 12px; margin-bottom: 6px; letter-spacing: 0.5px;
    }
    .p-1 .podium-badge { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #78350f; }
    .p-2 .podium-badge { background: linear-gradient(135deg, #d1d5db, #9ca3af); color: #374151; }
    .p-3 .podium-badge { background: linear-gradient(135deg, #fbbf24, #d97706); color: #78350f; }
    .podium-name {
      font-size: 16px; font-weight: 800; color: #1f2937;
      text-align: center; margin-bottom: 2px; word-break: keep-all;
    }
    .podium-name a { color: inherit; text-decoration: none; }
    .podium-name a:hover { color: #7c3aed; }
    .podium-solved { font-size: 12px; color: #9ca3af; margin-bottom: 6px; }
    .podium-score-num { font-weight: 900; line-height: 1; }
    .p-1 .podium-score-num { font-size: 36px; color: #7c3aed; }
    .p-2 .podium-score-num { font-size: 28px; color: #6b7280; }
    .p-3 .podium-score-num { font-size: 28px; color: #92400e; }
    .podium-score-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-top: 2px; }

    /* ===== Table ===== */
    .rl-card {
      background: #fff; border-radius: 16px; overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
      border: 1px solid #e5e7eb;
    }
    .rl-table { width: 100%; border-collapse: collapse; }
    .rl-table thead tr { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    .rl-table th {
      padding: 14px 16px; font-size: 12px; font-weight: 700;
      text-align: center; color: #fff; letter-spacing: 0.3px;
      text-transform: uppercase;
    }
    .rl-table td {
      padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #f3f4f6;
      text-align: center; vertical-align: middle; color: #374151;
    }
    .rl-table tbody tr { transition: all 0.15s; }
    .rl-table tbody tr:hover { background: #faf5ff; }
    .rl-table tbody tr:last-child td { border-bottom: none; }
    .rl-table td a { color: #7c3aed; text-decoration: none; font-weight: 600; }
    .rl-table td a:hover { text-decoration: underline; }

    /* Medal rows */
    .rl-table .row-gold { background: linear-gradient(90deg, rgba(254,243,199,0.4), transparent); }
    .rl-table .row-silver { background: linear-gradient(90deg, rgba(243,244,246,0.5), transparent); }
    .rl-table .row-bronze { background: linear-gradient(90deg, rgba(254,243,199,0.2), transparent); }
    .rank-medal { font-size: 18px; }
    .rank-num { font-weight: 800; color: #9ca3af; font-size: 15px; }

    /* Solved highlight */
    .td-solved { font-weight: 800; color: #7c3aed; }
    .td-submit { font-weight: 600; color: #f59e0b; }
    .td-rate { font-weight: 700; }

    /* Progress bar in table */
    .td-bar { display: flex; align-items: center; gap: 8px; justify-content: center; }
    .mini-bar { width: 50px; height: 4px; border-radius: 2px; background: #f3f4f6; overflow: hidden; flex-shrink: 0; }
    .mini-bar-fill { height: 100%; border-radius: 2px; background: linear-gradient(90deg, #c4b5fd, #7c3aed); }

    /* Pagination */
    .rl-page { display: flex; justify-content: center; gap: 6px; margin-top: 24px; flex-wrap: wrap; }
    .rl-page a {
      padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
      border: 1.5px solid #e5e7eb; color: #6b7280; text-decoration: none;
      transition: all 0.15s;
    }
    .rl-page a:hover { border-color: #7c3aed; color: #7c3aed; }
    .rl-page a.pg-active { background: #7c3aed; color: #fff; border-color: #7c3aed; }

    @media (max-width: 600px) {
      .podium { gap: 10px; }
      .p-1 .podium-pedestal { min-width: 120px; }
      .p-2 .podium-pedestal, .p-3 .podium-pedestal { min-width: 100px; }
    }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rl-wrap">
  <div class="rl-header">
    <h2>🏆 랭킹</h2>
    <div class="rl-scope">
      <a href="ranklist.php" <?php if(!isset($scope)) echo 'class="active"'?>>전체</a>
      <a href="ranklist.php?scope=d" <?php if(isset($scope)&&$scope=='d') echo 'class="active"'?>>오늘</a>
      <a href="ranklist.php?scope=w" <?php if(isset($scope)&&$scope=='w') echo 'class="active"'?>>이번주</a>
      <a href="ranklist.php?scope=m" <?php if(isset($scope)&&$scope=='m') echo 'class="active"'?>>이번달</a>
      <a href="ranklist.php?scope=y" <?php if(isset($scope)&&$scope=='y') echo 'class="active"'?>>올해</a>
    </div>
  </div>

  <?php
  $all_rows = array_values($view_rank);
  $start_offset = isset($_GET['start']) ? intval($_GET['start']) : 0;

  // 시상대 (첫 페이지만)
  if($start_offset == 0 && count($all_rows) >= 1):
    $p = [null, null, null];
    foreach([0,1,2] as $idx) {
      if(isset($all_rows[$idx])) {
        $c = array_values((array)$all_rows[$idx]);
        $p[$idx] = [
          'id'=> strip_tags($c[1]),
          'nick'=> strip_tags($c[2]),
          'solved'=> preg_replace('/[^0-9]/', '', strip_tags($c[4])),
          'submit'=> preg_replace('/[^0-9]/', '', strip_tags($c[5]))
        ];
      }
    }
    $trophies = ['🏆','🥈','🥉'];
    $badges = ['1ST','2ND','3RD'];
    $order = [1,0,2]; // 2등, 1등, 3등 순서
  ?>
  <div class="podium-section">
    <div class="sparkles">
      <?php for($sp=0;$sp<10;$sp++): $sz=rand(2,4); ?>
      <div class="sparkle" style="width:<?php echo $sz?>px;height:<?php echo $sz?>px;left:<?php echo rand(5,95)?>%;top:<?php echo rand(10,80)?>%;animation-duration:<?php echo rand(2,5)?>s;animation-delay:<?php echo rand(0,4)?>s;"></div>
      <?php endfor; ?>
    </div>
    <div class="podium">
      <?php foreach($order as $idx):
        if(!$p[$idx]) continue;
        $u = $p[$idx];
        $ri = $idx + 1;
      ?>
      <a class="podium-item p-<?php echo $ri?>" href="userinfo.php?user=<?php echo htmlspecialchars($u['id'])?>" style="text-decoration:none">
        <div class="podium-icon">
          <div class="podium-trophy"><?php echo $trophies[$idx]?></div>
        </div>
        <div class="podium-pedestal">
          <div class="podium-badge"><?php echo $badges[$idx]?></div>
          <div class="podium-name"><?php echo htmlspecialchars($u['nick']?:$u['id'])?></div>
          <div class="podium-solved"><?php echo $u['solved']?>문제 · <?php echo $u['submit']?>제출</div>
          <div class="podium-score-num"><?php echo $u['solved']?></div>
          <div class="podium-score-label">solved</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="rl-card">
    <table class="rl-table">
      <thead>
        <tr>
          <th style="width:60px">순위</th>
          <th>사용자ID</th>
          <th>이름</th>
          <th>그룹</th>
          <th style="width:90px">통과</th>
          <th style="width:90px">제출</th>
          <th style="width:100px">정답률</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rank = $start_offset + 1;
        $max_solved = 1;
        if(count($all_rows) > 0) {
          $first_cells = array_values((array)$all_rows[0]);
          $max_solved = max(1, intval(preg_replace('/[^0-9]/', '', strip_tags($first_cells[4]))));
        }
        foreach($view_rank as $row):
          $cells = array_values((array)$row);
          $solved_n = intval(preg_replace('/[^0-9]/', '', strip_tags($cells[4])));
          $row_class = '';
          if($rank == 1) $row_class = 'row-gold';
          elseif($rank == 2) $row_class = 'row-silver';
          elseif($rank == 3) $row_class = 'row-bronze';
          $pct = round(($solved_n / $max_solved) * 100);
        ?>
        <tr class="<?php echo $row_class?>">
          <td>
            <?php if($rank <= 3): ?>
              <span class="rank-medal"><?php echo ['🥇','🥈','🥉'][$rank-1]?></span>
            <?php else: ?>
              <span class="rank-num"><?php echo $rank?></span>
            <?php endif; ?>
          </td>
          <td><?php echo $cells[1]?></td>
          <td style="font-weight:600"><?php echo $cells[2]?></td>
          <td><?php echo $cells[3]?></td>
          <td>
            <div class="td-bar">
              <span class="td-solved"><?php echo $cells[4]?></span>
              <div class="mini-bar"><div class="mini-bar-fill" style="width:<?php echo $pct?>%"></div></div>
            </div>
          </td>
          <td class="td-submit"><?php echo $cells[5]?></td>
          <td class="td-rate"><?php echo $cells[6]?></td>
        </tr>
        <?php $rank++; endforeach;?>
      </tbody>
    </table>
  </div>

  <div class="rl-page">
    <?php
    $qs = "";
    if(isset($scope)) $qs .= "&scope=".htmlspecialchars($scope);
    for($i=0; $i<$view_total; $i+=$page_size):
      $active = $start_offset == $i;
    ?>
    <a href="ranklist.php?start=<?php echo $i.$qs?>" <?php if($active) echo 'class="pg-active"'?>>
      <?php echo ($i+1)."-".($i+$page_size)?>
    </a>
    <?php endfor;?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
