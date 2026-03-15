<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="10; url='printer.php'">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>프린터 목록 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.pr-wrap { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }
.pr-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.pr-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; flex: 1; }
.pr-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; }
.pr-toolbar { padding: 14px 20px; border-bottom: 1px solid #f0f0f0; }
.pr-btn-danger { background: #dc2626; color: #fff; border: none; padding: 8px 18px; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; }
.pr-table { width: 100%; border-collapse: collapse; }
.pr-table thead tr { background: #7c3aed; color: #fff; }
.pr-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; }
.pr-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.pr-table tbody tr:hover { background: #f5f8ff; }
.pr-table tbody tr:last-child td { border-bottom: none; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="pr-wrap">
  <div class="pr-header">
    <span style="font-size:24px">🖨️</span>
    <h2>프린터 대기 목록</h2>
  </div>
  <div class="pr-card">
    <div class="pr-toolbar">
      <form action="printer.php" method="post" onsubmit="return confirm('모든 작업을 삭제하시겠습니까?')">
        <input type="hidden" name="clean">
        <?php require_once(dirname(__FILE__)."/../../include/set_post_key.php")?>
        <button class="pr-btn-danger" type="submit">🗑 전체 삭제</button>
      </form>
    </div>
    <table class="pr-table">
      <thead><tr><th>ID</th><th><?php echo $MSG_USER_ID?></th><th><?php echo $MSG_STATUS?></th><th></th></tr></thead>
      <tbody>
      <?php foreach($view_printer as $row): ?>
      <tr><?php foreach($row as $cell): ?><td><?php echo $cell?></td><?php endforeach; ?></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
