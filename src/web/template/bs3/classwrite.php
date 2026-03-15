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
.cw-wrap{max-width:960px;margin:36px auto;padding:0 16px}
.cw-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:36px}
.cw-card h2{font-size:20px;font-weight:700;color:#7c3aed;margin:0 0 28px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:13px;font-weight:600;color:#555;margin-bottom:7px}
.form-group input[type=text]{width:100%;padding:11px 14px;border:1.5px solid #e0e0e0;border-radius:8px;font-size:14px;color:#333;box-sizing:border-box;font-family:inherit}
.form-group input:focus{outline:none;border-color:#7c3aed}
.pid-tip{background:#f0f6ff;border:1px solid #c8dff8;border-radius:8px;padding:12px 16px;margin-bottom:8px;font-size:13px;color:#7c3aed}
.error-msg{background:#fff0f0;border:1px solid #f5c0c0;border-radius:8px;padding:12px 16px;color:#e74c3c;font-size:14px;margin-bottom:16px}
.btn-row{display:flex;gap:10px;margin-top:24px}
.btn-submit{background:#7c3aed;color:#fff;border:none;padding:11px 28px;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer}
.btn-submit:hover{background:#6d28d9}
.btn-cancel{background:#f0f0f0;color:#555;padding:11px 20px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none}
.btn-cancel:hover{background:#e0e0e0}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cw-wrap">
  <div class="cw-card">
    <h2><?php echo $cid > 0 ? '✏️ 수업 수정' : '📚 수업 만들기' ?></h2>
    <?php if(isset($error)): ?><div class="error-msg"><?php echo htmlspecialchars($error)?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>수업 제목</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($class['title'])?>" placeholder="예) 1단원 - 변수와 입출력">
      </div>
      <div class="form-group">
        <label>수업 설명 (한 줄 요약)</label>
        <input type="text" name="description" value="<?php echo htmlspecialchars($class['description'])?>" placeholder="수업 목록에 표시되는 짧은 설명">
      </div>
      <div class="form-group">
        <label>수업 내용 (이미지/설명 자유롭게 작성)</label>
        <textarea name="content" id="ke-content"><?php echo htmlspecialchars($class['content'])?></textarea>
      </div>
      <div class="form-group">
        <div class="pid-tip">📝 문제 ID를 쉼표로 구분해서 입력하세요. 예) <code>1001, 1002, 1003</code></div>
        <label>문제 ID 목록</label>
        <input type="text" name="problem_ids" value="<?php echo htmlspecialchars($class['problem_ids'])?>" placeholder="예) 1001, 1002, 1003">
      </div>
      <div class="btn-row">
        <button type="submit" class="btn-submit">저장</button>
        <a href="classop.php" class="btn-cancel">취소</a>
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
    height: '400px',
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
