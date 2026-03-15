<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>문제 통계 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

.ps-wrap { max-width: 1100px; margin: 32px auto; padding: 0 20px 60px; }

/* 상단 헤더 */
.ps-header {
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff;
  border-radius: 12px;
  padding: 20px 28px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.ps-header h2 { margin: 0; font-size: 20px; font-weight: 900; }
.ps-header a { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 13px; margin-left: auto; }
.ps-header a:hover { color: #fff; }

/* 카드 */
.ps-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
  margin-bottom: 20px;
}
.ps-card-head {
  background: #f8fafc;
  border-bottom: 1px solid #e5e9f0;
  padding: 14px 20px;
  font-size: 15px;
  font-weight: 700;
  color: #7c3aed;
  display: flex;
  align-items: center;
  gap: 8px;
}
.ps-card-body { padding: 20px; }

/* 2열 레이아웃 */
.ps-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; }
@media(max-width:768px){ .ps-grid { grid-template-columns: 1fr; } }

/* 통계 테이블 */
.ps-stat-table { width: 100%; border-collapse: collapse; }
.ps-stat-table th { background: #7c3aed; color: #fff; padding: 10px 14px; font-size: 13px; text-align: center; }
.ps-stat-table td { padding: 9px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.ps-stat-table tr:last-child td { border-bottom: none; }
.ps-stat-table td:first-child { color: #555; font-weight: 500; }
.ps-stat-table td:last-child { text-align: right; font-weight: 700; color: #222; }
.ps-stat-table td a { color: #7c3aed; text-decoration: none; }
.ps-stat-table td a:hover { text-decoration: underline; }

/* 파이차트 */
#chart-pie { width: 100%; height: 260px; }

/* 정답자 테이블 */
.ps-sol-table { width: 100%; border-collapse: collapse; }
.ps-sol-table thead tr { background: #7c3aed; color: #fff; }
.ps-sol-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; text-align: center; white-space: nowrap; }
.ps-sol-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; text-align: center; vertical-align: middle; }
.ps-sol-table tbody tr:hover { background: #f5f8ff; }
.ps-sol-table tbody tr:last-child td { border-bottom: none; }
.ps-sol-table td a { color: #7c3aed; text-decoration: none; }
.ps-sol-table td a:hover { text-decoration: underline; }
.rank-medal { font-size: 18px; }

/* 추천 문제 */
.ps-recommand { display: flex; flex-wrap: wrap; gap: 8px; }
.ps-recommand a {
  display: inline-block;
  padding: 5px 12px;
  background: #f0f7ff;
  color: #7c3aed;
  border: 1px solid #c3d9f5;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.15s;
}
.ps-recommand a:hover { background: #7c3aed; color: #fff; }

/* 페이지네이션 */
.ps-page { display: flex; justify-content: center; gap: 8px; margin-top: 16px; }
.ps-page a { padding: 7px 16px; border-radius: 7px; font-size: 13px; border: 1px solid #e0e0e0; color: #555; text-decoration: none; }
.ps-page a:hover { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ps-wrap">

  <!-- 헤더 -->
  <div class="ps-header">
    <span style="font-size:24px">📊</span>
    <h2>문제 #<?php echo $id?> 통계</h2>
    <a href="problem.php?id=<?php echo $id?>">← 문제 보기</a>
  </div>

  <div class="ps-grid">

    <!-- 왼쪽: 통계 + 파이차트 -->
    <div>
      <div class="ps-card">
        <div class="ps-card-head">📈 채점 통계</div>
        <div class="ps-card-body" style="padding:0">
          <table class="ps-stat-table">
            <thead><tr><th>항목</th><th>수</th></tr></thead>
            <tbody>
            <?php foreach($view_problem as $i=>$row): ?>
            <tr>
              <td><?php echo $row[0]?></td>
              <td><?php echo $row[1]?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="ps-card">
        <div class="ps-card-head">🥧 결과 분포</div>
        <div class="ps-card-body" style="padding:8px">
          <div id="chart-pie"></div>
        </div>
      </div>

      <?php if(isset($view_recommand) && count($view_recommand) > 0): ?>
      <div class="ps-card">
        <div class="ps-card-head">💡 추천 문제</div>
        <div class="ps-card-body">
          <div class="ps-recommand">
            <?php foreach($view_recommand as $row): ?>
            <a href="problem.php?id=<?php echo $row[0]?>">#<?php echo $row[0]?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- 오른쪽: 정답자 목록 -->
    <div>
      <div class="ps-card">
        <div class="ps-card-head">🏆 정답자 목록 (AC)</div>
        <div class="ps-card-body" style="padding:0">
          <table class="ps-sol-table">
            <thead>
              <tr>
                <th>#</th>
                <th>채점번호</th>
                <th>사용자</th>
                <th>메모리</th>
                <th>시간</th>
                <th>언어</th>
                <th>코드</th>
                <th>제출시간</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($view_solution as $i=>$row): ?>
            <tr>
              <td>
                <?php
                $rank = $row[0];
                if($rank == 1)      echo '<span class="rank-medal">🥇</span>';
                elseif($rank == 2)  echo '<span class="rank-medal">🥈</span>';
                elseif($rank == 3)  echo '<span class="rank-medal">🥉</span>';
                else                echo $rank;
                ?>
              </td>
              <td><?php echo $row[1]?></td>
              <td><?php echo $row[2]?></td>
              <td><?php echo $row[3]?></td>
              <td><?php echo $row[4]?></td>
              <td><?php echo $row[5]?></td>
              <td><?php echo $row[6]?></td>
              <td><?php echo $row[7]?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($view_solution)): ?>
            <tr><td colspan="8" style="text-align:center;color:#999;padding:24px">아직 정답자가 없습니다</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 페이지네이션 -->
      <div class="ps-page">
        <a href="problemstatus.php?id=<?php echo $id?>">« 처음</a>
        <?php if($page > $pagemin): ?>
        <a href="problemstatus.php?id=<?php echo $id?>&page=<?php echo $page-1?>">‹ 이전</a>
        <?php endif; ?>
        <?php if($page < $pagemax): ?>
        <a href="problemstatus.php?id=<?php echo $id?>&page=<?php echo $page+1?>">다음 ›</a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>

<!-- ECharts 파이차트 -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
var pieData = [];
<?php
// view_problem[3] 이후가 결과별 카운트
$colorMap = [
  0  => '#6b7280', // Waiting
  4  => '#059669', // AC
  5  => '#f59e0b', // PE
  6  => '#dc2626', // WA
  7  => '#f97316', // TLE
  8  => '#8b5cf6', // MLE
  9  => '#ec4899', // OLE
  10 => '#ef4444', // RE
  11 => '#94a3b8', // CE
];
if(isset($view_problem_title)) {
  foreach($view_problem_title as $i=>$title) {
    $num = isset($view_problem_number[$i]) ? intval($view_problem_number[$i]) : 0;
    if($num > 0) {
      echo "pieData.push({value:$num, name:'" . addslashes($title) . "'});\n";
    }
  }
}
?>

if(pieData.length > 0) {
  var chart = echarts.init(document.getElementById('chart-pie'), null, {renderer:'svg'});
  chart.setOption({
    tooltip: { trigger:'item', formatter:'{b}: {c} ({d}%)' },
    series: [{
      type: 'pie',
      radius: ['35%','65%'],
      center: ['50%','50%'],
      data: pieData,
      label: { fontSize: 11 },
      color: ['#059669','#dc2626','#f97316','#8b5cf6','#94a3b8','#f59e0b','#ef4444','#ec4899'],
      emphasis: { itemStyle: { shadowBlur:10, shadowOffsetX:0, shadowColor:'rgba(0,0,0,0.3)' } }
    }]
  });
  window.addEventListener('resize', function(){ chart.resize(); });
}
</script>
</body>
</html>
