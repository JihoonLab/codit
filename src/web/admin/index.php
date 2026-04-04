<?php
require_once("../include/db_info.inc.php");
?><html>
<head>
<title><?php echo $OJ_NAME.$MSG_ADMIN?></title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  html, body { height:100%; overflow:hidden; }
</style>
</head>
<frameset cols="260,*" frameborder="0" border="0" framespacing="0">
  <frame name="menu" src="menu2.php" scrolling="auto" noresize>
  <frame name="main" src="help.php" scrolling="auto">
  <noframes></noframes>
</frameset>
</html>
