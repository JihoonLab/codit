<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>정보 수정 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    body{font-family:'Inter','Noto Sans KR',sans-serif;background:#f0f2f5;color:#1d1d1f}

    .modify-wrap{max-width:640px;margin:30px auto;padding:0 20px 60px}

    .modify-header{
      display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;
    }
    .modify-header h2{font-size:24px;font-weight:800;color:#1a1a2e;margin:0}
    .modify-header h2 em{color:#7c3aed;font-style:normal}

    /* ===== 카드 ===== */
    .m-card{
      background:#fff;border-radius:16px;
      box-shadow:0 1px 3px rgba(0,0,0,0.04),0 4px 12px rgba(0,0,0,0.03);
      border:1px solid #e5e7eb;margin-bottom:16px;
      overflow:hidden;
      transition:box-shadow 0.25s,transform 0.25s;
    }
    .m-card:hover{box-shadow:0 2px 8px rgba(0,0,0,0.06),0 8px 24px rgba(0,0,0,0.06);transform:translateY(-2px)}

    .m-card-header{
      display:flex;align-items:center;justify-content:space-between;
      padding:14px 22px;
      border-bottom:2px solid #ede9fe;
    }
    .m-card-header h3{
      font-size:15px;font-weight:800;color:#7c3aed;margin:0;
      display:flex;align-items:center;gap:8px;
    }
    .m-card-header h3 .sec-icon{font-size:18px}

    .m-card-body{padding:24px 22px}

    /* ===== 필드 ===== */
    .m-field{margin-bottom:18px}
    .m-field:last-child{margin-bottom:0}
    .m-field label{
      display:block;font-size:13px;font-weight:700;
      color:#374151;margin-bottom:7px;
    }
    .m-field-static{
      font-size:16px;font-weight:800;
      background:linear-gradient(135deg,#7c3aed,#6d28d9);
      -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
      padding:4px 0;
    }
    .m-field input,.m-field select{
      width:100%;padding:12px 16px;
      border:2px solid #e5e9f0;border-radius:12px;
      font-size:14px;font-family:inherit;color:#1d1d1f;
      background:#fafbfc;
      transition:all 0.2s;
    }
    .m-field input:focus,.m-field select:focus{
      outline:none;border-color:#7c3aed;background:#fff;
      box-shadow:0 0 0 4px rgba(124,58,237,0.08);
    }

    .m-field-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    @media(max-width:480px){.m-field-row{grid-template-columns:1fr}}

    .grade-row{display:flex;gap:10px;align-items:center}
    .grade-row select{flex:1}
    .grade-sep{color:#c4b5fd;font-weight:800;font-size:18px}

    /* ===== 저장 버튼 ===== */
    .m-card-footer{padding:0 22px 20px;display:flex;justify-content:flex-end}
    .btn-save{
      padding:11px 32px;
      background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;
      border:none;border-radius:12px;
      font-size:14px;font-weight:700;font-family:inherit;
      cursor:pointer;transition:all 0.25s;
      display:flex;align-items:center;gap:6px;
      box-shadow:0 2px 8px rgba(124,58,237,0.25);
    }
    .btn-save:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,0.35)}
    .btn-save:active{transform:translateY(0)}
    .btn-save:disabled{opacity:0.5;cursor:not-allowed;transform:none;box-shadow:none}
    .btn-save .spinner{
      width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);
      border-top-color:#fff;border-radius:50%;
      animation:spin 0.6s linear infinite;display:none;
    }
    .btn-save.loading .spinner{display:block}
    .btn-save.loading .btn-text{display:none}

    /* ===== 비밀번호 토글 ===== */
    .pw-toggle{
      padding:7px 18px;
      background:#f5f3ff;
      border:2px solid #ede9fe;border-radius:8px;
      font-size:13px;font-weight:700;color:#7c3aed;
      cursor:pointer;font-family:inherit;transition:all 0.2s;
    }
    .pw-toggle:hover{background:#ede9fe;border-color:#c4b5fd}

    .pw-fields{
      max-height:0;overflow:hidden;
      transition:max-height 0.4s cubic-bezier(0.4,0,0.2,1),opacity 0.3s;
      opacity:0;
    }
    .pw-fields.open{max-height:500px;opacity:1}

    /* 비밀번호 강도 */
    .pw-strength{height:4px;background:#e5e9f0;border-radius:2px;margin-top:8px;overflow:hidden}
    .pw-strength-bar{height:100%;width:0;border-radius:2px;transition:all 0.3s}
    .pw-label{font-size:12px;font-weight:700;margin-top:4px;min-height:16px;transition:color 0.3s}

    /* 체크 아이콘 */
    .m-field{position:relative}
    .check-icon{
      position:absolute;right:16px;top:38px;
      color:#34c759;font-size:16px;font-weight:700;
      opacity:0;transform:scale(0);
      transition:all 0.3s cubic-bezier(0.34,1.56,0.64,1);
    }
    .check-icon.show{opacity:1;transform:scale(1)}
    .mismatch-icon{
      position:absolute;right:16px;top:38px;
      color:#ef4444;font-size:16px;font-weight:700;
      opacity:0;transform:scale(0);
      transition:all 0.3s cubic-bezier(0.34,1.56,0.64,1);
    }
    .mismatch-icon.show{opacity:1;transform:scale(1)}
    .pw-match-label{font-size:12px;font-weight:700;margin-top:4px;min-height:16px;transition:color 0.3s}

    /* ===== 토스트 ===== */
    .toast{
      position:fixed;top:80px;left:50%;transform:translateX(-50%) translateY(-120px);
      padding:14px 28px;border-radius:14px;
      font-size:14px;font-weight:700;font-family:inherit;
      box-shadow:0 8px 32px rgba(0,0,0,0.15);
      z-index:9999;transition:transform 0.5s cubic-bezier(0.34,1.56,0.64,1);
      display:flex;align-items:center;gap:8px;
      backdrop-filter:blur(12px);
    }
    .toast.show{transform:translateX(-50%) translateY(0)}
    .toast.success{background:rgba(15,23,42,0.92);color:#fff}
    .toast.error{background:rgba(220,38,38,0.92);color:#fff}

    .back-link{
      display:block;text-align:center;margin-top:12px;
      font-size:13px;color:#9ca3af;text-decoration:none;
      font-weight:500;transition:color 0.2s;
    }
    .back-link:hover{color:#7c3aed}

    @keyframes spin{to{transform:rotate(360deg)}}

    @media(max-width:480px){
      .modify-wrap{padding:0 12px 60px}
      .m-card-body,.m-card-footer{padding-left:16px;padding-right:16px}
    }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="modify-wrap">

  <div class="modify-header">
    <h2><em>정보 수정</em></h2>
  </div>

  <!-- 프로필 카드 -->
  <div class="m-card" id="profile-card">
    <div class="m-card-header">
      <h3><span class="sec-icon">👤</span> 프로필</h3>
    </div>
    <div class="m-card-body">
      <div class="m-field">
        <label>사용자 ID</label>
        <div class="m-field-static"><?php echo htmlspecialchars($_SESSION[$OJ_NAME.'_'.'user_id'])?></div>
      </div>
      <div class="m-field">
        <label for="nick">이름 (닉네임)</label>
        <input id="nick" type="text" value="<?php echo htmlspecialchars($row['nick'] ?? '', ENT_QUOTES, 'UTF-8')?>" placeholder="표시될 이름" maxlength="20">
        <span class="check-icon">&#10003;</span>
      </div>
      <div class="m-field">
        <label>학년 / 반</label>
        <input id="school" type="hidden" value="<?php echo htmlspecialchars($row['school'] ?? '', ENT_QUOTES, 'UTF-8')?>">
        <div class="grade-row">
          <select id="sel-grade">
            <option value="">학년 선택</option>
            <option value="1">1학년</option>
            <option value="2">2학년</option>
            <option value="3">3학년</option>
          </select>
          <span class="grade-sep">-</span>
          <select id="sel-class">
            <option value="">반 선택</option>
            <?php for($i=1;$i<=8;$i++): ?>
            <option value="<?php echo $i?>"><?php echo $i?>반</option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
      <div class="m-field">
        <label for="student_no">번호</label>
        <input id="student_no" type="text" value="<?php echo htmlspecialchars($row['student_no'] ?? '', ENT_QUOTES, 'UTF-8')?>" placeholder="출석번호" maxlength="10" inputmode="numeric">
        <span class="check-icon">&#10003;</span>
      </div>
    </div>
    <div class="m-card-footer">
      <button class="btn-save" id="btn-profile">
        <span class="spinner"></span>
        <span class="btn-text">저장하기</span>
      </button>
    </div>
  </div>

  <!-- 비밀번호 카드 -->
  <div class="m-card" id="pw-card">
    <div class="m-card-header">
      <h3><span class="sec-icon">🔒</span> 비밀번호</h3>
      <button class="pw-toggle" id="pw-toggle">변경하기</button>
    </div>
    <div class="pw-fields" id="pw-fields">
      <div class="m-card-body">
        <div class="m-field">
          <label for="opassword">현재 비밀번호</label>
          <input id="opassword" type="password" placeholder="현재 비밀번호 입력" autocomplete="current-password">
        </div>
        <div class="m-field-row">
          <div class="m-field">
            <label for="npassword">새 비밀번호</label>
            <input id="npassword" type="password" placeholder="6자 이상" autocomplete="new-password">
            <div class="pw-strength"><div class="pw-strength-bar" id="pw-bar"></div></div>
            <div class="pw-label" id="pw-label"></div>
          </div>
          <div class="m-field">
            <label for="rptpassword">새 비밀번호 확인</label>
            <input id="rptpassword" type="password" placeholder="다시 입력" autocomplete="new-password">
            <span class="check-icon" id="pw-match-icon">&#10003;</span>
            <span class="mismatch-icon" id="pw-mismatch-icon">&#10007;</span>
            <div class="pw-match-label" id="pw-match-label"></div>
          </div>
        </div>
      </div>
      <div class="m-card-footer">
        <button class="btn-save" id="btn-pw">
          <span class="spinner"></span>
          <span class="btn-text">비밀번호 변경</span>
        </button>
      </div>
    </div>
  </div>

  <a href="userinfo.php?user=<?php echo urlencode($_SESSION[$OJ_NAME.'_'.'user_id'])?>" class="back-link">← 프로필로 돌아가기</a>
</div>

<div class="toast" id="toast"></div>

<?php include("template/$OJ_TEMPLATE/footer.php");?>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>include/md5-min.js"></script>
<script>
(function(){
  var cur = document.getElementById('school').value;
  if(cur && cur.indexOf('-') !== -1){
    var p = cur.split('-');
    document.getElementById('sel-grade').value = p[0];
    document.getElementById('sel-class').value = p[1];
  }
  function updateSchool(){
    var g = document.getElementById('sel-grade').value;
    var c = document.getElementById('sel-class').value;
    document.getElementById('school').value = (g && c) ? g+'-'+c : '';
  }
  document.getElementById('sel-grade').onchange = updateSchool;
  document.getElementById('sel-class').onchange = updateSchool;

  function toast(msg, type){
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type;
    setTimeout(function(){ t.classList.add('show'); }, 10);
    setTimeout(function(){ t.classList.remove('show'); }, 3000);
  }

  function send(btn, data, cb){
    btn.classList.add('loading'); btn.disabled = true;
    var fd = new FormData();
    for(var k in data) fd.append(k, data[k]);
    fetch('modify.php', {method:'POST', body:fd, credentials:'same-origin'})
      .then(function(r){ return r.json(); })
      .then(function(j){
        btn.classList.remove('loading'); btn.disabled = false;
        if(j.ok){ toast(j.msg, 'success'); if(cb) cb(); }
        else toast(j.msg, 'error');
      })
      .catch(function(){
        btn.classList.remove('loading'); btn.disabled = false;
        toast('서버 오류가 발생했습니다.', 'error');
      });
  }

  document.getElementById('btn-profile').onclick = function(){
    var nick = document.getElementById('nick').value.trim();
    if(!nick){ toast('이름을 입력해주세요.', 'error'); return; }
    send(this, {
      action:'profile', nick:nick,
      student_no:document.getElementById('student_no').value.trim(),
      school:document.getElementById('school').value
    }, function(){
      document.querySelectorAll('#profile-card .check-icon').forEach(function(el){
        el.classList.add('show');
        setTimeout(function(){ el.classList.remove('show'); }, 2000);
      });
    });
  };

  document.getElementById('pw-toggle').onclick = function(){
    var f = document.getElementById('pw-fields');
    f.classList.toggle('open');
    this.textContent = f.classList.contains('open') ? '취소' : '변경하기';
  };

  document.getElementById('npassword').addEventListener('input', function(){
    var v = this.value, s = 0, bar = document.getElementById('pw-bar'), lbl = document.getElementById('pw-label');
    if(v.length >= 6) s++;
    if(v.length >= 10) s++;
    if(/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
    if(/[0-9]/.test(v)) s++;
    if(/[^A-Za-z0-9]/.test(v)) s++;
    var pct = Math.min(s/4*100, 100);
    var colors = ['#ef4444','#f59e0b','#eab308','#22c55e'];
    var labels = ['위험','약함','보통','안전'];
    bar.style.width = pct+'%';
    bar.style.background = colors[Math.min(s-1, 3)] || '#e5e9f0';
    if(v.length === 0){ lbl.textContent = ''; }
    else { lbl.textContent = '비밀번호 안전성 : ' + (labels[Math.min(s-1, 3)] || '위험'); lbl.style.color = colors[Math.min(s-1, 3)] || '#ef4444'; }
    checkMatch();
  });

  function checkMatch(){
    var np = document.getElementById('npassword').value;
    var rp = document.getElementById('rptpassword').value;
    var ok = document.getElementById('pw-match-icon');
    var no = document.getElementById('pw-mismatch-icon');
    var lbl = document.getElementById('pw-match-label');
    if(!rp){ ok.classList.remove('show'); no.classList.remove('show'); lbl.textContent=''; }
    else if(np === rp){ ok.classList.add('show'); no.classList.remove('show'); lbl.textContent='비밀번호 일치'; lbl.style.color='#22c55e'; }
    else { ok.classList.remove('show'); no.classList.add('show'); lbl.textContent='비밀번호가 일치하지 않습니다'; lbl.style.color='#ef4444'; }
  }
  document.getElementById('rptpassword').addEventListener('input', checkMatch);

  document.getElementById('btn-pw').onclick = function(){
    var op = document.getElementById('opassword');
    var np = document.getElementById('npassword');
    var rp = document.getElementById('rptpassword');
    if(!op.value){ toast('현재 비밀번호를 입력해주세요.', 'error'); op.focus(); return; }
    if(np.value.length < 6){ toast('새 비밀번호는 6자 이상이어야 합니다.', 'error'); np.focus(); return; }
    if(np.value !== rp.value){ toast('새 비밀번호가 일치하지 않습니다.', 'error'); rp.focus(); return; }
    send(this, {
      action:'password',
      opassword:hex_md5(op.value),
      npassword:hex_md5(np.value),
      rptpassword:hex_md5(rp.value)
    }, function(){
      op.value=''; np.value=''; rp.value='';
      document.getElementById('pw-bar').style.width='0';
      document.getElementById('pw-match-icon').classList.remove('show');
      document.getElementById('pw-fields').classList.remove('open');
      document.getElementById('pw-toggle').textContent='변경하기';
    });
  };
})();
</script>
</body>
</html>
