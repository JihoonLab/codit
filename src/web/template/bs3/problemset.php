<?php if(!isset($_GET['ajax'])): ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>문제 목록 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.ps-wrap{max-width:1300px;margin:30px auto;padding:0 20px}

.ps-header{
  display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;
}
.ps-header h2{font-size:24px;font-weight:800;color:#1a1a2e;margin:0}
.ps-header h2 em{color:#7c3aed;font-style:normal}
.ps-stats{display:flex;gap:12px;font-size:13px;color:#888}
.ps-stats .stat-item{background:#f8f9fc;padding:5px 14px;border-radius:16px;font-weight:600}
.ps-stats .stat-item strong{color:#7c3aed}

/* 검색 */
.ps-search{
  display:flex;gap:10px;margin-bottom:20px;
  background:#fff;padding:14px 18px;border-radius:12px;
  border:1px solid #e5e9f0;box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.ps-search input{
  flex:1;padding:10px 16px;border:1.5px solid #e5e9f0;border-radius:8px;
  font-size:14px;outline:none;background:#f8f9fc;transition:border .2s;
}
.ps-search input:focus{border-color:#7c3aed;background:#fff}
.ps-search button{
  padding:10px 24px;background:#7c3aed;color:#fff;border:none;border-radius:8px;
  font-size:14px;font-weight:600;cursor:pointer;transition:background .2s;white-space:nowrap;
}
.ps-search button:hover{background:#6d28d9}

/* 필터 탭 */
.ps-filters{display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap}
.ps-filter-btn{
  padding:7px 18px;border-radius:20px;font-size:13px;font-weight:600;
  cursor:pointer;border:2px solid #e5e9f0;background:#fff;color:#666;transition:all .2s;
}
.ps-filter-btn:hover{border-color:#c4b5fd;color:#7c3aed}
.ps-filter-btn.active{background:#7c3aed;color:#fff;border-color:#7c3aed}
.ps-filter-btn .cnt{font-size:11px;opacity:.7;margin-left:2px}

/* 열 헤더 */
.ps-col-header{
  display:flex;align-items:center;gap:14px;
  padding:10px 20px;font-size:13px;font-weight:700;color:#7c3aed;
  border-bottom:2px solid #ede9fe;margin-bottom:4px;
}
.ps-col-header .ph-status{width:32px;flex-shrink:0;text-align:center}
.ps-col-header .ph-pid{min-width:50px;text-align:center;flex-shrink:0}
.ps-col-header .ph-title{flex:1}
.ps-col-header .ph-stats{display:flex;gap:16px;flex-shrink:0}
.ps-col-header .ph-stats span{min-width:52px;text-align:center}

/* 문제 리스트 */
.ps-list{display:flex;flex-direction:column;gap:6px}

/* ── 공통 카드 ── */
.ps-item{
  display:flex;align-items:center;gap:14px;
  border-radius:10px;padding:14px 20px;
  transition:all .2s;cursor:pointer;position:relative;
}
.ps-item:hover{transform:translateY(-1px)}

/* ── 해결한 문제 (초록 테마) ── */
.ps-item.ps-solved{
  background:linear-gradient(135deg,#f0fdf4,#ecfdf5);
  border:1px solid #86efac;
}
.ps-item.ps-solved:hover{
  border-color:#4ade80;box-shadow:0 3px 12px rgba(16,185,129,.15);
}
.ps-item.ps-solved .ps-pid{background:#dcfce7;color:#16a34a}
.ps-item.ps-solved .ps-title-area a{color:#15803d}
.ps-item.ps-solved .ps-title-area a:hover{color:#166534}
.ps-item.ps-solved .ps-stat-box .num a{color:#16a34a}

/* ── 틀린 문제 (주황 테마) ── */
.ps-item.ps-tried{
  background:linear-gradient(135deg,#fff7ed,#ffedd5);
  border:1px solid #fdba74;
}
.ps-item.ps-tried:hover{
  border-color:#fb923c;box-shadow:0 3px 12px rgba(249,115,22,.15);
}
.ps-item.ps-tried .ps-pid{background:#ffedd5;color:#ea580c}
.ps-item.ps-tried .ps-title-area a{color:#c2410c}
.ps-item.ps-tried .ps-title-area a:hover{color:#9a3412}
.ps-item.ps-tried .ps-stat-box .num a{color:#ea580c}

/* ── 미풀이 문제 (기본 흰색) ── */
.ps-item.ps-none{
  background:#fff;
  border:1px solid #e5e9f0;
}
.ps-item.ps-none:hover{
  border-color:#c4b5fd;box-shadow:0 3px 12px rgba(124,58,237,.08);
}
.ps-item.ps-none .ps-pid{background:#f5f3ff;color:#7c3aed}
.ps-item.ps-none .ps-title-area a{color:#333}
.ps-item.ps-none .ps-title-area a:hover{color:#7c3aed}

/* 상태 아이콘 */
.ps-status{
  width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:14px;flex-shrink:0;font-weight:700;
}
.ps-status.solved{background:#bbf7d0;color:#16a34a}
.ps-status.tried{background:#fed7aa;color:#ea580c}
.ps-status.none{background:#f3f4f6;color:#d1d5db}

/* 문제 ID */
.ps-pid{
  font-size:14px;font-weight:700;
  padding:4px 12px;border-radius:6px;
  min-width:50px;text-align:center;flex-shrink:0;
}

/* 제목 영역 */
.ps-title-area{flex:1;min-width:0}
.ps-title-area a{
  font-size:15px;font-weight:600;text-decoration:none;
  display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}

/* 출처/분류 태그 */
.ps-tags{display:flex;gap:4px;flex-wrap:wrap;margin-top:4px}
.ps-tags .ps-tag{
  font-size:11px;font-weight:600;padding:2px 8px;border-radius:8px;
  text-decoration:none;display:inline-block;
}
.ps-tag-default{background:#f3f4f6;color:#6b7280}
.ps-tag-primary{background:#e8f0fe;color:#3b82f6}
.ps-tag-success{background:#d1fae5;color:#059669}
.ps-tag-info{background:#e0f2fe;color:#0284c7}
.ps-tag-warning{background:#fef3c7;color:#d97706}
.ps-tag-danger{background:#fee2e2;color:#dc2626}

/* 통계 */
.ps-stat-col{display:flex;gap:16px;align-items:center;flex-shrink:0}
.ps-stat-box{text-align:center;min-width:52px}
.ps-stat-box .num{font-size:17px;font-weight:800;display:block}
.ps-stat-box .num a{text-decoration:none}
.ps-stat-box .num a:hover{opacity:.7}
.ps-stat-box .lbl{font-size:11px;color:#aaa;font-weight:600}

/* 페이지네이션 */
.ps-page{display:flex;justify-content:center;gap:4px;margin-top:24px;flex-wrap:wrap}
.ps-page a,.ps-page span{
  padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;
  border:1px solid #e5e9f0;color:#555;text-decoration:none;transition:all .2s;
}
.ps-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
.ps-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}

@media(max-width:768px){
  .ps-item{flex-wrap:wrap;gap:8px;padding:12px 14px}
  .ps-stat-col{width:100%;justify-content:flex-end;gap:12px}
  .ps-stats{display:none}
}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ps-wrap">
  <div class="ps-header">
    <h2>📋 <em>문제</em> 목록</h2>
    <div class="ps-stats">
      <span class="stat-item">전체 <strong><?php echo $view_total ?? 0?></strong>문제</span>
      <?php if(isset($view_total_page)): ?>
      <span class="stat-item">페이지 <strong><?php echo intval($page ?? 1)?></strong>/<?php echo $view_total_page?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="ps-search">
    <form action="problemset.php" style="display:flex;gap:10px;width:100%">
      <input type="text" name="search" id="ps-search-input" placeholder="🔍 문제 ID, 제목, 출처/분류 검색..." value="<?php echo htmlspecialchars($_GET['search'] ?? '')?>">
      <button type="submit">검색</button>
    </form>
  </div>

  <div class="ps-filters">
    <button class="ps-filter-btn active" onclick="filterStatus('all',this)">전체</button>
    <button class="ps-filter-btn" onclick="filterStatus('solved',this)" style="border-color:#86efac;color:#16a34a">✓ 해결</button>
    <button class="ps-filter-btn" onclick="filterStatus('tried',this)" style="border-color:#fdba74;color:#ea580c">✗ 오답</button>
    <button class="ps-filter-btn" onclick="filterStatus('none',this)" style="border-color:#e5e9f0;color:#888">미풀이</button>
  </div>

  <div class="ps-col-header">
    <span class="ph-status">상태</span>
    <span class="ph-pid">번호</span>
    <span class="ph-title">제목</span>
    <span class="ph-stats"><span>통과</span><span>제출</span></span>
  </div>
  <div class="ps-list" id="problemset">
<?php endif; ?>
    <?php
    foreach($view_problemset as $row) {
      $cells = array_values((array)$row);

      $status_html = $cells[0];
      $is_solved = (strpos($status_html, 'success') !== false);
      $is_tried = (strpos($status_html, 'danger') !== false);
      $status_class = $is_solved ? 'ps-solved' : ($is_tried ? 'ps-tried' : 'ps-none');
      $icon_class = $is_solved ? 'solved' : ($is_tried ? 'tried' : 'none');
      $icon = $is_solved ? '✓' : ($is_tried ? '✗' : '·');
      $data_status = $is_solved ? 'solved' : ($is_tried ? 'tried' : 'none');

      $pid_match = [];
      preg_match('/>\s*(\d+)\s*</', $cells[1], $pid_match);
      $pid = $pid_match[1] ?? '';

      $title_match = [];
      preg_match("/<a[^>]*>(.*?)<\/a>/", $cells[2], $title_match);
      $title_text = $title_match[1] ?? strip_tags($cells[2]);
      $href_match = [];
      preg_match('/href=["\']([^"\']+)/', $cells[2], $href_match);
      $href = $href_match[1] ?? 'problem.php?id='.$pid;

      $source_html = $cells[3];
      $tag_matches = [];
      preg_match_all("/<a[^>]*class='label label-(\w+)'[^>]*>(.*?)<\/a>/", $source_html, $tag_matches, PREG_SET_ORDER);

      $acc_match = [];
      preg_match('/>\s*(\d+)\s*</', $cells[4], $acc_match);
      $accepted = intval($acc_match[1] ?? 0);

      $sub_match = [];
      preg_match('/>\s*(\d+)\s*</', $cells[5], $sub_match);
      $submitted = intval($sub_match[1] ?? 0);

      $acc_href_match = [];
      preg_match('/href=["\']([^"\']+)/', $cells[4], $acc_href_match);
      $acc_href = $acc_href_match[1] ?? '#';
      $sub_href_match = [];
      preg_match('/href=["\']([^"\']+)/', $cells[5], $sub_href_match);
      $sub_href = $sub_href_match[1] ?? '#';
    ?>
    <div class="ps-item <?php echo $status_class?>" data-status="<?php echo $data_status?>" onclick="location.href='<?php echo $href?>'">
      <div class="ps-status <?php echo $icon_class?>"><?php echo $icon?></div>
      <div class="ps-pid"><?php echo $pid?></div>
      <div class="ps-title-area">
        <a href="<?php echo $href?>"><?php echo htmlspecialchars($title_text)?></a>
        <?php if(!empty($tag_matches)): ?>
        <div class="ps-tags">
          <?php foreach($tag_matches as $tm): ?>
          <a class="ps-tag ps-tag-<?php echo $tm[1]?>" href="problemset.php?search=<?php echo urlencode($tm[2])?>" onclick="event.stopPropagation()"><?php echo htmlspecialchars($tm[2])?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="ps-stat-col">
        <div class="ps-stat-box">
          <span class="num"><a href="<?php echo $acc_href?>" onclick="event.stopPropagation()"><?php echo $accepted?></a></span>
          <span class="lbl">통과</span>
        </div>
        <div class="ps-stat-box">
          <span class="num"><a href="<?php echo $sub_href?>" onclick="event.stopPropagation()"><?php echo $submitted?></a></span>
          <span class="lbl">제출</span>
        </div>
      </div>
    </div>
    <?php } ?>
<?php if(!isset($_GET['ajax'])): ?>
  </div>

  <div class="ps-page">
    <a href="problemset.php?page=1">&laquo;</a>
    <?php
    if(!isset($page)) $page = 1;
    $page = intval($page);
    $section = 8;
    $start = $page > $section ? $page - $section : 1;
    $end = min($page + $section, $view_total_page);
    for($i = $start; $i <= $end; $i++):
    ?>
    <?php if($page == $i): ?>
      <span class="active"><?php echo $i?></span>
    <?php else: ?>
      <a href="problemset.php?page=<?php echo $i . htmlentities($postfix, ENT_QUOTES, 'UTF-8')?>"><?php echo $i?></a>
    <?php endif; ?>
    <?php endfor; ?>
    <a href="problemset.php?page=<?php echo $view_total_page?>">&raquo;</a>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function filterStatus(status, btn) {
  document.querySelectorAll('.ps-filter-btn').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  document.querySelectorAll('.ps-item').forEach(function(item) {
    if(status === 'all') { item.style.display = ''; }
    else { item.style.display = (item.dataset.status === status) ? '' : 'none'; }
  });
}
$(document).ready(function() {
  $("#ps-search-input").on("input", function() {
    var q = $(this).val().toLowerCase();
    if (q === "") { $(".ps-item").show(); return; }
    $(".ps-item").each(function() {
      $(this).toggle($(this).text().toLowerCase().includes(q));
    });
  });
});
</script>
</body>
</html>
<?php endif; ?>
