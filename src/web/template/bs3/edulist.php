<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>교안 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.edu-wrap{max-width:1300px;margin:30px auto;padding:0 20px}

.edu-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px}
.edu-header h2{font-size:26px;font-weight:800;color:#1a1a2e;margin:0}
.edu-header h2 em{color:#7c3aed;font-style:normal}
.btn-create{
  background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:none;
  padding:12px 26px;border-radius:10px;font-size:15px;font-weight:700;
  cursor:pointer;text-decoration:none;transition:all .2s;
  box-shadow:0 4px 14px rgba(124,58,237,.3);
}
.btn-create:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.4);color:#fff}

/* 태그 필터 */
.edu-tags{display:flex;gap:8px;margin-bottom:22px;flex-wrap:wrap}
.edu-tag-btn{
  padding:9px 22px;border-radius:20px;font-size:14px;font-weight:700;
  cursor:pointer;border:2px solid #e5e9f0;background:#fff;color:#666;transition:all .2s;
}
.edu-tag-btn:hover{border-color:#c4b5fd;color:#7c3aed}
.edu-tag-btn.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

/* 태그별 색상 */
.edu-tag-btn[data-tag="정보"]{border-color:#93c5fd}
.edu-tag-btn[data-tag="정보"].active{background:#3b82f6;border-color:#3b82f6}
.edu-tag-btn[data-tag="정보"]:hover{border-color:#3b82f6;color:#3b82f6}
.edu-tag-btn[data-tag="인공지능기초"]{border-color:#a78bfa}
.edu-tag-btn[data-tag="인공지능기초"].active{background:#7c3aed;border-color:#7c3aed}
.edu-tag-btn[data-tag="인공지능기초"]:hover{border-color:#7c3aed;color:#7c3aed}

/* 카드 리스트 */
.edu-list{display:flex;flex-direction:column;gap:12px}

.edu-card{
  background:#fff;border-radius:14px;overflow:hidden;
  border:1px solid #e5e9f0;transition:all .25s;
  display:flex;align-items:center;text-decoration:none;color:inherit;
}
.edu-card:hover{
  border-color:#c4b5fd;
  box-shadow:0 6px 24px rgba(124,58,237,.1);
  transform:translateY(-2px);
  text-decoration:none;color:inherit;
}

/* 왼쪽 아이콘 */
.edu-icon{
  width:64px;height:64px;margin:0 0 0 22px;border-radius:14px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:28px;
}
.edu-icon.tag-정보{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb}
.edu-icon.tag-인공지능기초{background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed}
.edu-icon.tag-default{background:linear-gradient(135deg,#f3f4f6,#e5e7eb);color:#6b7280}

/* 카드 본문 */
.edu-card-body{flex:1;padding:20px 24px;min-width:0}
.edu-card-title{font-size:18px;font-weight:800;color:#1a1a2e;margin-bottom:5px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.edu-card:hover .edu-card-title{color:#7c3aed}
.edu-card-desc{font-size:14px;color:#888;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

/* 태그 뱃지 */
.edu-badge{
  padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;
  display:inline-block;margin-right:8px;
}
.edu-badge.tag-정보{background:#dbeafe;color:#1d4ed8}
.edu-badge.tag-인공지능기초{background:#ede9fe;color:#6d28d9}
.edu-badge.tag-default{background:#f3f4f6;color:#6b7280}

/* 우측 정보 */
.edu-card-right{
  padding:20px 24px;text-align:right;flex-shrink:0;
  display:flex;flex-direction:column;align-items:flex-end;gap:6px;
}
.edu-card-date{font-size:14px;color:#999;font-weight:600}
.edu-card-author{font-size:13px;color:#bbb}

/* 빈 상태 */
.edu-empty{
  text-align:center;padding:80px 20px;color:#bbb;font-size:16px;
  background:#fff;border-radius:16px;border:1px solid #e5e9f0;
}
.edu-empty-icon{font-size:48px;margin-bottom:12px;display:block}

/* 관리자 버튼 */
.edu-admin-btns{display:flex;gap:6px;margin-left:8px;flex-shrink:0;padding-right:20px}
.edu-abtn{
  width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;
  font-size:15px;text-decoration:none;transition:all .2s;cursor:pointer;border:none;
}
.edu-abtn:hover{transform:scale(1.15)}
.edu-abtn.edit{background:#eff6ff;color:#3b82f6}
.edu-abtn.del{background:#fef2f2;color:#ef4444}

/* 페이지네이션 */
.edu-page{display:flex;justify-content:center;gap:6px;margin-top:24px}
.edu-page a,.edu-page span{
  padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;
  border:1.5px solid #e5e9f0;color:#666;text-decoration:none;transition:all .2s;background:#fff;
}
.edu-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
.edu-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}

@media(max-width:600px){
  .edu-card{flex-direction:column;align-items:flex-start}
  .edu-icon{margin:16px 0 0 16px}
  .edu-card-right{flex-direction:row;padding:0 16px 16px;gap:12px}
  .edu-admin-btns{padding:0 16px 16px}
}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">

  <div class="edu-header">
    <h2>📖 <em>교안</em></h2>
    <?php if($is_admin): ?><a href="eduwrite.php" class="btn-create">+ 교안 작성</a><?php endif; ?>
  </div>

  <?php if(!empty($all_tags)): ?>
  <div class="edu-tags">
    <button class="edu-tag-btn active" data-tag="" onclick="filterEdu('',this)">전체</button>
    <?php foreach($all_tags as $t): ?>
    <button class="edu-tag-btn" data-tag="<?php echo htmlspecialchars($t)?>" onclick="filterEdu('<?php echo htmlspecialchars($t)?>',this)"><?php echo htmlspecialchars($t)?></button>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="edu-list">
  <?php if(empty($rows)): ?>
    <div class="edu-empty">
      <span class="edu-empty-icon">📭</span>
      등록된 교안이 없습니다.
    </div>
  <?php else: ?>
    <?php foreach($rows as $i => $row):
      $tag = trim($row['tag'] ?? '');
      $tag_cls = ($tag !== '') ? 'tag-'.htmlspecialchars($tag) : 'tag-default';
      $icon = '📄';
      if($tag === '정보') $icon = '💻';
      elseif($tag === '인공지능기초') $icon = '🤖';
      $num = $total - ($page-1)*$per_page - $i;
    ?>
    <div class="edu-card-wrap" data-tag="<?php echo htmlspecialchars($tag)?>">
      <a class="edu-card" href="eduview.php?id=<?php echo $row['edu_id']?>">
        <div class="edu-icon <?php echo $tag_cls?>"><?php echo $icon?></div>
        <div class="edu-card-body">
          <div class="edu-card-title"><?php echo htmlspecialchars($row['title'])?></div>
          <div class="edu-card-desc">
            <span class="edu-badge <?php echo $tag_cls?>"><?php echo $tag !== '' ? htmlspecialchars($tag) : '일반'?></span>
            <?php echo htmlspecialchars($row['user_id'])?>
          </div>
        </div>
        <div class="edu-card-right">
          <span class="edu-card-date"><?php echo substr($row['time'],0,10)?></span>
        </div>
      </a>
      <?php if($is_admin): ?>
      <div class="edu-admin-btns" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);z-index:2">
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>

  <?php if($total_pages > 1): ?>
  <div class="edu-page">
    <?php for($p=1;$p<=$total_pages;$p++): ?>
      <?php if($p==$page): ?><span class="active"><?php echo $p?></span>
      <?php else: ?><a href="edulist.php?page=<?php echo $p?>"><?php echo $p?></a><?php endif; ?>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function filterEdu(tag, btn) {
  document.querySelectorAll('.edu-tag-btn').forEach(function(b){ b.classList.remove('active'); });
  btn.classList.add('active');
  document.querySelectorAll('.edu-card-wrap').forEach(function(card) {
    if(tag === '' || card.dataset.tag === tag) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>
</body>
</html>
