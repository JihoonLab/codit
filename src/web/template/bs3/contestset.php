<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>대회 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.ct-wrap{max-width:1300px;margin:30px auto;padding:0 20px}

.ct-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px}
.ct-header h2{font-size:26px;font-weight:800;color:#1a1a2e;margin:0}
.ct-header h2 em{color:#7c3aed;font-style:normal}

.ct-clock{
  display:inline-flex;align-items:center;gap:10px;
  background:linear-gradient(135deg,#faf5ff,#f3e8ff);
  border:1.5px solid #e9d5ff;border-radius:14px;padding:10px 22px;
}
.ct-clock-label{font-size:14px;color:#7c3aed;font-weight:600}
.ct-clock-time{font-size:22px;font-weight:800;color:#7c3aed;letter-spacing:1px;font-variant-numeric:tabular-nums}

/* 필터 탭 */
.ct-tabs{display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap}
.ct-tab{
  padding:9px 22px;border-radius:20px;font-size:14px;font-weight:700;
  cursor:pointer;border:2px solid #e5e9f0;background:#fff;color:#666;transition:all .2s;
}
.ct-tab:hover{border-color:#c4b5fd;color:#7c3aed}
.ct-tab.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

/* ═══ 대회 카드 리스트 ═══ */
.ct-list{display:flex;flex-direction:column;gap:12px}

.ct-card{
  background:#fff;border-radius:16px;overflow:hidden;
  border:1px solid #e5e9f0;transition:all .25s;
  display:flex;align-items:stretch;position:relative;
  text-decoration:none;color:inherit;
}
.ct-card:hover{
  border-color:#c4b5fd;
  box-shadow:0 8px 30px rgba(124,58,237,.12);
  transform:translateY(-2px);
  text-decoration:none;color:inherit;
}

/* 왼쪽 상태 바 */
.ct-status-bar{width:6px;flex-shrink:0}
.ct-status-bar.running{background:linear-gradient(180deg,#ef4444,#f97316)}
.ct-status-bar.upcoming{background:linear-gradient(180deg,#10b981,#34d399)}
.ct-status-bar.ended{background:linear-gradient(180deg,#94a3b8,#cbd5e1)}

/* 카드 본문 */
.ct-card-body{flex:1;padding:22px 28px;display:flex;align-items:center;gap:20px}

/* 좌측: 대회ID 뱃지 */
.ct-id{
  width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;
  font-size:16px;font-weight:800;flex-shrink:0;
}
.ct-card.running .ct-id{background:linear-gradient(135deg,#fef2f2,#fee2e2);color:#ef4444}
.ct-card.upcoming .ct-id{background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#10b981}
.ct-card.ended .ct-id{background:linear-gradient(135deg,#f8fafc,#f1f5f9);color:#94a3b8}

/* 중앙: 정보 */
.ct-info{flex:1;min-width:0}
.ct-title{font-size:18px;font-weight:800;color:#1a1a2e;margin-bottom:4px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ct-card:hover .ct-title{color:#7c3aed}
.ct-meta{display:flex;gap:10px;align-items:center;flex-wrap:wrap}

/* 상태 뱃지 */
.ct-badge{
  padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700;
  display:inline-flex;align-items:center;gap:4px;
}
.ct-badge.running{background:#fef2f2;color:#ef4444;border:1px solid #fecaca}
.ct-badge.upcoming{background:#ecfdf5;color:#10b981;border:1px solid #a7f3d0}
.ct-badge.ended{background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0}

.ct-badge-dot{width:7px;height:7px;border-radius:50%;display:inline-block}
.ct-badge.running .ct-badge-dot{background:#ef4444;animation:pulse-dot 1.5s infinite}
.ct-badge.upcoming .ct-badge-dot{background:#10b981}
.ct-badge.ended .ct-badge-dot{background:#94a3b8}

@keyframes pulse-dot{
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.5;transform:scale(1.3)}
}

.ct-time{font-size:13px;color:#888;font-weight:500}
.ct-time b{color:#555;font-weight:700}

/* 우측: 구분 + 작성자 */
.ct-right{display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0;min-width:80px}
.ct-type{font-size:13px;font-weight:700;padding:4px 12px;border-radius:8px}
.ct-type.public{background:#eff6ff;color:#3b82f6}
.ct-type.private{background:#fef2f2;color:#ef4444}
.ct-author{font-size:13px;color:#999;font-weight:500}

/* 진행중 카드 타임바 */
.ct-timebar{
  height:4px;background:#f0f0f0;position:absolute;bottom:0;left:6px;right:0;
}
.ct-timebar-fill{height:100%;background:linear-gradient(90deg,#ef4444,#f97316);border-radius:0 2px 2px 0;transition:width 1s}

/* 빈 상태 */
.ct-empty{
  text-align:center;padding:80px 20px;color:#bbb;font-size:16px;
  background:#fff;border-radius:16px;border:1px solid #e5e9f0;
}
.ct-empty-icon{font-size:48px;margin-bottom:12px;display:block}

/* 페이지네이션 */
.ct-page{display:flex;justify-content:center;gap:6px;margin-top:24px;flex-wrap:wrap}
.ct-page a,.ct-page span{
  padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;
  border:1.5px solid #e5e9f0;color:#666;text-decoration:none;transition:all .2s;
  background:#fff;
}
.ct-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed;transform:translateY(-1px)}
.ct-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}

@media(max-width:700px){
  .ct-card-body{flex-direction:column;align-items:flex-start;gap:12px;padding:16px}
  .ct-right{flex-direction:row;align-items:center}
  .ct-id{width:44px;height:44px;font-size:14px}
}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ct-wrap">

  <div class="ct-header">
    <h2>🏆 <em>대회</em></h2>
    <div class="ct-clock">
      <span class="ct-clock-label">⏰ 현재 시간</span>
      <span class="ct-clock-time" id="nowdate"></span>
    </div>
  </div>

  <!-- 필터 탭 -->
  <div class="ct-tabs">
    <button class="ct-tab active" onclick="filterContest('all',this)">전체</button>
    <button class="ct-tab" onclick="filterContest('running',this)">🔴 진행중</button>
    <button class="ct-tab" onclick="filterContest('upcoming',this)">🟢 예정</button>
    <button class="ct-tab" onclick="filterContest('ended',this)">⚪ 종료</button>
  </div>

  <div class="ct-list">
  <?php if(empty($view_contest)): ?>
    <div class="ct-empty">
      <span class="ct-empty-icon">🏆</span>
      등록된 대회가 없습니다.
    </div>
  <?php else: ?>
    <?php foreach($view_contest as $row):
      $cells = array_values((array)$row);
      $cid = $cells[0];

      // 상태 파싱
      $status_html = $cells[2];
      if(strpos($status_html, 'text-danger') !== false) {
        $status = 'running';
        $status_label = '진행중';
      } else if(strpos($status_html, 'text-success') !== false) {
        $status = 'upcoming';
        $status_label = '예정';
      } else {
        $status = 'ended';
        $status_label = '종료';
      }

      // 타이틀에서 링크 추출
      $title_html = $cells[1];
      preg_match('/href=[\'"]([^\'"]+)[\'"]/', $title_html, $href_match);
      $href = $href_match[1] ?? 'contest.php?cid='.$cid;
      $title_text = strip_tags($title_html);

      // 시간 추출
      preg_match('/\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}/', $status_html, $time_match);
      $time_str = $time_match[0] ?? '';

      // 구분
      $type = 'public';
      $type_label = '공개';
      if(isset($cells[5]) && strpos($cells[5], 'Private') !== false) {
        $type = 'private';
        $type_label = '비공개';
      }

      // 작성자
      $author = $cells[6] ?? ($cells[4] ?? '');

      // 진행중이면 타임바 비율 계산 (status_html에서 시간 정보 추출)
      $progress_pct = 0;
      if($status === 'running') {
        // start_time은 status_html에 포함
        preg_match_all('/\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}/', $status_html, $all_times);
        if(!empty($all_times[0])) {
          $st = strtotime($all_times[0][0]);
          $now_ts = time();
          // 남은시간 텍스트에서 유추
          $total_guess = 10800; // 3h default
          $elapsed = $now_ts - $st;
          if($elapsed > 0 && $total_guess > 0) {
            $progress_pct = min(100, round($elapsed / $total_guess * 100));
          }
        }
      }
    ?>
    <a class="ct-card <?php echo $status?>" href="<?php echo $href?>" data-status="<?php echo $status?>">
      <div class="ct-status-bar <?php echo $status?>"></div>
      <div class="ct-card-body">
        <div class="ct-id">#<?php echo $cid?></div>
        <div class="ct-info">
          <div class="ct-title"><?php echo htmlspecialchars($title_text)?></div>
          <div class="ct-meta">
            <span class="ct-badge <?php echo $status?>">
              <span class="ct-badge-dot"></span>
              <?php echo $status_label?>
            </span>
            <?php if($time_str): ?>
            <span class="ct-time"><?php echo $time_str?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="ct-right">
          <span class="ct-type <?php echo $type?>"><?php echo $type_label?></span>
          <span class="ct-author"><?php echo htmlspecialchars($author)?></span>
        </div>
      </div>
      <?php if($status === 'running'): ?>
      <div class="ct-timebar"><div class="ct-timebar-fill" style="width:<?php echo $progress_pct?>%"></div></div>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>

  <div class="ct-page">
    <a href="contest.php?page=1">&laquo;</a>
    <?php
    if(!isset($page)) $page=1;
    $page=intval($page);
    $section=8;
    $start=$page>$section?$page-$section:1;
    $end=min($page+$section,$view_total_page);
    for($i=$start;$i<=$end;$i++):
    ?>
    <?php if($page==$i):?>
      <span class="active"><?php echo $i?></span>
    <?php else:?>
      <a href="contest.php?page=<?php echo $i.(isset($_GET['my'])?"&my":"")?>"><?php echo $i?></a>
    <?php endif;?>
    <?php endfor;?>
    <a href="contest.php?page=<?php echo $view_total_page?>">&raquo;</a>
  </div>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
function clock(){
  var x=new Date(new Date().getTime()+diff);
  var y=x.getFullYear(),mon=x.getMonth()+1,d=x.getDate(),h=x.getHours(),m=x.getMinutes(),s=x.getSeconds();
  var pad=function(n){return n>=10?n:"0"+n};
  document.getElementById('nowdate').innerHTML=y+"-"+pad(mon)+"-"+pad(d)+" "+pad(h)+":"+pad(m)+":"+pad(s);
  setTimeout(clock,1000);
}
clock();

function filterContest(status, btn) {
  document.querySelectorAll('.ct-tab').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  document.querySelectorAll('.ct-card').forEach(function(card) {
    if(status === 'all' || card.dataset.status === status) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>
</body>
</html>
