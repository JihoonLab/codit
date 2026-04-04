<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>로그인 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&family=Fira+Code:wght@400;500&display=swap');
html, body { margin: 0 !important; padding: 0 !important; background: #0a0e1a !important; overflow-x: hidden; min-height: 100vh; }

.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  font-family: 'Inter','Noto Sans KR',sans-serif;
}

/* === Animated gradient blobs === */
.bg-blob {
  position: fixed; border-radius: 50%; filter: blur(100px); pointer-events: none; opacity: 0.5;
}
.bg-blob-1 {
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(124,58,237,0.35), transparent 70%);
  top: -10%; left: -5%;
  animation: blobFloat1 12s ease-in-out infinite;
}
.bg-blob-2 {
  width: 400px; height: 400px;
  background: radial-gradient(circle, rgba(59,130,246,0.25), transparent 70%);
  bottom: -10%; right: -5%;
  animation: blobFloat2 15s ease-in-out infinite;
}
.bg-blob-3 {
  width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(168,85,247,0.2), transparent 70%);
  top: 50%; left: 50%; transform: translate(-50%,-50%);
  animation: blobFloat3 10s ease-in-out infinite;
}
@keyframes blobFloat1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,30px)} }
@keyframes blobFloat2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-30px,-40px)} }
@keyframes blobFloat3 { 0%,100%{transform:translate(-50%,-50%) scale(1)} 50%{transform:translate(-50%,-50%) scale(1.15)} }

/* === Code rain === */
.code-bg {
  position: fixed; inset: 0; pointer-events: none;
  display: flex; justify-content: space-between;
  opacity: 0.18;
  font-family: 'Fira Code', monospace;
  font-size: 15px; color: #c4b5fd; line-height: 1.9;
  white-space: pre; overflow: hidden;
}
.code-col { width: 420px; overflow: hidden; flex-shrink: 0; }
.code-col:nth-child(1) { padding-left: 32px; }
.code-col:nth-child(2) { padding-right: 32px; }
.code-scroll { animation: codeUp 28s linear infinite; }
.code-col:nth-child(2) .code-scroll { animation-duration: 35s; }
@keyframes codeUp { 0%{transform:translateY(0)} 100%{transform:translateY(-50%)} }

