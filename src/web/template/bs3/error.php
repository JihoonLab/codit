<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>오류 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.err-wrap {
  max-width: 680px;
  margin: 80px auto;
  padding: 0 20px;
  text-align: center;
}
.err-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 16px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.08);
  overflow: hidden;
}
.err-card-header {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  padding: 32px 24px 24px;
}
.err-icon { font-size: 48px; line-height: 1; }
.err-card-body { padding: 28px 32px 32px; }
.err-title {
  font-size: 22px;
  font-weight: 700;
  color: #222;
  margin: 0 0 16px;
}
.err-message {
  font-size: 15px;
  color: #555;
  line-height: 1.7;
  margin-bottom: 24px;
}
.err-message a {
  color: #7c3aed;
  font-weight: 600;
  text-decoration: none;
}
.err-message a:hover { text-decoration: underline; }
.err-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 24px;
  background: #7c3aed;
  color: #fff;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.15s;
}
.err-btn:hover { background: #6d28d9; color: #fff; text-decoration: none; }
</style>
</head>
<body>
<?php if(isset($OJ_MEMCACHE)) include("template/$OJ_TEMPLATE/nav.php");?>
<div class="err-wrap">
  <div class="err-card">
    <div class="err-card-header">
      <div class="err-icon">⚠️</div>
    </div>
    <div class="err-card-body">
      <h2 class="err-title">오류가 발생했습니다</h2>
      <div class="err-message">
        <?php echo $view_errors?>
      </div>
      <a href="javascript:history.back()" class="err-btn">← 뒤로 가기</a>
    </div>
  </div>
</div>
<iframe src="refresh-privilege.php" height="0" width="0" style="display:none"></iframe>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
