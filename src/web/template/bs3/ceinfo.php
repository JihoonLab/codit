<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>컴파일 에러 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

.ce-wrap { max-width: 960px; margin: 32px auto; padding: 0 20px 60px; }

/* 상단 카드 */
.ce-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.ce-card-header {
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  color: #fff;
  padding: 18px 28px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.ce-card-header h2 { margin: 0; font-size: 20px; font-weight: 700; }
.ce-card-header .sid-badge {
  margin-left: auto;
  background: rgba(255,255,255,0.2);
  border-radius: 6px;
  padding: 4px 12px;
  font-size: 13px;
  font-weight: 600;
}

/* 에러 출력 - 어두운 배경 + 밝은 텍스트 */
.ce-errbox {
  background: #1e1e1e;
  color: #b91c1c;
  font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
  font-size: 13px;
  line-height: 1.8;
  padding: 20px 28px;
  margin: 0;
  white-space: pre-wrap;
  word-break: break-all;
  max-height: 400px;
  overflow-y: auto;
  border-bottom: 1px solid #fecaca;
}
.ce-errbox::-webkit-scrollbar { width: 6px; }
.ce-errbox::-webkit-scrollbar-thumb { background: #e5a0a0; border-radius: 3px; }

/* 에러 분석 */
.ce-explain { padding: 20px 28px; }
.ce-explain h4 {
  font-size: 14px;
  font-weight: 700;
  color: #555;
  margin: 0 0 12px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.ce-explain-item {
  background: #f8f9fb;
  border-left: 3px solid #7c3aed;
  border-radius: 0 8px 8px 0;
  padding: 12px 16px;
  margin-bottom: 8px;
}
.ce-explain-item .match {
  color: #dc2626;
  font-weight: 600;
  font-family: 'SF Mono', 'Consolas', monospace;
  font-size: 12.5px;
  word-break: break-all;
}
.ce-explain-item .desc {
  color: #444;
  margin-top: 6px;
  font-size: 13px;
  line-height: 1.5;
}

/* 소스 코드 카드 */
.ce-src-card {
  background: #272822;
  border: 1px solid #3a3a3a;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.2);
  overflow: hidden;
  margin-bottom: 16px;
}
.ce-src-header {
  background: #1e1e1e;
  color: #ccc;
  padding: 12px 20px;
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  border-bottom: 1px solid #3a3a3a;
}
.ce-src-header .lang-tag {
  background: #3a3a3a;
  color: #aaa;
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 12px;
}
#ace-container {
  background: #272822;
  width: 100%;
  min-height: 200px;
}
#source-ace {
  width: 100%;
  min-height: 200px;
  font-size: 14px;
  background: #272822 !important;
}

/* 버튼 */
.ce-btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 9px 20px;
  background: #fff;
  color: #555;
  border: 1px solid #e0e5ec;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}
.ce-btn-back:hover { background: #f5f8fc; text-decoration: none; color: #333; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ce-wrap">

  <!-- 컴파일 에러 카드 -->
  <div class="ce-card">
    <div class="ce-card-header">
      <span style="font-size:24px">⚠️</span>
      <h2>컴파일 에러</h2>
      <span class="sid-badge">채점번호 #<?php echo $id?></span>
    </div>
    <!-- 에러 메시지 -->
    <pre class="ce-errbox" id="errtxt"><?php echo htmlspecialchars($view_reinfo, ENT_QUOTES, 'UTF-8')?></pre>

    <!-- 에러 분석 -->
    <div class="ce-explain">
      <h4>💡 에러 분석</h4>
      <div id="errexp"><div style="color:#aaa;font-size:13px">분석 중...</div></div>
    </div>
  </div>

  <!-- 소스 코드 카드 -->
  <div class="ce-src-card">
    <div class="ce-src-header">
      📄 제출 코드
      <span id="src-lang-tag" class="lang-tag"></span>
    </div>
    <div id="ace-container">
      <div id="source-ace"></div>
    </div>
  </div>

  <a href="javascript:history.back()" class="ce-btn-back">← 뒤로 가기</a>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>ace/ace.js"></script>
<script>
var pats = [], exps = [];
function addPat(p, e){ pats.push(p); exps.push(e); }
addPat(/System\.out\.print.*%.*/, "printf 형식 출력을 사용했습니다. Java에서는 System.out.printf()를 사용하세요.");
addPat(/'.*' was not declared in this scope/, "해당 변수나 함수가 선언되지 않았습니다. 이름을 확인하거나 헤더를 include했는지 확인하세요.");
addPat(/not a statement/, "올바르지 않은 구문입니다. 세미콜론 누락이나 괄호 불일치를 확인하세요.");
addPat(/class, interface, or enum expected/, "Java 클래스 구조 오류입니다. 클래스 선언이 올바른지 확인하세요.");
addPat(/package .* does not exist/, "패키지를 찾을 수 없습니다. import문을 확인하세요.");
addPat(/possible loss of precision/, "데이터 형변환 손실이 발생할 수 있습니다. 명시적 형변환(casting)을 사용하세요.");
addPat(/incompatible types/, "타입이 호환되지 않습니다. 변수 타입을 확인하세요.");
addPat(/illegal start of expression/, "올바르지 않은 표현식입니다. 괄호나 세미콜론을 확인하세요.");
addPat(/cannot find symbol/, "해당 심볼(변수, 메서드, 클래스)을 찾을 수 없습니다.");
addPat(/';' expected/, "세미콜론(;)이 누락되었습니다.");
addPat(/should be declared in a file named/, "Java에서 public 클래스명이 파일명과 달라야 합니다.");
addPat(/expected '.*' at end of input/, "파일 끝에서 예상치 못한 입력이 있습니다. 괄호 쌍을 확인하세요.");
addPat(/main' must return 'int'/, "main 함수의 반환형을 int로 선언하세요. `int main()`");
addPat(/printf.*was not declared in this scope/, "#include <stdio.h> 또는 #include <cstdio>가 누락되었습니다.");
addPat(/scanf.*was not declared in this scope/, "#include <stdio.h> 또는 #include <cstdio>가 누락되었습니다.");
addPat(/memset.*was not declared in this scope/, "#include <string.h> 또는 #include <cstring>이 누락되었습니다.");
addPat(/malloc.*was not declared in this scope/, "#include <stdlib.h> 또는 #include <cstdlib>이 누락되었습니다.");
addPat(/'import' does not name a type/, "C/C++에서 import는 사용할 수 없습니다. #include를 사용하세요.");
addPat(/redefinition of/, "동일한 이름으로 재정의(중복 선언)되었습니다. 함수나 변수 이름 중복을 확인하세요.");
addPat(/expected declaration or statement at end of input/, "파일 끝에서 선언 또는 구문이 예상됩니다. 중괄호({})를 확인하세요.");
addPat(/implicit declaration of function/, "함수를 선언하지 않고 사용했습니다. 함수 원형(prototype)을 추가하세요.");
addPat(/too .* arguments to function/, "함수 호출 시 인자 수가 맞지 않습니다.");
addPat(/division by zero/, "0으로 나누기를 시도했습니다.");
addPat(/unterminated comment/, "주석이 닫히지 않았습니다. `*/` 를 추가하세요.");
addPat(/iostream: No such file or directory/, "#include <iostream>을 확인하세요.");
addPat(/variably modified/, "배열 크기는 상수여야 합니다. #define이나 const int를 사용하세요.");
addPat(/gets' was not declared in this scope/, "gets() 함수는 C++에서 제거되었습니다. fgets() 또는 cin을 사용하세요.");
addPat(/extra tokens at end of #include directive/, "#include 지시문 끝에 불필요한 문자가 있습니다.");
addPat(/subscripted value is neither array nor pointer/, "배열이 아닌 값에 인덱스를 사용했습니다. 배열 선언을 확인하세요.");
addPat(/warning: unused variable/, "선언했지만 사용하지 않은 변수가 있습니다.");
addPat(/undefined reference to/, "함수나 변수가 정의되지 않았습니다. 함수 본문이 있는지 확인하세요.");
addPat(/stray .* in program/, "코드에 잘못된 문자가 포함되어 있습니다. 한글 따옴표나 특수문자를 확인하세요.");
addPat(/expected .* before/, "구문 오류입니다. 괄호, 세미콜론, 중괄호 등을 확인하세요.");
addPat(/ld returned 1 exit status/, "링크 오류입니다. main 함수가 있는지, 함수 이름이 올바른지 확인하세요.");
addPat(/error: expected/, "문법 오류입니다. 해당 위치에서 빠진 기호를 확인하세요.");
addPat(/IndentationError/, "Python 들여쓰기 오류입니다. 탭과 스페이스를 혼용하지 마세요.");
addPat(/SyntaxError/, "Python 문법 오류입니다. 콜론(:), 괄호, 따옴표를 확인하세요.");
addPat(/NameError/, "Python에서 정의되지 않은 변수나 함수를 사용했습니다.");
addPat(/TypeError/, "Python에서 타입이 맞지 않는 연산을 수행했습니다.");

function explain(){
  var errmsg = document.getElementById('errtxt').textContent;
  var items = [];
  for(var i=0; i<pats.length; i++){
    var ret = pats[i].exec(errmsg);
    if(ret){
      items.push(
        '<div class="ce-explain-item">' +
        '<div class="match">→ ' + ret[0].substring(0,120).replace(/</g,'&lt;').replace(/>/g,'&gt;') + (ret[0].length>120?'...':'') + '</div>' +
        '<div class="desc">' + exps[i] + '</div>' +
        '</div>'
      );
    }
  }
  var box = document.getElementById('errexp');
  box.innerHTML = items.length > 0
    ? items.join('')
    : '<div style="color:#888;font-size:13px">자동 분석된 패턴이 없습니다. 에러 메시지를 직접 읽어보세요.</div>';
}

$.ajax({
  url: 'showsource2.php?id=<?php echo $id?>',
  success: function(html){
    var parser = new DOMParser();
    var doc = parser.parseFromString(html, 'text/html');
    var preEl = doc.querySelector('pre');
    var codeText = preEl ? preEl.textContent : html;

    var langMode = 'c_cpp';
    if(preEl){
      var cls = preEl.getAttribute('class') || '';
      var m = cls.match(/brush:(\w+)/);
      if(m){
        var brushMap = {c:'c_cpp','c++':'c_cpp',cpp:'c_cpp',java:'java',
          python:'python',python3:'python',ruby:'ruby',bash:'sh',
          php:'php',perl:'perl',csharp:'csharp',vb:'vbscript',
          javascript:'javascript',golang:'golang',lua:'lua',delphi:'pascal'};
        langMode = brushMap[m[1]] || 'text';
        document.getElementById('src-lang-tag').textContent = m[1].toUpperCase();
      }
    }

    var container = document.getElementById('source-ace');
    var lineCount = (codeText.match(/\n/g)||[]).length + 1;
    var editorHeight = Math.max(200, Math.min(lineCount * 19 + 20, window.innerHeight * 0.65));
    container.style.height = editorHeight + 'px';
    container.style.background = '#272822';

    var editor = ace.edit('source-ace');
    editor.setTheme('ace/theme/monokai');
    editor.getSession().setMode('ace/mode/' + langMode);
    editor.getSession().setValue(codeText);
    editor.setReadOnly(true);
    editor.setOptions({ fontSize: '14px', showPrintMargin: false, wrap: false });
    editor.renderer.setScrollMargin(8, 8);

    explain();
  },
  error: function(){
    document.getElementById('ace-container').innerHTML =
      '<div style="color:#aaa;padding:20px;font-size:13px">소스 코드를 불러올 수 없습니다.</div>';
    explain();
  }
});
</script>
</body>
</html>
