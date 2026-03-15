<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>교안 작성 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>

  <style>
.edu-wrap{max-width:960px;margin:36px auto;padding:0 16px}
.edu-write-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:36px}
.edu-write-card h2{font-size:20px;font-weight:700;color:#7c3aed;margin:0 0 28px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:7px}
.form-group input[type=text],.form-group textarea{width:100%;padding:11px 14px;border:1.5px solid #e0e0e0;border-radius:8px;font-size:14px;color:#333;box-sizing:border-box;font-family:inherit}
.form-group input[type=text]:focus,.form-group textarea:focus{outline:none;border-color:#7c3aed}
.form-group textarea{height:420px;resize:vertical;line-height:1.7}
.img-tip{background:#f0f6ff;border:1px solid #c8dff8;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#7c3aed}
.img-tip strong{display:block;margin-bottom:4px}
.error-msg{background:#fff0f0;border:1px solid #f5c0c0;border-radius:8px;padding:12px 16px;color:#e74c3c;font-size:14px;margin-bottom:16px}
.btn-row{display:flex;gap:10px}
.btn-submit{background:#7c3aed;color:#fff;border:none;padding:11px 28px;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer}
.btn-submit:hover{background:#6d28d9}
.btn-cancel{background:#f0f0f0;color:#555;padding:11px 20px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none}
.btn-cancel:hover{background:#e0e0e0;color:#333}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">
  <div class="edu-write-card">
    <h2><?php echo $edu_id > 0 ? '✏️ 교안 수정' : '📝 교안 작성' ?></h2>
    <?php if(isset($error)): ?><div class="error-msg"><?php echo htmlspecialchars($error)?></div><?php endif; ?>
    <div class="img-tip">
      <strong>⌨️ KindEditor 단축키 모음</strong>
      <div style="display:flex;flex-wrap:wrap;gap:6px 20px;margin-top:8px">
        <span><kbd>Ctrl+B</kbd> 굵게</span>
        <span><kbd>Ctrl+I</kbd> 기울임</span>
        <span><kbd>Ctrl+U</kbd> 밑줄</span>
        <span><kbd>Ctrl+Z</kbd> 되돌리기</span>
        <span><kbd>Ctrl+Y</kbd> 다시실행</span>
        <span><kbd>Ctrl+A</kbd> 전체선택</span>
        <span><kbd>Ctrl+C</kbd> 복사</span>
        <span><kbd>Ctrl+V</kbd> 붙여넣기</span>
      </div>
    </div>
    <form method="POST">
      <div class="form-group">
        <label>제목</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title'])?>" placeholder="교안 제목을 입력하세요">
      </div>
      <div class="form-group">
        <label>내용</label>
        <textarea name="content" id="ke-content" class="kindeditor" placeholder="교안 내용을 입력하세요."><?php echo htmlspecialchars($news['content'])?></textarea>
      </div>
      <div class="btn-row">
        <button type="submit" class="btn-submit">저장</button>
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
  var editor1 = K.create('#ke-content', {
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
</script>
<script charset="utf-8" src="/kindeditor/kindeditor.js?v=20260309"></script>
<script charset="utf-8" src="/kindeditor/lang/ko.js"></script>


</body>
</html>
