<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>프린터 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.pr-wrap { max-width: 800px; margin: 60px auto; padding: 0 20px; }
.pr-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; }
.pr-card-head { background: #7c3aed; color: #fff; padding: 18px 24px; font-size: 18px; font-weight: 700; }
.pr-card-body { padding: 24px; }
.pr-textarea { width: 100%; min-height: 280px; padding: 12px; border: 1.5px solid #e0e4ea; border-radius: 8px; font-size: 14px; resize: vertical; margin-bottom: 14px; font-family: monospace; outline: none; }
.pr-textarea:focus { border-color: #7c3aed; }
.pr-btn { padding: 10px 28px; background: #7c3aed; color: #fff; border: none; border-radius: 7px; font-size: 14px; font-weight: 600; cursor: pointer; }
.pr-btn:hover { background: #6d28d9; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="pr-wrap">
  <div class="pr-card">
    <div class="pr-card-head">🖨️ 코드 인쇄</div>
    <div class="pr-card-body">
      <form id="frmSolution" action="printer.php" method="post">
        <textarea class="pr-textarea" id="source" name="content" placeholder="인쇄할 코드를 입력하세요..."></textarea>
        <?php require_once(dirname(__FILE__)."/../../include/set_post_key.php")?>
        <button class="pr-btn" type="submit"><?php echo $MSG_PRINTER?></button>
      </form>
    </div>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
