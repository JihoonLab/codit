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
