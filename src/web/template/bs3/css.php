<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
<?php 

	$dir=basename(getcwd());
	if($dir=="discuss3"||$dir=="admin") $path_fix="../";
	else $path_fix="";
?>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo $path_fix?>favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $path_fix?>favicon-32.png">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo $path_fix?>favicon-192.png">
<link rel="apple-touch-icon" href="<?php echo $path_fix?>favicon-192.png">
<link rel="stylesheet" href="<?php echo $path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.css">

<?php if(!isset($OJ_FLAT)||!$OJ_FLAT){?>
<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>bootstrap-theme.min.css">
<?php }?>
<link rel="stylesheet" href="<?php echo $path_fix."template/$OJ_TEMPLATE/$OJ_CSS"?>?v=0.1">
<link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>katex.min.css">
<link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>mathjax.css">

