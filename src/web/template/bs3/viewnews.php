<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo isset($news_title)?htmlspecialchars($news_title):'공지사항'?> - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.vn-wrap { max-width: 860px; margin: 32px auto; padding: 0 20px 60px; }
.vn-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  overflow: hidden;
}
.vn-header {
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff;
  padding: 28px 32px;
}
.vn-header h1 { margin: 0 0 8px; font-size: 22px; font-weight: 700; }
.vn-header .vn-meta { font-size: 13px; opacity: 0.85; }
.vn-body {
  padding: 32px;
  font-size: 15px;
  line-height: 1.9;
  color: #333;
  min-height: 120px;
}
/* 마크다운/BBCode 내부 스타일 */
.vn-body img { max-width: 100%; border-radius: 8px; margin: 8px 0; }
.vn-body pre { background: #1e1e1e; color: #e2e8f0; border-radius: 8px; padding: 16px 20px; font-size: 14px; overflow-x: auto; }
.vn-body code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
.vn-body pre code { background: none; padding: 0; color: inherit; }
.vn-body table { width: 100%; border-collapse: collapse; margin: 12px 0; }
.vn-body table th { background: #7c3aed; color: #fff; padding: 10px 14px; font-size: 13px; text-align: center; }
.vn-body table td { padding: 9px 14px; border-bottom: 1px solid #f0f0f0; font-size: 13.5px; text-align: center; }
.vn-body table tbody tr:nth-child(even) { background: #f8fafc; }
.vn-body a { color: #7c3aed; }
.vn-body h2, .vn-body h3 { color: #7c3aed; font-weight: 700; margin-top: 24px; }
.vn-footer {
  padding: 16px 32px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  gap: 10px;
}
.vn-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: 8px;
  font-size: 13px; font-weight: 600;
  text-decoration: none; transition: background 0.15s;
}
.vn-btn-back { background: #f0f3f7; color: #555; }
.vn-btn-back:hover { background: #e2e8f0; color: #333; text-decoration: none; }
</style>
</head>
<body>
<script src="<?php echo "template/bs3/"?>marked.min.js"></script>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="vn-wrap">
  <div class="vn-card">
    <div class="vn-header">
      <h1>📢 <?php echo isset($news_title)?htmlspecialchars($news_title):'공지사항'?></h1>
    </div>
    <div class="vn-body">
      <div id="content" class="md"><?php echo bbcode_to_html($news_content)?></div>
    </div>
    <div class="vn-footer">
      <a href="javascript:history.back()" class="vn-btn vn-btn-back">← 뒤로 가기</a>
    </div>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
$(document).ready(function(){
  marked.use({ async: false, pedantic: false, gfm: true, mangle: false, headerIds: false });
  $(".md").each(function(){
    var raw = $(this).html();
    try { $(this).html(marked.parse(raw)); } catch(e) {}
  });
  for(var i=1;i<10;i++){
    $(".language-input"+i).parent().before("<div style='font-size:12px;color:#888;margin-top:8px'>입력 예"+i+":</div>");
    $(".language-output"+i).parent().before("<div style='font-size:12px;color:#888;margin-top:8px'>출력 예"+i+":</div>");
  }
});
</script>
</body>
</html>
