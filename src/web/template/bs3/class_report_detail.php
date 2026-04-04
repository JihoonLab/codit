<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php
  $tp = explode('-', $tag);
  $tag_label = (count($tp)===2 && is_numeric($tp[0]) && is_numeric($tp[1]))
    ? $tp[0].'학년 '.$tp[1].'반' : $tag;
?><?php echo htmlspecialchars($tag_label)?> 포트폴리오 리포트</title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
*{box-sizing:border-box}
body{font-family:'Noto Sans KR',sans-serif;background:#f4f6f9;margin:0;color:#333}

.rd-wrap{max-width:1200px;margin:36px auto;padding:0 16px}
.rd-header{background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border-radius:14px;padding:28px 32px;margin-bottom:24px;box-shadow:0 4px 20px rgba(124,58,237,.25)}
.rd-header h1{font-size:22px;font-weight:800;margin:0 0 4px}
.rd-header .meta{font-size:13px;opacity:.85}
.rd-actions{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap}
.rd-btn{padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;border:none;cursor:pointer;font-family:inherit}
.rd-btn-back{background:#f0f0f0;color:#555}.rd-btn-back:hover{background:#e0e0e0}
.rd-btn-csv{background:#22c55e;color:#fff}.rd-btn-csv:hover{background:#16a34a}
.rd-btn-print{background:#3b82f6;color:#fff}.rd-btn-print:hover{background:#2563eb}

.rd-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:14px;margin-bottom:24px}
.rd-sum-card{background:#fff;border-radius:10px;padding:18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e8f0fe}
.rd-sum-num{font-size:26px;font-weight:800;color:#7c3aed}
.rd-sum-label{font-size:12px;color:#999;margin-top:2px}

/* 점수 기준 범례 */
.rd-score-legend{background:#fff;border-radius:10px;padding:16px 24px;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e8f0fe;margin-bottom:20px}
.rd-score-legend h3{font-size:14px;font-weight:700;color:#7c3aed;margin:0 0 10px}
.rd-score-row{display:flex;gap:8px;flex-wrap:wrap}
.rd-score-item{display:flex;align-items:center;gap:5px;font-size:12px;color:#555;background:#f8f9fa;padding:3px 10px;border-radius:5px}

/* 수업 목록 */
.rd-class-list{background:#fff;border-radius:10px;padding:16px 24px;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e8f0fe;margin-bottom:20px}
.rd-class-list h3{font-size:14px;font-weight:700;color:#7c3aed;margin:0 0 10px}
.rd-class-chips{display:flex;gap:8px;flex-wrap:wrap}
.rd-class-chip{background:#f0e6ff;color:#7c3aed;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600}

/* 테이블 */
.rd-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid #e5e9f0;margin-bottom:24px;background:#fff}
.rd-table{width:100%;border-collapse:collapse;min-width:600px}
.rd-table thead tr{background:#7c3aed;color:#fff}
.rd-table th{padding:10px 8px;font-size:11px;font-weight:600;text-align:center;white-space:nowrap;position:sticky;top:0}
.rd-table th.th-rank{width:40px}
.rd-table th.th-user{text-align:left;min-width:100px;position:sticky;left:0;z-index:2;background:#7c3aed}
.rd-table td{padding:8px 6px;font-size:12px;border-bottom:1px solid #f0f0f0;text-align:center}
.rd-table td.td-rank{font-weight:600;color:#aaa}
.rd-table td.td-user{text-align:left;font-weight:600;color:#333;position:sticky;left:0;background:#fff;z-index:1;white-space:nowrap}
.rd-table tbody tr:hover td{background:#f5f0ff}
.rd-table tbody tr:hover td.td-user{background:#f5f0ff}

.class-cell{font-size:11px;font-weight:600}
.class-cell.perfect{color:#22c55e}
.class-cell.partial{color:#f59e0b}
.class-cell.zero{color:#e0e0e0}

.pct-cell{font-weight:700}
.rd-bar-mini{display:inline-block;width:50px;height:7px;background:#f0f0f0;border-radius:4px;vertical-align:middle;margin-left:3px;overflow:hidden}
.rd-bar-fill{height:100%;border-radius:4px}

.score-badge{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 6px;border-radius:8px;font-size:15px;font-weight:900;color:#fff;white-space:nowrap}

@media print {
  .rd-actions, nav, .rd-score-legend, .rd-class-list { display:none !important }
  .rd-header { background:#7c3aed !important; -webkit-print-color-adjust:exact; print-color-adjust:exact }
  .rd-table th { background:#7c3aed !important; color:#fff !important; -webkit-print-color-adjust:exact; print-color-adjust:exact }
  .score-badge { -webkit-print-color-adjust:exact; print-color-adjust:exact }
  .rd-wrap { max-width:100%; margin:0; padding:0 }
}
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rd-wrap">

  <div class="rd-actions">
    <a href="class_report.php" class="rd-btn rd-btn-back">← 대시보드</a>
    <a href="class_report.php?tag=<?php echo urlencode($tag)?>&export=csv" class="rd-btn rd-btn-csv">📥 엑셀(CSV) 내보내기</a>
    <button onclick="window.print()" class="rd-btn rd-btn-print">🖨️ 인쇄</button>
  </div>

  <div class="rd-header">
    <h1>📋 <?php echo htmlspecialchars($tag_label)?> 포트폴리오 종합 리포트</h1>
    <div class="meta">
      수업 <?php echo count($classes)?>개 · 총 문제 <?php echo $total?>개 · 학생 <?php echo count($students)?>명 · <?php echo date('Y-m-d H:i')?> 기준
    </div>
  </div>

  <?php
    $avg_pct = count($report) > 0 ? round(array_sum(array_column($report, 'pct')) / count($report), 1) : 0;
    $avg_score = count($report) > 0 ? round(array_sum(array_column($report, 'score')) / count($report), 1) : 0;
    $score_dist = array_count_values(array_column($report, 'score'));
  ?>

  <div class="rd-summary">
    <div class="rd-sum-card">
      <div class="rd-sum-num"><?php echo count($classes)?></div>
      <div class="rd-sum-label">수업 수</div>
    </div>
    <div class="rd-sum-card">
      <div class="rd-sum-num"><?php echo $total?></div>
      <div class="rd-sum-label">총 문제</div>
    </div>
    <div class="rd-sum-card">
      <div class="rd-sum-num"><?php echo count($students)?></div>
      <div class="rd-sum-label">학생 수</div>
    </div>
    <div class="rd-sum-card">
      <div class="rd-sum-num"><?php echo $avg_pct?>%</div>
      <div class="rd-sum-label">평균 진척도</div>
    </div>
    <div class="rd-sum-card">
      <div class="rd-sum-num"><?php echo $avg_score?></div>
      <div class="rd-sum-label">평균 점수</div>
    </div>
  </div>

  <div class="rd-score-legend">
    <h3>📏 포트폴리오 점수 기준 (10점 만점)</h3>
    <div class="rd-score-row" style="display:grid;grid-template-columns:repeat(9,1fr);gap:6px">
      <?php foreach($score_table as $s): ?>
      <div class="rd-score-item" style="flex-direction:column;text-align:center;padding:8px 4px;gap:4px">
        <span class="score-badge" style="min-width:32px;height:32px;padding:0 6px;font-size:13px;border-radius:8px;background:<?php
          if($s['score']>=9) echo '#22c55e';
          elseif($s['score']>=7) echo '#84cc16';
          elseif($s['score']>=5) echo '#f59e0b';
          elseif($s['score']>=4) echo '#f97316';
          else echo '#ef4444';
        ?>"><?php echo $s['score']?>점</span>
        <span style="font-size:11px"><?php echo $s['min']?>%↑</span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="rd-class-list">
    <h3>📚 포함된 수업 (<?php echo count($classes)?>개)</h3>
    <div class="rd-class-chips">
      <?php foreach($classes as $i => $c): ?>
      <span class="rd-class-chip"><?php echo ($i+1).'. '.htmlspecialchars($c['title'])?> (<?php echo count($class_problems[$c['class_id']])?>문제)</span>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if(empty($report)): ?>
  <div style="text-align:center;padding:40px;color:#aaa">등록된 학생이 없습니다.</div>
  <?php else: ?>
  <div class="rd-table-wrap">
    <table class="rd-table">
      <thead>
        <tr>
          <th class="th-rank">번호</th>
          <th class="th-user">학생</th>
          <?php foreach($classes as $i => $c): ?>
          <th title="<?php echo htmlspecialchars($c['title'])?>"><?php echo ($i+1)?></th>
          <?php endforeach; ?>
          <th>해결</th>
          <th>진척도</th>
          <th>점수</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($report as $r): ?>
        <tr>
          <td class="td-rank"><?php echo !empty($r['student_no']) ? htmlspecialchars($r['student_no']) : '-'?></td>
          <td class="td-user">
            <?php echo htmlspecialchars($r['nick'] ?: $r['user_id'])?>
            <?php if($r['nick']): ?><br><span style="color:#aaa;font-size:10px"><?php echo htmlspecialchars($r['user_id'])?></span><?php endif; ?>
          </td>
          <?php foreach($classes as $c):
            $pc = $r['per_class'][$c['class_id']];
            $cls_class = 'zero';
            if($pc['total']>0 && $pc['solved']==$pc['total']) $cls_class='perfect';
            elseif($pc['solved']>0) $cls_class='partial';
          ?>
          <td class="class-cell <?php echo $cls_class?>"><?php echo $pc['solved']?>/<?php echo $pc['total']?></td>
          <?php endforeach; ?>
          <td><b><?php echo $r['solved_count']?></b>/<?php echo $total?></td>
          <td class="pct-cell">
            <?php echo $r['pct']?>%
            <div class="rd-bar-mini">
              <div class="rd-bar-fill" style="width:<?php echo $r['pct']?>%;background:<?php
                echo $r['pct']>=70?'#22c55e':($r['pct']>=40?'#f59e0b':'#ef4444')
              ?>"></div>
            </div>
          </td>
          <td>
            <span class="score-badge" style="background:<?php
              if($r['score']>=9) echo '#22c55e';
              elseif($r['score']>=7) echo '#84cc16';
              elseif($r['score']>=5) echo '#f59e0b';
              elseif($r['score']>=4) echo '#f97316';
              else echo '#ef4444';
            ?>"><?php echo $r['score']?></span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- 점수 분포 요약 -->
  <div style="background:#fff;border-radius:10px;padding:20px 24px;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e8f0fe">
    <h3 style="font-size:14px;font-weight:700;color:#7c3aed;margin:0 0 14px">📊 점수 분포</h3>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <?php for($sc=10;$sc>=2;$sc--): ?>
      <div style="text-align:center;min-width:50px">
        <div style="font-size:20px;font-weight:800;color:<?php
          if($sc>=9) echo '#22c55e';
          elseif($sc>=7) echo '#84cc16';
          elseif($sc>=5) echo '#f59e0b';
          elseif($sc>=4) echo '#f97316';
          else echo '#ef4444';
        ?>"><?php echo $score_dist[$sc] ?? 0?></div>
        <div style="font-size:11px;color:#999"><?php echo $sc?>점</div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
