<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>수업 작성 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <link rel="stylesheet" href="/kindeditor/themes/default/default.css" />
  <link rel="stylesheet" href="/kindeditor/plugins/code/prettify.css" />
  <style>
.cw-wrap{max-width:760px;margin:36px auto;padding:0 16px}
.cw-card{background:#fff;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.08);padding:36px 32px;border:1px solid #f0ecf9}
.cw-card h2{font-size:22px;font-weight:800;color:#7c3aed;margin:0 0 8px;display:flex;align-items:center;gap:8px}
.cw-card .sub-desc{font-size:13px;color:#999;margin-bottom:28px}

.cw-row{display:flex;gap:12px;margin-bottom:18px;align-items:flex-end}
.cw-field{flex:1}
.cw-field label{display:block;font-size:12px;font-weight:700;color:#666;margin-bottom:6px;letter-spacing:0.3px}
.cw-field input,.cw-field select,.cw-field textarea{
  width:100%;padding:11px 14px;border:1.5px solid #e5e0f0;border-radius:10px;
  font-size:14px;color:#333;box-sizing:border-box;font-family:inherit;
  background:#faf9fd;transition:border .2s;
}
.cw-field input:focus,.cw-field select:focus,.cw-field textarea:focus{outline:none;border-color:#7c3aed;background:#fff}
.cw-field.small{max-width:120px;flex:none}

/* Title preview */
.cw-title-preview{
  background:#f8f6fd;border:1.5px solid #e5e0f0;border-radius:10px;
  padding:12px 16px;margin-bottom:18px;font-size:15px;font-weight:700;color:#7c3aed;
  min-height:42px;display:flex;align-items:center;gap:6px;
}
.cw-title-preview .tp-label{font-size:11px;font-weight:600;color:#999;margin-right:4px}

/* Problem picker */
.pp-search{
  display:flex;gap:8px;margin-bottom:8px;
}
.pp-search input{
  flex:1;padding:10px 14px;border:1.5px solid #e5e0f0;border-radius:10px;
  font-size:13px;background:#faf9fd;font-family:inherit;
}
.pp-search input:focus{outline:none;border-color:#7c3aed;background:#fff}
.pp-toggle{
  padding:8px 16px;border-radius:10px;font-size:13px;font-weight:700;
  cursor:pointer;border:1.5px solid #e5e0f0;background:#faf9fd;color:#7c3aed;
  transition:all .15s;font-family:inherit;white-space:nowrap;
}
.pp-toggle:hover{background:#f0ecf9;border-color:#d4c8f0}
.pp-toggle.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

.pp-list{
  display:none;max-height:320px;overflow-y:auto;
  border:1.5px solid #e5e0f0;border-radius:10px;background:#fff;margin-top:8px;
}
.pp-list::-webkit-scrollbar{width:4px}
.pp-list::-webkit-scrollbar-thumb{background:#d4c8f0;border-radius:4px}
.pp-list table{width:100%;border-collapse:collapse;font-size:13px}
.pp-list thead tr{background:#f8f6fd;position:sticky;top:0;z-index:1}
.pp-list th{padding:8px 10px;font-weight:700;color:#666}
.pp-row{transition:background .1s;cursor:pointer}
.pp-row:hover{background:#f8f6fd}
.pp-row.checked{background:#f0ecf9}
.pp-row td{padding:6px 10px}
.pp-chk{width:16px;height:16px;accent-color:#7c3aed;cursor:pointer}

/* Selected tags */
.sel-tags{display:flex;flex-wrap:wrap;gap:6px;min-height:24px}
.sel-tag{
  display:inline-flex;align-items:center;gap:4px;
  padding:4px 10px 4px 12px;border-radius:20px;font-size:12px;font-weight:700;
  background:linear-gradient(135deg,#f0ecf9,#e8e0f8);color:#7c3aed;
  border:1px solid #d4c8f0;animation:tagPop .2s ease;
}
.sel-tag .tx{
  width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-size:10px;cursor:pointer;background:rgba(124,58,237,.15);color:#7c3aed;
  transition:all .15s;line-height:1;
}
.sel-tag .tx:hover{background:#7c3aed;color:#fff}
@keyframes tagPop{from{opacity:0;transform:scale(.8)}to{opacity:1;transform:scale(1)}}
.sel-count{
  display:inline-flex;align-items:center;gap:4px;padding:4px 10px;
  border-radius:20px;font-size:11px;font-weight:700;background:#7c3aed;color:#fff;
}

/* Buttons */
.cw-btn-row{display:flex;gap:10px;margin-top:24px}
.cw-btn{
  padding:12px 28px;border-radius:10px;font-size:15px;font-weight:700;
  cursor:pointer;border:none;font-family:inherit;transition:all .2s;
  text-decoration:none;display:inline-block;text-align:center;
}
.cw-btn-primary{background:#7c3aed;color:#fff}
.cw-btn-primary:hover{background:#6d28d9;transform:translateY(-1px);box-shadow:0 4px 16px rgba(124,58,237,.3)}
.cw-btn-secondary{background:#f0f0f0;color:#555}
.cw-btn-secondary:hover{background:#e0e0e0}

.error-msg{background:#fff0f0;border:1px solid #f5c0c0;border-radius:10px;padding:12px 16px;color:#e74c3c;font-size:14px;margin-bottom:16px}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<?php
  // Parse existing title for edit mode
  $edit_date = '';
  $edit_grade = '';
  $edit_ban = '';
  $edit_subject = '';
  $edit_topic = $class['title'];
  if($cid > 0 && preg_match('/^\((\d+\/\d+)\)\s*\[([^\]]+)\]\s*\[([^\]]+)\]\s*(.+)$/', $class['title'], $m)) {
    $edit_date = $m[1];
    $tag_part = $m[2]; // e.g., "2-3" or "AI-1"
    $edit_subject = $m[3];
    $edit_topic = $m[4];
    if(preg_match('/^(\d)-(\d)$/', $tag_part, $tm)) {
      $edit_grade = $tm[1];
      $edit_ban = $tm[2];
    } else if(preg_match('/^AI-\d$/', $tag_part)) {
      $edit_grade = '3';
      $edit_ban = $tag_part; // "AI-1", "AI-2", "AI-3"
    }
  }
?>
<div class="cw-wrap">
  <div class="cw-card">
    <h2><?php echo $cid > 0 ? '✏️ 수업 수정' : '📚 수업 만들기' ?></h2>
    <div class="sub-desc">날짜·학년·반을 선택하고 주제를 입력하면 제목이 자동 생성됩니다.</div>

    <?php if(isset($error)): ?><div class="error-msg"><?php echo htmlspecialchars($error)?></div><?php endif; ?>

    <form method="POST" id="cwForm">
      <input type="hidden" name="title" id="f-title">
      <input type="hidden" name="tag" id="f-tag" value="<?php echo htmlspecialchars($class['tag'] ?? '')?>">

      <!-- Row 1: Date / Grade / Class / Subject -->
      <div class="cw-row">
        <div class="cw-field small">
          <label>📅 날짜</label>
          <input type="text" id="f-date" value="<?php echo $edit_date ?: date('n/j')?>" placeholder="4/2">
        </div>
        <div class="cw-field" style="max-width:140px">
          <label>🎓 학년</label>
          <select id="f-grade">
            <option value="">선택</option>
            <option value="2" <?php if($edit_grade=='2') echo 'selected'?>>2학년</option>
            <option value="3" <?php if($edit_grade=='3') echo 'selected'?>>3학년</option>
          </select>
        </div>
        <div class="cw-field" style="max-width:120px">
          <label>🏫 반</label>
          <select id="f-ban">
            <option value="">선택</option>
          </select>
        </div>
        <div class="cw-field" style="max-width:160px">
          <label>📘 과목</label>
          <input type="text" id="f-subject" value="<?php echo htmlspecialchars($edit_subject)?>" readonly style="background:#f0ecf9;color:#7c3aed;font-weight:700">
        </div>
      </div>

      <!-- Row 2: Topic -->
      <div class="cw-row">
        <div class="cw-field">
          <label>✏️ 주제</label>
          <input type="text" id="f-topic" value="<?php echo htmlspecialchars($edit_topic)?>" placeholder="예: 조건문 1" autocomplete="off">
        </div>
      </div>

      <!-- Title preview -->
      <div class="cw-title-preview" id="title-preview">
        <span class="tp-label">제목:</span>
        <span id="title-text">-</span>
      </div>

      <!-- Description -->
      <div class="cw-row">
        <div class="cw-field">
          <label>📄 수업 설명 (선택)</label>
          <input type="text" name="description" value="<?php echo htmlspecialchars($class['description'])?>" placeholder="수업 목록에 표시되는 짧은 설명">
        </div>
      </div>

      <!-- Content (KindEditor) -->
      <div class="cw-row">
        <div class="cw-field">
          <label>📖 수업 내용 (선택)</label>
          <textarea name="content" id="ke-content"><?php echo htmlspecialchars($class['content'])?></textarea>
        </div>
      </div>

      <!-- Problem picker -->
      <div class="cw-row">
        <div class="cw-field">
          <label>📝 문제 선택</label>
          <input type="hidden" name="problem_ids" id="f-pids" value="<?php echo htmlspecialchars($class['problem_ids'] ?? '')?>">
          <div class="pp-search">
            <input type="text" id="pp-search-input" placeholder="문제 ID 또는 제목 검색...">
            <button type="button" class="pp-toggle" id="pp-toggle-btn" onclick="toggleList()">📋 목록</button>
          </div>
          <div class="sel-tags" id="sel-tags"></div>
          <div class="pp-list" id="pp-list">
            <table>
              <thead>
                <tr>
                  <th style="width:40px;text-align:center"></th>
                  <th style="width:60px;text-align:center">ID</th>
                  <th style="text-align:left">제목</th>
                </tr>
              </thead>
              <tbody id="pp-tbody">
              <?php
                $all_probs = pdo_query("SELECT problem_id, title FROM problem WHERE defunct='N' ORDER BY problem_id ASC");
                if($all_probs) foreach($all_probs as $p):
              ?>
                <tr class="pp-row" data-pid="<?php echo $p['problem_id']?>" data-title="<?php echo htmlspecialchars(strtolower($p['title']))?>">
                  <td style="text-align:center"><input type="checkbox" class="pp-chk" value="<?php echo $p['problem_id']?>"></td>
                  <td style="text-align:center;font-weight:700;color:#7c3aed"><?php echo $p['problem_id']?></td>
                  <td><?php echo htmlspecialchars($p['title'])?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="cw-btn-row">
        <button type="submit" class="cw-btn cw-btn-primary">💾 저장</button>
        <a href="/classop.php" class="cw-btn cw-btn-secondary">취소</a>
      </div>
    </form>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script charset="utf-8" src="/kindeditor/kindeditor.js?v=20260309"></script>
<script charset="utf-8" src="/kindeditor/lang/ko.js"></script>
<script>
KindEditor.ready(function(K) {
  K.create('#ke-content', {
    width:'100%', height:'300px',
    uploadJson:'/kindeditor/php/upload_json.php',
    fileManagerJson:'/kindeditor/php/file_manager_json.php',
    allowFileManager:false, allowImageRemote:true, filterMode:false, pasteType:2,
    afterBlur:function(){this.sync()}, afterChange:function(){this.sync()}
  });
});

// === Config ===
var banMap = {2:[1,2,3,4,5,6,7,8], 3:[{v:'AI-1',t:'AI-1 박지훈T'},{v:'AI-2',t:'AI-2 박지훈T'},{v:'AI-3',t:'AI-3 안예찬T'}]};
var subjectMap = {2:'정보', 3:'인공지능기초'};

var fDate = document.getElementById('f-date');
var fGrade = document.getElementById('f-grade');
var fBan = document.getElementById('f-ban');
var fSubject = document.getElementById('f-subject');
var fTopic = document.getElementById('f-topic');
var fTitle = document.getElementById('f-title');
var fTag = document.getElementById('f-tag');
var titleText = document.getElementById('title-text');

// === Grade → Ban options + Subject ===
function onGradeChange() {
  var g = parseInt(fGrade.value) || 0;
  fBan.innerHTML = '<option value="">선택</option>';
  if(g && banMap[g]) {
    banMap[g].forEach(function(b) {
      var opt = document.createElement('option');
      if(typeof b === 'object') { opt.value = b.v; opt.textContent = b.t; }
      else { opt.value = b; opt.textContent = b + '반'; }
      fBan.appendChild(opt);
    });
  }
  fSubject.value = subjectMap[g] || '';
  <?php if($edit_ban): ?>
  // Restore selected ban on edit
  if(!fBan._restored && fGrade.value == '<?php echo $edit_grade?>') {
    fBan.value = '<?php echo $edit_ban?>';
    fBan._restored = true;
  }
  <?php endif; ?>
  updateTitle();
}
fGrade.addEventListener('change', onGradeChange);
fBan.addEventListener('change', updateTitle);
fTopic.addEventListener('input', updateTitle);
fDate.addEventListener('input', updateTitle);

function updateTitle() {
  var date = fDate.value.trim();
  var g = fGrade.value;
  var b = fBan.value;
  var subj = fSubject.value;
  var topic = fTopic.value.trim();

  if(!g || !b || !topic) {
    titleText.textContent = '학년, 반, 주제를 입력하세요';
    titleText.style.opacity = '0.4';
    return;
  }
  var tag = (b.indexOf('AI') === 0) ? b : g + '-' + b;
  var title = (date ? '(' + date + ') ' : '') + '[' + tag + '] [' + subj + '] ' + topic;
  titleText.textContent = title;
  titleText.style.opacity = '1';
  fTitle.value = title;
  fTag.value = tag;
}

// Init on load
onGradeChange();
updateTitle();

// === Problem Picker ===
function toggleList() {
  var list = document.getElementById('pp-list');
  var btn = document.getElementById('pp-toggle-btn');
  if(list.style.display === 'block') {
    list.style.display = 'none';
    btn.classList.remove('active');
  } else {
    list.style.display = 'block';
    btn.classList.add('active');
  }
}

// Search
document.getElementById('pp-search-input').addEventListener('input', function() {
  var q = this.value.toLowerCase();
  document.querySelectorAll('.pp-row').forEach(function(row) {
    var pid = row.dataset.pid;
    var title = row.dataset.title;
    row.style.display = (!q || pid.indexOf(q)!==-1 || title.indexOf(q)!==-1) ? '' : 'none';
  });
  // Auto open list on search
  if(q) { document.getElementById('pp-list').style.display = 'block'; document.getElementById('pp-toggle-btn').classList.add('active'); }
});

// Click row
document.querySelectorAll('.pp-row').forEach(function(row) {
  row.addEventListener('click', function(e) {
    if(e.target.type==='checkbox') return;
    var chk = this.querySelector('.pp-chk');
    chk.checked = !chk.checked;
    refreshSelected();
  });
});

// Checkbox change
document.querySelectorAll('.pp-chk').forEach(function(chk) {
  chk.addEventListener('change', refreshSelected);
});

function refreshSelected() {
  var checks = document.querySelectorAll('.pp-chk:checked');
  var ids = [];
  var html = '';
  checks.forEach(function(c) {
    ids.push(c.value);
    html += '<span class="sel-tag">#'+c.value+'<span class="tx" onclick="rmProb('+c.value+')">✕</span></span>';
  });
  document.getElementById('f-pids').value = ids.join(', ');
  document.getElementById('sel-tags').innerHTML = ids.length
    ? '<span class="sel-count">📝 '+ids.length+'문제</span>' + html : '';
  document.querySelectorAll('.pp-row').forEach(function(r){
    r.classList.toggle('checked', r.querySelector('.pp-chk').checked);
  });
}

function rmProb(pid) {
  var c = document.querySelector('.pp-chk[value="'+pid+'"]');
  if(c){c.checked=false; refreshSelected();}
}

// Restore selected on edit
(function(){
  var cur = document.getElementById('f-pids').value;
  if(!cur) return;
  cur.split(',').forEach(function(id) {
    id = id.trim();
    var c = document.querySelector('.pp-chk[value="'+id+'"]');
    if(c) c.checked = true;
  });
  refreshSelected();
})();

// Form submit
document.getElementById('cwForm').addEventListener('submit', function(e) {
  if(!fTitle.value) {
    e.preventDefault();
    alert('학년, 반, 주제를 모두 입력해주세요.');
    return;
  }
});
</script>
</body>
</html>
