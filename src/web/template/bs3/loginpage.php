<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>로그인 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
body { margin: 0; background: #f0f4f8; }
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #7c3aed 0%, #0d3d6e 100%);
  position: relative;
  overflow: hidden;
}
.login-page::before {
  content: '';
  position: absolute;
  width: 600px; height: 600px;
  background: rgba(255,255,255,0.04);
  border-radius: 50%;
  top: -150px; right: -150px;
}
.login-page::after {
  content: '';
  position: absolute;
  width: 400px; height: 400px;
  background: rgba(255,255,255,0.04);
  border-radius: 50%;
  bottom: -100px; left: -100px;
}
.login-box {
  background: #fff;
  border-radius: 20px;
  padding: 48px 44px 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
  position: relative;
  z-index: 1;
}
.login-logo {
  text-align: center;
  margin-bottom: 32px;
}
.login-logo .brand {
  font-size: 36px;
  font-weight: 900;
  color: #7c3aed;
  letter-spacing: -1px;
}
.login-logo .sub {
  font-size: 14px;
  color: #999;
  margin-top: 4px;
}
.login-field {
  margin-bottom: 16px;
}
.login-field label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #555;
  margin-bottom: 7px;
}
.login-field input {
  width: 100%;
  padding: 13px 16px;
  border: 1.5px solid #e0e4ea;
  border-radius: 10px;
  font-size: 15px;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s;
  background: #f8fafc;
}
.login-field input:focus {
  border-color: #7c3aed;
  background: #fff;
}
.login-btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 8px;
  transition: all 0.2s;
  letter-spacing: 0.5px;
}
.login-btn:hover {
  background: linear-gradient(135deg, #6d28d9, #0a4a8f);
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(26,111,196,0.35);
}
.login-footer {
  text-align: center;
  margin-top: 20px;
}
.login-footer a {
  font-size: 13px;
  color: #aaa;
  text-decoration: none;
}
.login-footer a:hover { color: #7c3aed; }
.vcode-row { display: flex; gap: 10px; align-items: center; }
.vcode-row input { flex: 1; }
.vcode-row img { height: 40px; border-radius: 6px; cursor: pointer; border: 1px solid #e0e4ea; }
  </style>
</head>
<body>
<div class="login-page">
  <div class="login-box">
    <div class="login-logo">
      <div class="brand"><?php echo $OJ_NAME?></div>
      <div class="sub">Chungju High School Online Judge</div>
    </div>
    <form id="login" action="login.php" method="post" onsubmit="return jsMd5();">
      <div class="login-field">
        <label><?php echo $MSG_USER_ID?></label>
        <input name="user_id" type="text" placeholder="사용자 ID" required autocomplete="username">
      </div>
      <div class="login-field">
        <label><?php echo $MSG_PASSWORD?></label>
        <input name="password" type="password" placeholder="비밀번호" required autocomplete="current-password">
      </div>
      <?php if($OJ_VCODE):?>
      <div class="login-field">
        <label><?php echo $MSG_VCODE?></label>
        <div class="vcode-row">
          <input name="vcode" type="text" placeholder="보안문자" autocomplete="off">
          <img id="vcode-img" alt="클릭하여 새로고침" onclick="this.src='vcode.php?'+Math.random()">
        </div>
      </div>
      <?php endif;?>
      <button class="login-btn" type="submit">로그인</button>
    </form>

  </div>
</div>
<script src="<?php echo $OJ_CDN_URL?>include/md5-min.js"></script>
<script>
function jsMd5(){
  var pw = document.querySelector("input[name=password]");
  if(pw.value=="") return false;
  pw.value = hex_md5(pw.value);
  return true;
}
</script>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<?php if($OJ_VCODE):?>
<script>
$(document).ready(function(){
  $("#vcode-img").attr("src","vcode.php?"+Math.random());
});
</script>
<?php endif;?>
</body>
</html>
