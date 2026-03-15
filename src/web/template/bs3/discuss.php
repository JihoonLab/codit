<!-- /web/template/bs3/discuss.php -->
<?php $show_title="$MSG_BBS - $OJ_NAME"; ?>
<?php
$view_discuss=ob_get_contents();
ob_end_clean();
require_once(dirname(__FILE__)."/../../lang/$OJ_LANG.php");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $MSG_BBS?> - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.dc-wrap { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }
.dc-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.dc-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.dc-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
  padding: 28px 32px;
  font-size: 15px;
  line-height: 1.9;
  color: #333;
}
/* BBS 내부 스타일 정비 */
.dc-card table { width: 100%; border-collapse: collapse; }
.dc-card table thead tr { background: #7c3aed; color: #fff; }
.dc-card table th { padding: 11px 14px; font-size: 13px; font-weight: 600; text-align: left; }
.dc-card table td { padding: 11px 14px; font-size: 13px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.dc-card table tbody tr:hover { background: #f5f8ff; }
.dc-card table tbody tr:last-child td { border-bottom: none; }
.dc-card table td a { color: #7c3aed; text-decoration: none; }
.dc-card table td a:hover { text-decoration: underline; }
.dc-card .form-control { border: 1.5px solid #e0e4ea; border-radius: 7px; padding: 8px 14px; font-size: 14px; }
.dc-card .btn, .dc-card button[type=submit] {
  background: #7c3aed; color: #fff; border: none;
  padding: 9px 20px; border-radius: 7px; font-size: 14px; font-weight: 600;
  cursor: pointer; text-decoration: none; display: inline-block;
}
.dc-card .btn:hover, .dc-card button[type=submit]:hover { background: #6d28d9; }
.dc-card .glyphicon-tags::before { content: "📌 "; }
.dc-card .glyphicon-tag::before  { content: "🔖 "; }
.dc-card .toprow { background: #7c3aed; color: #fff; }
.dc-card .evenrow { background: #fff; }
.dc-card .oddrow  { background: #f8fafc; }
.dc-card h1 { font-size: 20px; font-weight: 700; color: #7c3aed; margin: 0 0 20px; }
.dc-card .panel { border: 1px solid #e5e9f0; border-radius: 8px; overflow: hidden; margin-bottom: 12px; }
.dc-card .panel-heading { background: #f8fafc; padding: 12px 16px; font-weight: 600; font-size: 14px; border-bottom: 1px solid #e5e9f0; }
.dc-card .panel-body { padding: 16px; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="dc-wrap">
  <div class="dc-header">
    <span style="font-size:24px">💬</span>
    <h2><?php echo $MSG_BBS?></h2>
  </div>
  <div class="dc-card">
    <?php include("include/bbcode.php");?>
    <?php echo $view_discuss?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
