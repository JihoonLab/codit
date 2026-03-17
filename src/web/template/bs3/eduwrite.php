<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>교안 작성 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.edu-wrap{max-width:960px;margin:36px auto;padding:0 16px}
.edu-write-card{background:#fff;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,.08);padding:40px;border:1px solid #e5e9f0}
.edu-write-card h2{font-size:24px;font-weight:800;color:#1a1a2e;margin:0 0 28px}
.edu-write-card h2 em{color:#7c3aed;font-style:normal}

.form-group{margin-bottom:22px}
.form-group label{display:block;font-size:14px;font-weight:700;color:#333;margin-bottom:8px}
.form-group label .required{color:#ef4444;margin-left:2px}

.form-group input[type=text],.form-group textarea,.form-group select{
  width:100%;padding:12px 16px;border:1.5px solid #e5e9f0;border-radius:10px;
  font-size:15px;color:#333;box-sizing:border-box;font-family:inherit;
  background:#f8f9fc;transition:all .2s;
}
.form-group input[type=text]:focus,.form-group textarea:focus,.form-group select:focus{
  outline:none;border-color:#7c3aed;background:#fff;
  box-shadow:0 0 0 3px rgba(124,58,237,.1);
}
.form-group textarea{height:420px;resize:vertical;line-height:1.7}
.form-group select{cursor:pointer;appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 14px center;padding-right:36px;
}

/* 태그 선택 버튼 스타일 */
.tag-select-wrap{display:flex;gap:8px;flex-wrap:wrap}
.tag-select-btn{
  padding:10px 22px;border-radius:12px;font-size:14px;font-weight:700;
  cursor:pointer;border:2px solid #e5e9f0;background:#f8f9fc;color:#666;transition:all .2s;
}
.tag-select-btn:hover{border-color:#c4b5fd;color:#7c3aed;background:#faf5ff}
.tag-select-btn.selected{background:#7c3aed;color:#fff;border-color:#7c3aed;box-shadow:0 2px 8px rgba(124,58,237,.25)}
.tag-select-btn[data-tag="정보"].selected{background:#3b82f6;border-color:#3b82f6;box-shadow:0 2px 8px rgba(59,130,246,.25)}
.tag-select-btn[data-tag="인공지능기초"].selected{background:#7c3aed;border-color:#7c3aed}

.tag-custom-wrap{display:flex;gap:8px;margin-top:10px;align-items:center}
.tag-custom-wrap input{flex:1;max-width:250px}
.tag-custom-add{
  padding:10px 18px;border-radius:10px;font-size:13px;font-weight:700;
  cursor:pointer;border:none;background:#7c3aed;color:#fff;transition:all .2s;
}
.tag-custom-add:hover{background:#6d28d9}

.img-tip{
  background:linear-gradient(135deg,#faf5ff,#ede9fe);
  border:1.5px solid #ddd6fe;border-radius:12px;padding:14px 18px;margin-bottom:22px;
  font-size:13px;color:#6d28d9;
}
.img-tip strong{display:block;margin-bottom:6px;font-size:14px}

/* 파일 업로드 영역 */
.file-upload-area{margin-top:4px}
.file-drop-zone{
  border:2px dashed #d4d4d8;border-radius:12px;padding:28px 20px;
  text-align:center;cursor:pointer;transition:all .2s;
  background:#fafafe;
}
.file-drop-zone:hover{border-color:#7c3aed;background:#faf5ff}
.file-drop-zone.dragover{border-color:#7c3aed;background:#f3e8ff;transform:scale(1.01)}
.file-drop-icon{font-size:32px;display:block;margin-bottom:8px}
.file-drop-text{font-size:15px;font-weight:700;color:#555;display:block}
.file-drop-hint{font-size:12px;color:#999;display:block;margin-top:4px}

.file-item{
  display:flex;align-items:center;gap:10px;padding:10px 14px;
  background:#fff;border:1.5px solid #e5e9f0;border-radius:10px;margin-top:8px;
  transition:all .2s;
}
.file-item:hover{border-color:#c4b5fd}
.file-item-icon{font-size:20px}
.file-item-name{flex:1;font-size:14px;font-weight:600;color:#333;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.file-item-size{font-size:12px;color:#999}
.file-item-del{
  width:28px;height:28px;border-radius:6px;border:none;
  background:#fef2f2;color:#ef4444;font-size:14px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;transition:all .2s;
}
.file-item-del:hover{background:#fee2e2;transform:scale(1.1)}
.file-uploading{color:#7c3aed;font-size:13px;font-weight:600;padding:12px 0}

.error-msg{
  background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;
  padding:14px 18px;color:#dc2626;font-size:14px;font-weight:600;margin-bottom:18px;
}

.btn-row{display:flex;gap:12px;margin-top:8px}
.btn-submit{
  background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:none;
  padding:13px 32px;border-radius:10px;font-size:16px;font-weight:700;
  cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(124,58,237,.3);
}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.4)}
.btn-cancel{
  background:#f3f4f6;color:#555;padding:13px 24px;border-radius:10px;
  font-size:15px;font-weight:600;text-decoration:none;transition:all .2s;
}
.btn-cancel:hover{background:#e5e7eb;color:#333}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">
  <div class="edu-write-card">
    <h2><?php echo $edu_id > 0 ? '✏️ <em>교안</em> 수정' : '📝 <em>교안</em> 작성' ?></h2>

    <?php if(isset($error)): ?><div class="error-msg">⚠️ <?php echo htmlspecialchars($error)?></div><?php endif; ?>

    <div class="img-tip">
      <strong>⌨️ KindEditor 단축키 모음</strong>
      <div style="display:flex;flex-wrap:wrap;gap:6px 20px;margin-top:8px">
        <span><kbd>Ctrl+B</kbd> 굵게</span>
        <span><kbd>Ctrl+I</kbd> 기울임</span>
        <span><kbd>Ctrl+U</kbd> 밑줄</span>
        <span><kbd>Ctrl+Z</kbd> 되돌리기</span>
        <span><kbd>Ctrl+Y</kbd> 다시실행</span>
        <span><kbd>Ctrl+A</kbd> 전체선택</span>
      </div>
    </div>

    <form method="POST">
      <div class="form-group">
        <label>과목 태그 <span class="required">*</span></label>
        <input type="hidden" name="tag" id="tag-input" value="<?php echo htmlspecialchars($news['tag'] ?? '')?>">
        <div class="tag-select-wrap" id="tag-buttons">
          <?php
          $preset_tags = ['정보', '인공지능기초'];
          // 기존 태그 중 프리셋에 없는 것도 포함
          $all_tag_list = $preset_tags;
          if(!empty($existing_tags)) {
            foreach($existing_tags as $et) {
              if(!in_array($et, $all_tag_list)) $all_tag_list[] = $et;
            }
          }
          $current_tag = $news['tag'] ?? '';
          foreach($all_tag_list as $t):
            $sel = ($current_tag === $t) ? ' selected' : '';
          ?>
          <button type="button" class="tag-select-btn<?php echo $sel?>" data-tag="<?php echo htmlspecialchars($t)?>" onclick="selectTag(this)"><?php echo htmlspecialchars($t)?></button>
          <?php endforeach; ?>
        </div>
        <div class="tag-custom-wrap">
          <input type="text" id="custom-tag" placeholder="새 태그 직접 입력..." style="font-size:13px;padding:8px 12px">
          <button type="button" class="tag-custom-add" onclick="addCustomTag()">+ 추가</button>
        </div>
      </div>

      <div class="form-group">
        <label>제목 <span class="required">*</span></label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title'])?>" placeholder="교안 제목을 입력하세요">
      </div>

      <div class="form-group">
        <label>📎 파일 첨부 (PDF, PPT 등)</label>
        <div class="file-upload-area" id="file-upload-area">
          <div class="file-drop-zone" id="file-drop-zone" onclick="document.getElementById('file-input').click()">
            <span class="file-drop-icon">📄</span>
            <span class="file-drop-text">클릭하거나 파일을 드래그하세요</span>
            <span class="file-drop-hint">PDF, PPT, HWP, DOC, ZIP 등</span>
          </div>
          <input type="file" id="file-input" accept=".pdf,.ppt,.pptx,.hwp,.doc,.docx,.xls,.xlsx,.zip,.rar" style="display:none" onchange="uploadFile(this)">
          <div id="file-list"></div>
        </div>
      </div>

      <div class="form-group">
        <label>내용 <span class="required">*</span></label>
        <textarea name="content" id="ke-content" class="kindeditor" placeholder="교안 내용을 입력하세요."><?php echo htmlspecialchars($news['content'])?></textarea>
      </div>

      <div class="btn-row">
        <button type="submit" class="btn-submit">💾 저장</button>
        <a href="edulist.php" class="btn-cancel">취소</a>
      </div>
    </form>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script charset="utf-8" src="/kindeditor/kindeditor.js?v=20260309"></script>
<script charset="utf-8" src="/kindeditor/lang/ko.js"></script>
<script>
KindEditor.ready(function(K) {
  window.keEditor = K.create('#ke-content', {
    width: '100%',
    height: '500px',
    uploadJson: '/kindeditor/php/upload_json.php',
    fileManagerJson: '/kindeditor/php/file_manager_json.php',
    allowFileManager: false,
    allowImageRemote: true,
    filterMode: false,
    pasteType: 2,
    afterBlur: function() { this.sync(); },
    afterChange: function() { this.sync(); }
  });
});

function selectTag(btn) {
  var prevTag = document.getElementById('tag-input').value;
  document.querySelectorAll('.tag-select-btn').forEach(function(b){ b.classList.remove('selected'); });
  btn.classList.add('selected');
  var newTag = btn.dataset.tag;
  document.getElementById('tag-input').value = newTag;

  // 제목 필드에 [태그] 접두사 자동 업데이트
  var titleInput = document.querySelector('input[name="title"]');
  var title = titleInput.value;
  // 기존 [태그] 접두사 제거
  title = title.replace(/^\[.*?\]\s*/, '');
  // 새 접두사 추가
  titleInput.value = '[' + newTag + '] ' + title;
}

function addCustomTag() {
  var val = document.getElementById('custom-tag').value.trim();
  if(!val) return;
  // 이미 있으면 선택만
  var existing = document.querySelector('.tag-select-btn[data-tag="'+val+'"]');
  if(existing) { selectTag(existing); return; }
  var btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'tag-select-btn selected';
  btn.dataset.tag = val;
  btn.textContent = val;
  btn.onclick = function(){ selectTag(this); };
  document.querySelectorAll('.tag-select-btn').forEach(function(b){ b.classList.remove('selected'); });
  document.getElementById('tag-buttons').appendChild(btn);
  document.getElementById('tag-input').value = val;
  document.getElementById('custom-tag').value = '';
  // 제목에도 반영
  var titleInput = document.querySelector('input[name="title"]');
  var title = titleInput.value.replace(/^\[.*?\]\s*/, '');
  titleInput.value = '[' + val + '] ' + title;
}

// 파일 업로드
function uploadFile(input) {
  var file = input.files[0];
  if(!file) return;
  var maxSize = 50 * 1024 * 1024; // 50MB
  if(file.size > maxSize) { alert('파일 크기가 50MB를 초과합니다.'); return; }

  var formData = new FormData();
  formData.append('file', file);
  
  var listDiv = document.getElementById('file-list');
  var loadingDiv = document.createElement('div');
  loadingDiv.className = 'file-uploading';
  loadingDiv.textContent = '⏳ 업로드 중... ' + file.name;
  listDiv.appendChild(loadingDiv);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/ajax_upload.php');
  xhr.onload = function() {
    listDiv.removeChild(loadingDiv);
    if(xhr.status === 200) {
      var text = xhr.responseText.trim();
      // HTML 응답이면 로그인 필요
      if(text.charAt(0) === '<') { alert('로그인이 필요합니다. 페이지를 새로고침하세요.'); return; }
      var jsonStart = text.indexOf('{');
      var jsonEnd = text.lastIndexOf('}');
      if(jsonStart >= 0 && jsonEnd > jsonStart) text = text.substring(jsonStart, jsonEnd + 1);
      var res;
      try { res = JSON.parse(text); } catch(e) { alert('JSON 파싱 오류: ' + e.message); return; }
      if(res.error === 0 && res.url) {
        try {
          addFileToList(res.url, file.name, file.size);
          insertFileToEditor(res.url, file.name);
        } catch(e2) { console.log('파일 목록 추가 중 오류:', e2); }
      } else {
        alert('업로드 실패: ' + (res.message || '알 수 없는 오류'));
      }
    } else { alert('업로드 실패 (HTTP ' + xhr.status + ')'); }
    input.value = '';
  };
  xhr.onerror = function() {
    listDiv.removeChild(loadingDiv);
    alert('업로드 중 네트워크 오류');
    input.value = '';
  };
  xhr.send(formData);
}

function addFileToList(url, name, size) {
  var ext = name.split('.').pop().toLowerCase();
  var icon = '📄';
  if(ext === 'pdf') icon = '📕';
  else if(ext === 'ppt' || ext === 'pptx') icon = '📊';
  else if(ext === 'hwp') icon = '📃';
  else if(ext === 'doc' || ext === 'docx') icon = '📝';
  else if(ext === 'zip' || ext === 'rar') icon = '🗜️';

  var sizeStr = size < 1024*1024 ? Math.round(size/1024) + 'KB' : (size/(1024*1024)).toFixed(1) + 'MB';

  var div = document.createElement('div');
  div.className = 'file-item';
  div.dataset.url = url;
  div.innerHTML = '<span class="file-item-icon">' + icon + '</span>' +
    '<span class="file-item-name">' + name + '</span>' +
    '<span class="file-item-size">' + sizeStr + '</span>' +
    '<button type="button" class="file-item-del" onclick="removeFile(this)" title="삭제">✕</button>';
  document.getElementById('file-list').appendChild(div);
}

function insertFileToEditor(url, name) {
  var ext = name.split('.').pop().toLowerCase();
  var html = '';
  if(ext === 'pdf') {
    html = '<p><a href="' + url + '" target="_blank">📕 ' + name + '</a></p>';
  } else {
    html = '<p><a href="' + url + '" target="_blank">📎 ' + name + '</a></p>';
  }
  if(window.keEditor) {
    window.keEditor.html(window.keEditor.html() + html);
    window.keEditor.sync();
  } else {
    // fallback: textarea에 직접 추가
    var ta = document.getElementById('ke-content');
    ta.value = ta.value + html;
  }
}

function removeFile(btn) {
  var item = btn.closest('.file-item');
  if(!item) { alert('삭제 오류'); return; }
  var url = item.dataset.url;
  // 에디터에서도 제거
  try {
    var editorHtml = window.keEditor ? window.keEditor.html() : document.getElementById('ke-content').value;
    // URL에 포함된 해당 a태그 라인 제거
    var tempDiv = document.createElement('div');
    tempDiv.innerHTML = editorHtml;
    var links = tempDiv.querySelectorAll('a[href="' + url + '"]');
    links.forEach(function(a) {
      var parent = a.parentNode;
      if(parent && parent.tagName === 'P') parent.remove();
      else a.remove();
    });
    if(window.keEditor) { window.keEditor.html(tempDiv.innerHTML); window.keEditor.sync(); }
    else { document.getElementById('ke-content').value = tempDiv.innerHTML; }
  } catch(e) { console.log('에디터 제거 실패', e); }
  item.remove();
}

// 드래그앤드롭
var dropZone = document.getElementById('file-drop-zone');
['dragenter','dragover'].forEach(function(ev) {
  dropZone.addEventListener(ev, function(e) { e.preventDefault(); dropZone.classList.add('dragover'); });
});
['dragleave','drop'].forEach(function(ev) {
  dropZone.addEventListener(ev, function(e) { e.preventDefault(); dropZone.classList.remove('dragover'); });
});
dropZone.addEventListener('drop', function(e) {
  var files = e.dataTransfer.files;
  if(files.length > 0) {
    var input = document.getElementById('file-input');
    input.files = files;
    uploadFile(input);
  }
});

// 기존 content에서 파일 목록 복원 (수정 모드)
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    var html = window.keEditor ? window.keEditor.html() : document.getElementById('ke-content').value;
    var regex = /<a[^>]*href="(\/upload\/(?:file|image)\/[^"]+)"[^>]*>.*?<\/a>/gi;
    var match;
    while((match = regex.exec(html)) !== null) {
      var url = match[1];
      var name = decodeURIComponent(url.split('/').pop());
      addFileToList(url, name, 0);
    }
  }, 500);
});
</script>
</body>
</html>
