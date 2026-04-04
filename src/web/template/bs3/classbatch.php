<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>수업 일괄생성 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.cb-wrap{max-width:720px;margin:36px auto;padding:0 16px}
.cb-card{background:#fff;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.08);padding:36px 32px;border:1px solid #f0ecf9}
.cb-card h2{font-size:22px;font-weight:800;color:#7c3aed;margin:0 0 8px;display:flex;align-items:center;gap:8px}
.cb-card .sub{font-size:13px;color:#999;margin-bottom:28px}

.cb-row{display:flex;gap:12px;margin-bottom:18px;align-items:flex-end}
.cb-field{flex:1}
.cb-field label{display:block;font-size:12px;font-weight:700;color:#666;margin-bottom:6px;letter-spacing:0.3px}
.cb-field input,.cb-field select{
  width:100%;padding:11px 14px;border:1.5px solid #e5e0f0;border-radius:10px;
  font-size:14px;color:#333;box-sizing:border-box;font-family:inherit;
  background:#faf9fd;transition:border .2s;
}
.cb-field input:focus,.cb-field select:focus{outline:none;border-color:#7c3aed;background:#fff}
.cb-field.small{max-width:120px;flex:none}

/* Preview */
.cb-preview{
  background:#f8f6fd;border:1.5px dashed #d4c8f0;border-radius:12px;
  padding:16px 20px;margin:20px 0;min-height:48px;
}
.cb-preview-title{font-size:12px;font-weight:700;color:#999;margin-bottom:10px;letter-spacing:0.5px}
.cb-preview-item{
  font-size:14px;font-weight:600;color:#333;padding:6px 0;
  border-bottom:1px solid #eee;display:flex;align-items:center;gap:8px;
}
.cb-preview-item:last-child{border-bottom:none}
.cb-preview-item .tag{
  font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;
  background:#7c3aed;color:#fff;
}
.cb-preview-empty{font-size:13px;color:#bbb;font-style:italic}

/* Buttons */
.cb-btn-row{display:flex;gap:10px;margin-top:24px}
.cb-btn{
  padding:12px 28px;border-radius:10px;font-size:15px;font-weight:700;
  cursor:pointer;border:none;font-family:inherit;transition:all .2s;
  text-decoration:none;display:inline-block;text-align:center;
}
.cb-btn-primary{background:#7c3aed;color:#fff}
.cb-btn-primary:hover{background:#6d28d9;transform:translateY(-1px);box-shadow:0 4px 16px rgba(124,58,237,.3)}
.cb-btn-primary:disabled{background:#ccc;cursor:not-allowed;transform:none;box-shadow:none}
.cb-btn-secondary{background:#f0f0f0;color:#555}
.cb-btn-secondary:hover{background:#e0e0e0}

/* Result */
.cb-result{
  background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;
  padding:20px;margin-bottom:24px;
}
.cb-result h3{font-size:15px;font-weight:800;color:#16a34a;margin:0 0 12px;display:flex;align-items:center;gap:6px}
.cb-result li{font-size:13px;color:#333;padding:4px 0;list-style:none}
.cb-result ul{padding:0;margin:0}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cb-wrap">
  <div class="cb-card">
    <h2>🚀 수업 일괄생성</h2>
    <div class="sub">학년을 선택하고 주제만 입력하면 전체 반 수업이 한 번에 만들어집니다.</div>

    <?php if(!empty($batch_result)): ?>
    <div class="cb-result">
      <h3>✅ <?php echo count($batch_result)?>개 수업이 생성되었습니다!</h3>
      <ul>
        <?php foreach($batch_result as $t): ?>
        <li><?php echo htmlspecialchars($t)?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

    <form method="POST" id="batchForm">

      <div class="cb-row">
        <div class="cb-field small">
          <label>📅 날짜</label>
          <input type="text" name="date" id="f-date" value="<?php echo date('n/j')?>" placeholder="4/2">
        </div>
        <div class="cb-field" style="max-width:160px">
          <label>🎓 학년</label>
          <select name="grade" id="f-grade">
            <option value="">선택</option>
            <option value="2">2학년 (4개반)</option>
            <option value="3">3학년 (3개반)</option>
          </select>
        </div>
        <div class="cb-field" style="max-width:180px">
          <label>📘 과목</label>
          <input type="text" name="subject" id="f-subject" readonly style="background:#f0ecf9;color:#7c3aed;font-weight:700">
        </div>
      </div>

      <div class="cb-row">
        <div class="cb-field">
          <label>✏️ 주제</label>
          <input type="text" name="topic" id="f-topic" placeholder="예: 조건문 1" autocomplete="off">
        </div>
      </div>

      <div class="cb-row">
        <div class="cb-field">
          <label>📝 문제 선택</label>
          <input type="hidden" name="problem_ids" id="f-pids">
          <div style="display:flex;gap:8px;margin-bottom:8px">
            <input type="text" id="prob-search" placeholder="문제 ID 또는 제목 검색..." style="flex:1;padding:10px 14px;border:1.5px solid #e5e0f0;border-radius:10px;font-size:13px;background:#faf9fd">
            <button type="button" onclick="toggleProbList()" class="cb-btn cb-btn-secondary" style="padding:8px 16px;font-size:13px;border-radius:10px">📋 목록 열기</button>
          </div>
          <div id="selected-tags" style="display:flex;flex-wrap:wrap;gap:6px;min-height:24px"></div>
          <div id="prob-list-wrap" style="display:none;max-height:320px;overflow-y:auto;border:1.5px solid #e5e0f0;border-radius:10px;background:#fff;margin-top:8px">
            <table style="width:100%;border-collapse:collapse;font-size:13px">
              <thead>
                <tr style="background:#f8f6fd;position:sticky;top:0">
                  <th style="padding:8px 10px;text-align:center;width:40px"></th>
                  <th style="padding:8px 10px;text-align:center;width:60px">ID</th>
                  <th style="padding:8px 10px;text-align:left">제목</th>
                </tr>
              </thead>
              <tbody id="prob-tbody">
              <?php
                $all_problems = pdo_query("SELECT problem_id, title FROM problem WHERE defunct='N' ORDER BY problem_id ASC");
                if($all_problems) foreach($all_problems as $p):
              ?>
                <tr class="prob-row" data-pid="<?php echo $p['problem_id']?>" data-title="<?php echo htmlspecialchars(strtolower($p['title']))?>">
                  <td style="padding:6px 10px;text-align:center">
                    <input type="checkbox" class="prob-chk" value="<?php echo $p['problem_id']?>" onchange="updateSelected()">
                  </td>
                  <td style="padding:6px 10px;text-align:center;font-weight:700;color:#7c3aed"><?php echo $p['problem_id']?></td>
                  <td style="padding:6px 10px"><?php echo htmlspecialchars($p['title'])?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="cb-preview" id="preview">
        <div class="cb-preview-title">미리보기</div>
        <div class="cb-preview-empty" id="preview-empty">학년과 주제를 입력하면 생성될 수업 목록이 표시됩니다.</div>
        <div id="preview-list"></div>
      </div>

      <div class="cb-btn-row">
        <button type="submit" class="cb-btn cb-btn-primary" id="submitBtn" disabled>일괄생성</button>
        <a href="/classop.php" class="cb-btn cb-btn-secondary">돌아가기</a>
        <a href="/classop.php?action=write" class="cb-btn cb-btn-secondary">개별 생성</a>
      </div>
    </form>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<style>
/* Problem picker styles */
#prob-list-wrap::-webkit-scrollbar{width:4px}
#prob-list-wrap::-webkit-scrollbar-thumb{background:#d4c8f0;border-radius:4px}
.prob-row{transition:background .1s;cursor:pointer}
.prob-row:hover{background:#f8f6fd}
.prob-row.checked{background:#f0ecf9}
.prob-chk{width:16px;height:16px;accent-color:#7c3aed;cursor:pointer}
.sel-tag{
  display:inline-flex;align-items:center;gap:4px;
  padding:4px 10px 4px 12px;border-radius:20px;font-size:12px;font-weight:700;
  background:linear-gradient(135deg,#f0ecf9,#e8e0f8);color:#7c3aed;
  border:1px solid #d4c8f0;animation:tagIn .2s ease;
}
.sel-tag .tag-x{
  width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:10px;cursor:pointer;background:rgba(124,58,237,.15);color:#7c3aed;
  transition:all .15s;
}
.sel-tag .tag-x:hover{background:#7c3aed;color:#fff}
@keyframes tagIn{from{opacity:0;transform:scale(.8)}to{opacity:1;transform:scale(1)}}
.prob-count{
  display:inline-flex;align-items:center;gap:4px;padding:4px 10px;
  border-radius:20px;font-size:11px;font-weight:700;
  background:#7c3aed;color:#fff;
}
</style>
<script>
var classMap = {2: [1,2,3,4], 3: [1,2,3]};
var subjectMap = {2: '정보', 3: '인공지능기초'};

var fGrade = document.getElementById('f-grade');
var fSubject = document.getElementById('f-subject');
var fTopic = document.getElementById('f-topic');
var fDate = document.getElementById('f-date');
var previewList = document.getElementById('preview-list');
var previewEmpty = document.getElementById('preview-empty');
var submitBtn = document.getElementById('submitBtn');

function updatePreview() {
  var grade = parseInt(fGrade.value) || 0;
  var topic = fTopic.value.trim();
  var date = fDate.value.trim();
  var subject = fSubject.value;

  previewList.innerHTML = '';
  if (!grade || !topic) {
    previewEmpty.style.display = 'block';
    submitBtn.disabled = true;
    return;
  }
  previewEmpty.style.display = 'none';
  submitBtn.disabled = false;

  var bans = classMap[grade] || [];
  bans.forEach(function(ban) {
    var tag = grade + '-' + ban;
    var title = (date ? '(' + date + ') ' : '') + '[' + tag + '] [' + subject + '] ' + topic;
    var div = document.createElement('div');
    div.className = 'cb-preview-item';
    div.innerHTML = '<span class="tag">' + tag + '</span> ' + title;
    previewList.appendChild(div);
  });
}

fGrade.addEventListener('change', function() {
  var g = parseInt(this.value) || 0;
  fSubject.value = subjectMap[g] || '';
  updatePreview();
});

fTopic.addEventListener('input', updatePreview);
fDate.addEventListener('input', updatePreview);

document.getElementById('batchForm').addEventListener('submit', function(e) {
  if (!fGrade.value || !fTopic.value.trim()) {
    e.preventDefault();
    alert('학년과 주제를 입력해주세요.');
    return;
  }
  submitBtn.textContent = '생성 중...';
  submitBtn.disabled = true;
});

// Problem picker
function toggleProbList() {
  var w = document.getElementById('prob-list-wrap');
  w.style.display = w.style.display === 'none' ? 'block' : 'none';
}

// Click row to toggle checkbox
document.querySelectorAll('.prob-row').forEach(function(row) {
  row.addEventListener('click', function(e) {
    if (e.target.type === 'checkbox') return;
    var chk = this.querySelector('.prob-chk');
    chk.checked = !chk.checked;
    updateSelected();
  });
});

// Search filter
document.getElementById('prob-search').addEventListener('input', function() {
  var q = this.value.toLowerCase();
  document.querySelectorAll('.prob-row').forEach(function(row) {
    var pid = row.dataset.pid;
    var title = row.dataset.title;
    row.style.display = (!q || pid.indexOf(q) !== -1 || title.indexOf(q) !== -1) ? '' : 'none';
  });
});

function updateSelected() {
  var checks = document.querySelectorAll('.prob-chk:checked');
  var ids = [];
  var tagsHtml = '';
  checks.forEach(function(c) {
    ids.push(c.value);
    tagsHtml += '<span class="sel-tag">#' + c.value +
      '<span class="tag-x" onclick="removeProb(' + c.value + ')">✕</span></span>';
  });
  document.getElementById('f-pids').value = ids.join(',');
  var tagArea = document.getElementById('selected-tags');
  tagArea.innerHTML = ids.length > 0
    ? '<span class="prob-count">📝 ' + ids.length + '문제 선택됨</span>' + tagsHtml
    : '';

  // Highlight checked rows
  document.querySelectorAll('.prob-row').forEach(function(r) {
    r.classList.toggle('checked', r.querySelector('.prob-chk').checked);
  });
}

function removeProb(pid) {
  var chk = document.querySelector('.prob-chk[value="' + pid + '"]');
  if (chk) { chk.checked = false; updateSelected(); }
}
</script>
</body>
</html>