/* === Glass card === */
.login-box {
  background: rgba(255,255,255,0.06);
  backdrop-filter: blur(24px) saturate(1.4);
  -webkit-backdrop-filter: blur(24px) saturate(1.4);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 28px;
  padding: 48px 44px 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 32px 80px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
  position: relative;
  z-index: 1;
  animation: cardIn 0.6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes cardIn { from { opacity:0; transform:translateY(24px) scale(0.96); } to { opacity:1; transform:translateY(0) scale(1); } }

/* top accent line */
.login-box::before {
  content: '';
  position: absolute; top: 0; left: 40px; right: 40px; height: 3px;
  background: linear-gradient(90deg, #7c3aed, #a855f7, #3b82f6);
  border-radius: 0 0 4px 4px;
}

.login-logo {
  text-align: center;
  margin-bottom: 36px;
}
.login-logo .brand {
  font-size: 38px;
  font-weight: 900;
  letter-spacing: -1.5px;
  background: linear-gradient(135deg, #c4b5fd, #a78bfa, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.login-logo .sub {
  font-size: 14px;
  color: rgba(255,255,255,0.45);
  margin-top: 8px;
  font-weight: 500;
  letter-spacing: 0.5px;
}
.login-logo .sub .oj {
  background: linear-gradient(135deg, #a78bfa, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
}

.login-field {
  margin-bottom: 20px;
}
.login-field label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: rgba(255,255,255,0.5);
  margin-bottom: 8px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
.login-field input {
  width: 100%;
  padding: 14px 18px;
  border: 1.5px solid rgba(255,255,255,0.1);
  border-radius: 14px;
  font-size: 15px;
  outline: none;
  box-sizing: border-box;
  transition: all 0.25s;
  background: rgba(255,255,255,0.05);
  color: #fff;
  font-family: 'Inter','Noto Sans KR',sans-serif;
}
.login-field input::placeholder { color: rgba(255,255,255,0.2); }
.login-field input:focus {
  border-color: rgba(124,58,237,0.6);
  background: rgba(255,255,255,0.08);
  box-shadow: 0 0 0 3px rgba(124,58,237,0.15), 0 4px 16px rgba(124,58,237,0.1);
}

.login-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff;
  border: none;
  border-radius: 14px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 10px;
  transition: all 0.3s;
  letter-spacing: 0.5px;
  font-family: 'Noto Sans KR',sans-serif;
  position: relative;
  overflow: hidden;
}
.login-btn::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
  transform: translateX(-100%);
  transition: transform 0.5s;
}
.login-btn:hover::before { transform: translateX(100%); }
.login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(124,58,237,0.5), 0 0 60px rgba(124,58,237,0.15);
}
.login-btn:active { transform: translateY(0); }

.login-footer {
  text-align: center;
  margin-top: 24px;
}
.login-footer a {
  font-size: 13px;
  color: rgba(255,255,255,0.35);
  text-decoration: none;
  transition: color 0.2s;
}
.login-footer a:hover { color: #a78bfa; }
.login-footer strong { color: #a78bfa; }

.vcode-row { display: flex; gap: 10px; align-items: center; }
.vcode-row input { flex: 1; }
.vcode-row img { height: 46px; border-radius: 10px; cursor: pointer; border: 1.5px solid rgba(255,255,255,0.1); }

/* floating particles */
.particles { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
.particle {
  position: absolute;
  width: 3px; height: 3px;
  background: rgba(167,139,250,0.4);
  border-radius: 50%;
  animation: particleFloat linear infinite;
}
@keyframes particleFloat {
  0% { transform: translateY(100vh) scale(0); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(-10vh) scale(1); opacity: 0; }
}

/* === Footer override for dark theme === */
.codit-global-footer {
  background: transparent !important;
  border-top: 1px solid rgba(255,255,255,0.06) !important;
  margin-top: 0 !important;
  color: rgba(255,255,255,0.3) !important;
}
.codit-global-footer a { color: rgba(167,139,250,0.6) !important; }
.codit-global-footer a:hover { color: #a78bfa !important; }
.cgf-sep { color: rgba(255,255,255,0.12) !important; }

@media (max-width: 500px) {
  .login-box { margin: 16px; padding: 36px 28px 32px; }
  .code-col { width: 100px; font-size: 10px; }
}
  </style>
</head>
<body>
<div class="login-page">
  <!-- Animated blobs -->
  <div class="bg-blob bg-blob-1"></div>
  <div class="bg-blob bg-blob-2"></div>
  <div class="bg-blob bg-blob-3"></div>

  <!-- Floating particles -->
  <div class="particles" id="particles"></div>

  <!-- Code animation background -->
  <div class="code-bg">
    <div class="code-col"><div class="code-scroll">#include &lt;stdio.h&gt;
int main() {
    int n;
    scanf("%d", &n);
    for(int i=1; i&lt;=n; i++)
        printf("%d\n", i);
    return 0;
}

def solution(n):
    result = []
    for i in range(n):
        result.append(i*i)
    return sum(result)

int gcd(int a, int b) {
    while(b) {
        int t = b;
        b = a % b;
        a = t;
    }
    return a;
}

#include &lt;stdio.h&gt;
int main() {
    int n;
    scanf("%d", &n);
    for(int i=1; i&lt;=n; i++)
        printf("%d\n", i);
    return 0;
}

def solution(n):
    result = []
    for i in range(n):
        result.append(i*i)
    return sum(result)</div></div>
    <div class="code-col"><div class="code-scroll">def fibonacci(n):
    if n &lt;= 1:
        return n
    a, b = 0, 1
    for _ in range(2, n+1):
        a, b = b, a + b
    return b

void quickSort(int a[],
    int lo, int hi) {
    if(lo &lt; hi) {
        int p = partition(
            a, lo, hi);
        quickSort(a,lo,p-1);
        quickSort(a,p+1,hi);
    }
}

arr = sorted(arr)
left = 0
right = len(arr) - 1
while left &lt; right:
    s = arr[left]+arr[right]
    if s == target:
        return True

def fibonacci(n):
    if n &lt;= 1:
        return n
    a, b = 0, 1
    for _ in range(2, n+1):
        a, b = b, a + b
    return b</div></div>
  </div>

  <div class="login-box">
    <div class="login-logo">
      <div class="brand"><?php echo $OJ_NAME?></div>
      <div class="sub">Chungju Highschool <span class="oj">Online Judge</span></div>
    </div>
    <form id="login" action="login.php" method="post" onsubmit="return jsMd5();">
      <div class="login-field">
        <label><?php echo $MSG_USER_ID?></label>
        <input name="user_id" type="text" placeholder="사용자 ID를 입력하세요" required autocomplete="username">
      </div>
      <div class="login-field">
        <label><?php echo $MSG_PASSWORD?></label>
        <input name="password" type="password" placeholder="비밀번호를 입력하세요" required autocomplete="current-password">
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
    <div class="login-footer">
      <a href="registerpage.php">계정이 없으신가요? <strong>회원가입</strong></a>
    </div>
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
// Generate floating particles
(function(){
  var c = document.getElementById('particles');
  for(var i=0;i<20;i++){
    var p = document.createElement('div');
    p.className='particle';
    p.style.left = Math.random()*100+'%';
    p.style.animationDuration = (8+Math.random()*12)+'s';
    p.style.animationDelay = Math.random()*10+'s';
    p.style.width = p.style.height = (2+Math.random()*3)+'px';
    c.appendChild(p);
  }
})();
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
