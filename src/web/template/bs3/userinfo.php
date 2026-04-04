<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($nick ?: $user)?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

    .ui-wrap { max-width: 960px; margin: 32px auto; padding: 0 20px 60px; }

    /* 프로필 헤더 카드 */
    .profile-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
      padding: 32px 36px;
      display: flex;
      align-items: center;
      gap: 32px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .profile-avatar {
      width: 80px; height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      display: flex; align-items: center; justify-content: center;
      font-size: 34px; font-weight: 900; color: #fff;
      flex-shrink: 0;
    }
    .profile-info { flex: 1; min-width: 0; }
    .profile-nick {
      font-size: 24px; font-weight: 900; color: #1a1a1a;
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .profile-uid { font-size: 14px; color: #888; margin-top: 4px; }
    .profile-school { font-size: 13px; color: #aaa; margin-top: 2px; }
    .star-badge { font-size: 18px; }

    /* 수정 버튼 (본인) */
    .btn-edit-profile {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 20px; border-radius: 8px;
      background: #f0f4fb; color: #7c3aed;
      font-size: 14px; font-weight: 600; text-decoration: none;
      transition: background 0.15s;
      flex-shrink: 0;
    }
    .btn-edit-profile:hover { background: #dde8f8; color: #7c3aed; text-decoration: none; }

    /* 통계 카드 행 */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 20px;
    }
    .stat-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      padding: 20px 24px;
      text-align: center;
    }
    .stat-card .s-num {
      font-size: 36px; font-weight: 900; color: #7c3aed; line-height: 1;
    }
    .stat-card .s-label {
      font-size: 13px; color: #888; margin-top: 6px;
    }
    .stat-card a { text-decoration: none; }
    .stat-card a .s-num:hover { color: #6d28d9; }

    /* 하단 2열 */
    .bottom-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    @media(max-width:700px) {
      .stats-row { grid-template-columns: 1fr 1fr; }
      .bottom-grid { grid-template-columns: 1fr; }
      .profile-card { padding: 24px 20px; gap: 20px; }
    }

    /* 공통 카드 */
    .info-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      overflow: hidden;
    }
    .info-card-header {
      background: #fff;
      border-bottom: 1px solid #f0f2f5;
      padding: 14px 20px;
      font-size: 14px; font-weight: 700; color: #2d2d3a;
      display: flex; align-items: center; gap: 8px;
    }
    .info-card-body { padding: 16px 20px; }

    /* 채점 결과 테이블 */
    .result-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .result-table tr { border-bottom: 1px solid #f0f3f7; }
    .result-table tr:last-child { border-bottom: none; }
    .result-table td { padding: 7px 4px; }
    .result-table td:last-child { text-align: right; font-weight: 700; color: #7c3aed; }
    .result-table a { color: #555; text-decoration: none; }
    .result-table a:hover { color: #7c3aed; }

    /* 풀은 문제 태그 */
    .solved-list { display: flex; flex-wrap: wrap; gap: 6px; padding: 4px 0; }
    .solved-tag {
      display: inline-block;
      padding: 4px 10px; border-radius: 6px;
      background: #e8f0fe; color: #7c3aed;
      font-size: 12px; font-weight: 600;
      text-decoration: none;
      transition: background 0.1s;
    }
    .solved-tag:hover { background: #7c3aed; color: #fff; text-decoration: none; }

    /* 활동 차트 */
    #activityChart { width: 100%; height: 200px; }

    /* 관리자 로그인 로그 */
    .log-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .log-table th { background: #7c3aed; color: #fff; padding: 8px 12px; text-align: left; }
    .log-table td { padding: 7px 12px; border-bottom: 1px solid #f0f3f7; color: #555; }
    .log-table tr:last-child td { border-bottom: none; }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="ui-wrap">

  <!-- 프로필 헤더 -->
  <div class="profile-card">
    <div class="profile-avatar">
      <?php echo mb_substr($nick ?: $user, 0, 1)?>
    </div>
    <div class="profile-info">
      <div class="profile-nick">
        <?php echo htmlspecialchars($nick ?: $user)?>
        <?php if($starred): ?><span class="star-badge" title="우수 사용자">⭐</span><?php endif; ?>
      </div>
      <div class="profile-uid">@<?php echo htmlspecialchars($user)?></div>
      <?php if($school): ?>
      <div class="profile-school">🏫 <?php
        $s_parts = explode('-', $school);
        if(count($s_parts) === 2 && is_numeric($s_parts[0]) && is_numeric($s_parts[1])) {
          echo $s_parts[0] . '학년 ' . $s_parts[1] . '반';
        } else {
          echo htmlspecialchars($school);
        }
      ?></div>
      <?php endif; ?>
    </div>
    <?php if(isset($_SESSION[$OJ_NAME.'_user_id']) && $_SESSION[$OJ_NAME.'_user_id'] == $user): ?>
    <a href="modifypage.php" class="btn-edit-profile">✏️ 정보 수정</a>
    <?php endif; ?>
  </div>

  <!-- 통계 3칸 -->
  <div class="stats-row">
    <div class="stat-card">
      <a href="ranklist.php">
        <div class="s-num"><?php echo $Rank?></div>
      </a>
      <div class="s-label">전체 순위</div>
    </div>
    <div class="stat-card">
      <a href="status.php?user_id=<?php echo urlencode($user)?>&jresult=4">
        <div class="s-num"><?php echo $AC?></div>
      </a>
      <div class="s-label">해결한 문제</div>
    </div>
    <div class="stat-card">
      <a href="status.php?user_id=<?php echo urlencode($user)?>">
        <div class="s-num"><?php echo $Submit?></div>
      </a>
      <div class="s-label">제출한 문제</div>
    </div>
  </div>

  <!-- 활동 차트 (전체 너비) -->
  <div class="info-card" style="margin-bottom:20px">
    <div class="info-card-header">📈 제출 활동</div>
    <div class="info-card-body" style="padding:12px 16px">
      <div id="activityChart"></div>
    </div>
  </div>

  <!-- 하단 2열: 채점 통계 + 풀은 문제 -->
  <div class="bottom-grid">

    <!-- 채점 결과 통계 -->
    <div class="info-card">
      <div class="info-card-header">📊 채점 결과</div>
      <div class="info-card-body">
        <table class="result-table">
          <?php foreach($view_userstat as $row): ?>
          <tr>
            <td><a href="status.php?user_id=<?php echo urlencode($user)?>&jresult=<?php echo $row[0]?>">
              <?php echo $jresult[$row[0]]?>
            </a></td>
            <td><?php echo $row[1]?></td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>

    <!-- 풀은 문제 목록 -->
    <div class="info-card">
      <div class="info-card-header">✅ 해결한 문제 <span style="font-weight:400;color:#aaa;font-size:12px">(<?php echo $AC?>개)</span></div>
      <div class="info-card-body">
        <div class="solved-list">
          <?php
          $sql = "SELECT problem_id FROM solution WHERE user_id=? AND result=4 GROUP BY problem_id $not_in_noip ORDER BY problem_id ASC";
          $solved = pdo_query($sql, $user);
          if($solved && count($solved) > 0):
            foreach($solved as $sp):
          ?>
          <a class="solved-tag" href="problem.php?id=<?php echo $sp['problem_id']?>"><?php echo $sp['problem_id']?></a>
          <?php endforeach; else: ?>
          <span style="color:#aaa;font-size:13px">아직 해결한 문제가 없습니다.</span>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <!-- 관리자: 로그인 로그 -->
  <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) && !empty($view_userinfo)): ?>
  <div class="info-card" style="margin-top:20px">
    <div class="info-card-header">🔐 로그인 기록 (관리자)</div>
    <div class="info-card-body" style="padding:0">
      <table class="log-table">
        <thead>
          <tr><th>UserID</th><th>Password</th><th>IP</th><th>Time</th></tr>
        </thead>
        <tbody>
          <?php foreach($view_userinfo as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row[0])?></td>
            <td><?php echo htmlspecialchars($row[1])?></td>
            <td><?php echo htmlspecialchars($row[2])?></td>
            <td><?php echo htmlspecialchars($row[3])?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL.$path_fix."template/syzoj"?>/js/echarts.min.js"></script>
<script>
(function(){
  var allData = {};
  var acData  = {};
  <?php foreach($chart_data_all as $k=>$d): ?>
  allData[<?php echo $k?>] = <?php echo $d?>;
  <?php endforeach; ?>
  <?php foreach($chart_data_ac as $k=>$d): ?>
  acData[<?php echo $k?>] = <?php echo $d?>;
  <?php endforeach; ?>

  // 전체 날짜 범위
  var keys = Object.keys(allData).map(Number).sort(function(a,b){return a-b;});
  if(keys.length === 0) {
    document.getElementById('activityChart').style.display='none';
    return;
  }

  var dates=[], serAll=[], serAc=[];
  keys.forEach(function(k){
    var d = new Date(k);
    var label = d.getFullYear()+'-'+(d.getMonth()+1<10?'0':'')+(d.getMonth()+1)+'-'+(d.getDate()<10?'0':'')+d.getDate();
    dates.push(label);
    serAll.push(allData[k]||0);
    serAc.push(acData[k]||0);
  });

  var chart = echarts.init(document.getElementById('activityChart'));
  chart.setOption({
    tooltip: {
      trigger:'axis',
      backgroundColor:'rgba(30,30,40,0.9)',
      borderWidth:0,
      textStyle:{color:'#fff',fontSize:12},
      formatter: function(p){
        var s='<b>'+p[0].axisValue+'</b><br>';
        p.forEach(function(i){s+=i.marker+' '+i.seriesName+': <b>'+i.value+'</b><br>';});
        return s;
      }
    },
    legend: { data:['전체 제출','맞은 문제'], bottom:0, textStyle:{fontSize:12,color:'#888'}, itemWidth:12, itemHeight:12, icon:'roundRect' },
    grid: { left:40, right:16, top:16, bottom:40 },
    xAxis: { type:'category', data:dates, axisLabel:{fontSize:11, color:'#999', rotate:0, formatter:function(v){var p=v.split('-');return p[1]+'/'+p[2];}}, axisLine:{lineStyle:{color:'#eee'}}, axisTick:{show:false} },
    yAxis: { type:'value', minInterval:1, axisLabel:{fontSize:11,color:'#bbb'}, splitLine:{lineStyle:{color:'#f5f5f5'}}, axisLine:{show:false}, axisTick:{show:false} },
    series: [
      { name:'전체 제출', type:'bar', data:serAll, itemStyle:{color:'#c4b5fd',borderRadius:[4,4,0,0]}, barMaxWidth:32 },
      { name:'맞은 문제', type:'bar', data:serAc, itemStyle:{color:'#7c3aed',borderRadius:[4,4,0,0]}, barMaxWidth:32 }
    ]
  });
  window.addEventListener('resize',function(){chart.resize();});
})();
</script>
</body>
</html>
