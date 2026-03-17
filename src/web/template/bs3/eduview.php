<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($news['title'])?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.edu-wrap{max-width:1100px;margin:30px auto;padding:0 20px}

/* 상단 네비 */
.edu-back{
  display:inline-flex;align-items:center;gap:6px;
  font-size:14px;font-weight:600;color:#7c3aed;text-decoration:none;
  margin-bottom:16px;transition:all .2s;
}
.edu-back:hover{color:#6d28d9;transform:translateX(-3px)}

/* 헤더 카드 */
.edu-view-header{
  border-radius:16px;padding:32px 36px;margin-bottom:24px;
  position:relative;overflow:hidden;
}
.edu-view-header.tag-정보{
  background:linear-gradient(135deg,#3b82f6,#6366f1,#8b5cf6);color:#fff;
}
.edu-view-header.tag-인공지능기초{
  background:linear-gradient(135deg,#7c3aed,#a855f7,#c084fc);color:#fff;
}
.edu-view-header.tag-default{
  background:linear-gradient(135deg,#6b7280,#9ca3af);color:#fff;
}
.edu-view-header::after{
  content:'';position:absolute;right:-30px;top:-30px;
  width:180px;height:180px;border-radius:50%;
  background:rgba(255,255,255,.08);
}
.edu-view-header::before{
  content:'';position:absolute;right:40px;bottom:-40px;
  width:120px;height:120px;border-radius:50%;
  background:rgba(255,255,255,.05);
}

.edu-tag-badge{
  display:inline-flex;align-items:center;gap:5px;
  background:rgba(255,255,255,.2);backdrop-filter:blur(4px);
  padding:6px 16px;border-radius:20px;font-size:13px;font-weight:700;
  margin-bottom:12px;
}
.edu-view-title{font-size:26px;font-weight:900;margin:0 0 10px;line-height:1.3;position:relative;z-index:1}
.edu-view-desc{font-size:15px;opacity:.85;margin-bottom:12px;position:relative;z-index:1}
.edu-view-meta{font-size:13px;opacity:.7;position:relative;z-index:1}

/* 본문 영역 */
.edu-view-content{
  background:#fff;border-radius:16px;border:1px solid #e5e9f0;
  box-shadow:0 2px 12px rgba(0,0,0,.06);
  overflow:hidden;margin-bottom:20px;
}

/* 본문 텍스트 */
.edu-view-body{
  padding:36px 40px;font-size:16px;line-height:2;color:#333;
}
.edu-view-body img{max-width:100%;border-radius:10px;margin:16px 0;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.edu-view-body p{margin:0 0 16px}
.edu-view-body h1,.edu-view-body h2,.edu-view-body h3{color:#1a1a2e;margin:24px 0 12px}
.edu-view-body table{border-collapse:collapse;width:100%}
.edu-view-body table td,.edu-view-body table th{border:1px solid #e5e9f0;padding:10px 14px}
.edu-view-body a{color:#7c3aed}

/* PDF 임베드 */
.edu-pdf-section{
  border-top:1px solid #f0f0f0;padding:24px 40px;
  background:#fafafe;
}
.edu-pdf-header{
  display:flex;justify-content:space-between;align-items:center;
  margin-bottom:16px;flex-wrap:wrap;gap:10px;
}
.edu-pdf-title{font-size:16px;font-weight:700;color:#1a1a2e}
.edu-pdf-title span{color:#7c3aed}
.edu-pdf-actions{display:flex;gap:8px}
.edu-pdf-btn{
  padding:8px 18px;border-radius:8px;font-size:13px;font-weight:700;
  text-decoration:none;transition:all .2s;display:inline-flex;align-items:center;gap:5px;
  border:none;cursor:pointer;
}
.edu-pdf-btn.view{background:#7c3aed;color:#fff}
.edu-pdf-btn.view:hover{background:#6d28d9;color:#fff}
.edu-pdf-btn.download{background:#f3f4f6;color:#555}
.edu-pdf-btn.download:hover{background:#e5e7eb;color:#333}

.edu-pdf-container{position:relative;border-radius:12px;overflow:hidden}
.edu-pdf-container.fullscreen{
  position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;
  border-radius:0;background:#000;
}
.edu-pdf-container.fullscreen .edu-pdf-embed{height:100vh;border-radius:0}
.edu-pdf-container.fullscreen .pdf-close-fs{
  display:flex;position:absolute;top:16px;right:16px;z-index:10000;
  width:44px;height:44px;border-radius:50%;background:rgba(0,0,0,.6);
  color:#fff;font-size:22px;border:none;cursor:pointer;
  align-items:center;justify-content:center;transition:background .2s;
}
.edu-pdf-container.fullscreen .pdf-close-fs:hover{background:rgba(0,0,0,.8)}
.pdf-close-fs{display:none}
.edu-pdf-embed{
  width:100%;height:750px;border:none;border-radius:12px;
  background:#f8f9fc;box-shadow:inset 0 1px 4px rgba(0,0,0,.08);
}

/* 하단 버튼 */
.edu-btn-row{display:flex;gap:10px;flex-wrap:wrap}
.btn-back{
  background:#f3f4f6;color:#555;padding:11px 24px;border-radius:10px;
  font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;
}
.btn-back:hover{background:#e5e7eb;color:#333}
.btn-edit{
  background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;
  padding:11px 24px;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;
  transition:all .2s;box-shadow:0 3px 10px rgba(59,130,246,.25);
}
.btn-edit:hover{transform:translateY(-1px);box-shadow:0 5px 16px rgba(59,130,246,.3);color:#fff}
.btn-del{
  background:#fef2f2;color:#ef4444;padding:11px 24px;border-radius:10px;
  font-size:14px;font-weight:700;text-decoration:none;transition:all .2s;
  border:1.5px solid #fecaca;
}
.btn-del:hover{background:#fee2e2;color:#dc2626}

/* 이전/다음 네비 */
.edu-nav{
  display:flex;justify-content:space-between;margin-top:20px;gap:12px;
}
.edu-nav a{
  flex:1;padding:16px 20px;background:#fff;border-radius:12px;
  border:1px solid #e5e9f0;text-decoration:none;transition:all .2s;
}
.edu-nav a:hover{border-color:#c4b5fd;box-shadow:0 4px 16px rgba(124,58,237,.08);transform:translateY(-1px)}
.edu-nav a .nav-label{font-size:12px;color:#999;font-weight:600;margin-bottom:4px}
.edu-nav a .nav-title{font-size:14px;color:#333;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

@media(max-width:600px){
  .edu-view-header{padding:24px 20px}
  .edu-view-body{padding:24px 20px}
  .edu-pdf-section{padding:20px}
  .edu-pdf-embed{height:400px}
}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">

  <a href="edulist.php" class="edu-back">← 교안 목록으로</a>

  <?php
    $tag = trim($news['tag'] ?? '');
    $tag_cls = ($tag !== '') ? 'tag-'.htmlspecialchars($tag) : 'tag-default';
    $tag_icon = '📄';
    if($tag === '정보') $tag_icon = '💻';
    elseif($tag === '인공지능기초') $tag_icon = '🤖';
  ?>

  <!-- 헤더 -->
  <div class="edu-view-header <?php echo $tag_cls?>">
    <span class="edu-tag-badge"><?php echo $tag_icon?> <?php echo $tag !== '' ? htmlspecialchars($tag) : '일반'?></span>
    <h1 class="edu-view-title"><?php echo htmlspecialchars($news['title'])?></h1>
    <div class="edu-view-meta">
      ✍️ <?php echo htmlspecialchars($news['user_id'])?> &nbsp;·&nbsp;
      📅 <?php echo substr($news['time'],0,10)?>
    </div>
  </div>

  <!-- 본문 -->
  <div class="edu-view-content">
    <?php
      $content = $news['content'];
      // PDF 파일 링크 추출
      $pdf_files = [];
      if(preg_match_all('#(/upload/(?:file|image)/[^"\'<>\s]+\.pdf)#i', $content, $pdf_matches)) {
        $pdf_files = array_unique($pdf_matches[1]);
      }
      // 일반 파일 링크 추출
      $other_files = [];
      if(preg_match_all('#(/upload/(?:file|image)/[^"\'<>\s]+\.(?:pptx?|xlsx?|docx?|hwp|zip|rar))#i', $content, $file_matches)) {
        $other_files = array_unique($file_matches[1]);
      }

      // HTML 내용 처리
      if (strip_tags($content) === $content) {
        $content = htmlspecialchars($content, ENT_QUOTES);
        $content = preg_replace('/https?:\/\/\S+\.(jpg|jpeg|png|gif|webp)(\?\S*)?/i','<img src="$0" alt="이미지">',$content);
        $content = nl2br($content);
      }

      // content에서 PDF 다운로드 링크 제거 (임베드로 대체)
      $display_content = $content;
      foreach($pdf_files as $pdf) {
        // a 태그로 감싸진 PDF 링크 제거
        $display_content = preg_replace('#<a[^>]*href=["\']'.preg_quote($pdf,'#').'["\'][^>]*>.*?</a>#is', '', $display_content);
      }
      // 빈 p 태그 정리
      $display_content = preg_replace('#<p>\s*</p>#', '', $display_content);
      $display_content = trim($display_content);
    ?>

    <?php if($display_content !== ''): ?>
    <div class="edu-view-body">
      <?php echo $display_content; ?>
    </div>
    <?php endif; ?>

    <!-- PDF 임베드 -->
    <?php foreach($pdf_files as $idx => $pdf):
      $pdf_name = basename(urldecode($pdf));
    ?>
    <div class="edu-pdf-section">
      <div class="edu-pdf-header">
        <div class="edu-pdf-title">📄 <span><?php echo htmlspecialchars($pdf_name)?></span></div>
        <div class="edu-pdf-actions">
          <button onclick="togglePdfFullscreen(<?php echo $idx?>)" class="edu-pdf-btn view" style="border:none;cursor:pointer">⛶ 전체화면</button>
          <a href="<?php echo htmlspecialchars($pdf)?>" target="_blank" class="edu-pdf-btn view">🔍 새 탭</a>
          <a href="<?php echo htmlspecialchars($pdf)?>" download class="edu-pdf-btn download">📥 다운로드</a>
        </div>
      </div>
      <div class="edu-pdf-container" id="pdf-container-<?php echo $idx?>">
        <iframe src="<?php echo htmlspecialchars($pdf)?>#toolbar=1&navpanes=0&zoom=page-fit" class="edu-pdf-embed" id="pdf-embed-<?php echo $idx?>" allowfullscreen></iframe>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- 기타 첨부파일 -->
    <?php if(!empty($other_files)): ?>
    <div class="edu-pdf-section">
      <div class="edu-pdf-title" style="margin-bottom:12px">📎 첨부파일</div>
      <?php foreach($other_files as $file):
        $fname = basename(urldecode($file));
        $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
        $ficon = '📄';
        if(in_array($ext,['ppt','pptx'])) $ficon = '📊';
        elseif(in_array($ext,['xls','xlsx'])) $ficon = '📈';
        elseif(in_array($ext,['doc','docx'])) $ficon = '📝';
        elseif($ext==='hwp') $ficon = '📃';
        elseif(in_array($ext,['zip','rar'])) $ficon = '🗜️';
      ?>
      <a href="<?php echo htmlspecialchars($file)?>" download
         style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;
                background:#fff;border:1.5px solid #e5e9f0;border-radius:10px;
                text-decoration:none;color:#333;font-size:14px;font-weight:600;
                margin:4px 4px 4px 0;transition:all .2s">
        <?php echo $ficon?> <?php echo htmlspecialchars($fname)?>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- 하단 버튼 -->
  <div class="edu-btn-row">
    <a href="edulist.php" class="btn-back">← 목록</a>
    <?php if($is_admin): ?>
    <a href="eduwrite.php?id=<?php echo $news['edu_id']?>" class="btn-edit">✏️ 수정</a>
    <a href="eduview.php?id=<?php echo $news['edu_id']?>&action=delete" class="btn-del" onclick="return confirm('정말 삭제할까요?')">🗑 삭제</a>
    <?php endif; ?>
  </div>

  <!-- 이전/다음 교안 -->
  <?php
    $prev = pdo_query("SELECT edu_id, title FROM edu WHERE defunct='N' AND time > ? ORDER BY time ASC LIMIT 1", $news['time']);
    $next = pdo_query("SELECT edu_id, title FROM edu WHERE defunct='N' AND time < ? ORDER BY time DESC LIMIT 1", $news['time']);
  ?>
  <?php if(!empty($prev) || !empty($next)): ?>
  <div class="edu-nav">
    <?php if(!empty($prev)): ?>
    <a href="eduview.php?id=<?php echo $prev[0]['edu_id']?>">
      <div class="nav-label">← 이전 교안</div>
      <div class="nav-title"><?php echo htmlspecialchars($prev[0]['title'])?></div>
    </a>
    <?php else: ?><div style="flex:1"></div><?php endif; ?>

    <?php if(!empty($next)): ?>
    <a href="eduview.php?id=<?php echo $next[0]['edu_id']?>" style="text-align:right">
      <div class="nav-label">다음 교안 →</div>
      <div class="nav-title"><?php echo htmlspecialchars($next[0]['title'])?></div>
    </a>
    <?php else: ?><div style="flex:1"></div><?php endif; ?>
  </div>
  <?php endif; ?>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function togglePdfFullscreen(idx) {
  var container = document.getElementById('pdf-container-' + idx);
  if(container.classList.contains('fullscreen')) {
    container.classList.remove('fullscreen');
    document.body.style.overflow = '';
  } else {
    container.classList.add('fullscreen');
    document.body.style.overflow = 'hidden';
    // 닫기 버튼이 없으면 추가
    if(!container.querySelector('.pdf-close-fs')) {
      var btn = document.createElement('button');
      btn.className = 'pdf-close-fs';
      btn.innerHTML = '✕';
      btn.onclick = function(){ togglePdfFullscreen(idx); };
      container.appendChild(btn);
    }
  }
}
// ESC로도 전체화면 닫기
document.addEventListener('keydown', function(e) {
  if(e.key === 'Escape') {
    document.querySelectorAll('.edu-pdf-container.fullscreen').forEach(function(c) {
      c.classList.remove('fullscreen');
      document.body.style.overflow = '';
    });
  }
});
</script>
</body>
</html>
