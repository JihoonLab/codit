<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>카테고리 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.cat-wrap { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }
.cat-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.cat-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.cat-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
  padding: 28px 32px;
  font-size: 15px;
  line-height: 1.9;
}
.cat-card a { color: #7c3aed; text-decoration: none; }
.cat-card a:hover { text-decoration: underline; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cat-wrap">
  <div class="cat-header">
    <span style="font-size:24px">🏷️</span>
    <h2><?php echo $MSG_SOURCE?></h2>
  </div>
  <div class="cat-card">
    <?php echo $view_category?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
