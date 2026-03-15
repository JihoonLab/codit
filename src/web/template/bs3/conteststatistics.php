<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>통계 - <?php echo htmlspecialchars($view_title)?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
    .cs-wrap { max-width: 1100px; margin: 32px auto; padding: 0 20px 60px; }

    /* 상단 바 */
    .cs-topbar { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 16px 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .cs-topbar-title { font-size: 16px; font-weight: 900; color: #7c3aed; flex: 1; }
    .cs-topbar-clock { font-size: 13px; font-weight: 700; color: #e74c3c; background: #fff5f5; border: 1px solid #fcc; border-radius: 6px; padding: 4px 12px; }
    .cs-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .cs-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 7px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
    .cs-btn:hover { text-decoration: none; }
    .cs-btn-gray { background: #f0f3f7; color: #555 !important; }
    .cs-btn-gray:hover { background: #e2e8f0; }
    .cs-btn-active { background: #e8f0fe; color: #7c3aed !important; border: 1.5px solid #7c3aed; }

    /* 카드 */
    .cs-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 20px; }
    .cs-card-header { background: #f8fafc; border-bottom: 2px solid #7c3aed; padding: 13px 20px; font-size: 15px; font-weight: 700; color: #7c3aed; }

    /* 통계 테이블 */
    .cs-table-wrap { overflow-x: auto; }
    .cs-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .cs-table thead tr { background: #7c3aed; }
    .cs-table thead th { padding: 10px 14px; color: #fff; font-weight: 600; text-align: center; white-space: nowrap; }
    .cs-table tbody tr { border-bottom: 1px solid #f0f3f7; transition: background 0.1s; }
    .cs-table tbody tr:last-child { background: #f8fafc; font-weight: 700; }
    .cs-table tbody tr:hover { background: #f5f8ff; }
    .cs-table td { padding: 10px 14px; text-align: center; }
    .cs-table td.td-pid { font-weight: 700; color: #7c3aed; }
    .cs-table td a { color: #7c3aed; text-decoration: none; }

    /* 결과 뱃지색 */
    .col-ac  { color: #059669; font-weight: 700; }
    .col-wa  { color: #dc2626; }
    .col-tle { color: #b45309; }
    .col-mle { color: #be185d; }
    .col-re  { color: #7c3aed; }
    .col-ce  { color: #64748b; }
    .col-total { font-weight: 700; color: #7c3aed; }

    #actChart { width: 100%; height: 240px; }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="cs-wrap">

  <!-- 상단 바 -->
  <div class="cs-topbar">
    <div class="cs-topbar-title">📈 <?php echo htmlspecialchars($view_title)?> — 통계</div>
    <div class="cs-topbar-clock">🕐 <span id="nowdate"><?php echo date("Y-m-d H:i:s")?></span></div>
    <div class="cs-actions">
      <a href="contest.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">📋 문제</a>
      <a href="status.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">📊 제출현황</a>
      <a href="contestrank.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-gray">🏆 순위표</a>
      <a href="conteststatistics.php?cid=<?php echo $cid?>" class="cs-btn cs-btn-active">📈 통계</a>
      <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])): ?>
      <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $cid?>" class="cs-btn" style="background:#7c3aed;color:#fff">⚙️ 수정</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- 채점 결과 통계 테이블 -->
  <div class="cs-card">
    <div class="cs-card-header">📊 문제별 채점 결과</div>
    <div class="cs-table-wrap">
      <table class="cs-table">
        <thead>
          <tr>
            <th>문제</th>
            <th class="col-ac">AC</th>
            <th>PE</th>
            <th class="col-wa" style="color:#fff">WA</th>
            <th class="col-tle" style="color:#fff">TLE</th>
            <th class="col-mle" style="color:#fff">MLE</th>
            <th>OLE</th>
            <th class="col-re" style="color:#fff">RE</th>
            <th class="col-ce" style="color:#fff">CE</th>
            <th>TR</th>
            <th style="border-left:2px solid rgba(255,255,255,0.3)">합계</th>
            <?php
            $i = 0;
            foreach($language_name as $lang) {
              if(isset($R[$pid_cnt][$i+11]))
                echo "<th>".htmlspecialchars($lang)."</th>";
              $i++;
            }
            ?>
          </tr>
        </thead>
        <tbody>
          <?php for($i=0; $i<$pid_cnt; $i++):
            if(!isset($PID[$i])) $PID[$i] = "";
          ?>
          <tr>
            <td class="td-pid">
              <?php if(time() < $end_time): ?>
                <a href="problem.php?cid=<?php echo $cid?>&pid=<?php echo $i?>"><?php echo $PID[$i]?></a>
              <?php else:
                $sql = "SELECT cp.problem_id FROM contest_problem cp WHERE cp.contest_id=? AND cp.num=?";
                $tr2 = pdo_query($sql, $cid, $i);
                $tpid = isset($tr2[0][0]) ? $tr2[0][0] : 0;
              ?>
                <a href="problem.php?id=<?php echo $tpid?>"><?php echo $PID[$i]?></a>
              <?php endif; ?>
            </td>
            <?php for($j=0; $j<count($language_name)+11; $j++):
              if($j >= 11 && !isset($R[$pid_cnt][$j])) continue;
              $val = isset($R[$i][$j]) ? $R[$i][$j] : 0;
              $cls = '';
              if($j==1) $cls='col-ac';
              elseif($j==3) $cls='col-wa';
              elseif($j==4) $cls='col-tle';
              elseif($j==5) $cls='col-mle';
              elseif($j==7) $cls='col-re';
              elseif($j==8) $cls='col-ce';
              elseif($j==11) $cls='col-total';
              $style = ($j==11) ? 'style="border-left:2px solid #e5e9f0"' : '';
            ?>
            <td class="<?php echo $cls?>" <?php echo $style?>><?php echo $val ?: '<span style="color:#ddd">—</span>'?></td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
          <!-- Total 행 -->
          <tr>
            <td style="font-weight:700;color:#333">합계</td>
            <?php for($j=0; $j<count($language_name)+11; $j++):
              if($j >= 11 && !isset($R[$pid_cnt][$j])) continue;
              $val = isset($R[$pid_cnt][$j]) ? $R[$pid_cnt][$j] : 0;
              $style = ($j==11) ? 'style="border-left:2px solid #e5e9f0"' : '';
            ?>
            <td class="col-total" <?php echo $style?>><?php echo $val ?: '—'?></td>
            <?php endfor; ?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 제출 활동 차트 -->
  <div class="cs-card">
    <div class="cs-card-header">📅 시간대별 제출 현황</div>
    <div style="padding: 16px">
      <div id="actChart"></div>
    </div>
  </div>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL.$path_fix."template/syzoj"?>/js/echarts.min.js"></script>
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

// ECharts 차트
(function(){
  var allData = {}, acData = {};
  <?php foreach($chart_data_all as $k=>$d): ?>
  allData[<?php echo $k?>] = <?php echo $d?>;
  <?php endforeach; ?>
  <?php foreach($chart_data_ac as $k=>$d): ?>
  acData[<?php echo $k?>] = <?php echo $d?>;
  <?php endforeach; ?>

  var keys = Object.keys(allData).map(Number).sort((a,b)=>a-b);
  if(!keys.length) { document.getElementById('actChart').style.display='none'; return; }

  var dates=[], serAll=[], serAc=[];
  keys.forEach(k=>{
    var d=new Date(k);
    var label=d.getFullYear()+'-'+(d.getMonth()+1<10?'0':'')+(d.getMonth()+1)+'-'+(d.getDate()<10?'0':'')+d.getDate()+' '+(d.getHours()<10?'0':'')+d.getHours()+':00';
    dates.push(label);
    serAll.push(allData[k]||0);
    serAc.push(acData[k]||0);
  });

  echarts.init(document.getElementById('actChart')).setOption({
    tooltip:{trigger:'axis'},
    legend:{data:['전체 제출','정답'],bottom:0,textStyle:{fontSize:12}},
    grid:{left:40,right:16,top:12,bottom:36},
    xAxis:{type:'category',data:dates,axisLabel:{fontSize:11,rotate:30}},
    yAxis:{type:'value',minInterval:1,axisLabel:{fontSize:11}},
    series:[
      {name:'전체 제출',type:'bar',data:serAll,itemStyle:{color:'#93c5fd'}},
      {name:'정답',type:'bar',data:serAc,itemStyle:{color:'#7c3aed'}}
    ]
  });
})();
</script>
</body>
</html>
