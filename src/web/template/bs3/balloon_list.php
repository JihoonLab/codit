<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="10; url='balloon.php?cid=<?php echo $cid?>'">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>풍선 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.bl-wrap { max-width: 800px; margin: 32px auto; padding: 0 20px 60px; }
.bl-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.bl-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; flex: 1; }
.bl-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; margin-bottom: 20px; }
.bl-toolbar { padding: 14px 20px; border-bottom: 1px solid #f0f0f0; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.bl-input { padding: 8px 14px; border: 1.5px solid #e0e4ea; border-radius: 7px; font-size: 14px; outline: none; }
.bl-input:focus { border-color: #7c3aed; }
.bl-btn { padding: 8px 18px; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; }
.bl-btn-primary { background: #7c3aed; color: #fff; }
.bl-btn-danger  { background: #dc2626; color: #fff; }
.bl-table { width: 100%; border-collapse: collapse; }
.bl-table thead tr { background: #7c3aed; color: #fff; }
.bl-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; text-align: left; }
.bl-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.bl-table tbody tr:hover { background: #f5f8ff; }
.bl-table tbody tr:last-child td { border-bottom: none; }
.bl-empty { text-align: center; padding: 32px; color: #aaa; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="bl-wrap">
  <div class="bl-header">
    <span style="font-size:24px">🎈</span>
    <h2><?php echo $MSG_BALLOON?> - 대회 #<?php echo $cid?></h2>
  </div>
  <div class="bl-card">
    <div class="bl-toolbar">
      <form action="balloon.php" method="get" style="display:flex;gap:8px;align-items:center">
        <label style="font-size:13px;font-weight:600;color:#555">대회 ID</label>
        <input class="bl-input" type="text" name="cid" value="<?php echo $cid?>" style="width:80px">
        <button class="bl-btn bl-btn-primary" type="submit">조회</button>
      </form>
      <form action="balloon.php?cid=<?php echo $cid?>" method="post" style="margin-left:auto"
            onsubmit="return confirm('모든 작업을 삭제하시겠습니까?')">
        <input type="hidden" name="cid" value="<?php echo $cid?>">
        <input type="hidden" name="clean">
        <?php require_once(dirname(__FILE__)."/../../include/set_post_key.php")?>
        <button class="bl-btn bl-btn-danger" type="submit">🗑 전체 삭제</button>
      </form>
    </div>
    <table class="bl-table">
      <thead><tr><th>ID</th><th><?php echo $MSG_USER_ID?></th><th><?php echo $MSG_COLOR?></th><th><?php echo $MSG_STATUS?></th><th>-</th></tr></thead>
      <tbody>
      <?php if(empty($view_balloon)): ?>
        <tr><td colspan="5" class="bl-empty">배달할 풍선이 없습니다 🎈</td></tr>
      <?php else: foreach($view_balloon as $row): ?>
      <tr><?php foreach($row as $cell): ?><td><?php echo $cell?></td><?php endforeach; ?></tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
