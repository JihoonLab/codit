<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>비밀번호 재설정 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.lp-wrap { max-width: 480px; margin: 80px auto; padding: 0 20px; }
.lp-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; }
.lp-header { background: linear-gradient(135deg, #059669, #047857); padding: 28px 32px; text-align: center; color: #fff; }
.lp-header h2 { margin: 0; font-size: 22px; font-weight: 700; }
.lp-header p { margin: 8px 0 0; font-size: 13px; opacity: 0.85; }
.lp-body { padding: 28px 32px; }
.lp-group { margin-bottom: 18px; }
.lp-label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
.lp-input { width: 100%; padding: 10px 14px; border: 1.5px solid #e0e4ea; border-radius: 8px; font-size: 14px; font-family: 'Noto Sans KR', sans-serif; outline: none; transition: border-color 0.15s; }
.lp-input:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }
.lp-vcode-row { display: flex; gap: 10px; align-items: center; }
.lp-vcode-row .lp-input { flex: 1; }
.lp-vcode-row img { border-radius: 6px; cursor: pointer; height: 38px; }
.lp-hint { font-size: 12px; color: #888; margin-top: 4px; }
.lp-btn { width: 100%; padding: 12px; background: #059669; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: 'Noto Sans KR', sans-serif; transition: background 0.15s; margin-top: 4px; }
.lp-btn:hover { background: #047857; }
.lp-back { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: #888; }
.lp-back a { color: #7c3aed; text-decoration: none; }
.lp-back a:hover { text-decoration: underline; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="lp-wrap">
  <div class="lp-card">
    <div class="lp-header">
      <div style="font-size:36px;margin-bottom:8px">🔐</div>
      <h2>비밀번호 재설정</h2>
      <p>이메일로 받은 키와 새 비밀번호를 입력하세요</p>
    </div>
    <div class="lp-body">
      <form action="lostpassword2.php" method="post">
        <div class="lp-group">
          <label class="lp-label">사용자 ID</label>
          <input class="lp-input" name="user_id" type="text" autocomplete="username" placeholder="아이디 입력">
        </div>
        <div class="lp-group">
          <label class="lp-label">이메일로 받은 키</label>
          <input class="lp-input" name="lost_key" type="text" autocomplete="off" placeholder="이메일에서 복사한 키 입력">
          <div class="lp-hint">* 이 키가 새 비밀번호가 됩니다</div>
        </div>
        <div class="lp-group">
          <label class="lp-label">보안 코드</label>
          <div class="lp-vcode-row">
            <input class="lp-input" name="vcode" type="text" autocomplete="off" placeholder="코드 입력" maxlength="6">
            <img src="vcode.php" alt="보안코드" onclick="this.src='vcode.php#'+Math.random()">
          </div>
        </div>
        <button class="lp-btn" type="submit">✅ 비밀번호 재설정</button>
      </form>
      <div class="lp-back">
        <a href="loginpage.php">← 로그인으로 돌아가기</a>
      </div>
    </div>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
