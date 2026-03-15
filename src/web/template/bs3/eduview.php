<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($news['title'])?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.edu-wrap{max-width:960px;margin:36px auto;padding:0 16px}
.edu-view-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);overflow:hidden}
.edu-view-header{background:#7c3aed;color:#fff;padding:28px 32px}
.edu-view-header h1{font-size:22px;font-weight:700;margin:0 0 10px}
.edu-view-meta{font-size:13px;opacity:.85}
.edu-view-body{padding:32px;font-size:15px;line-height:1.9;color:#333}
.edu-view-body img{max-width:100%;border-radius:8px;margin:12px 0}
.edu-btn-row{display:flex;gap:10px;margin-top:28px}
.btn-back{background:#f0f0f0;color:#555;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;text-decoration:none}
.btn-back:hover{background:#e0e0e0;color:#333}
.btn-edit{background:#7c3aed;color:#fff;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;text-decoration:none}
.btn-edit:hover{background:#6d28d9;color:#fff}
.btn-del{background:#e74c3c;color:#fff;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;text-decoration:none}
.btn-del:hover{background:#c0392b;color:#fff}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">
  <div class="edu-view-card">
    <div class="edu-view-header">
      <h1><?php echo htmlspecialchars($news['title'])?></h1>
      <div class="edu-view-meta">✍️ <?php echo htmlspecialchars($news['user_id'])?> &nbsp;·&nbsp; 🕒 <?php echo substr($news['time'],0,16)?></div>
    </div>
    <div class="edu-view-body">
      <?php
        $content = $news['content'];
        // 외부 이미지 URL 자동변환 (KindEditor HTML이 아닌 경우 대비)
        if (strip_tags($content) === $content) {
          $content = htmlspecialchars($content, ENT_QUOTES);
          $content = preg_replace('/https?:\/\/\S+\.(jpg|jpeg|png|gif|webp)(\?\S*)?/i','<img src="$0" alt="이미지">',$content);
          $content = nl2br($content);
        }
        echo $content;
      ?>
    </div>
  </div>
  <div class="edu-btn-row">
    <a href="edulist.php" class="btn-back">← 목록</a>
    <?php if($is_admin): ?>
    <a href="eduwrite.php?id=<?php echo $news['edu_id']?>" class="btn-edit">✏️ 수정</a>
    <a href="eduview.php?id=<?php echo $news['edu_id']?>&action=delete" class="btn-del" onclick="return confirm('정말 삭제할까요?')">🗑 삭제</a>
    <?php endif; ?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
