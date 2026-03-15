<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>쪽지 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.mail-wrap { max-width: 860px; margin: 32px auto; padding: 0 20px 60px; }
.mail-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.mail-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.mail-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; margin-bottom: 20px; }
.mail-card-head { background: #f8fafc; border-bottom: 1px solid #e5e9f0; padding: 14px 20px; font-size: 14px; font-weight: 700; color: #7c3aed; }
.mail-card-body { padding: 20px; }
/* 이전 쪽지 */
.mail-prev { background: #f8fafc; border: 1px solid #e5e9f0; border-radius: 8px; padding: 14px 18px; margin-bottom: 16px; font-family: monospace; font-size: 13.5px; white-space: pre-wrap; }
/* 폼 */
.mail-row { display: flex; gap: 10px; margin-bottom: 12px; align-items: center; flex-wrap: wrap; }
.mail-row label { font-size: 13px; font-weight: 600; color: #555; width: 50px; flex-shrink: 0; }
.mail-input { flex: 1; padding: 9px 14px; border: 1.5px solid #e0e4ea; border-radius: 7px; font-size: 14px; outline: none; }
.mail-input:focus { border-color: #7c3aed; }
.mail-textarea { width: 100%; padding: 12px 14px; border: 1.5px solid #e0e4ea; border-radius: 7px; font-size: 14px; outline: none; resize: vertical; min-height: 120px; font-family: 'Noto Sans KR', sans-serif; }
.mail-textarea:focus { border-color: #7c3aed; }
.mail-btn { padding: 10px 24px; background: #7c3aed; color: #fff; border: none; border-radius: 7px; font-size: 14px; font-weight: 600; cursor: pointer; }
.mail-btn:hover { background: #6d28d9; }
/* 목록 */
.mail-table { width: 100%; border-collapse: collapse; }
.mail-table thead tr { background: #7c3aed; color: #fff; }
.mail-table th { padding: 11px 14px; font-size: 13px; font-weight: 600; }
.mail-table td { padding: 10px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.mail-table tbody tr:hover { background: #f5f8ff; }
.mail-table td a { color: #7c3aed; text-decoration: none; }
.mail-table td a:hover { text-decoration: underline; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="mail-wrap">
  <div class="mail-header">
    <span style="font-size:24px">✉️</span>
    <h2>쪽지</h2>
  </div>

  <?php if($view_content): ?>
  <div class="mail-card">
    <div class="mail-card-head">📩 받은 쪽지</div>
    <div class="mail-card-body">
      <div class="mail-prev"><?php echo htmlspecialchars(str_replace("\n\r","\n",$view_title), ENT_QUOTES, 'UTF-8')?>

<?php echo htmlspecialchars(str_replace("\n\r","\n",$view_content), ENT_QUOTES, 'UTF-8')?></div>
    </div>
  </div>
  <?php endif; ?>

  <div class="mail-card">
    <div class="mail-card-head">✏️ 쪽지 쓰기</div>
    <div class="mail-card-body">
      <form method="post" action="mail.php">
        <div class="mail-row">
          <label>보내는 이</label>
          <span style="font-size:14px;color:#555"><?php echo htmlspecialchars($from_user)?></span>
        </div>
        <div class="mail-row">
          <label>받는 이</label>
          <input class="mail-input" name="to_user" type="text" value="<?php echo htmlspecialchars(($from_user==$_SESSION[$OJ_NAME.'_user_id']||$from_user=='')?$to_user:$from_user)?>">
        </div>
        <div class="mail-row">
          <label>제목</label>
          <input class="mail-input" name="title" type="text" value="<?php echo htmlspecialchars($title)?>">
        </div>
        <textarea class="mail-textarea" name="content" placeholder="내용을 입력하세요..."></textarea>
        <div style="margin-top:12px">
          <button class="mail-btn" type="submit"><?php echo $MSG_SUBMIT?></button>
        </div>
      </form>
    </div>
  </div>

  <div class="mail-card">
    <div class="mail-card-head">📋 쪽지 목록</div>
    <table class="mail-table">
      <thead><tr><th>ID</th><th>발신/제목</th><th>날짜</th></tr></thead>
      <tbody>
      <?php foreach($view_mail as $row): ?>
      <tr><?php foreach($row as $cell): ?><td><?php echo $cell?></td><?php endforeach; ?></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
