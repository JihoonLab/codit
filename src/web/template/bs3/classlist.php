<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>수업 목록 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.cl-wrap{max-width:1300px;margin:30px auto;padding:0 20px}

.cl-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.cl-header h2{font-size:26px;font-weight:800;color:#1a1a2e;margin:0}
.cl-header h2 em{color:#7c3aed;font-style:normal}
.btn-create{
  background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:none;
  padding:12px 26px;border-radius:10px;font-size:15px;font-weight:700;
  cursor:pointer;text-decoration:none;transition:all .2s;
  box-shadow:0 4px 14px rgba(124,58,237,.3);
}
.btn-create:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.4);color:#fff}

/* 검색 */
.cl-search{
  display:flex;gap:10px;margin-bottom:18px;
  background:#fff;padding:14px 18px;border-radius:12px;
  border:1px solid #e5e9f0;box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.cl-search input{
  flex:1;padding:11px 16px;border:1.5px solid #e5e9f0;border-radius:8px;
  font-size:14px;outline:none;background:#f8f9fc;transition:border .2s;
}
.cl-search input:focus{border-color:#7c3aed;background:#fff}
.cl-search button{
  padding:11px 24px;background:#7c3aed;color:#fff;border:none;border-radius:8px;
  font-size:14px;font-weight:600;cursor:pointer;transition:background .2s;
}
.cl-search button:hover{background:#6d28d9}

/* 태그 필터 */
.cl-tags{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:18px}
.cl-tag-btn{
  padding:7px 18px;border-radius:20px;font-size:13px;font-weight:600;
  cursor:pointer;border:2px solid #e5e9f0;background:#fff;color:#666;transition:all .2s;
}
.cl-tag-btn:hover{border-color:#c4b5fd;color:#7c3aed}
.cl-tag-btn.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

/* ═══ 카드 그리드 ═══ */
.cl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:16px}

.cl-card{
  background:#fff;border-radius:16px;overflow:hidden;
  border:1px solid #e5e9f0;transition:all .25s;
  display:flex;flex-direction:column;position:relative;
}
.cl-card:hover{
  border-color:#c4b5fd;
  box-shadow:0 8px 30px rgba(124,58,237,.12);
  transform:translateY(-3px);
}
.cl-card.defunct{opacity:.4}

/* 카드 상단 컬러바 */
.cl-card-top{
  height:6px;
  background:linear-gradient(90deg,#7c3aed,#a78bfa,#c4b5fd);
}

/* 카드 본문 */
.cl-card-body{padding:20px 22px 16px;flex:1;cursor:pointer}
.cl-card-title{font-size:18px;font-weight:800;color:#1a1a2e;margin-bottom:4px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.cl-card-desc{font-size:13px;color:#999;margin-bottom:12px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-height:18px}

/* 메타 태그 줄 */
.cl-card-meta{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
.cl-meta-tag{
  background:#f3e8ff;color:#7c3aed;padding:3px 10px;border-radius:8px;
  font-size:11px;font-weight:700;
}
.cl-meta-count{
  background:#eff6ff;color:#3b82f6;padding:3px 10px;border-radius:8px;
  font-size:11px;font-weight:700;
}
.cl-meta-author{font-size:11px;color:#bbb;margin-left:auto}

/* 진행률 바 영역 */
.cl-progress-area{padding:0 22px 16px}
.cl-progress-row{display:flex;align-items:center;gap:10px}
.cl-progress-bar{flex:1;height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden}
.cl-progress-fill{height:100%;border-radius:4px;transition:width .5s ease;
  background:linear-gradient(90deg,#7c3aed,#a78bfa)}
.cl-progress-fill.done{background:linear-gradient(90deg,#10b981,#34d399)}
.cl-progress-text{font-size:13px;font-weight:800;color:#7c3aed;min-width:42px;text-align:right}
.cl-progress-text.done{color:#10b981}
.cl-progress-label{font-size:11px;color:#aaa;margin-top:3px}

/* 카드 하단 관리 영역 */
.cl-card-footer{
  display:flex;justify-content:space-between;align-items:center;
  padding:12px 22px;border-top:1px solid #f0f0f0;background:#fafafe;
}
.cl-card-date{font-size:11px;color:#bbb}
.cl-admin-btns{display:flex;gap:4px}
.cl-abtn{
  width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;
  font-size:14px;text-decoration:none;transition:all .2s;cursor:pointer;border:none;
}
.cl-abtn:hover{transform:scale(1.15)}
.cl-abtn.copy{background:#f3e8ff;color:#7c3aed}
.cl-abtn.lock{background:#fff7e6;color:#f59e0b}
.cl-abtn.unlock{background:#ecfdf5;color:#10b981}
.cl-abtn.edit{background:#eff6ff;color:#3b82f6}
.cl-abtn.del{background:#fef2f2;color:#ef4444}

.cl-empty{
  text-align:center;padding:80px 20px;color:#bbb;font-size:16px;
  background:#fff;border-radius:16px;border:1px solid #e5e9f0;
  grid-column:1/-1;
}
.cl-empty-icon{font-size:48px;margin-bottom:12px;display:block}

@media(max-width:600px){
  .cl-grid{grid-template-columns:1fr}
  .cl-card-body{padding:16px}
}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cl-wrap">
  <div class="cl-header">
    <h2>📚 <em>수업</em> 목록</h2>
    <?php if($is_admin): ?><a href="classop.php?action=write" class="btn-create">+ 수업 만들기</a><?php endif; ?>
  </div>

  <div class="cl-search">
    <input type="text" id="cl-search" placeholder="🔍 수업 제목 검색..." oninput="filterClass()">
    <button onclick="filterClass()">검색</button>
  </div>

  <?php
  $all_tags = [];
  foreach($classes as $c) {
    $t = trim($c['tag'] ?? '');
    if($t !== '' && !in_array($t, $all_tags)) $all_tags[] = $t;
  }
  sort($all_tags);
  $student_tag = $is_admin ? '' : ($my_school ?? '');
  ?>
  <?php if(!empty($all_tags)): ?>
  <div class="cl-tags">
    <button class="cl-tag-btn active" onclick="filterTag('',this)">전체</button>
    <?php foreach($all_tags as $t): ?>
    <button class="cl-tag-btn" onclick="filterTag('<?php echo htmlspecialchars($t)?>',this)"><?php echo htmlspecialchars($t)?></button>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="cl-grid">
  <?php if(empty($classes)): ?>
    <div class="cl-empty">
      <span class="cl-empty-icon">📭</span>
      등록된 수업이 없습니다.
    </div>
  <?php else: ?>
    <?php foreach($classes as $c):
      $total_p = intval($c['problem_count']);
      $my_solved = $my_solved_map[$c['class_id']] ?? 0;
      $pct = $total_p > 0 ? round($my_solved / $total_p * 100) : 0;
      $is_done = ($total_p > 0 && $my_solved == $total_p);
      $tag = trim($c['tag'] ?? '');
    ?>
    <div class="cl-card <?php echo $c['defunct']=='Y'?'defunct':''?>"
         data-title="<?php echo htmlspecialchars(strtolower($c['title']))?>"
         data-tag="<?php echo htmlspecialchars($tag)?>">
      <div class="cl-card-top"></div>
      <div class="cl-card-body" onclick="location.href='classop.php?action=view&id=<?php echo $c['class_id']?>'">
        <div class="cl-card-title"><?php echo htmlspecialchars($c['title'])?></div>
        <div class="cl-card-desc"><?php echo htmlspecialchars($c['description'] ?? '')?></div>
        <div class="cl-card-meta">
          <?php if($tag !== ''): ?><span class="cl-meta-tag">🏷 <?php echo htmlspecialchars($tag)?></span><?php endif; ?>
          <span class="cl-meta-count">📋 <?php echo $total_p?>문제</span>
          <span class="cl-meta-author"><?php echo htmlspecialchars($c['user_id'])?></span>
        </div>
      </div>
      <?php if($total_p > 0): ?>
      <div class="cl-progress-area">
        <div class="cl-progress-row">
          <div class="cl-progress-bar">
            <div class="cl-progress-fill <?php echo $is_done?'done':''?>" style="width:<?php echo $pct?>%"></div>
          </div>
          <span class="cl-progress-text <?php echo $is_done?'done':''?>"><?php echo $my_solved?>/<?php echo $total_p?></span>
        </div>
        <div class="cl-progress-label"><?php echo $is_done?'🎉 완료!':'진행률 '.$pct.'%'?></div>
      </div>
      <?php endif; ?>
      <div class="cl-card-footer">
        <span class="cl-card-date">📅 <?php echo substr($c['time'],0,10)?></span>
        <?php if($is_admin): ?>
        <div class="cl-admin-btns">
          <a class="cl-abtn copy" href="classop.php?action=copy&id=<?php echo $c['class_id']?>" onclick="event.stopPropagation();return confirm('복사할까요?')" title="복사">📋</a>
          <a class="cl-abtn <?php echo $c['defunct']=='Y'?'unlock':'lock'?>" href="classop.php?action=toggle_defunct&id=<?php echo $c['class_id']?>" onclick="event.stopPropagation()" title="<?php echo $c['defunct']=='Y'?'공개':'비공개'?>"><?php echo $c['defunct']=='Y'?'👁':'🔒'?></a>
          <a class="cl-abtn edit" href="classop.php?action=write&id=<?php echo $c['class_id']?>" onclick="event.stopPropagation()" title="수정">✏️</a>
          <a class="cl-abtn del" href="classop.php?action=delete&id=<?php echo $c['class_id']?>" onclick="event.stopPropagation();return confirm('정말 삭제할까요?')" title="삭제">🗑</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var currentTag = '';
var studentTag = '<?php echo addslashes($student_tag)?>';

function filterTag(tag, btn) {
  currentTag = tag;
  document.querySelectorAll('.cl-tag-btn').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  filterClass();
}

function filterClass() {
  var q = document.getElementById('cl-search').value.toLowerCase();
  document.querySelectorAll('.cl-card').forEach(function(card) {
    var matchTitle = card.dataset.title.includes(q);
    var matchTag = (currentTag === '' || card.dataset.tag === currentTag);
    card.style.display = (matchTitle && matchTag) ? '' : 'none';
  });
}

<?php if(!$is_admin && $student_tag !== ''): ?>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.cl-tag-btn').forEach(function(b) {
    if(b.textContent.trim() === studentTag) b.click();
  });
});
<?php endif; ?>
</script>
</body>
</html>
