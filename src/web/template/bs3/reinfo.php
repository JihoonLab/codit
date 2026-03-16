<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>채점 상세 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }

.ri-wrap { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }

.ri-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  overflow: hidden;
  margin-bottom: 20px;
}
.ri-card-header {
  color: #fff;
  padding: 20px 28px;
  display: flex;
  align-items: center;
  gap: 14px;
}
.ri-card-header.ac  { background: linear-gradient(135deg, #059669, #047857); }
.ri-card-header.wa  { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.ri-card-header.pe  { background: linear-gradient(135deg, #ea580c, #c2410c); }
.ri-card-header.tle { background: linear-gradient(135deg, #d97706, #b45309); }
.ri-card-header.mle { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
.ri-card-header.ole { background: linear-gradient(135deg, #0891b2, #0e7490); }
.ri-card-header.re  { background: linear-gradient(135deg, #e11d48, #be123c); }
.ri-card-header.ce  { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.ri-card-header.pending { background: linear-gradient(135deg, #6b7280, #4b5563); }

.ri-card-header-icon { font-size: 32px; line-height: 1; }
.ri-card-header-text h2 { margin: 0; font-size: 22px; font-weight: 900; }
.ri-card-header-text p  { margin: 4px 0 0; font-size: 13px; opacity: 0.85; }
.ri-sid-badge {
  margin-left: auto;
  background: rgba(255,255,255,0.2);
  border-radius: 8px;
  padding: 6px 14px;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}

/* 에러 메시지 표시 */
/* 에러 메시지 공통 */
pre.ri-errbox {
  font-family: 'SF Mono', 'Consolas', 'Monaco', monospace !important;
  font-size: 13px !important;
  line-height: 1.8 !important;
  padding: 20px 28px !important;
  margin: 0 !important;
  white-space: pre-wrap !important;
  word-break: break-all !important;
  max-height: 400px !important;
  overflow-y: auto !important;
  border: none !important;
  border-radius: 0 !important;
  box-shadow: none !important;
}
pre.ri-errbox::-webkit-scrollbar { width: 6px; }
pre.ri-errbox::-webkit-scrollbar-thumb { border-radius: 3px; }

/* 결과별 에러 박스 색상 */
pre.ri-errbox.err-wa  { background: #fef2f2 !important; color: #991b1b !important; }
pre.ri-errbox.err-wa::-webkit-scrollbar-thumb { background: #e5a0a0; }
pre.ri-errbox.err-pe  { background: #fff7ed !important; color: #9a3412 !important; }
pre.ri-errbox.err-pe::-webkit-scrollbar-thumb { background: #e5c0a0; }
pre.ri-errbox.err-tle { background: #fffbeb !important; color: #92400e !important; }
pre.ri-errbox.err-tle::-webkit-scrollbar-thumb { background: #e5d0a0; }
pre.ri-errbox.err-mle { background: #f5f3ff !important; color: #5b21b6 !important; }
pre.ri-errbox.err-mle::-webkit-scrollbar-thumb { background: #c4b5fd; }
pre.ri-errbox.err-ole { background: #ecfdf5 !important; color: #065f46 !important; }
pre.ri-errbox.err-ole::-webkit-scrollbar-thumb { background: #a0d0c0; }
pre.ri-errbox.err-re  { background: #fff1f2 !important; color: #9f1239 !important; }
pre.ri-errbox.err-re::-webkit-scrollbar-thumb { background: #e5a0b0; }
pre.ri-errbox.err-ce  { background: #fff5f5 !important; color: #b91c1c !important; }
pre.ri-errbox.err-ce::-webkit-scrollbar-thumb { background: #e5a0a0; }

/* 원인 분석 (고정 + JS 세부) */
.ri-reason { padding: 20px 28px; }
.ri-reason h4 {
  font-size: 14px;
  font-weight: 700;
  color: #555;
  margin: 0 0 14px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.ri-reason-main {
  background: #f0f5ff;
  border: 1px solid #d0e0f5;
  border-radius: 10px;
  padding: 16px 20px;
  margin-bottom: 12px;
}
.ri-reason-main .reason-title {
  font-size: 14px;
  font-weight: 700;
  color: #7c3aed;
  margin: 0 0 8px;
}
.ri-reason-main .reason-desc {
  font-size: 13px;
  color: #444;
  line-height: 1.7;
}
.ri-reason-main .reason-tips {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid #d0e0f5;
}
.ri-reason-main .reason-tips li {
  font-size: 12.5px;
  color: #555;
  line-height: 1.8;
  margin-bottom: 2px;
}
/* JS 세부 분석 */
.ri-detail-item {
  background: #f8f9fb;
  border-left: 3px solid #7c3aed;
  border-radius: 0 8px 8px 0;
  padding: 12px 16px;
  margin-bottom: 8px;
}
.ri-detail-item .match {
  color: #dc2626;
  font-weight: 600;
  font-family: 'SF Mono', 'Consolas', monospace;
  font-size: 12.5px;
}
.ri-detail-item .desc {
  color: #444;
  margin-top: 6px;
  font-size: 13px;
  line-height: 1.5;
}

/* 소스 코드 카드 */
.ri-src-card {
  background: #272822 !important;
  border: 1px solid #3a3a3a;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.2);
  overflow: hidden;
  margin-bottom: 16px;
}
.ri-src-header {
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
.ri-src-header .lang-tag {
  background: #3a3a3a;
  color: #aaa;
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 12px;
}
#ace-container { background: #272822 !important; width: 100%; min-height: 200px; }
#source-ace { width: 100%; min-height: 200px; font-size: 14px; background: #272822 !important; }

/* AC 테이블 */
#errtxt-wrap { padding: 20px 24px; }
#errtxt-wrap table { width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden; font-size: 14px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); }
#errtxt-wrap table thead tr { background: #7c3aed; color: #fff; }
#errtxt-wrap table th { padding: 12px 16px; font-weight: 700; text-align: center; font-size: 13px; }
#errtxt-wrap table td { padding: 11px 16px; text-align: center; border-bottom: 1px solid #eef0f5; font-size: 13.5px; color: #333; background: #fff; }
#errtxt-wrap table tbody tr:last-child td { border-bottom: none !important; border: none !important; }
#errtxt-wrap table tbody tr:nth-child(even) td { background: #f7f9fc; }

/* 버튼 */
.ri-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.ri-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
  text-decoration: none; transition: all 0.2s; border: 1px solid #e0e5ec;
}
.ri-btn-back { background: #fff; color: #555; }
.ri-btn-back:hover { background: #f5f8fc; color: #333; text-decoration: none; }
.ri-btn-edit { background: #7c3aed; color: #fff; border-color: #7c3aed; }
.ri-btn-edit:hover { background: #1259a3; color: #fff; text-decoration: none; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ri-wrap">

  <?php
  $res = intval($solution_row['result']);
  $resultMap = array(
    0  => array('pending','⏳','채점 대기중','Pending'),
    1  => array('pending','⏳','재채점 대기중','Pending Rejudge'),
    2  => array('pending','⚙️','컴파일 중','Compiling'),
    3  => array('pending','⚙️','채점 중','Running & Judging'),
    4  => array('ac','✅','정답','Accepted'),
    5  => array('pe','📝','출력 형식 오류','Presentation Error'),
    6  => array('wa','❌','오답','Wrong Answer'),
    7  => array('tle','⏱️','시간 초과','Time Limit Exceeded'),
    8  => array('mle','💾','메모리 초과','Memory Limit Exceeded'),
    9  => array('ole','📤','출력 초과','Output Limit Exceeded'),
    10 => array('re','💥','런타임 에러','Runtime Error'),
    11 => array('ce','⚠️','컴파일 에러','Compile Error'),
  );
  $info = isset($resultMap[$res]) ? $resultMap[$res] : array('wa','❓','채점 결과','Result');
  $headerClass = $info[0];
  $headerIcon  = $info[1];
  $headerTitle = $info[2];
  $headerSub   = $info[3];
  $isAC_flag   = ($res == 4);
  $problemId   = $solution_row['problem_id'];
  $solutionId  = $solution_row['solution_id'];

  // 결과별 원인 설명
  $reasonMap = array(
    5 => array(
      '출력 결과는 맞지만, 형식이 다릅니다',
      '정답의 내용은 맞지만 공백, 줄바꿈, 대소문자 등 출력 형식이 예상과 다릅니다.',
      array(
        '불필요한 공백이나 줄바꿈이 출력에 포함되어 있는지 확인하세요.',
        'printf로 출력할 때 마지막에 \\n이 빠지거나 추가되었는지 확인하세요.',
        '대소문자가 문제에서 요구하는 것과 일치하는지 확인하세요.',
      )
    ),
    6 => array(
      '출력 결과가 예상 답과 다릅니다',
      '프로그램은 정상 실행되었지만, 출력한 값이 정답과 일치하지 않습니다.',
      array(
        '문제를 다시 읽고, 입출력 예시를 정확히 따르고 있는지 확인하세요.',
        '경계값(0, 음수, 최댓값 등)에서 정상 작동하는지 테스트하세요.',
        '변수 타입이 올바른지 확인하세요. (int 범위 초과 → long long 사용)',
        '반복문의 범위와 조건문의 논리를 다시 점검하세요.',
      )
    ),
    7 => array(
      '프로그램 실행 시간이 제한을 초과했습니다',
      '알고리즘의 시간 복잡도가 높아 주어진 시간 안에 실행이 완료되지 못했습니다.',
      array(
        '반복문이 불필요하게 많이 중첩되어 있지 않은지 확인하세요.',
        '무한루프에 빠지지 않는지 종료 조건을 점검하세요.',
        'O(N²) → O(N log N) 등 더 효율적인 알고리즘을 고려하세요.',
        'C/C++: scanf/printf가 cin/cout보다 빠릅니다.',
        'Python: sys.stdin.readline()이 input()보다 빠릅니다.',
      )
    ),
    8 => array(
      '프로그램이 사용한 메모리가 제한을 초과했습니다',
      '배열이나 자료구조가 너무 많은 메모리를 사용하고 있습니다.',
      array(
        '배열 크기가 필요 이상으로 크지 않은지 확인하세요.',
        '재귀 호출이 너무 깊어 스택 메모리를 초과하지 않는지 확인하세요.',
        '동적 할당(malloc, new) 후 해제(free, delete)를 하고 있는지 확인하세요.',
        '불필요한 전역 배열이 있는지 점검하세요.',
      )
    ),
    9 => array(
      '프로그램의 출력량이 제한을 초과했습니다',
      '출력이 너무 많아 출력 제한을 넘었습니다. 보통 무한루프에서 출력이 계속되는 경우입니다.',
      array(
        '반복문 안에서 의도하지 않은 출력이 반복되고 있지 않은지 확인하세요.',
        '디버깅용 printf/cout/print 문을 제거했는지 확인하세요.',
        '무한루프에 빠지지 않는지 종료 조건을 점검하세요.',
      )
    ),
    10 => array(
      '프로그램 실행 중 비정상 종료되었습니다',
      '코드가 실행되다가 오류로 인해 강제 종료되었습니다.',
      array(
        '배열 인덱스가 범위를 벗어나지 않는지 확인하세요. (가장 흔한 원인)',
        '0으로 나누기를 하고 있지 않은지 확인하세요.',
        '초기화하지 않은 포인터를 사용하고 있지 않은지 확인하세요.',
        '재귀 호출의 종료 조건이 올바른지 확인하세요. (스택 오버플로우)',
        'Java: Scanner로 입력받을 때 입력 형식이 일치하는지 확인하세요.',
        'Python: 리스트 인덱스, 딕셔너리 키, 형변환 등을 확인하세요.',
      )
    ),
    11 => array(
      '코드에 문법 오류가 있어 컴파일에 실패했습니다',
      '프로그램이 실행되기 전 단계에서 문법 오류로 컴파일이 되지 않았습니다.',
      array(
        '세미콜론(;), 괄호(()), 중괄호({})가 빠지거나 잘못 닫히지 않았는지 확인하세요.',
        '#include 헤더 파일이 올바른지 확인하세요.',
        '변수명, 함수명에 오타가 없는지 확인하세요.',
        '선언하지 않은 변수를 사용하고 있지 않은지 확인하세요.',
      )
    ),
  );
  ?>

  <div class="ri-card">
    <div class="ri-card-header <?php echo $headerClass?>">
      <div class="ri-card-header-icon"><?php echo $headerIcon?></div>
      <div class="ri-card-header-text">
        <h2><?php echo $headerTitle?></h2>
        <p><?php echo $headerSub?></p>
      </div>
      <span class="ri-sid-badge">채점번호 #<?php echo $id?></span>
    </div>

    <div class="ri-card-body">
      <?php if($isAC_flag): ?>
        <div id="errtxt-wrap">
          <div id="errtxt" style="display:none"><?php echo $view_reinfo?></div>
          <div id="errtxt-rendered"></div>
        </div>
      <?php else: ?>
        <?php
        $hasErrText = !empty(trim($view_reinfo));
        if($hasErrText): ?>
          <pre class="ri-errbox err-<?php echo $headerClass?>" id="errtxt"><?php echo $view_reinfo?></pre>
        <?php endif; ?>

        <div class="ri-reason">
          <h4>💡 원인 분석</h4>

          <?php if(isset($reasonMap[$res])): ?>
          <div class="ri-reason-main">
            <div class="reason-title"><?php echo $reasonMap[$res][0]?></div>
            <div class="reason-desc"><?php echo $reasonMap[$res][1]?></div>
            <ul class="reason-tips">
              <?php foreach($reasonMap[$res][2] as $tip): ?>
              <li><?php echo $tip?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php if($hasErrText): ?>
          <div id="errdetail"></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 소스 코드 -->
  <div class="ri-src-card">
    <div class="ri-src-header">
      📄 제출 코드
      <span id="src-lang-tag" class="lang-tag"></span>
    </div>
    <div id="ace-container">
      <div id="source-ace"></div>
    </div>
  </div>

  <div class="ri-actions">
    <?php if($problemId > 0): ?><a href="problem.php?id=<?php echo $problemId?>" class="ri-btn ri-btn-back">← 문제로 돌아가기</a><?php else: ?><a href="javascript:history.back()" class="ri-btn ri-btn-back">← 뒤로 가기</a><?php endif; ?>
    <?php if($problemId > 0): ?>
    <a href="submitpage.php?id=<?php echo $problemId?>&sid=<?php echo $solutionId?>" class="ri-btn ri-btn-edit">✏️ 코드 수정 후 재제출</a>
    <?php endif; ?>
  </div>

</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="<?php echo $OJ_CDN_URL?>ace/ace.js"></script>
<script src="<?php echo $OJ_CDN_URL?>template/bs3/marked.min.js"></script>
<script>
<?php if($isAC_flag): ?>
$(document).ready(function(){
  marked.use({ gfm: true, breaks: false, mangle: false, headerIds: false });
  var raw = document.getElementById('errtxt').innerHTML;
  var txt = $('<textarea/>').html(raw).val();
  document.getElementById('errtxt-rendered').innerHTML = marked.parse(txt);

  $('#errtxt-rendered th').each(function(){
    var h = $(this).text().trim();
    var map = {'Expected':'예상 출력','Yours':'제출 출력','filename':'파일명','size':'크기','result':'채점결과','memory':'메모리','time':'시간','score':'점수'};
    if(map[h]) $(this).text(map[h]);
  });
  $('#errtxt-rendered td').each(function(){
    var t = $(this).text().trim();
    var badges = {AC:['#d1fae5','#059669'],WA:['#fee2e2','#dc2626'],TLE:['#fef3c7','#d97706'],MLE:['#ede9fe','#7c3aed'],RE:['#ffe4e6','#e11d48']};
    if(badges[t]) $(this).html('<span style="background:'+badges[t][0]+';color:'+badges[t][1]+';padding:3px 10px;border-radius:5px;font-weight:700;font-size:13px">'+t+'</span>');
  });
});
<?php else: ?>
// 에러 메시지 세부 분석 (추가 정보)
var pats=[], exps=[];
function addP(p,e){ pats.push(p); exps.push(e); }
addP(/Segmentation fault/, "세그먼테이션 폴트: 배열 범위 초과, 널 포인터 참조 등이 원인입니다.");
addP(/Floating point exception/, "0으로 나누기(또는 0으로 모듈러 연산)가 발생했습니다.");
addP(/buffer overflow detected/, "버퍼 오버플로우: 배열 크기보다 더 많이 썼습니다.");
addP(/Killed/, "프로세스가 강제 종료되었습니다 (메모리 초과 가능성).");
addP(/Alarm clock/, "시간 제한을 초과하여 프로세스가 종료되었습니다.");
addP(/Bus error/, "잘못된 메모리 정렬 접근이 발생했습니다.");
addP(/Aborted/, "비정상 종료: assert 실패, 이중 free, 힙 손상 등.");
addP(/stack smashing detected/, "스택 버퍼 오버플로우가 감지되었습니다.");
addP(/ArrayIndexOutOfBoundsException/, "Java 배열 인덱스가 범위를 벗어났습니다.");
addP(/StringIndexOutOfBoundsException/, "Java 문자열 인덱스가 범위를 벗어났습니다.");
addP(/NullPointerException/, "null 객체를 참조했습니다.");
addP(/StackOverflowError/, "재귀 깊이 초과 (스택 오버플로우).");
addP(/OutOfMemoryError/, "JVM 메모리 부족.");
addP(/NumberFormatException/, "숫자로 변환할 수 없는 문자열입니다.");
addP(/NoSuchElementException/, "더 이상 읽을 입력이 없습니다.");
addP(/InputMismatchException/, "입력 타입이 일치하지 않습니다.");
addP(/IndexError/, "Python 리스트 인덱스가 범위를 벗어났습니다.");
addP(/KeyError/, "Python 딕셔너리에 해당 키가 없습니다.");
addP(/ValueError/, "잘못된 값 변환 (예: int('abc')).");
addP(/ZeroDivisionError/, "0으로 나누기가 발생했습니다.");
addP(/RecursionError/, "Python 재귀 깊이 초과.");
addP(/TypeError/, "타입이 맞지 않는 연산입니다.");
addP(/MemoryError/, "Python 메모리 부족.");

$(document).ready(function(){
  var el = document.getElementById('errtxt');
  if(!el) return;
  var errmsg = el.textContent;
  var items = [];
  for(var i=0;i<pats.length;i++){
    var ret = pats[i].exec(errmsg);
    if(ret) items.push('<div class="ri-detail-item"><div class="match">→ '+ret[0].substring(0,120).replace(/</g,'&lt;').replace(/>/g,'&gt;')+'</div><div class="desc">'+exps[i]+'</div></div>');
  }
  var box = document.getElementById('errdetail');
  if(box && items.length > 0) box.innerHTML = '<div style="font-size:12px;color:#888;margin-bottom:8px;font-weight:600">에러 메시지 세부 분석</div>' + items.join('');
});
<?php endif; ?>

// 소스 코드 로드
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
  },
  error: function(){
    document.getElementById('ace-container').innerHTML = '<div style="color:#aaa;padding:20px;font-size:13px">소스 코드를 불러올 수 없습니다.</div>';
  }
});
</script>
</body>
</html>
