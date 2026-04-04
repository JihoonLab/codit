<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>정보 수정 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

    .modify-wrap {
      max-width: 520px;
      margin: 48px auto;
      padding: 0 20px 60px;
    }

    .modify-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    .modify-card-header {
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      padding: 28px 36px;
      color: #fff;
      text-align: center;
    }
    .modify-card-header h2 {
      font-size: 20px; font-weight: 900; margin: 0 0 4px;
    }
    .modify-card-header p {
      font-size: 13px; opacity: 0.8; margin: 0;
    }

    .modify-card-body { padding: 32px 36px; }

    .form-section {
      border-bottom: 1px solid #f0f3f7;
      padding-bottom: 24px;
      margin-bottom: 24px;
    }
    .form-section:last-of-type { border-bottom: none; margin-bottom: 0; }

    .section-label {
      font-size: 11px; font-weight: 700;
      color: #aaa; text-transform: uppercase;
      letter-spacing: 1px; margin-bottom: 14px;
    }

    .field-row { margin-bottom: 14px; }
    .field-row label {
      display: block; font-size: 13px; font-weight: 600;
      color: #555; margin-bottom: 6px;
    }
    .field-static {
      font-size: 15px; font-weight: 700; color: #7c3aed;
      padding: 9px 0;
    }
    .field-row input[type=text],
    .field-row input[type=password] {
      width: 100%; padding: 10px 14px;
      border: 1.5px solid #e0e6ef; border-radius: 8px;
      font-size: 14px; font-family: inherit; color: #333;
      transition: border-color 0.15s, box-shadow 0.15s;
      background: #fff;
    }
    .field-row input:focus {
      outline: none;
      border-color: #7c3aed;
      box-shadow: 0 0 0 3px rgba(26,111,196,0.1);
    }
    .field-hint { font-size: 12px; color: #aaa; margin-top: 4px; }

    /* 개인정보 동의 */
    .privacy-row {
      display: flex; align-items: flex-start; gap: 10px;
      background: #f8fafc; border-radius: 8px;
      padding: 14px 16px; margin-bottom: 24px;
    }
    .privacy-row input[type=checkbox] {
      width: 16px; height: 16px; margin-top: 2px;
      accent-color: #7c3aed; cursor: pointer; flex-shrink: 0;
    }
    .privacy-row label {
      font-size: 13px; color: #555; cursor: pointer; line-height: 1.6;
    }
    .privacy-row a { color: #7c3aed; text-decoration: none; }
    .privacy-row a:hover { text-decoration: underline; }

    /* 버튼 */
    .btn-row { display: flex; gap: 10px; }
    .btn-save {
      flex: 1; padding: 12px;
      background: #7c3aed; color: #fff;
      border: none; border-radius: 8px;
      font-size: 15px; font-weight: 700;
      cursor: pointer; font-family: inherit;
      transition: background 0.15s;
    }
    .btn-save:hover { background: #6d28d9; }
    .btn-reset {
      padding: 12px 24px;
      background: #f0f3f7; color: #666;
      border: none; border-radius: 8px;
      font-size: 15px; font-weight: 600;
      cursor: pointer; font-family: inherit;
      transition: background 0.15s;
    }
    .btn-reset:hover { background: #e2e8f0; }

    .back-link {
      display: block; text-align: center;
      margin-top: 16px; font-size: 13px;
      color: #aaa; text-decoration: none;
    }
    .back-link:hover { color: #7c3aed; }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="modify-wrap">
  <div class="modify-card">

    <div class="modify-card-header">
      <h2>✏️ 정보 수정</h2>
      <p>이름, 비밀번호, 학년/반을 변경할 수 있습니다</p>
    </div>

    <div class="modify-card-body">
      <form action="modify.php" method="post">
        <?php require_once('./include/set_post_key.php');?>

        <!-- 기본 정보 -->
        <div class="form-section">
          <div class="section-label">기본 정보</div>
          <div class="field-row">
            <label>사용자 ID</label>
            <div class="field-static"><?php echo htmlspecialchars($_SESSION[$OJ_NAME.'_'.'user_id'])?></div>
          </div>
          <div class="field-row">
            <label for="nick">이름 (닉네임)</label>
            <input id="nick" name="nick" type="text"
              value="<?php echo htmlspecialchars($row['nick'], ENT_QUOTES, 'UTF-8')?>"
              placeholder="표시될 이름">
          </div>
          <div class="field-row">
            <label for="student_no">번호</label>
            <input id="student_no" name="student_no" type="text"
              value="<?php echo htmlspecialchars($row['student_no'] ?? '', ENT_QUOTES, 'UTF-8')?>"
              placeholder="출석번호" maxlength="10" inputmode="numeric">
          </div>
          <div class="field-row">
            <label>학년/반</label>
            <input id="school" name="school" type="hidden" value="<?php echo htmlspecialchars($row['school'], ENT_QUOTES, 'UTF-8')?>">
            <div style="display:flex;gap:10px;align-items:center">
              <select id="sel-grade" style="flex:1;padding:10px 14px;border:2px solid #e5e9f0;border-radius:8px;font-size:15px;background:#fff">
                <option value="">학년 선택</option>
                <option value="1">1학년</option>
                <option value="2">2학년</option>
                <option value="3">3학년</option>
              </select>
              <span style="font-size:18px;color:#aaa;font-weight:700">-</span>
              <select id="sel-class" style="flex:1;padding:10px 14px;border:2px solid #e5e9f0;border-radius:8px;font-size:15px;background:#fff">
                <option value="">반 선택</option>
                <?php for($i=1;$i<=8;$i++): ?>
                <option value="<?php echo $i?>"><?php echo $i?>반</option>
                <?php endfor; ?>
              </select>
            </div>
            <script>
            (function(){
              var cur = document.getElementById('school').value;
              if(cur && cur.indexOf('-') !== -1) {
                var parts = cur.split('-');
                document.getElementById('sel-grade').value = parts[0];
                document.getElementById('sel-class').value = parts[1];
              }
              function updateSchool() {
                var g = document.getElementById('sel-grade').value;
                var c = document.getElementById('sel-class').value;
                document.getElementById('school').value = (g && c) ? g + '-' + c : '';
              }
              document.getElementById('sel-grade').onchange = updateSchool;
              document.getElementById('sel-class').onchange = updateSchool;
            })();
            </script>
          </div>
        </div>

        <!-- 비밀번호 변경 -->
        <div class="form-section">
          <div class="section-label">비밀번호 변경 <span style="font-weight:400;text-transform:none;letter-spacing:0">(변경 시에만 입력)</span></div>
          <div class="field-row">
            <label for="opassword">현재 비밀번호</label>
            <input id="opassword" name="opassword" type="password" placeholder="현재 비밀번호" autocomplete="off">
          </div>
          <div class="field-row">
            <label for="npassword">새 비밀번호</label>
            <input id="npassword" name="npassword" type="password" placeholder="새 비밀번호 (6자 이상)" autocomplete="off">
          </div>
          <div class="field-row">
            <label for="rptpassword">새 비밀번호 확인</label>
            <input id="rptpassword" name="rptpassword" type="password" placeholder="새 비밀번호 재입력" autocomplete="off">
          </div>
        </div>

        <!-- 버튼 -->
        <div class="btn-row">
          <button type="submit" name="submit" class="btn-save">저장하기</button>
          <button type="reset" class="btn-reset">초기화</button>
        </div>
      </form>

      <a href="userinfo.php?user=<?php echo urlencode($_SESSION[$OJ_NAME.'_'.'user_id'])?>" class="back-link">← 프로필로 돌아가기</a>
    </div>
  </div>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>include/md5-min.js"></script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
  var op = document.getElementById('opassword');
  var np = document.getElementById('npassword');
  var rp = document.getElementById('rptpassword');
  if(np.value && np.value.length < 6) {
    alert('새 비밀번호는 6자 이상이어야 합니다.');
    np.focus();
    e.preventDefault(); return;
  }
  if(np.value && np.value !== rp.value) {
    alert('새 비밀번호가 일치하지 않습니다.');
    rp.focus();
    e.preventDefault(); return;
  }
  // MD5 해싱 (로그인/회원가입과 동일)
  if(op.value) op.value = hex_md5(op.value);
  if(np.value) np.value = hex_md5(np.value);
  if(rp.value) rp.value = hex_md5(rp.value);
});
</script>
</body>
</html>
