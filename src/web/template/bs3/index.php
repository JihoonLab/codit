<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&family=Fira+Code:wght@400&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Inter','Noto Sans KR',sans-serif; background: #f0f2f5; margin: 0; }

    /* ===== HERO (컴팩트) ===== */
    .hero {
      position: relative; overflow: hidden;
      padding: 56px 20px 40px;
      text-align: center;
      background: #0f172a;
    }
    /* 코드 배경 - 양쪽에만 */
    .hero-code {
      position: absolute; inset: 0; pointer-events: none;
      display: flex; justify-content: space-between;
      padding: 0;
      opacity: 0.12;
      font-family: 'Fira Code', monospace;
      font-size: 12px; color: #fff; line-height: 1.7;
      white-space: pre;
    }
    .hero-code-l, .hero-code-r {
      width: 220px; overflow: hidden; flex-shrink: 0;
    }
    .hero-code-l { text-align: left; padding-left: 20px; }
    .hero-code-r { text-align: left; padding-right: 20px; }
    .hero-code-scroll { animation: codeUp 25s linear infinite; }
    .hero-code-r .hero-code-scroll { animation-duration: 30s; }
    @keyframes codeUp { 0%{transform:translateY(0)} 100%{transform:translateY(-50%)} }

    /* 글로우 */
    .hero::after {
      content: '';
      position: absolute;
      top: -60%; left: 50%; transform: translateX(-50%);
      width: 600px; height: 400px;
      background: radial-gradient(ellipse, rgba(124,58,237,0.15) 0%, transparent 70%);
      pointer-events: none;
    }

    .hero-inner { position: relative; z-index: 2; }
    .hero h1 {
      font-size: 40px; font-weight: 900; margin: 0 0 6px;
      letter-spacing: -1.5px; color: #fff;
      overflow: hidden;
    }
    .hero-sub {
      font-size: 15px; font-weight: 500; color: rgba(255,255,255,0.5);
      margin: 0 0 24px; letter-spacing: 0.3px;
      animation: fadeSlideUp 0.8s ease 0.3s both;
    }
    .hero-sub .oj {
      display: inline-block;
      background: linear-gradient(135deg, #a78bfa, #7c3aed, #6d28d9);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700; font-size: 16px;
      animation: glowPulse 3s ease-in-out infinite;
    }
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes glowPulse {
      0%,100% { filter: brightness(1) drop-shadow(0 0 0px transparent); }
      50% { filter: brightness(1.2) drop-shadow(0 0 8px rgba(124,58,237,0.4)); }
    }
    .hero h1 .codit-txt {
      display: inline-block;
      animation: fadeSlideUp 0.6s ease 0.1s both;
    }
    .hero-tags { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; animation: fadeSlideUp 1s ease 0.5s both; }
    .hero-tags span {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.10);
      padding: 5px 14px; border-radius: 20px;
      font-size: 12px; color: rgba(255,255,255,0.65); font-weight: 500;
    }

    

    /* ===== MAIN ===== */
    .codit-main { max-width: 1100px; margin: 0 auto; padding: 24px 20px 48px; }

    /* ===== CARD ===== */
    .codit-card {
      background: #fff; border-radius: 14px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
      overflow: hidden; border: 1px solid #e8ebf0;
    }
    .codit-card-header {
      padding: 14px 20px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid #f0f2f5;
    }
    .codit-card-header h3 {
      font-size: 15px; font-weight: 800; color: #2d2d3a; margin: 0;
    }
    .codit-card-header a {
      font-size: 12px; color: #7c3aed; text-decoration: none; font-weight: 600;
    }
    .codit-card-header a:hover { color: #6d28d9; }

    /* ===== LIST ===== */
    .home-list { list-style: none; padding: 0; margin: 0; }
    .home-list li {
      padding: 11px 20px; border-bottom: 1px solid #f5f6f8;
      display: flex; align-items: center; gap: 10px;
      font-size: 14px; transition: background 0.1s;
    }
    .home-list li:last-child { border-bottom: none; }
    .home-list li:hover { background: #fafafc; }
    .home-list li a {
      color: #3d3d4e; text-decoration: none; flex: 1;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500;
    }
    .home-list li a:hover { color: #7c3aed; }

    .hb {
      font-size: 10.5px; font-weight: 700;
      padding: 3px 9px; border-radius: 6px;
      white-space: nowrap; flex-shrink: 0;
    }
    .hb-v { background: #ede9fe; color: #7c3aed; }
    .hb-g { background: #ecfdf5; color: #059669; }
    .hb-r { background: #fff1f2; color: #e11d48; }
    .hb-b { background: #f0f9ff; color: #0284c7; }

    /* ===== LAYOUT ===== */
    .home-row {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 20px; margin-bottom: 20px;
      grid-template-rows: 380px;
    }
    .col-l { display: flex; flex-direction: column; gap: 0; }
    .col-l .codit-card { flex: 1; min-height: 0; overflow: hidden; border-radius: 0; border-bottom: none; }
    .col-l .codit-card:first-child { border-radius: 14px 14px 0 0; }
    .col-l .codit-card:last-child { border-radius: 0 0 14px 14px; border-bottom: 1px solid #e8ebf0; }
    @media (max-width: 780px) { .home-row { grid-template-columns: 1fr; } }

    /* ===== RANK ===== */
    .rk {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 20px; border-bottom: 1px solid #f5f6f8; transition: background 0.1s;
    }
    .rk:last-child { border-bottom: none; }
    .rk:hover { background: #fafafc; }
    .rk.rk-gold { background: linear-gradient(135deg,#fffbeb,#fef3c7); }
    .rk-pos { font-size: 17px; min-width: 26px; text-align: center; flex-shrink: 0; }
    .rk-num { font-size: 12px; font-weight: 800; color: #ccc; min-width: 26px; text-align: center; flex-shrink: 0; }
    .rk-info { flex: 1; min-width: 0; }
    .rk-name { font-size: 14px; font-weight: 600; color: #2d2d3a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .rk-name a { color: inherit; text-decoration: none; }
    .rk-name a:hover { color: #7c3aed; }
    .rk-uid { font-size: 11px; color: #aaa; margin-top: 1px; }
    .rk-sc { flex-shrink: 0; text-align: right; }
    .rk-sc-n { font-size: 20px; font-weight: 900; color: #7c3aed; }
    .rk-sc-l { font-size: 10px; color: #bbb; text-transform: uppercase; letter-spacing: 0.5px; }

    .nt { margin-left: auto; font-size: 12px; color: #bbb; flex-shrink: 0; }
  </style>
</head>
<body>

<?php include("template/$OJ_TEMPLATE/nav.php");?>

<!-- Hero -->
<div class="hero">
  <div class="hero-code">
    <div class="hero-code-l"><div class="hero-code-scroll">#include &lt;stdio.h&gt;
int main() {
    int n;
    scanf("%d", &n);
    for(int i=1; i<=n; i++)
        printf("%d\n", i);
    return 0;
}

def solution(n):
    result = []
    for i in range(n):
        result.append(i*i)
    return sum(result)

int gcd(int a, int b) {
    while(b) {
        int t = b;
        b = a % b;
        a = t;
    }
    return a;
}

#include &lt;stdio.h&gt;
int main() {
    int n;
    scanf("%d", &n);
    for(int i=1; i<=n; i++)
        printf("%d\n", i);
    return 0;
}

def solution(n):
    result = []
    for i in range(n):
        result.append(i*i)
    return sum(result)</div></div>
    <div class="hero-code-r"><div class="hero-code-scroll">def fibonacci(n):
    if n &lt;= 1:
        return n
    a, b = 0, 1
    for _ in range(2, n+1):
        a, b = b, a + b
    return b

void quickSort(int a[],
    int lo, int hi) {
    if(lo &lt; hi) {
        int p = partition(
            a, lo, hi);
        quickSort(a,lo,p-1);
        quickSort(a,p+1,hi);
    }
}

arr = sorted(arr)
left = 0
right = len(arr) - 1
while left &lt; right:
    s = arr[left]+arr[right]
    if s == target:
        return True

def fibonacci(n):
    if n &lt;= 1:
        return n
    a, b = 0, 1
    for _ in range(2, n+1):
        a, b = b, a + b
    return b</div></div>
  </div>
  <div class="hero-inner">
    <h1><span class="codit-txt"><?php echo $OJ_NAME?></span></h1>
    <p class="hero-sub">충주고등학교 <span class="oj">Online Judge</span></p>
    <div class="hero-tags">
      <span>💻 C/C++</span>
      <span>🐍 Python</span>
      <span>☕ Java</span>
      <span>⚡ 실시간 채점</span>
    </div>
  </div>
</div>
<!-- Main -->
<div class="codit-main">

  <?php if(isset($view_homebanner) && $view_homebanner != ""): ?>
  <div style="margin-bottom:20px"><?php echo $view_homebanner; ?></div>
  <?php endif; ?>

  <div class="home-row">
    <div class="col-l" style="height:100%">
      <div class="codit-card">
        <div class="codit-card-header">
          <h3>📚 수업 목록</h3>
          <a href="classop.php">전체 보기 →</a>
        </div>
        <ul class="home-list">
        <?php
          $class_rows = pdo_query("SELECT class_id, title FROM class WHERE defunct='N' ORDER BY class_id DESC LIMIT 4");
          if($class_rows && count($class_rows) > 0):
            foreach($class_rows as $cl):
        ?>
        <li>
          <span class="hb hb-v">수업</span>
          <a href="classop.php?action=view&id=<?php echo $cl['class_id']?>"><?php echo htmlspecialchars($cl['title'])?></a>
        </li>
        <?php endforeach; else: ?>
        <li style="color:#aaa;justify-content:center;padding:24px">등록된 수업이 없습니다.</li>
        <?php endif; ?>
        </ul>
      </div>
      <div class="codit-card">
        <div class="codit-card-header">
          <h3>📖 교안</h3>
          <a href="edulist.php">전체 보기 →</a>
        </div>
        <ul class="home-list">
        <?php
          $edu_rows = pdo_query("SELECT edu_id, title FROM edu WHERE defunct='N' ORDER BY importance DESC, time DESC LIMIT 4");
          if(is_array($edu_rows) && count($edu_rows) > 0):
            foreach($edu_rows as $ep):
        ?>
        <li>
          <span class="hb hb-g">교안</span>
          <a href="eduview.php?id=<?php echo $ep['edu_id']?>"><?php echo htmlspecialchars($ep['title'])?></a>
        </li>
        <?php endforeach; else: ?>
        <li style="color:#aaa;justify-content:center;padding:24px">등록된 교안이 없습니다.</li>
        <?php endif; ?>
        </ul>
      </div>
    </div>

    <div>
      <div class="codit-card" style="height:100%;overflow:hidden">
        <div class="codit-card-header">
          <h3>🏆 명예의 전당</h3>
          <a href="ranklist.php">전체 보기 →</a>
        </div>
        <?php
          $rank_rows = pdo_query("SELECT u.user_id, u.nick, COUNT(DISTINCT s.problem_id) as solved FROM users u JOIN solution s ON u.user_id=s.user_id WHERE s.result=4 GROUP BY u.user_id ORDER BY solved DESC LIMIT 5");
          if($rank_rows && count($rank_rows) > 0):
            $medals = ['🥇','🥈','🥉'];
            $rank = 1;
            foreach($rank_rows as $ru):
        ?>
        <div class="rk <?php echo $rank==1?'rk-gold':''?>">
          <?php if(isset($medals[$rank-1])): ?>
            <span class="rk-pos"><?php echo $medals[$rank-1]?></span>
          <?php else: ?>
            <span class="rk-num"><?php echo $rank?></span>
          <?php endif; ?>
          <div class="rk-info">
            <div class="rk-name"><a href="userinfo.php?user=<?php echo htmlspecialchars($ru['user_id'])?>"><?php echo htmlspecialchars($ru['nick'] ?: $ru['user_id'])?></a></div>
            <div class="rk-uid"><?php echo htmlspecialchars($ru['user_id'])?></div>
          </div>
          <div class="rk-sc">
            <div class="rk-sc-n"><?php echo $ru['solved']?></div>
            <div class="rk-sc-l">solved</div>
          </div>
        </div>
        <?php $rank++; endforeach; else: ?>
        <div style="padding:24px;text-align:center;color:#aaa;font-size:13px">아직 문제를 푼 사용자가 없습니다.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="codit-card">
    <div class="codit-card-header">
      <h3>📢 공지사항</h3>
    </div>
    <ul class="home-list">
    <?php if(isset($view_news_rows) && count($view_news_rows) > 0): ?>
      <?php foreach($view_news_rows as $nrow): ?>
      <li>
        <?php if($nrow['importance'] > 0): ?>
          <span class="hb hb-r">중요</span>
        <?php else: ?>
          <span class="hb hb-b">공지</span>
        <?php endif; ?>
        <a href="viewnews.php?id=<?php echo $nrow['news_id']?>"><?php echo htmlspecialchars($nrow['title'])?></a>
        <span class="nt"><?php echo substr($nrow['time'],0,10)?></span>
      </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li style="color:#aaa;justify-content:center;padding:24px">등록된 공지사항이 없습니다.</li>
    <?php endif; ?>
    </ul>
  </div>
</div>

<div class="codit-global-footer"><div class="cgf-inner"><span>&copy; <?php echo date("Y")?> Codit &middot; <a href="https://school.cbe.go.kr/chungju-h/" target="_blank">충주고등학교</a> 정보교사 박지훈</span><span class="cgf-sep">|</span><a href="privacy.php">개인정보처리방침</a><span class="cgf-sep">|</span><a href="mailto:wlgnsdl122@naver.com">wlgnsdl122@naver.com</a></div></div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
