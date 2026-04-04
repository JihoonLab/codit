<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $OJ_NAME?> - 코드 제출</title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
  @font-face {
    font-family: 'D2Coding';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/noonfonts_three@1.0/D2Coding.woff') format('woff');
    font-weight: normal;
    font-style: normal;
  }
  @font-face {
    font-family: 'D2Coding';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/noonfonts_three@1.0/D2CodingBold.woff') format('woff');
    font-weight: bold;
    font-style: normal;
  }

  .submit-wrap {
    max-width: 960px;
    margin: 24px auto 0;
    padding: 0 16px 40px;
  }

  /* 상단 카드 */
  .submit-card {
    background: #fff;
    border: 1px solid #e0e4ed;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 16px;
  }

  .submit-card-header {
    background: #7c3aed;
    color: #fff;
    padding: 16px 24px;
    font-size: 17px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-align: center;
  }

  .submit-card-body {
    padding: 20px 28px 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #f0f2f5;
  }

  /* 메타 정보 행 */
  .submit-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 16px;
    width: 100%;
  }

  .submit-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    color: #444;
    font-weight: 500;
  }

  .submit-meta-item strong {
    color: #7c3aed;
    font-weight: 700;
  }

  /* 언어 선택 */
  .submit-lang-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 16px;
    width: 100%;
  }

  .submit-lang-label {
    font-size: 14px;
    font-weight: 600;
    color: #555;
    white-space: nowrap;
  }

  .submit-lang-select {
    border: 1px solid #d0d7e2;
    border-radius: 7px;
    padding: 8px 14px;
    font-size: 14px;
    font-family: 'Noto Sans KR', sans-serif;
    color: #333;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.15s;
    min-width: 160px;
  }
  .submit-lang-select:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(26,111,196,0.12);
  }

  /* ── 에디터 통합 블록 (topbar + editor + hint 하나의 다크 카드) ── */
  .editor-container {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    border: none;
    margin-bottom: 16px;
    background: #272822;
  }
  /* 에디터 상단 바 */
  .editor-topbar {
    background: #1e1e1e;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    gap: 10px;
  }
  .editor-topbar-left {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .editor-dot {
    width: 12px; height: 12px;
    border-radius: 50%;
    display: inline-block;
  }
  /* 글자 크기 컨트롤 */
  .font-ctrl {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .font-ctrl-label {
    font-size: 12px;
    color: #8a9bb8;
    font-family: 'Noto Sans KR', sans-serif;
    white-space: nowrap;
    font-weight: 500;
  }
  .font-btn {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 6px;
    color: #b0c8f0;
    cursor: pointer;
    padding: 3px 10px;
    line-height: 1;
    font-weight: 900;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
  }
  .font-btn:hover {
    background: rgba(255,255,255,0.14);
    border-color: rgba(255,255,255,0.22);
    color: #d8ecff;
  }
  .font-btn-sm { font-size: 11px; }
  .font-btn-lg { font-size: 16px; }
  .font-display {
    font-size: 13px;
    font-family: monospace;
    font-weight: 700;
    color: #c0d8ff;
    min-width: 36px;
    text-align: center;
  }
  /* 에디터 본체 */
  .submit-editor-wrap {
    border: none;
    border-radius: 0;
    overflow: hidden;
    margin-bottom: 0;
    width: 100%;
    background: #272822;
  }
  /* 단축키 힌트 바 */
  .submit-editor-hint {
    background: #1e1e1e;
    border-top: 1px solid rgba(255,255,255,0.05);
    border-radius: 0;
    padding: 8px 16px;
    margin-bottom: 0;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: center;
    font-size: 12px;
    color: #5a6a8a;
    font-family: 'Noto Sans KR', sans-serif;
  }
  .submit-editor-hint .hint-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #7a8aaa;
    font-size: 12px;
    white-space: nowrap;
  }
  .submit-editor-hint kbd {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.08);
    color: #90b8f8;
    border-radius: 5px;
    padding: 2px 6px;
    font-size: 11px;
    font-family: monospace;
    font-weight: 700;
    border: 1px solid rgba(255,255,255,0.12);
    box-shadow: 0 1px 0 rgba(0,0,0,0.4);
    letter-spacing: 0.2px;
  }

  /* ACE 에디터 레이아웃 */
  #source {
    width: 100%;
    margin: 0;
    padding: 0;
    border: none;
    box-shadow: none;
    border-radius: 0;
  }
  .ace_print-margin { display: none !important; }

  /* 제출 버튼 */
  .submit-btn-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    width: 100%;
    margin-top: 4px;
  }

  .btn-submit-main {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #7c3aed;
    color: #fff !important;
    border: none;
    padding: 12px 48px;
    border-radius: 8px;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s;
    text-decoration: none !important;
    font-family: 'Noto Sans KR', sans-serif;
  }
  .btn-submit-main:hover { background: #6d28d9; }
  .btn-submit-main:disabled { background: #9bb8db; cursor: not-allowed; }

  /* 결과 패널 */
  .result-card {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    animation: resultSlideIn 0.4s ease;
  }
  @keyframes resultSlideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .result-card-header {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  .result-card-header .result-icon {
    font-size: 28px;
  }
  .result-card-header .result-text {
    font-size: 22px;
    font-weight: 900;
    color: #fff;
    letter-spacing: -0.5px;
  }
  .result-card-body {
    background: #fff;
    border: 1px solid #e5e9f0;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    font-size: 13px;
    color: #666;
  }
  .result-card-body .result-stat {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .result-card-body .result-stat strong {
    color: #333;
    font-weight: 700;
  }
  .result-loading {
    width: 100%;
    text-align: center;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e5e9f0;
  }
  .result-loading .loader-dots {
    display: inline-flex;
    gap: 6px;
    align-items: center;
  }
  .result-loading .loader-dots span {
    width: 8px; height: 8px;
    background: #7c3aed;
    border-radius: 50%;
    animation: dotPulse 1.2s ease infinite;
  }
  .result-loading .loader-dots span:nth-child(2) { animation-delay: 0.2s; }
  .result-loading .loader-dots span:nth-child(3) { animation-delay: 0.4s; }
  @keyframes dotPulse {
    0%,80%,100% { opacity: 0.3; transform: scale(0.8); }
    40% { opacity: 1; transform: scale(1.2); }
  }
  .result-loading-text {
    margin-top: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #7c3aed;
  }

  /* 테스트 런 영역 */
  .testrun-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-top: 14px;
  }
  .testrun-label {
    font-size: 13px;
    font-weight: 700;
    color: #888;
    margin-bottom: 6px;
  }
  .testrun-grid textarea {
    width: 100%;
    border: 1px solid #d0d7e2;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    resize: vertical;
    font-family: 'Consolas', monospace;
  }

  @media(max-width:600px){
    .testrun-grid { grid-template-columns: 1fr; }
    .submit-meta { gap: 12px; }
  }
  </style>
</head>

<body>
<div <?php if(!isset($_GET['spa'])) echo 'class="container"' ?>>
  <?php if(!isset($_GET['spa'])) include("template/$OJ_TEMPLATE/nav.php"); ?>

  <div class="submit-wrap">
    <script src="<?php echo $OJ_CDN_URL?>include/checksource.js"></script>
    <form id="frmSolution" action="submit.php" method="post" onsubmit="do_submit()">

      <!-- 제출 카드 -->
      <div class="submit-card">
        <div class="submit-card-header">
          ✏️ 코드 제출
        </div>
        <div class="submit-card-body">

          <!-- 숨김 필드 -->
          <?php if(isset($id)): ?>
            <input id="problem_id" type="hidden" value="<?php echo $id?>" name="id">
            <?php if(isset($_GET["class_id"])): ?><input type="hidden" value="<?php echo intval($_GET["class_id"])?>" name="class_id"><?php endif; ?>
          <?php else: ?>
            <input id="cid" type="hidden" value="<?php echo $cid?>" name="cid">
            <input id="pid" type="hidden" value="<?php echo $pid?>" name="pid">
          <?php endif; ?>

          <!-- 메타 정보 -->
          <div class="submit-meta">
            <div class="submit-meta-item">
              📋 문제:
              <?php if(isset($id)): ?>
                <strong><a href="problem.php?id=<?php echo $id?>" style="color:#7c3aed">No. <?php echo $id?></a></strong>
              <?php else: ?>
                <strong>Contest <?php echo $cid?> - <?php echo chr($pid+ord('A'))?></strong>
              <?php endif; ?>
            </div>
          </div>

          <!-- 언어 선택 -->
          <div class="submit-lang-wrap">
            <span class="submit-lang-label">🌐 언어 선택:</span>
            <select id="language" name="language" class="submit-lang-select" onchange="reloadtemplate($(this).val());">
              <?php
                $lang_count = count($language_ext);
                if(isset($_GET['langmask'])) $langmask = $_GET['langmask'];
                else $langmask = $OJ_LANGMASK;
                $lang = (~((int)$langmask)) & ((1<<($lang_count))-1);
                $lastlang = $_COOKIE['lastlang'];
                if($lastlang=="undefined") $lastlang = 0;
                // C(0) → Python(6) → C++(1) → 나머지 순서
                $priority_order = array(0, 6, 1);
                foreach($priority_order as $i){
                  if($i < $lang_count && ($lang&(1<<$i)))
                    echo "<option value=$i ".($lastlang==$i?"selected":"").">".$language_name[$i]."</option>";
                }
                for($i=0; $i<$lang_count; $i++){
                  if(!in_array($i, $priority_order) && ($lang&(1<<$i)))
                    echo "<option value=$i ".($lastlang==$i?"selected":"").">".$language_name[$i]."</option>";
                }
              ?>
            </select>
          </div>

          <!-- 에디터 + 툴바 통합 컨테이너 -->
          <div class="editor-container">
          <div class="editor-topbar">
            <div class="editor-topbar-left">
              <span class="editor-dot" style="background:#ff5f57"></span>
              <span class="editor-dot" style="background:#febc2e"></span>
              <span class="editor-dot" style="background:#28c840"></span>
            </div>
            <div class="font-ctrl">
              <span class="font-ctrl-label">글자 크기</span>
              <button class="font-btn font-btn-sm" type="button" onclick="changeFontSize(-1)" title="글자 작게">A</button>
              <span class="font-display" id="font-size-display">18px</span>
              <button class="font-btn font-btn-lg" type="button" onclick="changeFontSize(1)" title="글자 크게">A</button>
            </div>
          </div>

          <!-- 에디터 -->
          <div class="submit-editor-wrap">
            <?php
            if($OJ_ACE_EDITOR) {
              if(isset($OJ_TEST_RUN)&&$OJ_TEST_RUN) $height="300px"; else $height="360px";
            ?>
            <div style="width:100%;height:<?php echo $height?>;" id="source"></div>
            <script>var __initSrc = <?php
              if($view_src!="") {
                echo json_encode($view_src, JSON_UNESCAPED_UNICODE);
              } else {
                echo '""';
              }
            ?>;</script>
            <input type="hidden" id="hide_source" name="source" value=""/>
            <?php } else { ?>
            <textarea style="width:100%;height:520px;" cols=180 rows=20 id="source" name="source"><?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea>
            <?php } ?>
          </div><!-- /editor-wrap -->
          <div class="submit-editor-hint">
            <span class="hint-item"><kbd>Ctrl</kbd><kbd>A</kbd> 전체선택</span>
            <span class="hint-item"><kbd>Ctrl</kbd><kbd>C</kbd> 복사</span>
            <span class="hint-item"><kbd>Ctrl</kbd><kbd>V</kbd> 붙여넣기</span>
            <span class="hint-item"><kbd>Ctrl</kbd><kbd>Z</kbd> 되돌리기</span>
            <span class="hint-item"><kbd>Ctrl</kbd><kbd>/</kbd> 주석처리</span>
            <span class="hint-item"><kbd>Tab</kbd> 들여쓰기</span>
          </div>
          </div><!-- /editor-container -->

          <!-- 제출 버튼 -->
          <div class="submit-btn-wrap">
            <button id="Submit" class="btn-submit-main" type="button" onclick="do_submit()">
              🚀 제출하기
            </button>
          </div>

          <!-- 결과 패널 -->
          <div id="result-panel" style="display:none;width:100%;margin-top:12px;"></div>

          <!-- 테스트런 -->
          <?php if(isset($OJ_TEST_RUN)&&$OJ_TEST_RUN): ?>
          <div class="testrun-grid">
            <div>
              <div class="testrun-label">입력 (테스트)</div>
              <textarea id="input_text" name="input_text" rows="5"><?php echo $view_sample_input?></textarea>
            </div>
            <div>
              <div class="testrun-label">출력 (예상)</div>
              <textarea id="out" name="out" rows="5" disabled>SHOULD BE:<?php echo $view_sample_output?></textarea>
            </div>
          </div>
          <?php endif; ?>

        </div><!-- /card-body -->
      </div><!-- /card -->

    </form>
  </div><!-- /submit-wrap -->
</div><!-- /container -->

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var sid=0, count=0, using_blockly=false, handler_interval;
var judge_result=[<?php foreach($judge_result as $result){ echo "'$result',"; } ?>''];

function fresh_result(solution_id){
  sid=solution_id;
  var panel=document.getElementById('result-panel');
  panel.style.display='block';
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function(){
    if(xmlhttp.readyState==4&&xmlhttp.status==200){
      var ra=xmlhttp.responseText.split(",");
      var code=parseInt(ra[0]);
      // 0~3: 채점 진행 중, 14: 대기중(초기상태), 16: 원격채점 대기
      var isPending = (code<4 || code==14 || code==16);
      
      if(isPending){
        var statusText = '채점 중...';
        if(code==14) statusText = '대기 중...';
        else if(code==2) statusText = '컴파일 중...';
        else if(code==3) statusText = '실행 중...';
        panel.innerHTML='<div class="result-loading"><div class="loader-dots"><span></span><span></span><span></span></div><div class="result-loading-text">'+statusText+'</div></div>';
        window.setTimeout("fresh_result("+solution_id+")",1500);
      } else {
        // 최종 결과
        var isAC = (code==4);
        var isPE = (code==5);
        var isCE = (code==11);
        var isRE = (code==10);
        var resultName = judge_result[code] || '알 수 없음';
        
        // 결과별 색상/아이콘
        var bgColor, icon, subMsg;
        if(isAC)       { bgColor='#10b981'; icon='🎉'; subMsg='축하합니다!'; }
        else if(isPE)  { bgColor='#f59e0b'; icon='⚠️'; subMsg='출력 형식을 확인하세요'; }
        else if(isCE)  { bgColor='#dc2626'; icon='⚠️'; subMsg='클릭하여 에러 내용 확인 →'; }
        else if(code==6){ bgColor='#ef4444'; icon='❌'; subMsg='클릭하여 입출력 비교 확인 →'; }
        else if(code==7){ bgColor='#3b82f6'; icon='⏰'; subMsg='클릭하여 상세 정보 확인 →'; }
        else if(code==8){ bgColor='#6366f1'; icon='💾'; subMsg='클릭하여 상세 정보 확인 →'; }
        else if(code==9){ bgColor='#f97316'; icon='📤'; subMsg='출력 크기가 초과되었습니다'; }
        else if(isRE)  { bgColor='#dc2626'; icon='💥'; subMsg='클릭하여 에러 내용 확인 →'; }
        else           { bgColor='#ef4444'; icon='❌'; subMsg=''; }
        
        // 모든 결과에 상세 페이지 링크 (OJ_SHOW_DIFF=true)
        var hasDetailLink = true;
        var detailURL = isCE ? 'ceinfo.php?sid='+solution_id : 'reinfo.php?sid='+solution_id;
        
        var html = '<div class="result-card">';
        if(hasDetailLink){
          html += '<a href="'+detailURL+'" target="_blank" style="text-decoration:none;display:block;">';
        }
        html += '<div class="result-card-header" style="background:'+bgColor+';">';
        html += '<span class="result-icon">'+icon+'</span>';
        html += '<span class="result-text">'+resultName+'</span>';
        html += '</div>';
        if(hasDetailLink){
          html += '</a>';
        }
        html += '<div class="result-card-body">';
        if(isCE){
          html += '<span class="result-stat" style="color:#dc2626;font-weight:600;">'+subMsg+'</span>';
        } else {
          html += '<span class="result-stat">💾 메모리: <strong>'+ra[1]+'</strong></span>';
          html += '<span class="result-stat">⏱ 시간: <strong>'+ra[2]+'</strong></span>';
          if(subMsg) html += '<span class="result-stat" style="color:#999;">'+subMsg+'</span>';
        }
        html += '</div></div>';
        panel.innerHTML = html;
        
        // 부모 페이지 문제 헤더 위에 결과 배너 표시
        try {
          var parentDoc = window.parent.document;
          var headerEl = parentDoc.querySelector('.prob-header');
          if(headerEl) {
            // 기존 배너 제거
            var oldBanner = parentDoc.getElementById('solve-banner');
            if(oldBanner) oldBanner.remove();
            
            var banner = parentDoc.createElement('div');
            banner.id = 'solve-banner';
            if(isAC) {
              banner.style.cssText = 'background:linear-gradient(135deg,#d1fae5,#a7f3d0);border:1px solid #6ee7b7;border-radius:10px;padding:12px 20px;margin-bottom:12px;display:flex;align-items:center;gap:10px;animation:bannerSlide 0.4s ease;';
              banner.innerHTML = '<span style="font-size:24px;">🎉</span><span style="font-size:15px;font-weight:800;color:#059669;">해결한 문제</span><span style="font-size:13px;color:#047857;">축하합니다! 정답입니다.</span>';
            } else {
              banner.style.cssText = 'background:linear-gradient(135deg,#fee2e2,#fecaca);border:1px solid #fca5a5;border-radius:10px;padding:12px 20px;margin-bottom:12px;display:flex;align-items:center;gap:10px;animation:bannerSlide 0.4s ease;';
              banner.innerHTML = '<span style="font-size:24px;">❌</span><span style="font-size:15px;font-weight:800;color:#dc2626;">틀린 문제</span><span style="font-size:13px;color:#b91c1c;">다시 도전해 보세요!</span>';
            }
            headerEl.parentNode.insertBefore(banner, headerEl);
            
            // 애니메이션 스타일 추가
            if(!parentDoc.getElementById('badge-anim-style')) {
              var st = parentDoc.createElement('style');
              st.id = 'badge-anim-style';
              st.textContent = '@keyframes bannerSlide { 0%{opacity:0;transform:translateY(-10px)} 100%{opacity:1;transform:translateY(0)} }';
              parentDoc.head.appendChild(st);
            }
          }
        } catch(e) {}
        
        window.setTimeout("print_result("+solution_id+")",2000);
        count=1;
      }
    }
  };
  xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
  xmlhttp.send();
}

function print_result(solution_id){
  sid=solution_id;
  $("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);
}

function do_submit(){
  if(handler_interval) clearTimeout(handler_interval);
  $("#Submit").attr("disabled","true");
  if(typeof(editor)!="undefined") $("#hide_source").val(editor.getValue());
  var mark="<?php echo isset($id)?'problem_id':'cid';?>";
  var problem_id=document.getElementById(mark);
  if(mark=='problem_id') problem_id.value='<?php if(isset($id)) echo $id?>';
  else problem_id.value='<?php if(isset($cid)) echo $cid?>';
  document.getElementById("frmSolution").target="_self";
  <?php if(isset($_GET['spa'])): ?>
  $.post("submit.php?ajax",$("#frmSolution").serialize(),function(data){
    var parts = String(data).split('|');
    var sid = parseInt(parts[0]);
    if(parts[1]) { $("input[name='csrf']").val(parts[1]); }
    if(sid > 0) {
      fresh_result(sid);
    } else {
      // 쿨다운 중 - 제출 실패
      var panel = document.getElementById('result-panel');
      if(panel) {
        panel.style.display = 'block';
        panel.innerHTML = '<div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:10px;padding:14px 20px;text-align:center;"><span style="font-size:20px;">⏳</span> <span style="font-size:14px;font-weight:700;color:#92400e;">너무 빨리 제출했습니다. 잠시 후 다시 시도하세요.</span></div>';
      }
    }
  });
  $("#Submit").prop('disabled',true);
  count=<?php echo $OJ_SUBMIT_COOLDOWN_TIME?>;
  handler_interval=window.setTimeout("resume();",1000);
  <?php else: ?>
  document.getElementById("frmSolution").submit();
  <?php endif; ?>
}

function do_test_run(){
  if(handler_interval) window.clearInterval(handler_interval);
  var tb=document.getElementById('result');
  var source=typeof(editor)!="undefined"?editor.getValue():$("#source").val();
  if(source.length<10) return alert("코드가 너무 짧습니다!");
  if(tb) tb.innerHTML="<img width=18 src=image/loader.gif>";
  if(typeof(editor)!="undefined") $("#hide_source").val(source);
  var mark="<?php echo isset($id)?'problem_id':'cid';?>";
  var problem_id=document.getElementById(mark);
  problem_id.value=-problem_id.value;
  document.getElementById("frmSolution").target="testRun";
  $.post("submit.php?ajax",$("#frmSolution").serialize(),function(data){fresh_result(data);});
  $("#Submit").prop('disabled',true);
  problem_id.value=-problem_id.value;
  count=<?php echo $OJ_SUBMIT_COOLDOWN_TIME?>;
  handler_interval=window.setTimeout("resume();",1000);
}

function resume(){
  count--;
  var s=$("#Submit")[0];
  if(count<0){
    s.disabled=false;
    $("#Submit").prop('disabled', false).removeAttr('disabled').text("🚀 제출하기");
    if(handler_interval) window.clearInterval(handler_interval);
  } else {
    $("#Submit").text("⏳ 대기 중 ("+count+")");
    window.setTimeout("resume();",1000);
  }
}

// ── 언어별 기본 코드 템플릿 ──────────────────────────────────────
var defaultTemplates = {
  0:  '#include <stdio.h>\n\nint main()\n{\n\t\n\treturn 0;\n}',
  1:  '#include <iostream>\nusing namespace std;\n\nint main()\n{\n\t\n\treturn 0;\n}',
  3:  'import java.util.*;\nimport java.io.*;\n\npublic class Main {\n\tpublic static void main(String[] args) {\n\t\t\n\t}\n}',
  6:  '',
  9:  'using System;\n\nclass Program {\n\tstatic void Main(string[] args) {\n\t\t\n\t}\n}',
  13: '#include <stdio.h>\n\nint main()\n{\n\t\n\treturn 0;\n}',
  14: '#include <iostream>\nusing namespace std;\n\nint main()\n{\n\t\n\treturn 0;\n}',
  17: 'package main\n\nimport "fmt"\n\nfunc main() {\n\t\n}'
};

var templateCursorPos = {
  0: {row:4, col:1}, 1: {row:4, col:1}, 3: {row:5, col:2},
  6: {row:3, col:0}, 9: {row:4, col:2},
  13:{row:4, col:1}, 14:{row:4, col:1}, 17:{row:5, col:1}
};



// textarea를 커서 위치에 강제 동기화 (Ace 1.3.3 버그 우회)
function syncTextareaToCursor(row, col) {
  if (typeof(editor) === 'undefined') return;
  if (row === undefined) {
    var pos = editor.getCursorPosition();
    row = pos.row; col = pos.column;
  }
  editor.gotoLine(row + 1, col);
  editor.focus();
  setTimeout(function(){
    var cursorLayer = editor.renderer.$cursorLayer;
    var pixelPos = cursorLayer.getPixelPosition({row: row, column: col}, true);
    var textarea = editor.textInput.getElement();
    var config = editor.renderer.layerConfig;
    textarea.style.left = (pixelPos.left + editor.renderer.$padding) + 'px';
    textarea.style.top  = (pixelPos.top - config.offset) + 'px';
  }, 50);
}

function applyTemplate(lang) {
  if (typeof(editor) === 'undefined') return;
  var tpl = defaultTemplates[lang] !== undefined ? defaultTemplates[lang] : '';
  editor.setValue(tpl);
  editor.clearSelection();
  var pos = templateCursorPos[lang] || {row:0, col:0};
  if (tpl !== '') {
    // Ace 1.3.3: moveCursorToPosition 후 textarea 위치 동기화
    setTimeout(function(){ syncTextareaToCursor(pos.row, pos.col); }, 150);
  } else {
    editor.focus();
  }
}

function switchLang(lang){
  var langnames=["c_cpp","c_cpp","pascal","java","ruby","sh","python","php","perl","csharp","objectivec","vbscript","scheme","c_cpp","c_cpp","lua","javascript","golang"];
  if(typeof(editor)!="undefined") editor.getSession().setMode("ace/mode/"+langnames[lang]);
}

function reloadtemplate(lang){
  document.cookie="lastlang="+lang;
  switchLang(lang);
  if(typeof(editor) === 'undefined') return;
  var cur = editor.getValue().trim();
  // 빈 에디터이거나 알려진 템플릿 내용이면 바로 교체
  var isKnownTemplate = (cur === '');
  if(!isKnownTemplate) {
    for(var k in defaultTemplates) {
      if(defaultTemplates[k] && cur === defaultTemplates[k].trim()) {
        isKnownTemplate = true; break;
      }
    }
  }
  if(isKnownTemplate) {
    applyTemplate(lang);
  } else {
    // 사용자가 코드를 입력한 경우 확인
    if(confirm('언어를 바꾸면 현재 코드가 지워집니다. 계속하시겠습니까?')) {
      applyTemplate(lang);
    }
  }
}

// ── 글자 크기 조절 ─────────────────────────────────────────────
var FONT_SIZES = [12, 13, 14, 15, 16, 18, 20, 24];
var currentFontIdx = (function(){
  var saved = parseInt(localStorage.getItem('editorFontSize'));
  var idx = FONT_SIZES.indexOf(saved);
  return (idx >= 0) ? idx : 5; // 기본값: 18px (index 5)
})();

function applyFontSize(idx) {
  var sz = FONT_SIZES[idx];
  var disp = document.getElementById('font-size-display');
  if(disp) disp.textContent = sz + 'px';
  localStorage.setItem('editorFontSize', sz);
  // 동적 style 주입 - CSS 우선순위 문제 완전 우회
  var old = document.getElementById('ace-font-override');
  if(old) old.parentNode.removeChild(old);
  var st = document.createElement('style');
  st.id = 'ace-font-override';
  st.textContent = '#source { font-size: ' + sz + 'px !important; }';
  document.head.appendChild(st);
  if(typeof(editor) === 'undefined') return;
  editor.setFontSize(sz + 'px');
  editor.resize(true);
}

function changeFontSize(delta) {
  var newIdx = Math.max(0, Math.min(FONT_SIZES.length - 1, currentFontIdx + delta));
  currentFontIdx = newIdx;
  applyFontSize(currentFontIdx);
}

// 페이지 로드 시 display 즉시 반영 (ACE 없어도)
document.addEventListener('DOMContentLoaded', function(){
  var disp = document.getElementById('font-size-display');
  if(disp) disp.textContent = FONT_SIZES[currentFontIdx] + 'px';
});

function autoSave(){
  if(!!localStorage&&typeof(editor)!="undefined"&&editor.getValue().trim()!==""){
    let key="<?php echo $_SESSION[$OJ_NAME.'_user_id']?>source:"+location.href;
    $("#hide_source").val(editor.getValue());
    localStorage.setItem(key,$("#hide_source").val());
  }
}

// autoSave/restore는 Ace 초기화 후에 실행 (아래 ace init 블록에서 호출)
</script>

<script src="<?php echo $OJ_CDN_URL?>include/base64.js"></script>

<?php if($OJ_ACE_EDITOR): ?>
<script src="<?php echo $OJ_CDN_URL?>ace/ace.js"></script>
<script src="<?php echo $OJ_CDN_URL?>ace/ext-language_tools.js"></script>
<script>
  ace.config.set("basePath", "/ace/");
  ace.require("ace/ext/language_tools");
  var editor = ace.edit("source");
  editor.setTheme("ace/theme/monokai");
  var initialLang = <?php echo isset($lastlang)?$lastlang:0; ?>;
  switchLang(initialLang);
  editor.setOptions({
    enableBasicAutocompletion: true,
    enableSnippets: true,
    enableLiveAutocompletion: false,
    showPrintMargin: false,
    printMarginColumn: false,
    fontFamily: "'D2Coding','Fira Code','Consolas',monospace",
  });
  editor.renderer.setShowPrintMargin(false);
  editor.container.style.background = "#272822";

  // 에디터 높이 설정
  var edH = Math.max(Math.floor(window.innerHeight * 0.45), 280);
  document.getElementById('source').style.height = edH + 'px';
  editor.resize(true);

  // 글자 크기 적용
  applyFontSize(currentFontIdx);
  editor.resize(true);

  // 초기 코드 설정
  <?php if($view_src != ""): ?>
  editor.setValue(__initSrc, -1);
  <?php else: ?>
  applyTemplate(initialLang);
  <?php endif; ?>

  // localStorage 복원
  (function(){
    if(!localStorage) return;
    var key = "<?php echo $_SESSION[$OJ_NAME.'_user_id']?>source:" + location.href;
    var saved = localStorage.getItem(key);
    var isOldPlaceholder = saved && (
      saved.indexOf('제출할 언어를 먼저 선택하세요') !== -1 ||
      saved.indexOf('코드를 붙여넣고 제출 버튼') !== -1 ||
      saved.indexOf('전체선택: Ctrl+A') !== -1
    );
    if(isOldPlaceholder) {
      localStorage.removeItem(key);
    } else if(saved && saved.length > editor.getValue().length) {
      editor.setValue(saved);
      editor.clearSelection();
    }
  })();

  // 자동 저장
  window.setInterval(autoSave, 5000);

  // 최종 resize 보장
  editor.resize(true);
  editor.renderer.updateFull(true);

  // Ace 1.3.3 버그 우회: 초기 로드 후 textarea를 커서 위치에 동기화
  // applyTemplate이 아닌 경우(initSrc, localStorage 복원)에도 동기화
  setTimeout(function(){
    var pos = editor.getCursorPosition();
    syncTextareaToCursor(pos.row, pos.column);
  }, 300);

</script>
<?php endif; ?>

</body>
</html>
