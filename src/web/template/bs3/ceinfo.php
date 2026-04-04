<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>컴파일 에러 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;800&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

.ce-wrap { max-width: 960px; margin: 32px auto; padding: 0 20px 60px; }

.ce-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 14px;
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
.ce-card-header h2 { margin: 0; font-size: 20px; font-weight: 800; }
.ce-card-header .sid-badge {
  margin-left: auto;
  background: rgba(255,255,255,0.2);
  border-radius: 6px;
  padding: 4px 12px;
  font-size: 13px;
  font-weight: 600;
}

/* 에러 요약 카드 */
.ce-summary {
  padding: 24px 28px;
  border-bottom: 1px solid #f0f2f5;
}
.ce-error-item {
  background: #fef7f7;
  border: 1px solid #fecaca;
  border-radius: 10px;
  padding: 16px 20px;
  margin-bottom: 12px;
}
.ce-error-item:last-child { margin-bottom: 0; }
.ce-error-location {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}
.ce-line-badge {
  background: #dc2626;
  color: #fff;
  border-radius: 6px;
  padding: 3px 10px;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}
.ce-error-type {
  color: #dc2626;
  font-weight: 700;
  font-size: 13px;
}
.ce-error-korean {
  font-size: 15px;
  font-weight: 700;
  color: #333;
  line-height: 1.6;
  margin-bottom: 6px;
}
.ce-error-tip {
  font-size: 13px;
  color: #666;
  line-height: 1.5;
}
.ce-error-tip code {
  background: #f0f0f0;
  padding: 1px 6px;
  border-radius: 4px;
  font-family: 'D2Coding', 'Consolas', monospace;
  font-size: 12px;
  color: #c7254e;
}

