<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>랭킹 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.rl-wrap{max-width:900px;margin:36px auto;padding:0 16px}
.rl-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px}
.rl-top h2{font-size:22px;font-weight:700;color:#7c3aed;margin:0}
.rl-scope{display:flex;gap:6px}
.rl-scope a{padding:6px 14px;border-radius:6px;font-size:13px;font-weight:600;border:1.5px solid #e0e0e0;color:#555;text-decoration:none}
.rl-scope a:hover,.rl-scope a.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

/* 시상대 */
.podium{display:flex;justify-content:center;align-items:flex-end;gap:16px;margin-bottom:36px;padding:0 16px}
.podium-item{display:flex;flex-direction:column;align-items:center;gap:8px;flex:1;max-width:200px}
.podium-avatar{width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;color:#fff;flex-shrink:0}
.podium-name{font-size:14px;font-weight:700;color:#222;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px}
.podium-solved{font-size:13px;color:#888}
.podium-stand{border-radius:10px 10px 0 0;width:100%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;color:#fff}
.podium-1 .podium-avatar{background:linear-gradient(135deg,#f6d365,#fda085);width:72px;height:72px;font-size:34px;box-shadow:0 4px 16px rgba(253,160,133,.5)}
.podium-1 .podium-stand{background:linear-gradient(135deg,#f6d365,#fda085);height:120px;box-shadow:0 4px 16px rgba(253,160,133,.3)}
.podium-2 .podium-avatar{background:linear-gradient(135deg,#b0bec5,#78909c);box-shadow:0 4px 12px rgba(120,144,156,.4)}
.podium-2 .podium-stand{background:linear-gradient(135deg,#b0bec5,#78909c);height:90px}
.podium-3 .podium-avatar{background:linear-gradient(135deg,#ffab76,#c87941);box-shadow:0 4px 12px rgba(200,121,65,.4)}
.podium-3 .podium-stand{background:linear-gradient(135deg,#ffab76,#c87941);height:70px}

.rl-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.rl-table thead tr{background:#7c3aed;color:#fff}
.rl-table th{padding:13px 16px;font-size:13px;font-weight:600;text-align:center}
.rl-table td{padding:12px 16px;font-size:14px;border-bottom:1px solid #f0f0f0;text-align:center;vertical-align:middle}
.rl-table tbody tr:hover{background:#f5f8ff}
.rl-table tbody tr:last-child td{border-bottom:none}
.rl-table td a{color:#7c3aed;text-decoration:none;font-weight:500}
.rl-page{display:flex;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap}
.rl-page a{padding:6px 13px;border-radius:6px;font-size:13px;border:1px solid #e0e0e0;color:#555;text-decoration:none}
.rl-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rl-wrap">
  <div class="rl-top">
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
  // 시상대는 첫 페이지에서만
  if($start_offset == 0 && count($all_rows) >= 1):
    $p = [null, null, null];
    foreach([0,1,2] as $idx) {
      if(isset($all_rows[$idx])) {
        $c = array_values((array)$all_rows[$idx]);
        $p[$idx] = [
          'id'=> strip_tags($c[1]),
          'nick'=> strip_tags($c[2]),
          'solved'=> preg_replace('/[^0-9]/', '', strip_tags($c[4]))
        ];
      }
    }
  ?>
  <div class="podium">
    <!-- 2등 -->
    <?php if($p[1]): ?>
    <div class="podium-item podium-2">
      <div class="podium-avatar">🥈</div>
      <div class="podium-name"><a href="userinfo.php?user=<?php echo htmlspecialchars($p[1]['id'])?>" style="color:#222;text-decoration:none"><?php echo htmlspecialchars($p[1]['nick']?:$p[1]['id'])?></a></div>
      <div class="podium-solved"><?php echo $p[1]['solved']?>문제</div>
      <div class="podium-stand">2</div>
    </div>
    <?php endif;?>
    <!-- 1등 -->
    <?php if($p[0]): ?>
    <div class="podium-item podium-1">
      <div class="podium-avatar">🥇</div>
      <div class="podium-name"><a href="userinfo.php?user=<?php echo htmlspecialchars($p[0]['id'])?>" style="color:#222;text-decoration:none"><?php echo htmlspecialchars($p[0]['nick']?:$p[0]['id'])?></a></div>
      <div class="podium-solved"><?php echo $p[0]['solved']?>문제</div>
      <div class="podium-stand">1</div>
    </div>
    <?php endif;?>
    <!-- 3등 -->
    <?php if($p[2]): ?>
    <div class="podium-item podium-3">
      <div class="podium-avatar">🥉</div>
      <div class="podium-name"><a href="userinfo.php?user=<?php echo htmlspecialchars($p[2]['id'])?>" style="color:#222;text-decoration:none"><?php echo htmlspecialchars($p[2]['nick']?:$p[2]['id'])?></a></div>
      <div class="podium-solved"><?php echo $p[2]['solved']?>문제</div>
      <div class="podium-stand">3</div>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>

  <table class="rl-table">
    <thead>
      <tr>
        <th style="width:60px">순위</th>
        <th>사용자ID</th>
        <th>이름</th>
        <th>그룹</th>
        <th style="width:80px">통과</th>
        <th style="width:80px">제출</th>
        <th style="width:80px">정답률</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $rank = $start_offset + 1;
      $medals = ['🥇','🥈','🥉'];
      foreach($view_rank as $row):
        $cells = array_values((array)$row);
        $medal = isset($medals[$rank-1]) ? $medals[$rank-1] : $rank;
      ?>
      <tr>
        <td><strong><?php echo $medal?></strong></td>
        <td><?php echo $cells[1]?></td>
        <td><?php echo $cells[2]?></td>
        <td><?php echo $cells[3]?></td>
        <td><?php echo $cells[4]?></td>
        <td><?php echo $cells[5]?></td>
        <td><?php echo $cells[6]?></td>
      </tr>
      <?php $rank++; endforeach;?>
    </tbody>
  </table>

  <div class="rl-page">
    <?php
    $qs = "";
    if(isset($scope)) $qs .= "&scope=".htmlspecialchars($scope);
    for($i=0; $i<$view_total; $i+=$page_size):
      $active = $start_offset == $i;
    ?>
    <a href="ranklist.php?start=<?php echo $i.$qs?>" <?php if($active) echo 'style="background:#7c3aed;color:#fff;border-color:#7c3aed"'?>>
      <?php echo ($i+1)."-".($i+$page_size)?>
    </a>
    <?php endfor;?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
