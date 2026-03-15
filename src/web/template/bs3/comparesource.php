<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>소스 비교 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<link type="text/css" rel="stylesheet" href="mergely/codemirror.css"/>
<link type="text/css" rel="stylesheet" href="mergely/mergely.css"/>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.cs-wrap { max-width: 1300px; margin: 32px auto; padding: 0 20px 60px; }
.cs-header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.cs-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.cs-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
}
.cs-toolbar {
  padding: 12px 20px;
  background: #f8fafc;
  border-bottom: 1px solid #e5e9f0;
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}
.cs-toolbar label { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #555; cursor: pointer; }
.cs-toolbar input[type=checkbox] { cursor: pointer; }
.cs-paths {
  padding: 10px 20px;
  background: #1e1e1e;
  display: flex;
  gap: 0;
}
.cs-path-item {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 8px;
}
.cs-path-item:first-child { border-right: 1px solid #333; }
.cs-path-label { font-family: monospace; font-size: 12px; color: #888; flex: 1; }
.cs-save-link { color: #60a5fa; font-size: 12px; text-decoration: none; }
.cs-save-link:hover { text-decoration: underline; }
.cs-diff-wrap { background: #1e1e1e; }
#mergely-resizer { height: 600px; padding: 0 4px 4px; }
#compare { height: 100%; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cs-wrap">
  <div class="cs-header">
    <span style="font-size:24px">🔀</span>
    <h2>소스 코드 비교</h2>
    <span style="font-size:13px;color:#888">
      #<?php echo intval($_GET['left'])?> vs #<?php echo intval($_GET['right'])?>
    </span>
  </div>

  <div class="cs-card">
    <div class="cs-toolbar">
      <label>
        <input type="checkbox" id="ignorews"> 공백 무시
      </label>
      <span style="font-size:12px;color:#aaa;margin-left:auto">좌/우 코드를 드래그&드롭으로 교체할 수 있습니다</span>
    </div>
    <div class="cs-paths">
      <div class="cs-path-item">
        <span class="cs-path-label" id="path-lhs">채점번호 #<?php echo intval($_GET['left'])?></span>
        <a class="cs-save-link" id="save-lhs" href="#">💾 저장</a>
      </div>
      <div class="cs-path-item">
        <span class="cs-path-label" id="path-rhs">채점번호 #<?php echo intval($_GET['right'])?></span>
        <a class="cs-save-link" id="save-rhs" href="#">💾 저장</a>
      </div>
    </div>
    <div class="cs-diff-wrap">
      <div id="mergely-resizer">
        <div id="compare"></div>
      </div>
    </div>
  </div>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script type="text/javascript" src="mergely/codemirror.js"></script>
<script type="text/javascript" src="mergely/mergely.js"></script>
<script>
$(document).ready(function(){
  $('#compare').mergely({
    width: 'auto',
    height: 'auto',
    cmsettings: { readOnly: false },
  });

  var lhs_url = 'getsource.php?id=<?php echo intval($_GET['left'])?>';
  var rhs_url = 'getsource.php?id=<?php echo intval($_GET['right'])?>';

  $.ajax({ type:'GET', async:true, dataType:'text', url:lhs_url,
    success: function(r){ $('#path-lhs').text('소스 #<?php echo intval($_GET['left'])?>'); $('#compare').mergely('lhs', r); }
  });
  $.ajax({ type:'GET', async:true, dataType:'text', url:rhs_url,
    success: function(r){ $('#path-rhs').text('소스 #<?php echo intval($_GET['right'])?>'); $('#compare').mergely('rhs', r); }
  });

  function download_content(a, side){
    var txt = $('#compare').mergely('get', side);
    var uri = "data:plain/text;charset=UTF-8," + encodeURIComponent(txt);
    a.setAttribute('download', side+'.txt');
    a.setAttribute('href', uri);
  }
  document.getElementById('save-lhs').addEventListener('mouseover', function(){ download_content(this, 'lhs'); });
  document.getElementById('save-rhs').addEventListener('mouseover', function(){ download_content(this, 'rhs'); });
  document.getElementById('ignorews').addEventListener('change', function(){
    $('#compare').mergely('options', { ignorews: this.checked });
  });

  // 드래그&드롭
  function readFile(file, side){
    var reader = new FileReader();
    reader.onload = function(){ $('#path-'+side).text(file.name); $('#compare').mergely(side, reader.result); };
    reader.readAsBinaryString(file);
  }
  document.body.addEventListener('dragover', function(e){ e.stopPropagation(); e.preventDefault(); e.dataTransfer.dropEffect='copy'; });
  document.body.addEventListener('drop', function(e){
    e.stopPropagation(); e.preventDefault();
    var files = e.dataTransfer.files;
    if(files.length>0) readFile(files[0],'lhs');
    if(files.length>1) readFile(files[1],'rhs');
  });
});
</script>
</body>
</html>
