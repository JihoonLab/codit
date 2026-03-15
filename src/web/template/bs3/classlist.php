<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>수업 목록 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.cl-wrap{max-width:1000px;margin:36px auto;padding:0 16px}
.cl-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.cl-top h2{font-size:22px;font-weight:700;color:#7c3aed;margin:0}
.cl-search-bar{display:flex;gap:10px;margin-bottom:16px}
.cl-search-bar input{flex:1;padding:10px 16px;border:1.5px solid #e0e4ea;border-radius:8px;font-size:14px;outline:none}
.cl-search-bar input:focus{border-color:#7c3aed}
.cl-search-bar button{padding:10px 20px;background:#7c3aed;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer}
.cl-search-bar button:hover{background:#6d28d9}
.cl-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.cl-table thead tr{background:#7c3aed;color:#fff}
.cl-table th{padding:13px 16px;font-size:13px;font-weight:600;text-align:left}
.cl-table th.tc{text-align:center}
.cl-table td{padding:13px 16px;font-size:14px;border-bottom:1px solid #f0f0f0;color:#333}
.cl-table td.tc{text-align:center}
.cl-table tbody tr:hover{background:#f5f8ff}
.cl-table tbody tr:last-child td{border-bottom:none}
.cl-table td a{color:#7c3aed;text-decoration:none;font-weight:500}
.cl-table td a:hover{text-decoration:underline}
.cl-empty{text-align:center;padding:48px;color:#aaa;font-size:15px}
.btn-write{background:#7c3aed;color:#fff;border:none;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none}
.btn-write:hover{background:#6d28d9;color:#fff}
.badge-cnt{background:#e8f0fe;color:#7c3aed;border-radius:12px;padding:2px 10px;font-size:12px;font-weight:600;white-space:nowrap;display:inline-block}
.progress-wrap{display:flex;align-items:center;gap:8px}
.progress-bar{flex:1;height:8px;background:#eee;border-radius:4px;overflow:hidden;min-width:60px}
.progress-fill{height:100%;background:#27ae60;border-radius:4px;transition:width 0.3s}
.progress-text{font-size:12px;color:#555;white-space:nowrap}
.progress-done{color:#27ae60;font-weight:700}
.cl-date{color:#aaa;font-size:12px;white-space:nowrap}
.cl-author{color:#888;font-size:13px}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cl-wrap">
  <div class="cl-top">
    <h2>📚 수업 목록</h2>
    <?php if($is_admin): ?><a href="classop.php?action=write" class="btn-write">+ 수업 만들기</a><?php endif; ?>
  </div>

  <div class="cl-search-bar">
    <input type="text" id="cl-search" placeholder="수업 제목 검색..." oninput="filterClass()">
    <button onclick="filterClass()">검색</button>
  </div>

  <table class="cl-table">
    <thead>
      <tr>
        <th>제목</th>
        <th class="tc" style="width:80px">문제 수</th>
        <th style="width:160px">내 풀이</th>
        <th class="tc" style="width:90px">작성자</th>
        <th class="tc" style="width:100px">생성일</th>
        <?php if($is_admin): ?><th class="tc" style="width:160px">관리</th><?php endif; ?>
      </tr>
    </thead>
    <tbody id="cl-tbody">
    <?php if(empty($classes)): ?>
      <tr><td colspan="6" class="cl-empty">등록된 수업이 없습니다.</td></tr>
    <?php else: ?>
      <?php foreach($classes as $c):
        $total_p = intval($c['problem_count']);
        $my_solved = $my_solved_map[$c['class_id']] ?? 0;
        $pct = $total_p > 0 ? round($my_solved / $total_p * 100) : 0;
      ?>
      <tr class="cl-row" data-title="<?php echo htmlspecialchars(strtolower($c['title']))?>" style="<?php echo $c['defunct']=='Y'?'opacity:0.5':'' ?>">
        <td><a href="classop.php?action=view&id=<?php echo $c['class_id']?>"><?php echo htmlspecialchars($c['title'])?></a></td>
        <td class="tc"><span class="badge-cnt"><?php echo $total_p?>문제</span></td>
        <td>
          <?php if($total_p > 0): ?>
          <div class="progress-wrap">
            <div class="progress-bar"><div class="progress-fill" style="width:<?php echo $pct?>%"></div></div>
            <span class="progress-text <?php echo $my_solved==$total_p?'progress-done':''?>"><?php echo $my_solved?>/<?php echo $total_p?></span>
          </div>
          <?php else: ?>
          <span style="color:#ddd;font-size:12px">-</span>
          <?php endif; ?>
        </td>
        <td class="tc cl-author"><?php echo htmlspecialchars($c['user_id'])?></td>
        <td class="tc cl-date"><?php echo substr($c['time'],0,10)?></td>
        <?php if($is_admin): ?>
        <td class="tc">
          <div style="display:flex;gap:4px;justify-content:center;align-items:center">
            <a href="classop.php?action=copy&id=<?php echo $c['class_id']?>" onclick="return confirm('복사할까요?')" title="복사" style="width:28px;height:28px;border-radius:6px;background:#f3e8ff;color:#7c3aed;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none">📋</a>
            <a href="classop.php?action=toggle_defunct&id=<?php echo $c['class_id']?>" title="<?php echo $c['defunct']=='Y'?'공개로 전환':'비공개로 전환'?>" style="width:28px;height:28px;border-radius:6px;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none;<?php echo $c['defunct']=='Y'?'background:#e8faf0':'background:#fff7e6'?>"><?php echo $c['defunct']=='Y'?'🔓':'🔒'?></a>
            <a href="classop.php?action=write&id=<?php echo $c['class_id']?>" title="수정" style="width:28px;height:28px;border-radius:6px;background:#e8f0fe;color:#7c3aed;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none">✏️</a>
            <a href="classop.php?action=delete&id=<?php echo $c['class_id']?>" onclick="return confirm('삭제할까요?')" title="삭제" style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#e74c3c;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none">🗑️</a>
          </div>
        </td>
        <?php endif; ?>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function filterClass() {
  var q = document.getElementById('cl-search').value.toLowerCase();
  document.querySelectorAll('.cl-row').forEach(function(row) {
    row.style.display = row.dataset.title.includes(q) ? '' : 'none';
  });
}
document.getElementById('cl-search').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') filterClass();
});
</script>
</body>
</html>
