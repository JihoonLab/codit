<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>소스 보기 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

.ss-wrap { max-width: 960px; margin: 32px auto; padding: 0 20px 60px; }

/* 메타 카드 */
.ss-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.ss-card-header {
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  color: #fff;
  padding: 18px 28px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.ss-card-header h2 { margin: 0; font-size: 20px; font-weight: 700; }
.ss-card-header .sid-badge {
  margin-left: auto;
  background: rgba(255,255,255,0.2);
  border-radius: 6px;
  padding: 4px 12px;
  font-size: 13px;
  font-weight: 600;
}

/* 메타 정보 그리드 */
.ss-meta {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 0;
  border-top: 1px solid #f0f0f0;
}
.ss-meta-item {
  padding: 14px 20px;
  border-right: 1px solid #f0f0f0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.ss-meta-item:last-child { border-right: none; }
.ss-meta-label { font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.ss-meta-value { font-size: 15px; font-weight: 700; color: #222; }
.ss-meta-value a { color: #7c3aed; text-decoration: none; }
.ss-meta-value a:hover { text-decoration: underline; }

/* 결과 배지 */
.result-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 700;
}
.result-ac   { background: #d1fae5; color: #059669; }
.result-wa   { background: #fee2e2; color: #dc2626; }
.result-tle  { background: #fef3c7; color: #d97706; }
.result-mle  { background: #ede9fe; color: #7c3aed; }
.result-re   { background: #ffe4e6; color: #e11d48; }
.result-ce   { background: #f1f5f9; color: #64748b; }
.result-etc  { background: #f1f5f9; color: #64748b; }

/* 소스 코드 카드 */
.ss-src-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  overflow: hidden;
}
.ss-src-header {
  background: #1e1e1e;
  color: #ccc;
  padding: 12px 20px;
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}
.ss-lang-tag {
  background: #3a3a3a;
  color: #aaa;
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 12px;
}
.ss-src-body { background: #272822; }
#source-ace {
  width: 100%;
  min-height: 200px;
  font-size: 14px;
  background: #272822 !important;
}
/* ACE 내부 배경 강제 다크 */
#source-ace .ace_scroller,
#source-ace .ace_content,
#source-ace .ace_gutter {
  background: #272822 !important;
}

/* 접근 불가 */
.ss-denied {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}
.ss-denied h3 { font-size: 18px; color: #555; margin-bottom: 8px; }

/* 버튼 */
.ss-actions { margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap; }
.ss-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 9px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.15s;
}
.ss-btn-back { background: #f0f3f7; color: #555; }
.ss-btn-back:hover { background: #e2e8f0; color: #333; text-decoration: none; }
.ss-btn-resubmit { background: #7c3aed; color: #fff; }
.ss-btn-resubmit:hover { background: #6d28d9; color: #fff; text-decoration: none; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ss-wrap">

<?php if($ok == true): ?>

  <?php
  // 결과 배지 클래스 결정
  $rclass = 'result-etc';
  if($sresult == 4) $rclass = 'result-ac';
  elseif($sresult == 6) $rclass = 'result-wa';
  elseif($sresult == 7) $rclass = 'result-tle';
  elseif($sresult == 8) $rclass = 'result-mle';
  elseif($sresult == 10) $rclass = 'result-re';
  elseif($sresult == 11) $rclass = 'result-ce';

  // ACE 언어 모드 결정
  $lang_name_lc = strtolower($language_name[$slanguage]);
  $ace_modes = [
    'c'         => 'c_cpp',
    'c++'       => 'c_cpp',
    'java'      => 'java',
    'python'    => 'python',
    'python3'   => 'python',
    'ruby'      => 'ruby',
    'bash'      => 'sh',
    'php'       => 'php',
    'perl'      => 'perl',
    'c#'        => 'csharp',
    'csharp'    => 'csharp',
    'javascript'=> 'javascript',
    'golang'    => 'golang',
    'lua'       => 'lua',
    'pascal'    => 'pascal',
    'obj-c'     => 'c_cpp',
    'swift'     => 'swift',
  ];
  $ace_mode = isset($ace_modes[$lang_name_lc]) ? $ace_modes[$lang_name_lc] : 'text';
  ?>

  <!-- 메타 카드 -->
  <div class="ss-card">
    <div class="ss-card-header">
      <span style="font-size:24px">📄</span>
      <h2>소스 코드 보기</h2>
      <span class="sid-badge">채점번호 #<?php echo $id?></span>
    </div>
    <div class="ss-meta">
      <div class="ss-meta-item">
        <span class="ss-meta-label">문제</span>
        <span class="ss-meta-value">
          <a href="problem.php?id=<?php echo $sproblem_id?>">#<?php echo $sproblem_id?></a>
        </span>
      </div>
      <div class="ss-meta-item">
        <span class="ss-meta-label">제출자</span>
        <span class="ss-meta-value">
          <a href="userinfo.php?user=<?php echo htmlspecialchars($suser_id, ENT_QUOTES)?>"><?php echo htmlspecialchars($nick ?: $suser_id, ENT_QUOTES)?></a>
        </span>
      </div>
      <div class="ss-meta-item">
        <span class="ss-meta-label">언어</span>
        <span class="ss-meta-value"><?php echo htmlspecialchars($language_name[$slanguage], ENT_QUOTES)?></span>
      </div>
      <div class="ss-meta-item">
        <span class="ss-meta-label">채점결과</span>
        <span class="ss-meta-value">
          <span class="result-badge <?php echo $rclass?>"><?php echo htmlspecialchars($judge_result[$sresult], ENT_QUOTES)?></span>
        </span>
      </div>
      <?php if($sresult == 4): ?>
      <div class="ss-meta-item">
        <span class="ss-meta-label">시간</span>
        <span class="ss-meta-value"><?php echo $stime?> ms</span>
      </div>
      <div class="ss-meta-item">
        <span class="ss-meta-label">메모리</span>
        <span class="ss-meta-value"><?php echo $smemory?> KiB</span>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 소스 코드 카드 -->
  <div class="ss-src-card">
    <div class="ss-src-header">
      💻 소스 코드
      <span class="ss-lang-tag"><?php echo htmlspecialchars($language_name[$slanguage], ENT_QUOTES)?></span>
      <span style="margin-left:auto;color:#666;font-size:12px;font-weight:400"><?php echo strlen($view_source)?> bytes</span>
    </div>
    <div class="ss-src-body">
      <pre id="source-ace"><?php echo htmlspecialchars($view_source, ENT_QUOTES, 'UTF-8')?></pre>
    </div>
  </div>

  <div class="ss-actions">
    <a href="javascript:history.back()" class="ss-btn ss-btn-back">← 뒤로 가기</a>
    <?php
    // 재제출 버튼 (본인 코드이거나 관리자)
    if($suser_id == $_SESSION[$OJ_NAME.'_'.'user_id'] || isset($_SESSION[$OJ_NAME.'_'.'administrator'])):
    ?>
    <a href="submitpage.php?id=<?php echo $sproblem_id?>&sid=<?php echo $id?>" class="ss-btn ss-btn-resubmit">↩ 다시 제출</a>
    <?php endif; ?>
  </div>

<?php else: ?>
  <div class="ss-card">
    <div class="ss-denied">
      <h3>🔒 접근이 제한된 코드입니다</h3>
      <p>본인의 제출 코드이거나 대회가 종료된 후에만 볼 수 있습니다.</p>
      <a href="javascript:history.back()" class="ss-btn ss-btn-back" style="margin:0 auto;display:inline-flex">← 뒤로 가기</a>
    </div>
  </div>
<?php endif; ?>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>ace/ace.js"></script>
<script>
<?php if($ok == true): ?>
var editor = ace.edit('source-ace');
editor.setTheme('ace/theme/monokai');
editor.getSession().setMode('ace/mode/<?php echo $ace_mode?>');
editor.setReadOnly(true);
editor.setOptions({
  fontSize: '14px',
  showPrintMargin: false,
  maxLines: Infinity,
  minLines: 10,
  wrap: false,
});
// 배경 강제 다크 (흰색 틈새 방지)
editor.container.style.background = '#272822';
document.getElementById('source-ace').style.background = '#272822';
editor.renderer.setScrollMargin(12, 12, 0, 0);
<?php endif; ?>
</script>
</body>
</html>
