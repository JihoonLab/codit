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
    body {
      font-family: 'Noto Sans KR', sans-serif;
      background: #f4f6f9;
      margin: 0;
      color: #333;
    }

    .faq-wrapper {
      max-width: 900px;
      margin: 0 auto;
      padding: 32px 20px 60px;
    }

    .faq-title {
      text-align: center;
      margin-bottom: 36px;
    }
    .faq-title h1 {
      font-size: 28px;
      font-weight: 900;
      color: #7c3aed;
      margin: 0 0 8px;
    }
    .faq-title p {
      font-size: 14px;
      color: #888;
      margin: 0;
    }

    /* 아코디언 카드 */
    .faq-section {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.07);
      margin-bottom: 16px;
      overflow: hidden;
    }

    .faq-question {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 18px 24px;
      cursor: pointer;
      user-select: none;
      transition: background 0.15s;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      font-family: inherit;
    }
    .faq-question:hover { background: #f8faff; }

    .faq-q-badge {
      background: #7c3aed;
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .faq-q-text {
      flex: 1;
      font-size: 15px;
      font-weight: 700;
      color: #222;
    }

    .faq-chevron {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      transition: transform 0.25s;
      color: #bbb;
    }
    .faq-section.open .faq-chevron { transform: rotate(180deg); color: #7c3aed; }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease;
    }
    .faq-section.open .faq-answer {
      max-height: 2000px;
    }

    .faq-answer-inner {
      padding: 0 24px 24px 64px;
      font-size: 14px;
      line-height: 1.8;
      color: #444;
    }

    /* 코드 블록 */
    .faq-code {
      background: #1e293b;
      color: #e2e8f0;
      border-radius: 8px;
      padding: 16px 20px;
      font-family: 'JetBrains Mono', 'Fira Code', monospace;
      font-size: 13px;
      line-height: 1.6;
      overflow-x: auto;
      margin: 12px 0;
      white-space: pre;
    }
    .faq-code .code-comment { color: #64748b; }
    .faq-code .code-keyword { color: #93c5fd; }
    .faq-code .code-string { color: #86efac; }

    /* 컴파일러 테이블 */
    .faq-table {
      width: 100%;
      border-collapse: collapse;
      margin: 12px 0;
      font-size: 13px;
      border-radius: 8px;
      overflow: hidden;
    }
    .faq-table th {
      background: #7c3aed;
      color: #fff;
      padding: 10px 16px;
      text-align: left;
      font-weight: 600;
    }
    .faq-table td {
      padding: 10px 16px;
      border-bottom: 1px solid #f0f3f7;
    }
    .faq-table tr:last-child td { border-bottom: none; }
    .faq-table tr:hover td { background: #f8faff; }
    .faq-table .lang-tag {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 12px;
      min-width: 50px;
      text-align: center;
    }
    .lang-c   { background: #e8f0fe; color: #7c3aed; }
    .lang-cpp  { background: #e8f0fe; color: #6d28d9; }
    .lang-pas  { background: #fef3c7; color: #b45309; }
    .lang-java { background: #fee2e2; color: #dc2626; }
    .lang-py   { background: #d1fae5; color: #059669; }

    .faq-cmd {
      background: #f1f5f9;
      padding: 6px 12px;
      border-radius: 6px;
      font-family: 'JetBrains Mono', monospace;
      font-size: 12px;
      color: #334155;
      display: block;
      margin: 4px 0;
      word-break: break-all;
    }

    /* 채점 결과 뱃지 */
    .result-list { list-style: none; padding: 0; margin: 12px 0; }
    .result-list li {
      display: flex;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid #f0f3f7;
      align-items: flex-start;
    }
    .result-list li:last-child { border-bottom: none; }
    .result-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 700;
      white-space: nowrap;
      flex-shrink: 0;
      min-width: 100px;
      text-align: center;
    }
    .rb-ac  { background: #d1fae5; color: #059669; }
    .rb-pe  { background: #e0f2fe; color: #0284c7; }
    .rb-wa  { background: #fee2e2; color: #dc2626; }
    .rb-tle { background: #fef3c7; color: #b45309; }
    .rb-mle { background: #fce7f3; color: #be185d; }
    .rb-ole { background: #fef3c7; color: #92400e; }
    .rb-re  { background: #ede9fe; color: #7c3aed; }
    .rb-ce  { background: #f1f5f9; color: #475569; }
    .rb-wait { background: #f0f3f7; color: #888; }

    .faq-note {
      background: #eff6ff;
      border-left: 4px solid #7c3aed;
      padding: 12px 16px;
      border-radius: 0 8px 8px 0;
      font-size: 13px;
      margin: 12px 0;
      color: #1e40af;
    }

    @media (max-width: 600px) {
      .faq-answer-inner { padding-left: 24px; }
      .faq-wrapper { padding: 20px 12px 40px; }
    }
  </style>
</head>
<body>

<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="faq-wrapper">

  <div class="faq-title">
    <h1>&#x2753; &#xB3C4;&#xC6C0;&#xB9D0;</h1>
    <p><?php echo $OJ_NAME?> &#xC0AC;&#xC6A9; &#xAC00;&#xC774;&#xB4DC;</p>
  </div>

  <!-- Q2: 입출력 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">&#xC785;&#xB825;&#xACFC; &#xCD9C;&#xB825;&#xC740; &#xC5B4;&#xB5BB;&#xAC8C; &#xCC98;&#xB9AC;&#xD558;&#xB098;&#xC694;?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p><strong>&#xD45C;&#xC900; &#xC785;&#xB825;(stdin)</strong>&#xC5D0;&#xC11C; &#xC77D;&#xACE0;, <strong>&#xD45C;&#xC900; &#xCD9C;&#xB825;(stdout)</strong>&#xC73C;&#xB85C; &#xACB0;&#xACFC;&#xB97C; &#xCD9C;&#xB825;&#xD569;&#xB2C8;&#xB2E4;.<br>
        &#xD30C;&#xC77C; &#xC785;&#xCD9C;&#xB825;&#xC740; &#xD5C8;&#xC6A9;&#xB418;&#xC9C0; &#xC54A;&#xC73C;&#xBA70;, &#xC2DC;&#xB3C4; &#xC2DC; <strong>Runtime Error</strong>&#xAC00; &#xBC1C;&#xC0DD;&#xD569;&#xB2C8;&#xB2E4;.</p>

        <p><strong>&#xC608;&#xC81C; &#xBB38;&#xC81C; 1000 (A+B)</strong> &#xD480;&#xC774; &#xC608;&#xC2DC;:</p>

        <p><span class="lang-tag lang-cpp">C++</span></p>
<div class="faq-code">#include &lt;iostream&gt;
using namespace std;
int main(){
    int a, b;
    while(cin >> a >> b)
        cout &lt;&lt; a + b &lt;&lt; '\n';
    return 0;
}</div>

        <p><span class="lang-tag lang-c">C</span></p>
<div class="faq-code">#include &lt;stdio.h&gt;
int main(){
    int a, b;
    while(scanf("%d %d", &amp;a, &amp;b) != EOF)
        printf("%d\n", a + b);
    return 0;
}</div>

        <p><span class="lang-tag lang-py">Python</span></p>
<div class="faq-code">import sys
for line in sys.stdin:
    a, b = map(int, line.split())
    print(a + b)</div>

        <p><span class="lang-tag lang-java">Java</span></p>
<div class="faq-code">import java.util.*;
public class Main {
    public static void main(String[] args) {
        Scanner cin = new Scanner(System.in);
        while (cin.hasNextInt()) {
            int a = cin.nextInt(), b = cin.nextInt();
            System.out.println(a + b);
        }
    }
}</div>
      </div>
    </div>
  </div>

  <!-- Q3: 컴파일 에러 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">&#xCEF4;&#xD30C;&#xC77C; &#xC5D0;&#xB7EC;(Compile Error)&#xAC00; &#xBC1C;&#xC0DD;&#xD574;&#xC694;!</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p>&#xC774; &#xC800;&#xC9C0;&#xB294; <strong>GCC(GNU Compiler)</strong>&#xB97C; &#xC0AC;&#xC6A9;&#xD558;&#xBBC0;&#xB85C;, Visual Studio(MSVC)&#xC640; &#xCC28;&#xC774;&#xAC00; &#xC788;&#xC2B5;&#xB2C8;&#xB2E4;:</p>
        <ul style="padding-left:20px;line-height:2.2">
          <li><code>main</code> &#xD568;&#xC218;&#xB294; &#xBC18;&#xB4DC;&#xC2DC; <code>int main()</code>&#xC73C;&#xB85C; &#xC120;&#xC5B8; (<code>void main</code> &#x2716;)</li>
          <li><code>for(int i=0;...)</code>&#xC5D0;&#xC11C; <code>i</code>&#xB294; &#xBE14;&#xB85D; &#xBC16;&#xC5D0;&#xC11C; &#xC0AC;&#xC6A9; &#xBD88;&#xAC00;</li>
          <li><code>__int64</code> &#xB300;&#xC2E0; <code>long long</code> &#xC0AC;&#xC6A9;</li>
          <li><code>itoa()</code>&#xB294; &#xBE44;&#xD45C;&#xC900; &#xD568;&#xC218;&#xC774;&#xBBC0;&#xB85C; &#xC0AC;&#xC6A9; &#xBD88;&#xAC00;</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q4: 채점 결과 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">&#xCC44;&#xC810; &#xACB0;&#xACFC;&#xC758; &#xC758;&#xBBF8;&#xAC00; &#xBB54;&#xC778;&#xAC00;&#xC694;?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <ul class="result-list">
          <li>
            <span class="result-badge rb-ac">Accepted</span>
            <span>&#xC815;&#xB2F5;! &#xD504;&#xB85C;&#xADF8;&#xB7A8;&#xC774; &#xBAA8;&#xB4E0; &#xD14C;&#xC2A4;&#xD2B8; &#xCF00;&#xC774;&#xC2A4;&#xB97C; &#xD1B5;&#xACFC;&#xD588;&#xC2B5;&#xB2C8;&#xB2E4;.</span>
          </li>
          <li>
            <span class="result-badge rb-wa">Wrong Answer</span>
            <span>&#xCD9C;&#xB825; &#xACB0;&#xACFC;&#xAC00; &#xC815;&#xB2F5;&#xACFC; &#xB2E4;&#xB985;&#xB2C8;&#xB2E4;. &#xB85C;&#xC9C1;&#xC744; &#xB2E4;&#xC2DC; &#xD655;&#xC778;&#xD574; &#xBCF4;&#xC138;&#xC694;.</span>
          </li>
          <li>
            <span class="result-badge rb-pe">Presentation Error</span>
            <span>&#xB2F5;&#xC740; &#xB9DE;&#xC9C0;&#xB9CC; &#xCD9C;&#xB825; &#xD615;&#xC2DD;(&#xACF5;&#xBC31;, &#xC904;&#xBC14;&#xAFC8; &#xB4F1;)&#xC774; &#xB2E4;&#xB985;&#xB2C8;&#xB2E4;.</span>
          </li>
          <li>
            <span class="result-badge rb-tle">Time Limit Exceeded</span>
            <span>&#xC2DC;&#xAC04; &#xC81C;&#xD55C;&#xC744; &#xCD08;&#xACFC;&#xD588;&#xC2B5;&#xB2C8;&#xB2E4;. &#xB354; &#xD6A8;&#xC728;&#xC801;&#xC778; &#xC54C;&#xACE0;&#xB9AC;&#xC998;&#xC744; &#xC0AC;&#xC6A9;&#xD574; &#xBCF4;&#xC138;&#xC694;.</span>
          </li>
          <li>
            <span class="result-badge rb-mle">Memory Limit Exceeded</span>
            <span>&#xBA54;&#xBAA8;&#xB9AC; &#xC81C;&#xD55C;&#xC744; &#xCD08;&#xACFC;&#xD588;&#xC2B5;&#xB2C8;&#xB2E4;.</span>
          </li>
          <li>
            <span class="result-badge rb-ole">Output Limit Exceeded</span>
            <span>&#xCD9C;&#xB825;&#xC774; &#xB108;&#xBB34; &#xB9CE;&#xC2B5;&#xB2C8;&#xB2E4;. &#xBB34;&#xD55C; &#xB8E8;&#xD504;&#xB97C; &#xD655;&#xC778;&#xD574; &#xBCF4;&#xC138;&#xC694;. (&#xC81C;&#xD55C;: 1MB)</span>
          </li>
          <li>
            <span class="result-badge rb-re">Runtime Error</span>
            <span>&#xC2E4;&#xD589; &#xC911; &#xC624;&#xB958; &#xBC1C;&#xC0DD;. &#xBC30;&#xC5F4; &#xBC94;&#xC704; &#xCD08;&#xACFC;, 0&#xC73C;&#xB85C; &#xB098;&#xB204;&#xAE30; &#xB4F1;&#xC744; &#xD655;&#xC778;&#xD558;&#xC138;&#xC694;.</span>
          </li>
          <li>
            <span class="result-badge rb-ce">Compile Error</span>
            <span>&#xCEF4;&#xD30C;&#xC77C; &#xC2E4;&#xD328;. &#xC81C;&#xCD9C;&#xD604;&#xD669;&#xC5D0;&#xC11C; &#xC624;&#xB958; &#xBA54;&#xC2DC;&#xC9C0;&#xB97C; &#xD655;&#xC778;&#xD560; &#xC218; &#xC788;&#xC2B5;&#xB2C8;&#xB2E4;.</span>
          </li>
          <li>
            <span class="result-badge rb-wait">Pending</span>
            <span>&#xCC44;&#xC810; &#xB300;&#xAE30; &#xC911;&#xC785;&#xB2C8;&#xB2E4;. &#xC7A0;&#xC2DC; &#xAE30;&#xB2E4;&#xB824; &#xC8FC;&#xC138;&#xC694;.</span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q5: 대회 참가 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">&#xB300;&#xD68C;&#xC5D0;&#xB294; &#xC5B4;&#xB5BB;&#xAC8C; &#xCC38;&#xAC00;&#xD558;&#xB098;&#xC694;?</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p>&#xD68C;&#xC6D0;&#xAC00;&#xC785; &#xD6C4; &#xB85C;&#xADF8;&#xC778;&#xD558;&#xBA74; &#xB300;&#xD68C;&#xC5D0; &#xCC38;&#xAC00;&#xD560; &#xC218; &#xC788;&#xC2B5;&#xB2C8;&#xB2E4;.</p>
        <ul style="padding-left:20px;line-height:2.2">
          <li>&#xBA54;&#xB274;&#xC758; <strong>&#xC218;&#xC5C5;/&#xB300;&#xD68C;</strong> &rarr; <strong>&#xB300;&#xD68C;</strong>&#xB97C; &#xD074;&#xB9AD;</li>
          <li>&#xC9C4;&#xD589; &#xC911;&#xC778; &#xB300;&#xD68C;&#xB97C; &#xC120;&#xD0DD;&#xD558;&#xC5EC; &#xBB38;&#xC81C;&#xB97C; &#xD480;&#xBA74; &#xB429;&#xB2C8;&#xB2E4;</li>
          <li>&#xB300;&#xD68C; &#xC2DC;&#xAC04; &#xB0B4;&#xC5D0;&#xB9CC; &#xC81C;&#xCD9C;&#xC774; &#xC778;&#xC815;&#xB429;&#xB2C8;&#xB2E4;</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Q6: 비밀번호 -->
  <div class="faq-section">
    <button class="faq-question" onclick="toggleFaq(this)">
      <span class="faq-q-badge">Q</span>
      <span class="faq-q-text">&#xBE44;&#xBC00;&#xBC88;&#xD638;&#xB97C; &#xC78A;&#xC5B4;&#xBC84;&#xB838;&#xC5B4;&#xC694;!</span>
      <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div class="faq-answer">
      <div class="faq-answer-inner">
        <p>&#xBE44;&#xBC00;&#xBC88;&#xD638; &#xCC3E;&#xAE30; &#xAE30;&#xB2A5;&#xC740; &#xD604;&#xC7AC; &#xC81C;&#xACF5;&#xB418;&#xC9C0; &#xC54A;&#xC2B5;&#xB2C8;&#xB2E4;.<br>
        <strong>&#xC120;&#xC0DD;&#xB2D8;(&#xAD00;&#xB9AC;&#xC790;)</strong>&#xC5D0;&#xAC8C; &#xC9C1;&#xC811; &#xC694;&#xCCAD;&#xD558;&#xC5EC; &#xBE44;&#xBC00;&#xBC88;&#xD638;&#xB97C; &#xCD08;&#xAE30;&#xD654;&#xD560; &#xC218; &#xC788;&#xC2B5;&#xB2C8;&#xB2E4;.</p>
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
</body>
</html>
