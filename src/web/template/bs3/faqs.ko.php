<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $OJ_NAME?> - 도움말</title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

    .faq-wrapper { max-width: 900px; margin: 0 auto; padding: 32px 20px 60px; }
    .faq-title { text-align: center; margin-bottom: 36px; }
    .faq-title h1 { font-size: 28px; font-weight: 900; color: #7c3aed; margin: 0 0 8px; }
    .faq-title p { font-size: 14px; color: #888; margin: 0; }

    .faq-section { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); margin-bottom: 16px; overflow: hidden; }
    .faq-question { display: flex; align-items: center; gap: 12px; padding: 18px 24px; cursor: pointer; user-select: none; transition: background 0.15s; border: none; background: none; width: 100%; text-align: left; font-family: inherit; }
    .faq-question:hover { background: #f8faff; }
    .faq-q-badge { background: #7c3aed; color: #fff; font-size: 13px; font-weight: 700; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .faq-q-text { flex: 1; font-size: 15px; font-weight: 700; color: #222; }
    .faq-chevron { width: 20px; height: 20px; flex-shrink: 0; transition: transform 0.25s; color: #bbb; }
    .faq-section.open .faq-chevron { transform: rotate(180deg); color: #7c3aed; }
    .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
    .faq-section.open .faq-answer { max-height: 2000px; }
    .faq-answer-inner { padding: 0 24px 24px 64px; font-size: 14px; line-height: 1.8; color: #444; }

    .faq-code { background: #1e293b; color: #e2e8f0; border-radius: 8px; padding: 16px 20px; font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 13px; line-height: 1.6; overflow-x: auto; margin: 12px 0; white-space: pre; }
    .faq-table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 13px; border-radius: 8px; overflow: hidden; }
    .faq-table th { background: #7c3aed; color: #fff; padding: 10px 16px; text-align: left; font-weight: 600; }
    .faq-table td { padding: 10px 16px; border-bottom: 1px solid #f0f3f7; }
    .faq-table tr:last-child td { border-bottom: none; }
    .faq-table tr:hover td { background: #f8faff; }
    .lang-tag { display: inline-block; padding: 2px 10px; border-radius: 4px; font-weight: 700; font-size: 12px; min-width: 50px; text-align: center; }
    .lang-c { background: #e8f0fe; color: #7c3aed; }
    .lang-cpp { background: #e8f0fe; color: #6d28d9; }
    .lang-java { background: #fee2e2; color: #dc2626; }
    .lang-py { background: #d1fae5; color: #059669; }

    /* 채점 결과 리스트 */
    .result-list { list-style: none; padding: 0; margin: 0; }
    .result-list li { display: flex; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f0f3f7; align-items: center; }
    .result-list li:last-child { border-bottom: none; }
    .result-badge {
      display: inline-block;
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      white-space: nowrap;
      flex-shrink: 0;
      min-width: 110px;
      text-align: center;
      color: #fff;
    }
    .rb-ac  { background: #34c759; }
    .rb-wa  { background: #ff3b30; }
    .rb-pe  { background: #ff9500; }
    .rb-tle { background: #ff9500; }
    .rb-mle { background: #ff9500; }
    .rb-ole { background: #ff9500; }
    .rb-re  { background: #ff3b30; }
    .rb-ce  { background: #ff3b30; }
    .rb-wait { background: #8e8e93; }

    .result-desc { font-size: 13.5px; color: #444; line-height: 1.6; }
    .result-desc strong { color: #222; }

    @media (max-width: 600px) {
      .faq-answer-inner { padding-left: 24px; }
      .faq-wrapper { padding: 20px 12px 40px; }
      .result-list li { flex-direction: column; gap: 6px; }
    }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="faq-wrapper">
  <div class="faq-title">
    <h1>❓ 도움말</h1>
    <p><?php echo $OJ_NAME?> 사용 가이드</p>
  </div>

  <!-- Q1: 채점 결과 (맨 위) -->
  <div class="faq-section open">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">채점 결과의 의미가 뭔가요?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <ul class="result-list">
          <li>
            <span class="result-badge rb-ac">정답</span>
            <span class="result-desc"><strong>축하합니다!</strong> 프로그램이 모든 테스트 케이스를 통과했습니다.</span>
          </li>
          <li>
            <span class="result-badge rb-wa">오답</span>
            <span class="result-desc">출력 결과가 정답과 다릅니다. <strong>로직을 다시 확인</strong>하고, 경계값(0, 음수, 최댓값)도 테스트하세요.</span>
          </li>
          <li>
            <span class="result-badge rb-pe">출력 형식 오류</span>
            <span class="result-desc">답은 맞지만 <strong>공백, 줄바꿈, 대소문자</strong> 등 출력 형식이 다릅니다.</span>
          </li>
          <li>
            <span class="result-badge rb-tle">시간 초과</span>
            <span class="result-desc">실행 시간이 제한을 초과했습니다. <strong>더 효율적인 알고리즘</strong>을 사용하거나, 무한루프를 확인하세요.</span>
          </li>
          <li>
            <span class="result-badge rb-mle">메모리 초과</span>
            <span class="result-desc">메모리 사용량이 제한을 초과했습니다. <strong>배열 크기</strong>를 줄이거나, 재귀 깊이를 확인하세요.</span>
          </li>
          <li>
            <span class="result-badge rb-ole">출력 초과</span>
            <span class="result-desc">출력이 너무 많습니다. <strong>무한루프</strong>에서 출력이 계속되고 있는지, 디버깅 print문을 제거했는지 확인하세요.</span>
          </li>
          <li>
            <span class="result-badge rb-re">런타임 에러</span>
            <span class="result-desc">실행 중 비정상 종료. <strong>배열 범위 초과, 0으로 나누기, 널 포인터</strong> 등을 확인하세요.</span>
          </li>
          <li>
            <span class="result-badge rb-ce">컴파일 에러</span>
            <span class="result-desc">코드 문법 오류로 컴파일 실패. 제출현황에서 클릭하면 <strong>상세 에러 메시지</strong>를 확인할 수 있습니다.</span>
          </li>
          <li>
            <span class="result-badge rb-wait">채점 대기중</span>
            <span class="result-desc">채점 서버가 처리 중입니다. 잠시 기다려 주세요.</span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q2: 입출력 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">입력과 출력은 어떻게 처리하나요?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p><strong>표준 입력(stdin)</strong>에서 읽고, <strong>표준 출력(stdout)</strong>으로 결과를 출력합니다.<br>
        파일 입출력은 허용되지 않으며, 시도 시 <strong>Runtime Error</strong>가 발생합니다.</p>

        <p><strong>예제 (A+B 문제)</strong></p>

        <p><span class="lang-tag lang-c">C</span></p>
<div class="faq-code">#include &lt;stdio.h&gt;
int main()
{
	int a, b;
	scanf("%d %d", &amp;a, &amp;b);
	printf("%d\n", a + b);
	return 0;
}</div>

        <p><span class="lang-tag lang-py">Python</span></p>
<div class="faq-code">a, b = map(int, input().split())
print(a + b)</div>

        <p><span class="lang-tag lang-cpp">C++</span></p>
<div class="faq-code">#include &lt;iostream&gt;
using namespace std;
int main()
{
	int a, b;
	cin >> a >> b;
	cout &lt;&lt; a + b &lt;&lt; endl;
	return 0;
}</div>
      </div>
    </div>
  </div>

  <!-- Q3: 컴파일 에러 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">컴파일 에러(Compile Error)가 발생해요!</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p>이 저지는 <strong>GCC(GNU Compiler)</strong>를 사용하므로, Visual Studio(MSVC)와 차이가 있습니다:</p>
        <ul style="padding-left:20px;line-height:2.2">
          <li><code>main</code> 함수는 반드시 <code>int main()</code>으로 선언 (<code>void main</code> ✖)</li>
          <li><code>__int64</code> 대신 <code>long long</code> 사용</li>
          <li><code>itoa()</code>는 비표준 함수이므로 사용 불가</li>
          <li>세미콜론(;), 괄호, 중괄호 누락 확인</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q4: 대회 참가 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">수업/대회에는 어떻게 참가하나요?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <ul style="padding-left:20px;line-height:2.2">
          <li>메뉴의 <strong>수업/대회</strong>를 클릭</li>
          <li>진행 중인 대회를 선택하여 문제를 풀면 됩니다</li>
          <li>대회 시간 내에만 제출이 인정됩니다</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q5: IDE 설치 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">프로그래밍 IDE는 어떻게 설치하나요?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p>코드를 작성하고 실행하려면 <strong>통합개발환경(IDE)</strong>을 설치해야 합니다. 아래 자료를 따라 무료 IDE를 설치할 수 있습니다.</p>
        <p style="margin-top:16px"><strong>🐍 Python</strong></p>
        <ul>
          <li><a href="/upload/file/20250208/20250208194334_67081.pdf" target="_blank">Windows Python IDLE 설치하기</a></li>
          <li><a href="/upload/file/20250208/20250208194402_66329.pdf" target="_blank">macOS Python IDLE 설치하기</a></li>
        </ul>
        <p style="margin-top:12px"><strong>💻 C/C++</strong></p>
        <ul>
          <li><a href="/upload/file/20250208/20250208194421_73154.pdf" target="_blank">Windows Code::Blocks 설치하기</a></li>
          <li><a href="/upload/file/20250208/20250208194443_63873.pdf" target="_blank">macOS Xcode 설치하기</a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Q5: 비밀번호 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">비밀번호를 잊어버렸어요!</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p><strong>선생님(관리자)</strong>에게 직접 요청하여 비밀번호를 초기화할 수 있습니다.</p>
      </div>
    </div>
  </div>

</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function toggleFaq(btn) {
  var section = btn.parentElement;
  section.classList.toggle('open');
}
</script>
<div class="codit-global-footer"><div class="cgf-inner"><span>&copy; <?php echo date("Y")?> Codit · <a href="https://school.cbe.go.kr/chungju-h/" target="_blank">충주고등학교</a> 정보교사 박지훈</span><span class="cgf-sep">|</span><a href="privacy.php">개인정보처리방침</a><span class="cgf-sep">|</span><a href="mailto:wlgnsdl122@naver.com">wlgnsdl122@naver.com</a></div></div>
</body>
</html>
