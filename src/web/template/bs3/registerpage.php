<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>회원가입 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&family=Fira+Code:wght@400;500&display=swap');
html, body { margin: 0 !important; padding: 0 !important; background: #0a0e1a !important; overflow-x: hidden; min-height: 100vh; }

.reg-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  padding: 40px 16px;
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
.reg-box {
  background: rgba(255,255,255,0.06);
  backdrop-filter: blur(24px) saturate(1.4);
  -webkit-backdrop-filter: blur(24px) saturate(1.4);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 28px;
  padding: 44px 44px 36px;
  width: 100%;
  max-width: 480px;
  box-shadow: 0 32px 80px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
  position: relative;
  z-index: 1;
  animation: cardIn 0.6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes cardIn { from { opacity:0; transform:translateY(24px) scale(0.96); } to { opacity:1; transform:translateY(0) scale(1); } }

.reg-box::before {
  content: '';
  position: absolute; top: 0; left: 40px; right: 40px; height: 3px;
  background: linear-gradient(90deg, #7c3aed, #a855f7, #3b82f6);
  border-radius: 0 0 4px 4px;
}

.reg-logo {
  text-align: center;
  margin-bottom: 28px;
}
.reg-logo .brand {
  font-size: 34px;
  font-weight: 900;
  letter-spacing: -1.5px;
  background: linear-gradient(135deg, #c4b5fd, #a78bfa, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.reg-logo .sub {
  font-size: 14px;
  color: rgba(255,255,255,0.45);
  margin-top: 8px;
  font-weight: 500;
  letter-spacing: 0.5px;
}
.reg-logo .sub .oj {
  background: linear-gradient(135deg, #a78bfa, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
  text-transform: uppercase;
}

.reg-notice {
  background: rgba(124,58,237,0.12);
  border: 1px solid rgba(124,58,237,0.2);
  border-radius: 14px;
  padding: 13px 16px;
  margin-bottom: 26px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 12.5px;
  color: rgba(199,175,255,0.9);
  font-weight: 500;
  line-height: 1.5;
}
.reg-notice .ni { font-size: 18px; flex-shrink: 0; }

.reg-field {
  margin-bottom: 18px;
}
.reg-field label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: rgba(255,255,255,0.5);
  margin-bottom: 8px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
.reg-field label .req { color: #f87171; margin-left: 2px; }
.reg-field input, .reg-field select {
  width: 100%;
  padding: 13px 16px;
  border: 1.5px solid rgba(255,255,255,0.1);
  border-radius: 14px;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: all 0.25s;
  background: rgba(255,255,255,0.05);
  color: #fff;
  font-family: 'Inter','Noto Sans KR',sans-serif;
  -webkit-appearance: none;
}
.reg-field input::placeholder { color: rgba(255,255,255,0.2); }

/* ID check button */
.id-check-btn {
  padding: 13px 18px;
  border: 1.5px solid rgba(124,58,237,0.4);
  border-radius: 14px;
  background: rgba(124,58,237,0.15);
  color: #c4b5fd;
  font-size: 13px;
  font-weight: 700;
  font-family: 'Noto Sans KR',sans-serif;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.25s;
  flex-shrink: 0;
}
.id-check-btn:hover {
  background: rgba(124,58,237,0.3);
  border-color: rgba(124,58,237,0.6);
  color: #fff;
  box-shadow: 0 0 16px rgba(124,58,237,0.2);
}
.id-msg {
  font-size: 12px;
  margin-top: 8px;
  font-weight: 600;
  min-height: 0;
  transition: all 0.2s;
  overflow: hidden;
}
.id-msg:empty { margin-top: 0; }
.id-msg.ok { color: #34d399; }
.id-msg.fail { color: #f87171; }
.id-msg.warn { color: #fbbf24; }

/* Shake animation */
@keyframes fieldShake {
  0%,100% { transform: translateX(0); }
  15% { transform: translateX(-6px); }
  30% { transform: translateX(6px); }
  45% { transform: translateX(-4px); }
  60% { transform: translateX(4px); }
  75% { transform: translateX(-2px); }
  90% { transform: translateX(2px); }
}
.reg-field.shake, .privacy-row.shake, .select-row.shake {
  animation: fieldShake 0.5s ease;
}
.reg-field.shake input, .reg-field.shake .cdrop-toggle, .select-row.shake .cdrop-toggle {
  border-color: rgba(248,113,113,0.6) !important;
  box-shadow: 0 0 0 3px rgba(248,113,113,0.15) !important;
}
.privacy-row.shake {
  border-color: rgba(248,113,113,0.5) !important;
  background: rgba(248,113,113,0.08) !important;
}
.field-error {
  font-size: 12px; color: #f87171; font-weight: 600;
  margin-top: 8px;
  animation: fadeIn 0.3s ease;
}
@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }
.reg-field input:focus, .reg-field select:focus {
  border-color: rgba(124,58,237,0.6);
  background: rgba(255,255,255,0.08);
  box-shadow: 0 0 0 3px rgba(124,58,237,0.15), 0 4px 16px rgba(124,58,237,0.1);
}


.select-row {
  display: flex; gap: 10px; align-items: center;
}
.select-sep {
  font-weight: 800; color: rgba(255,255,255,0.2); font-size: 16px; flex-shrink: 0;
}

/* Custom dropdown */
.cdrop { position: relative; flex: 1; }
.cdrop-toggle {
  width: 100%;
  padding: 13px 38px 13px 16px;
  border: 1.5px solid rgba(255,255,255,0.1);
  border-radius: 14px;
  font-size: 14px;
  background: rgba(255,255,255,0.05);
  color: rgba(255,255,255,0.35);
  font-family: 'Noto Sans KR',sans-serif;
  cursor: pointer;
  transition: all 0.25s;
  position: relative;
  user-select: none;
  box-sizing: border-box;
}
.cdrop-toggle.has-value { color: #fff; }
.cdrop-toggle::after {
  content: '';
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  width: 0; height: 0;
  border-left: 5px solid transparent; border-right: 5px solid transparent;
  border-top: 5px solid rgba(255,255,255,0.3);
  transition: transform 0.2s;
}
.cdrop.open .cdrop-toggle::after { transform: translateY(-50%) rotate(180deg); }
.cdrop-toggle:hover, .cdrop.open .cdrop-toggle {
  border-color: rgba(124,58,237,0.5);
  background: rgba(255,255,255,0.08);
  box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
}
.cdrop-menu {
  display: none;
  position: absolute; top: calc(100% + 6px); left: 0; right: 0;
  background: rgba(20,16,48,0.95);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 14px;
  padding: 6px;
  z-index: 100;
  box-shadow: 0 16px 48px rgba(0,0,0,0.5), 0 0 0 1px rgba(124,58,237,0.1);
  animation: dropIn 0.2s cubic-bezier(0.16,1,0.3,1);
  max-height: 220px;
  overflow-y: auto;
}
.cdrop.open .cdrop-menu { display: block; }
@keyframes dropIn { from { opacity:0; transform:translateY(-8px) scale(0.97); } to { opacity:1; transform:translateY(0) scale(1); } }
.cdrop-item {
  padding: 11px 14px;
  border-radius: 10px;
  font-size: 14px;
  color: rgba(255,255,255,0.7);
  cursor: pointer;
  transition: all 0.15s;
  font-family: 'Noto Sans KR',sans-serif;
}
.cdrop-item:hover {
  background: rgba(124,58,237,0.2);
  color: #fff;
}
.cdrop-item.selected {
  background: rgba(124,58,237,0.3);
  color: #c4b5fd;
  font-weight: 600;
}
.cdrop-item.selected::before {
  content: '✓ ';
  color: #a78bfa;
}

/* Privacy consent */
.privacy-row {
  display: flex; align-items: center; gap: 12px;
  justify-content: center;
  margin-bottom: 20px; margin-top: 6px;
  padding: 14px 16px;
  background: rgba(124,58,237,0.08);
  border: 1px solid rgba(124,58,237,0.15);
  border-radius: 14px;
  cursor: pointer;
  transition: all 0.2s;
}
.privacy-row:hover {
  background: rgba(124,58,237,0.14);
  border-color: rgba(124,58,237,0.3);
}
.privacy-row input[type="checkbox"] {
  display: none;
}
.privacy-check-custom {
  width: 22px; height: 22px; flex-shrink: 0;
  border: 2px solid rgba(255,255,255,0.2);
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s;
  background: rgba(255,255,255,0.05);
}
.privacy-row input:checked + .privacy-check-custom {
  background: linear-gradient(135deg, #7c3aed, #a855f7);
  border-color: #7c3aed;
  box-shadow: 0 0 12px rgba(124,58,237,0.3);
}
.privacy-check-custom::after {
  content: '';
  width: 6px; height: 10px;
  border: solid #fff; border-width: 0 2.5px 2.5px 0;
  transform: rotate(45deg) scale(0);
  transition: transform 0.2s cubic-bezier(0.2,1,0.3,1);
  margin-top: -2px;
}
.privacy-row input:checked + .privacy-check-custom::after {
  transform: rotate(45deg) scale(1);
}
.privacy-row label {
  font-size: 13px; color: rgba(255,255,255,0.55);
  cursor: pointer; user-select: none;
  font-family: 'Noto Sans KR',sans-serif;
  line-height: 1.5;
}
.privacy-link {
  color: #a78bfa !important; text-decoration: none;
  font-weight: 600;
  border-bottom: 1px dashed rgba(167,139,250,0.4);
  transition: all 0.15s;
}
.privacy-link:hover { color: #c4b5fd !important; border-bottom-color: #c4b5fd; }

.reg-btn {
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
.reg-btn::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
  transform: translateX(-100%);
  transition: transform 0.5s;
}
.reg-btn:hover::before { transform: translateX(100%); }
.reg-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(124,58,237,0.5), 0 0 60px rgba(124,58,237,0.15);
}
.reg-btn:active { transform: translateY(0); }

.reg-footer {
  text-align: center;
  margin-top: 22px;
}
.reg-footer a {
  font-size: 13px;
  color: rgba(255,255,255,0.35);
  text-decoration: none;
  transition: color 0.2s;
}
.reg-footer a:hover { color: #a78bfa; }
.reg-footer strong { color: #a78bfa; }

/* Hide number input spinners */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; }

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

@media (max-width: 520px) {
  .reg-box { margin: 8px; padding: 32px 24px 28px; }
  .code-col { width: 100px; font-size: 10px; }
}
  </style>
</head>
<body>
<div class="reg-page">
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

  <div class="reg-box">
    <div class="reg-logo">
      <div class="brand"><?php echo $OJ_NAME?></div>
      <div class="sub">Chungju Highschool <span class="oj">Online Judge</span></div>
    </div>

    <div class="reg-notice">
      <span class="ni">ℹ️</span>
      <span>회원가입 후 <strong style="color:#c4b5fd">관리자 승인</strong>이 필요합니다.</span>
    </div>

    <form action="register.php" onsubmit="return check();" method="post" novalidate>

      <div class="reg-field">
        <label>사용자 ID <span class="req">*</span></label>
        <div style="display:flex;gap:10px;align-items:center;">
          <input id="user_id" name="user_id" type="text" placeholder="영문, 숫자 3자 이상" autocomplete="username" style="flex:1;">
          <button type="button" class="id-check-btn" onclick="checkDuplicate()">중복확인</button>
        </div>
        <div id="id-check-msg" class="id-msg"></div>
      </div>

      <div class="reg-field" id="field-nick">
        <label>이름 (닉네임) <span class="req">*</span></label>
        <input id="nick" name="nick" type="text" placeholder="실명을 입력해주세요">
        <div id="nick-msg"></div>
      </div>

      <div class="reg-field">
        <label>비밀번호 <span class="req">*</span></label>
        <input id="password" name="password" type="password" placeholder="6자 이상 입력" autocomplete="new-password">
        <div id="pw-msg" class="id-msg"></div>
      </div>

      <div class="reg-field">
        <label>비밀번호 확인 <span class="req">*</span></label>
        <input id="rptpassword" name="rptpassword" type="password" placeholder="비밀번호를 다시 입력" autocomplete="new-password">
        <div id="pw-match-msg" class="id-msg"></div>
      </div>

      <div class="reg-field" id="field-school">
        <label>학년 / 반 <span class="req">*</span></label>
        <input type="hidden" id="school" name="school" value="">
        <div class="select-row" id="school-row">
          <div class="cdrop" id="grade-drop">
            <div class="cdrop-toggle" data-value="" onclick="toggleDrop('grade-drop')">학년 선택</div>
            <div class="cdrop-menu">
              <div class="cdrop-item" data-val="1">1학년</div>
              <div class="cdrop-item" data-val="2">2학년</div>
              <div class="cdrop-item" data-val="3">3학년</div>
            </div>
          </div>
          <span class="select-sep">-</span>
          <div class="cdrop" id="class-drop">
            <div class="cdrop-toggle" data-value="" onclick="toggleDrop('class-drop')">반 선택</div>
            <div class="cdrop-menu">
              <?php for($i=1;$i<=8;$i++): ?>
              <div class="cdrop-item" data-val="<?php echo $i?>"><?php echo $i?>반</div>
              <?php endfor; ?>
            </div>
          </div>
        </div>
        <div id="school-msg"></div>
      </div>

      <div class="reg-field" id="field-student-no">
        <label>번호 <span class="req">*</span></label>
        <input id="student_no" name="student_no" type="number" placeholder="출석번호를 입력해주세요 (예: 15)" min="1" max="40" oninput="this.value=this.value.replace(/[^0-9]/g,'');if(this.value>40)this.value=40;if(this.value<0)this.value=''">
        <div id="student-no-msg" class="id-msg"></div>
      </div>

      <input type="hidden" id="email" name="email" value="">

      <?php if($OJ_VCODE):?>
      <div class="reg-field">
        <label><?php echo $MSG_VCODE?> <span class="req">*</span></label>
        <div class="vcode-row">
          <input name="vcode" type="text" placeholder="보안문자 입력" autocomplete="off">
          <img id="vcode-img" alt="클릭하여 새로고침" onclick="this.src='vcode.php?'+Math.random()">
        </div>
      </div>
      <?php endif;?>

      <div class="privacy-row" id="privacy-row" onclick="document.getElementById('privacy_check').click()">
        <input type="checkbox" id="privacy_check">
        <div class="privacy-check-custom"></div>
        <label for="privacy_check" onclick="event.stopPropagation()">
          <a href="privacy.php" target="_blank" class="privacy-link" onclick="event.stopPropagation()">개인정보처리방침</a>에 동의합니다.
        </label>
      </div>
      <div id="privacy-msg"></div>

      <button class="reg-btn" type="submit">회원가입</button>
    </form>

    <div class="reg-footer">
      <a href="loginpage.php">이미 계정이 있으신가요? <strong>로그인</strong></a>
    </div>
  </div>
</div>

<script src="<?php echo $OJ_CDN_URL?>include/md5-min.js"></script>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
// Custom dropdown logic
function toggleDrop(id) {
  var el = document.getElementById(id);
  var isOpen = el.classList.contains('open');
  document.querySelectorAll('.cdrop.open').forEach(function(d){ d.classList.remove('open'); });
  if (!isOpen) el.classList.add('open');
}
document.addEventListener('click', function(e){
  if (!e.target.closest('.cdrop')) {
    document.querySelectorAll('.cdrop.open').forEach(function(d){ d.classList.remove('open'); });
  }
});
document.querySelectorAll('.cdrop-item').forEach(function(item){
  item.addEventListener('click', function(){
    var drop = this.closest('.cdrop');
    var toggle = drop.querySelector('.cdrop-toggle');
    // update selection
    drop.querySelectorAll('.cdrop-item').forEach(function(x){ x.classList.remove('selected'); });
    this.classList.add('selected');
    toggle.textContent = this.textContent;
    toggle.dataset.value = this.dataset.val;
    toggle.classList.add('has-value');
    drop.classList.remove('open');
    updateSchool();
  });
});

function updateSchool(){
  var g = document.querySelector('#grade-drop .cdrop-toggle').dataset.value || '';
  var c = document.querySelector('#class-drop .cdrop-toggle').dataset.value || '';
  document.getElementById('school').value = (g && c) ? g+'-'+c : '';
}

var idChecked = false;

function checkDuplicate(){
  var uid = document.getElementById('user_id').value.trim();
  var msg = document.getElementById('id-check-msg');
  if(uid.length < 3){
    msg.className = 'id-msg warn';
    msg.textContent = '⚠ ID는 3자 이상이어야 합니다.';
    idChecked = false;
    return;
  }
  msg.className = 'id-msg warn';
  msg.textContent = '확인 중...';
  fetch('check_user_id.php?user_id='+encodeURIComponent(uid))
    .then(function(r){ return r.json(); })
    .then(function(data){
      if(data.status === 'ok'){
        msg.className = 'id-msg ok';
        msg.textContent = '✓ 사용 가능한 ID입니다.';
        idChecked = true;
      } else if(data.status === 'exists'){
        msg.className = 'id-msg fail';
        msg.textContent = '✕ 이미 사용 중인 ID입니다.';
        idChecked = false;
      } else {
        msg.className = 'id-msg warn';
        msg.textContent = '⚠ ID는 3자 이상이어야 합니다.';
        idChecked = false;
      }
    })
    .catch(function(){
      msg.className = 'id-msg fail';
      msg.textContent = '오류가 발생했습니다.';
      idChecked = false;
    });
}
// 비밀번호 실시간 검증
document.getElementById('password').addEventListener('input', function(){
  var msg = document.getElementById('pw-msg');
  var rpt = document.getElementById('rptpassword').value;
  if(this.value.length === 0){
    msg.textContent = ''; msg.className = 'id-msg';
  } else if(this.value.length < 6){
    msg.className = 'id-msg fail';
    msg.textContent = '✕ 6자 이상 입력해주세요. (' + this.value.length + '/6)';
  } else {
    msg.className = 'id-msg ok';
    msg.textContent = '✓ 사용 가능한 비밀번호입니다.';
  }
  // 비밀번호 확인도 같이 체크
  var matchMsg = document.getElementById('pw-match-msg');
  if(rpt.length > 0){
    if(this.value === rpt){
      matchMsg.className = 'id-msg ok';
      matchMsg.textContent = '✓ 비밀번호가 일치합니다.';
    } else {
      matchMsg.className = 'id-msg fail';
      matchMsg.textContent = '✕ 비밀번호가 일치하지 않습니다.';
    }
  }
});
document.getElementById('rptpassword').addEventListener('input', function(){
  var msg = document.getElementById('pw-match-msg');
  var pw = document.getElementById('password').value;
  if(this.value.length === 0){
    msg.textContent = ''; msg.className = 'id-msg';
  } else if(this.value !== pw){
    msg.className = 'id-msg fail';
    msg.textContent = '✕ 비밀번호가 일치하지 않습니다.';
  } else {
    msg.className = 'id-msg ok';
    msg.textContent = '✓ 비밀번호가 일치합니다.';
  }
});

// ID 변경 시 중복확인 초기화
document.getElementById('user_id').addEventListener('input', function(){
  idChecked = false;
  document.getElementById('id-check-msg').textContent = '';
  document.getElementById('id-check-msg').className = 'id-msg';
});

function shakeField(fieldEl, msgEl, message) {
  fieldEl.classList.remove('shake');
  void fieldEl.offsetWidth; // reflow
  fieldEl.classList.add('shake');
  msgEl.className = 'field-error';
  msgEl.textContent = message;
  fieldEl.scrollIntoView({ behavior:'smooth', block:'center' });
  setTimeout(function(){ fieldEl.classList.remove('shake'); }, 600);
}

function clearFieldError(msgId) {
  var el = document.getElementById(msgId);
  if(el) { el.textContent = ''; el.className = ''; }
}

function check(){
  // 모든 에러 초기화
  ['id-check-msg','nick-msg','pw-msg','pw-match-msg','school-msg','privacy-msg'].forEach(function(id){
    var el = document.getElementById(id);
    if(el && el.classList.contains('field-error')) { el.textContent = ''; el.className = ''; }
  });

  var uid = document.getElementById('user_id');
  if(uid.value.trim().length < 3){
    shakeField(uid.closest('.reg-field'), document.getElementById('id-check-msg'), '✕ ID는 3자 이상이어야 합니다.');
    uid.focus();
    return false;
  }
  if(!idChecked){
    shakeField(uid.closest('.reg-field'), document.getElementById('id-check-msg'), '✕ ID 중복확인을 해주세요.');
    uid.focus();
    return false;
  }

  var nick = document.getElementById('nick');
  if(nick.value.trim().length === 0){
    shakeField(document.getElementById('field-nick'), document.getElementById('nick-msg'), '✕ 이름을 입력해주세요.');
    nick.focus();
    return false;
  }

  var sno = document.getElementById('student_no');
  var snoVal = parseInt(sno.value);
  if(sno.value.trim().length === 0 || isNaN(snoVal)){
    shakeField(document.getElementById('field-student-no'), document.getElementById('student-no-msg'), '✕ 번호를 입력해주세요.');
    sno.focus();
    return false;
  }
  if(snoVal < 1 || snoVal > 40){
    shakeField(document.getElementById('field-student-no'), document.getElementById('student-no-msg'), '✕ 번호는 1~40 사이만 입력 가능합니다.');
    sno.focus();
    return false;
  }

  var pw = document.getElementById('password');
  if(pw.value.length < 6){
    shakeField(pw.closest('.reg-field'), document.getElementById('pw-msg'), '✕ 비밀번호는 6자 이상이어야 합니다.');
    pw.focus();
    return false;
  }

  var rpt = document.getElementById('rptpassword');
  if(pw.value !== rpt.value){
    shakeField(rpt.closest('.reg-field'), document.getElementById('pw-match-msg'), '✕ 비밀번호가 일치하지 않습니다.');
    rpt.focus();
    return false;
  }

  var g = document.querySelector('#grade-drop .cdrop-toggle').dataset.value;
  var c = document.querySelector('#class-drop .cdrop-toggle').dataset.value;
  if(!g || !c){
    shakeField(document.getElementById('field-school'), document.getElementById('school-msg'), '✕ 학년과 반을 선택해주세요.');
    return false;
  }

  var privacyRow = document.getElementById('privacy-row');
  if(!document.getElementById('privacy_check').checked){
    shakeField(privacyRow, document.getElementById('privacy-msg'), '✕ 개인정보처리방침에 동의해주세요.');
    return false;
  }

  pw.value = hex_md5(pw.value);
  rpt.value = hex_md5(rpt.value);
  return true;
}

// Particles
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
<?php if($OJ_VCODE):?>
<script>
$(document).ready(function(){
  $("#vcode-img").attr("src","vcode.php?"+Math.random());
});
</script>
<?php endif;?>
</body>
</html>
