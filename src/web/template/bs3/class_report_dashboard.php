<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>포트폴리오 대시보드 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
*{box-sizing:border-box}
body{font-family:'Noto Sans KR',sans-serif;background:#f4f6f9;margin:0;color:#333}

.rp-wrap{max-width:1100px;margin:36px auto;padding:0 16px}
.rp-header{background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border-radius:14px;padding:32px;margin-bottom:28px;box-shadow:0 4px 20px rgba(124,58,237,.25)}
.rp-header h1{font-size:24px;font-weight:800;margin:0 0 6px}
.rp-header p{opacity:.85;font-size:14px;margin:0}

.rp-score-legend{background:#fff;border-radius:12px;padding:20px 28px;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:24px;border:1px solid #e8f0fe}
.rp-score-legend h3{font-size:15px;font-weight:700;color:#7c3aed;margin:0 0 12px}
.rp-score-row{display:flex;gap:8px;flex-wrap:wrap}
.rp-score-item{display:flex;align-items:center;gap:5px;font-size:12px;color:#555;background:#f8f9fa;padding:4px 10px;border-radius:6px}
.rp-score-badge{min-width:32px;height:32px;padding:0 6px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;background:#7c3aed;white-space:nowrap}

.rp-table-wrap{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid #e8f0fe;overflow:hidden}
.rp-table{width:100%;border-collapse:collapse}
.rp-table thead tr{background:#7c3aed;color:#fff}
.rp-table th{padding:12px 16px;font-size:13px;font-weight:600;text-align:center;white-space:nowrap}
.rp-table th:first-child{text-align:left;padding-left:24px}
.rp-table td{padding:14px 16px;font-size:14px;border-bottom:1px solid #f0f3f7;text-align:center}
.rp-table td:first-child{text-align:left;padding-left:24px}
.rp-table tbody tr{cursor:pointer;transition:background .1s}
.rp-table tbody tr:hover{background:#f5f0ff}
.rp-table tbody tr:last-child td{border-bottom:none}
.rp-tag-label{font-size:16px;font-weight:800;color:#7c3aed}
.rp-tag-sub{font-size:11px;color:#999;margin-top:2px}
.rp-num{font-size:18px;font-weight:800;color:#333}
.rp-num-sub{font-size:11px;color:#999}
.rp-bar-wrap{background:#f0f0f0;border-radius:8px;height:10px;overflow:hidden;min-width:120px}
.rp-bar{height:100%;border-radius:8px;transition:width .5s}
.rp-bar.low{background:linear-gradient(90deg,#ef4444,#f97316)}
.rp-bar.mid{background:linear-gradient(90deg,#f59e0b,#84cc16)}
.rp-bar.high{background:linear-gradient(90deg,#22c55e,#10b981)}
.rp-pct{font-size:15px;font-weight:800}
.rp-pct.low{color:#ef4444}.rp-pct.mid{color:#f59e0b}.rp-pct.high{color:#22c55e}
.rp-arrow{color:#ccc;font-size:18px;transition:color .15s}
tr:hover .rp-arrow{color:#7c3aed}
.rp-empty{text-align:center;padding:60px;color:#aaa;font-size:16px}
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rp-wrap">
  <div class="rp-header">
    <h1>📊 포트폴리오 대시보드</h1>
    <p>반별 수업 진척도를 종합하여 포트폴리오 점수를 산출합니다.</p>
  </div>

  <?php
  function render_score_legend($title, $table, $max, $cols) {
    echo '<div class="rp-score-legend" style="margin-bottom:16px">';
    echo '<h3>📏 '.$title.' ('.$max.'점 만점)</h3>';
    echo '<div class="rp-score-row" style="display:grid;grid-template-columns:repeat('.$cols.',1fr);gap:6px">';
    foreach($table as $s) {
      $color = '#ef4444';
      if($max == 20) {
        if($s['score']>=18) $color = '#22c55e';
        elseif($s['score']>=14) $color = '#84cc16';
        elseif($s['score']>=10) $color = '#f59e0b';
        elseif($s['score']>=8) $color = '#f97316';
      } else {
        if($s['score']>=9) $color = '#22c55e';
        elseif($s['score']>=7) $color = '#84cc16';
        elseif($s['score']>=5) $color = '#f59e0b';
        elseif($s['score']>=4) $color = '#f97316';
      }
      echo '<div class="rp-score-item" style="flex-direction:column;text-align:center;padding:8px 4px;gap:4px">';
      echo '<div class="rp-score-badge" style="background:'.$color.'">'.$s['score'].'점</div>';
      echo '<span style="font-size:11px">'.$s['label'].'</span>';
      echo '</div>';
    }
    echo '</div></div>';
  }
  render_score_legend('정보 점수 기준', $score_table_10, 10, 9);
  render_score_legend('인공지능기초 점수 기준', $score_table_20, 20, 8);
  ?>

  <?php if(empty($dashboard)): ?>
  <div class="rp-empty">등록된 수업이 없습니다.<br>수업을 먼저 만들고 반 태그를 지정해주세요.</div>
  <?php else: ?>
  <div class="rp-table-wrap">
    <table class="rp-table">
      <thead>
        <tr>
          <th style="text-align:left">반</th>
          <th>수업</th>
          <th>총 문제</th>
          <th>학생 수</th>
          <th>평균 진척도</th>
          <th style="min-width:140px"></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($dashboard as $d):
          $tp = explode('-', $d['tag']);
          $tag_label = (count($tp)===2 && is_numeric($tp[0]) && is_numeric($tp[1]))
            ? $tp[0].'학년 '.$tp[1].'반' : $d['tag'];
          $pct_class = $d['avg_pct']>=70?'high':($d['avg_pct']>=40?'mid':'low');
        ?>
        <tr onclick="location.href='class_report.php?tag=<?php echo urlencode($d['tag'])?>'">
          <td>
            <div class="rp-tag-label"><?php echo htmlspecialchars($tag_label)?></div>
          </td>
          <td><span class="rp-num"><?php echo $d['class_count']?></span><div class="rp-num-sub">개</div></td>
          <td><span class="rp-num"><?php echo $d['problem_count']?></span><div class="rp-num-sub">문제</div></td>
          <td><span class="rp-num"><?php echo $d['student_count']?></span><div class="rp-num-sub">명</div></td>
          <td><span class="rp-pct <?php echo $pct_class?>"><?php echo $d['avg_pct']?>%</span></td>
          <td>
            <div class="rp-bar-wrap">
              <div class="rp-bar <?php echo $pct_class?>" style="width:<?php echo max($d['avg_pct'],2)?>%"></div>
            </div>
          </td>
          <td><span class="rp-arrow">→</span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