/* 원본 에러 토글 */
.ce-raw-toggle {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  color: #999;
  cursor: pointer;
  padding: 8px 0 0;
  border: none;
  background: none;
}
.ce-raw-toggle:hover { color: #666; }
.ce-raw-box {
  display: none;
  background: #1e1e1e;
  color: #e06c75;
  font-family: 'D2Coding', 'Consolas', monospace;
  font-size: 12px;
  line-height: 1.7;
  padding: 14px 20px;
  margin-top: 10px;
  border-radius: 8px;
  white-space: pre-wrap;
  word-break: break-all;
  max-height: 300px;
  overflow-y: auto;
}

/* 소스 코드 */
.ce-src-card {
  background: #272822;
  border: 1px solid #3a3a3a;
  border-radius: 14px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.2);
  overflow: hidden;
  margin-bottom: 20px;
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
#ace-container { background: #272822; width: 100%; min-height: 200px; }
#source-ace { width: 100%; min-height: 200px; font-size: 14px; background: #272822 !important; }
.ace_error-line { background: rgba(220, 38, 38, 0.15) !important; position: absolute !important; }

/* 버튼 */
.ce-actions { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; }
.ce-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  padding: 16px 36px; border-radius: 12px; font-size: 16px; font-weight: 700;
  text-decoration: none !important; transition: all 0.2s; border: 2px solid transparent;
  min-width: 200px; cursor: pointer;
}
.ce-btn-back { background: #f0f2f5; color: #444; border-color: #dde1e8; }
.ce-btn-back:hover { background: #e5e8ee; color: #222; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.ce-btn-edit { background: #7c3aed; color: #fff !important; border-color: #7c3aed; box-shadow: 0 3px 12px rgba(124,58,237,0.3); }
.ce-btn-edit:hover { background: #6d28d9; color: #fff !important; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(124,58,237,0.4); }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ce-wrap">

  <div class="ce-card">
    <div class="ce-card-header">
      <span style="font-size:24px">⚠️</span>
      <h2>컴파일 에러</h2>
      <span class="sid-badge">채점번호 #<?php echo $id?></span>
    </div>

    <div class="ce-summary">
      <div id="ce-errors">
        <div style="color:#aaa;font-size:13px">분석 중...</div>
      </div>

      <button class="ce-raw-toggle" onclick="var b=document.getElementById('ce-raw');b.style.display=b.style.display==='block'?'none':'block';this.querySelector('.arrow').textContent=b.style.display==='block'?'▲':'▼'">
        <span class="arrow">▼</span> 원본 에러 메시지 보기
      </button>
      <pre class="ce-raw-box" id="ce-raw"><?php echo htmlspecialchars(html_entity_decode($view_reinfo, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')?></pre>
    </div>
  </div>

  <!-- 소스 코드 -->
  <div class="ce-src-card">
    <div class="ce-src-header">
      📄 제출 코드
      <span id="src-lang-tag" class="lang-tag"></span>
    </div>
    <div id="ace-container">
      <div id="source-ace"></div>
    </div>
  </div>

  <div class="ce-actions">
    <?php
      $problemId = $solution_row['problem_id'];
      $solutionId = $solution_row['solution_id'];
    ?>
    <?php if($problemId > 0): ?>
    <a href="problem.php?id=<?php echo $problemId?>" class="ce-btn ce-btn-back">← 문제로 돌아가기</a>
    <a href="submitpage.php?id=<?php echo $problemId?>&sid=<?php echo $solutionId?>" class="ce-btn ce-btn-edit">✏️ 코드 수정 후 재제출</a>
    <?php else: ?>
    <a href="javascript:history.back()" class="ce-btn ce-btn-back">← 뒤로 가기</a>
    <?php endif; ?>
  </div>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="/ace/ace.js"></script>
<script>
// 에러 메시지 한글 번역 매핑
var translations = [
  // === C/C++ 에러 ===

  // 세미콜론/괄호/구문
  [/expected ';' before/, "세미콜론(;)이 빠졌습니다.", "이 줄 또는 바로 윗줄 끝에 <code>;</code>을 추가하세요."],
  [/expected '(.+)' before '(.+)'/, "'{2}' 앞에 '{1}'이(가) 필요합니다.", "해당 위치에 <code>{1}</code>을 추가하세요."],
  [/expected '(.+)' at end of input/, "파일 끝에 '{1}'이(가) 필요합니다.", "닫는 괄호 <code>{1}</code>이 빠져있지 않은지 확인하세요."],
  [/expected declaration or statement at end of input/, "코드 끝에 닫는 중괄호 <code>}</code>가 빠졌습니다.", "<code>{</code>와 <code>}</code>의 개수가 같은지 확인하세요."],
  [/expected expression before '(.+)'/, "'{1}' 앞에 값이나 수식이 필요합니다.", "빠진 변수나 값이 없는지 확인하세요."],
  [/expected '\)' before numeric constant/, "숫자 앞에 닫는 괄호 <code>)</code>가 필요합니다.", "괄호가 빠졌거나 쉼표 <code>,</code>를 빠뜨리지 않았는지 확인하세요."],
  [/expected '\)' before (.+)/, "'{1}' 앞에 닫는 괄호 <code>)</code>가 필요합니다.", "여는 괄호 <code>(</code>와 닫는 괄호 <code>)</code>의 짝이 맞는지 확인하세요."],

  // 선언/정의
  [/'(.+)' was not declared in this scope/, "'{1}'을(를) 사용했지만 선언되지 않았습니다.", "변수/함수 이름에 오타가 없는지 확인하세요. 대소문자도 구분됩니다."],
  [/'(.+)' undeclared/, "'{1}'이(가) 선언되지 않았습니다.", "변수를 사용하기 전에 선언했는지 확인하세요."],
  [/use of undeclared identifier '(.+)'/, "'{1}'이(가) 선언되지 않았습니다.", "변수를 사용하기 전에 선언했는지 확인하세요."],
  [/unknown type name '(.+)'/, "'{1}'이라는 타입은 존재하지 않습니다.", "오타를 확인하세요. <code>int</code>, <code>char</code>, <code>float</code>, <code>double</code> 등을 사용하세요."],

  // 함수 관련
  [/implicit declaration of function '(.+)'.*did you mean '(.+)'/, "'{1}' 함수를 찾을 수 없습니다. 혹시 <strong>{2}</strong>을(를) 쓰려고 했나요?", "오타를 수정하세요: <code>{1}</code> → <code>{2}</code>"],
  [/implicit declaration of function '(.+)'/, "'{1}' 함수가 선언되지 않았습니다.", "필요한 <code>#include</code>를 추가하거나 함수 이름을 확인하세요."],
  [/too few arguments to function '(.+)'/, "'{1}' 함수에 전달한 인자가 부족합니다.", "함수가 몇 개의 값을 받는지 확인하세요."],
  [/too many arguments to function '(.+)'/, "'{1}' 함수에 전달한 인자가 너무 많습니다.", "함수가 몇 개의 값을 받는지 확인하세요."],
  [/too (few|many) arguments to function/, "함수에 전달한 인자 수가 맞지 않습니다.", "함수 정의를 보고 매개변수 수를 맞추세요."],
  [/conflicting types for '(.+)'/, "'{1}'의 타입이 이전 선언과 다릅니다.", "같은 이름의 함수/변수가 다른 타입으로 선언되어 있는지 확인하세요."],

  // printf/scanf 관련
  [/format '(.+)' expects a matching '(.+)' argument/, "printf/scanf에서 <code>{1}</code>에 맞는 변수가 없습니다.", "printf에 <code>{1}</code>를 썼으면 그에 맞는 변수를 넣어야 합니다. 예: <code>printf(\"%d\", a);</code>"],
  [/format '(.+)' expects argument of type '(.+)'.*has type '(.+)'/, "printf/scanf에서 <code>{1}</code>은(는) {2} 타입인데 {3} 타입 변수를 넣었습니다.", "<code>%d</code>는 int, <code>%f</code>는 float/double, <code>%c</code>는 char입니다."],
  [/unknown conversion type character '(.+)' in format/, "printf/scanf에서 <code>%{1}</code>은(는) 잘못된 서식 문자입니다.", "사용 가능한 서식: <code>%d</code>(정수), <code>%f</code>(실수), <code>%c</code>(문자), <code>%s</code>(문자열), <code>%lf</code>(double)"],
  [/expected identifier or '\(' before '\)' token/, "변수명이 빠져 있습니다.", "변수를 선언할 때 타입 뒤에 변수 이름을 써야 합니다. 예: <code>float a;</code>"],
  [/expected identifier/, "변수명이나 함수명이 필요한 위치입니다.", "이름이 빠지지 않았는지 확인하세요."],

  // 타입/변환
  [/incompatible type/, "변수 타입이 호환되지 않습니다.", "대입하려는 값의 타입과 변수의 타입이 맞는지 확인하세요."],
  [/assignment makes (integer|pointer) from (integer|pointer) without a cast/, "타입이 다른 값을 대입하고 있습니다.", "변수 타입을 확인하세요."],
  [/comparison between pointer and integer/, "포인터와 정수를 비교하고 있습니다.", "비교 대상의 타입을 확인하세요."],

  // 배열/포인터
  [/subscripted value is neither array nor pointer/, "배열이 아닌 변수에 <code>[]</code>를 사용했습니다.", "변수가 배열로 선언되어 있는지 확인하세요."],
  [/array subscript is not an integer/, "배열 인덱스가 정수가 아닙니다.", "배열의 <code>[]</code> 안에는 정수만 넣을 수 있습니다."],
  [/variably modified/, "배열 크기에 변수를 사용했습니다.", "배열 크기는 상수(숫자)로 지정하세요. 예: <code>int arr[100];</code>"],

  // 이스케이프 시퀀스
  [/unknown escape sequence: '\\(.)'/, "알 수 없는 이스케이프 문자 <code>\\{1}</code>을(를) 사용했습니다.", "C에서 쓸 수 있는 이스케이프 문자: <code>\\n</code>(줄바꿈), <code>\\t</code>(탭), <code>\\\\</code>(역슬래시), <code>\\\"</code>(큰따옴표) 등. 일반 역슬래시를 출력하려면 <code>\\\\</code>을 사용하세요."],
  [/unknown escape sequence/, "알 수 없는 이스케이프 문자를 사용했습니다.", "<code>\\n</code>, <code>\\t</code>, <code>\\\\</code>, <code>\\\"</code> 등만 사용할 수 있습니다."],
  [/missing terminating " character/, "큰따옴표(<code>\"</code>)가 닫히지 않았습니다.", "문자열의 시작과 끝에 <code>\"</code>가 짝이 맞는지 확인하세요."],

  // 기타 C/C++
  [/redefinition of '(.+)'/, "'{1}'이(가) 중복 선언되었습니다.", "같은 이름으로 두 번 선언하지 않았는지 확인하세요."],
  [/stray .* in program/, "코드에 잘못된 문자가 포함되어 있습니다.", "한글 문자나 특수문자가 코드에 섞여 있지 않은지 확인하세요. 특히 한글 따옴표를 영문 따옴표로 바꿔보세요."],
  [/unterminated comment/, "주석이 닫히지 않았습니다.", "<code>/*</code>로 시작한 주석을 <code>*/</code>로 닫으세요."],
  [/unterminated string/, "문자열이 닫히지 않았습니다.", "따옴표 <code>\"</code>가 짝이 맞는지 확인하세요."],
  [/missing terminating (.) character/, "문자열/문자 리터럴이 닫히지 않았습니다.", "따옴표가 짝이 맞는지 확인하세요."],
  [/empty character constant/, "빈 문자 상수입니다.", "<code>''</code> 안에 문자를 넣으세요. 예: <code>'A'</code>"],
  [/multi-character character constant/, "문자 상수에 여러 글자가 들어있습니다.", "작은따옴표 <code>''</code>에는 한 글자만 넣을 수 있습니다. 문자열은 큰따옴표 <code>\"\"</code>를 사용하세요."],
  [/zero-length.*format string/, "빈 문자열로 printf를 호출했습니다.", "출력할 내용을 넣으세요."],
  [/main.*must return.*int/, "main 함수는 int를 반환해야 합니다.", "<code>int main()</code>으로 선언하세요."],
  [/return type of 'main' is not 'int'/, "main 함수의 반환 타입이 int가 아닙니다.", "<code>int main()</code>으로 수정하세요."],

  // 링커 에러
  [/undefined reference to '(.+)'.*did you mean '(.+)'/, "'{1}'을(를) 찾을 수 없습니다. 혹시 <strong>{2}</strong>을(를) 쓰려고 했나요?", "오타를 수정하세요: <code>{1}</code> → <code>{2}</code>"],
  [/undefined reference to '(.+)'/, "'{1}'이(가) 정의되지 않았습니다.", "함수를 선언만 하고 본문을 작성하지 않았거나, 이름에 오타가 있을 수 있습니다."],
  [/ld returned 1 exit status/, "링크에 실패했습니다.", "위의 에러를 먼저 해결하세요. 함수 이름 오타가 가장 흔한 원인입니다."],

  // === Java ===
  [/cannot find symbol.*variable (.+)/, "변수 '{1}'을(를) 찾을 수 없습니다.", "변수를 선언했는지, 이름에 오타가 없는지 확인하세요."],
  [/cannot find symbol.*method (.+)/, "메서드 '{1}'을(를) 찾을 수 없습니다.", "메서드 이름에 오타가 없는지 확인하세요."],
  [/cannot find symbol/, "사용한 변수나 메서드를 찾을 수 없습니다.", "이름에 오타가 없는지 확인하세요."],
  [/class.*interface.*enum expected/, "클래스 구조에 오류가 있습니다.", "중괄호 <code>{}</code> 짝이 맞는지 확인하세요."],
  [/reached end of file while parsing/, "파일 끝에서 닫는 중괄호가 부족합니다.", "<code>{</code>와 <code>}</code>의 개수를 맞추세요."],
  [/package .* does not exist/, "패키지를 찾을 수 없습니다.", "import문을 확인하세요."],
  [/'.+' has private access/, "private 멤버에 접근할 수 없습니다.", "접근 제한자를 확인하세요."],

  // === Python ===
  [/IndentationError.*expected an indented block/, "들여쓰기가 필요한 곳에 들여쓰기가 없습니다.", "<code>if</code>, <code>for</code>, <code>def</code> 등의 다음 줄은 들여쓰기(스페이스 4칸)를 해야 합니다."],
  [/IndentationError.*unexpected indent/, "불필요한 들여쓰기가 있습니다.", "이 줄의 들여쓰기를 제거하거나 맞추세요."],
  [/IndentationError/, "들여쓰기 오류입니다.", "탭과 스페이스를 혼용하지 말고, 스페이스 4칸으로 통일하세요."],
  [/SyntaxError.*invalid syntax/, "문법 오류입니다.", "콜론<code>:</code>, 괄호<code>()</code>, 따옴표<code>\"\"</code>가 빠지지 않았는지 확인하세요."],
  [/SyntaxError.*EOL while scanning string/, "문자열이 닫히지 않았습니다.", "따옴표가 짝이 맞는지 확인하세요."],
  [/SyntaxError/, "문법 오류입니다.", "이 줄의 문법을 다시 확인하세요."],
  [/NameError.*name '(.+)' is not defined/, "'{1}'이(가) 정의되지 않았습니다.", "변수/함수 이름에 오타가 없는지, 사용 전에 선언했는지 확인하세요."],
  [/TypeError.*'(.+)' not supported between/, "'{1}' 타입끼리 비교할 수 없습니다.", "변수 타입을 확인하세요. <code>int()</code>나 <code>str()</code>로 변환이 필요할 수 있습니다."],
  [/TypeError/, "타입이 맞지 않는 연산입니다.", "변수의 타입을 확인하세요."],
  [/ModuleNotFoundError.*'(.+)'/, "'{1}' 모듈을 찾을 수 없습니다.", "이 환경에서 사용할 수 없는 모듈입니다. 기본 라이브러리만 사용하세요."],
];

function parseErrors() {
  var raw = document.getElementById('ce-raw').textContent;
  // HTML 엔티티 + 스마트 따옴표 정리
  raw = raw.replace(/&lsquo;|&rsquo;/g, "'").replace(/&ldquo;|&rdquo;/g, '"').replace(/&quot;/g, '"').replace(/&amp;/g, '&');
  raw = raw.replace(/[\u2018\u2019\u0060\u00B4]/g, "'").replace(/[\u201C\u201D]/g, '"');

  var lines = raw.split('\n');
  var errors = [];
  var errorLines = [];

  for(var i = 0; i < lines.length; i++) {
    var line = lines[i].trim();
    // error: 또는 Error 패턴 찾기
    var m = line.match(/(?:Main\.c|Main\.cpp|Main\.java|solution\.py|File.*line)\S*?:(\d+):\d+:\s*(error|warning|Error|Warning):\s*(.+)/i)
         || line.match(/(?:Main\.c|Main\.cpp|Main\.java)\S*?:(\d+):\s*(error|warning|Error|Warning):\s*(.+)/i)
         || line.match(/collect2:\s*(error):\s*(.+)/i);
    if(!m) {
      // Python 스타일: line X
      var pm = line.match(/line (\d+)/);
      var isErr = line.match(/(Error|error):\s*(.+)/);
      if(pm && isErr) {
        m = [null, pm[1], isErr[1], isErr[2]];
      }
    }
    // collect2 에러 (줄번호 없음)
    if(!m) {
      var cm = line.match(/collect2:\s*(error):\s*(.+)/i);
      if(cm) {
        m = [null, '0', cm[1], cm[2]];
      }
    }
    if(m) {
      var lineNum = parseInt(m[1]) || 0;
      var errMsg = m[3] ? m[3].trim() : (m[2] ? m[2].trim() : '');
      if(lineNum > 0) errorLines.push(lineNum);

      // 컴파일러 플래그 제거 [-Wformat=] [-Wunused-result] 등
      errMsg = errMsg.replace(/\s*\[-W[^\]]*\]\s*$/, '').trim();

      // 무해한 warning 건너뛰기
      if(errMsg.match(/ignoring return value of/)) continue;

      // 한글 번역 찾기
      var korean = null, tip = null;
      for(var j = 0; j < translations.length; j++) {
        var tr = translations[j][0].exec(errMsg);
        if(tr) {
          korean = translations[j][1];
          tip = translations[j][2];
          // {1}, {2} 치환
          for(var k = 1; k < tr.length; k++) {
            korean = korean.replace('{'+k+'}', tr[k]);
            if(tip) tip = tip.replace('{'+k+'}', tr[k]);
          }
          break;
        }
      }

      var errType = m[2].toLowerCase();
      // 중복 에러 줄 건너뛰기
      var isDup = false;
      for(var d = 0; d < errors.length; d++){
        if(errors[d].line === lineNum && errors[d].line > 0) { isDup = true; break; }
      }
      if(!isDup) {
        errors.push({
          line: lineNum,
          type: errType,
          raw: errMsg,
          korean: korean || errMsg,
          tip: tip
        });
      }
    }
  }

  // 렌더링
  var box = document.getElementById('ce-errors');
  if(errors.length === 0) {
    box.innerHTML = '<div class="ce-error-item"><div class="ce-error-korean">컴파일 에러가 발생했습니다.</div><div class="ce-error-tip">아래 원본 에러 메시지를 확인하세요.</div></div>';
    return [];
  }

  var html = '';
  for(var i = 0; i < errors.length; i++) {
    var e = errors[i];
    html += '<div class="ce-error-item">';
    if(e.line > 0) {
      html += '<div class="ce-error-location"><span class="ce-line-badge">' + e.line + '번째 줄</span>';
    } else {
      html += '<div class="ce-error-location">';
    }
    html += '<span class="ce-error-type">' + e.type + '</span></div>';
    html += '<div class="ce-error-korean">' + e.korean + '</div>';
    if(e.tip) html += '<div class="ce-error-tip">' + e.tip + '</div>';
    html += '</div>';
  }
  box.innerHTML = html;
  return errorLines;
}

// 에러 분석 바로 실행
var _errorLines = parseErrors();

// 소스 코드 로드
try {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'showsource2.php?id=<?php echo $id?>', true);
  xhr.onload = function() {
    if(xhr.status !== 200) return;
    var parser = new DOMParser();
    var doc = parser.parseFromString(xhr.responseText, 'text/html');
    var preEl = doc.querySelector('pre');
    var codeText = preEl ? preEl.textContent : xhr.responseText;

    var langMode = 'c_cpp';
    if(preEl){
      var cls = preEl.getAttribute('class') || '';
      var m = cls.match(/brush:(\w+)/);
      if(m){
        var brushMap = {c:'c_cpp','c++':'c_cpp',cpp:'c_cpp',java:'java',python:'python',python3:'python'};
        langMode = brushMap[m[1]] || 'text';
        document.getElementById('src-lang-tag').textContent = m[1].toUpperCase();
      }
    }

    var container = document.getElementById('source-ace');
    var lineCount = (codeText.match(/\n/g)||[]).length + 1;
    container.style.height = Math.max(200, Math.min(lineCount * 19 + 20, window.innerHeight * 0.6)) + 'px';

    var editor = ace.edit('source-ace');
    editor.setTheme('ace/theme/monokai');
    editor.getSession().setMode('ace/mode/' + langMode);
    editor.getSession().setValue(codeText);
    editor.setReadOnly(true);
    editor.setOptions({ fontSize: '14px', showPrintMargin: false });
    editor.renderer.setScrollMargin(8, 8);

    // 에러 줄 하이라이팅
    try {
      var Range = ace.require('ace/range').Range;
      for(var i = 0; i < _errorLines.length; i++) {
        var row = _errorLines[i] - 1;
        if(row >= 0 && row < editor.session.getLength()) {
          editor.session.addGutterDecoration(row, 'ace_error');
          editor.session.addMarker(new Range(row, 0, row, Infinity), 'ace_error-line', 'fullLine');
        }
      }
      if(_errorLines.length > 0) editor.gotoLine(_errorLines[0], 0, true);
    } catch(e) {}
  };
  xhr.send();
} catch(e) {}
</script>
</body>
</html>
