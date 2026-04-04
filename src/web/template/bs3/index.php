<?php header("Cache-Control: no-cache, no-store, must-revalidate"); header("Pragma: no-cache"); header("Expires: 0"); ?>
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
    body { font-family: 'Inter','Noto Sans KR',sans-serif; background: #f0f2f5; }

    /* ===== HERO ===== */
    .hero {
      position: relative; overflow: hidden;
      padding: 52px 20px 36px;
      text-align: center;
      background: #0f172a;
    }
    .hero-code {
      position: absolute; inset: 0; pointer-events: none;
      display: flex; justify-content: space-between;
      opacity: 0.10;
      font-family: 'Fira Code', monospace;
      font-size: 12px; color: #fff; line-height: 1.7;
      white-space: pre;
    }
    .hero-code-l, .hero-code-r { width: 220px; overflow: hidden; flex-shrink: 0; }
    .hero-code-l { text-align: left; padding-left: 20px; }
    .hero-code-r { text-align: left; padding-right: 20px; }
    .hero-code-scroll { animation: codeUp 25s linear infinite; }
    .hero-code-r .hero-code-scroll { animation-duration: 30s; }
    @keyframes codeUp { 0%{transform:translateY(0)} 100%{transform:translateY(-50%)} }
    .hero::after {
      content: '';
      position: absolute;
      top: -60%; left: 50%; transform: translateX(-50%);
      width: 600px; height: 400px;
      background: radial-gradient(ellipse, rgba(124,58,237,0.15) 0%, transparent 70%);
      pointer-events: none;
    }
    .hero-inner { position: relative; z-index: 2; }
    .hero h1 { font-family: 'Inter','Noto Sans KR',sans-serif; font-size: 40px; font-weight: 900; margin: 0 0 14px; letter-spacing: -1.5px; overflow: hidden; }
    .hero h1 .codit-txt { display: inline-block; animation: fadeSlideUp 0.6s ease 0.1s both; background: linear-gradient(135deg, #c4b5fd, #a78bfa, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-sub { font-size: 15px; font-weight: 500; color: rgba(255,255,255,0.5); margin: 0 0 24px; letter-spacing: 0.5px; line-height: 1.4; animation: fadeSlideUp 0.8s ease 0.3s both; }
    .hero-sub .oj { display: inline; background: linear-gradient(135deg, #a78bfa, #7c3aed, #6d28d9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; font-size: 16px; animation: glowPulse 3s ease-in-out infinite; }
    @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes glowPulse { 0%,100% { filter: brightness(1); } 50% { filter: brightness(1.2) drop-shadow(0 0 8px rgba(124,58,237,0.4)); } }
    .hero-tags { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; animation: fadeSlideUp 1s ease 0.5s both; }
    .hero-tags span { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.10); padding: 5px 14px; border-radius: 20px; font-size: 12px; color: rgba(255,255,255,0.65); font-weight: 500; }

    /* ===== MAIN ===== */
    .codit-main { max-width: 1300px; margin: 0 auto; padding: 24px 20px 48px; }

    /* ===== CARD ===== */
    .codit-card {
      background: #fff; border-radius: 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
      overflow: hidden; border: 1px solid #e5e7eb;
      transition: box-shadow 0.25s, transform 0.25s;
    }
    .codit-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.06); transform: translateY(-2px); }
    .codit-card-header {
      padding: 0; display: flex; align-items: center; justify-content: space-between;
      border-bottom: none; position: relative; overflow: hidden;
    }
    .codit-card-header-inner {
      display: flex; align-items: center; justify-content: space-between;
      width: 100%; padding: 14px 22px; position: relative; z-index: 1;
    }
    .codit-card-header h3 {
      font-size: 15px; font-weight: 900; color: #fff; margin: 0;
      letter-spacing: -0.3px; display: flex; align-items: center; gap: 8px;
      text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .codit-card-header h3 .sec-icon {
      font-size: 18px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.15));
    }
    .codit-card-header a {
      font-size: 11px; color: rgba(255,255,255,0.85); text-decoration: none;
      font-weight: 700; transition: all 0.2s; display: flex; align-items: center; gap: 3px;
      background: rgba(255,255,255,0.18); padding: 4px 12px; border-radius: 20px;
      backdrop-filter: blur(4px);
    }
    .codit-card-header a:hover { background: rgba(255,255,255,0.3); color: #fff; }
    .codit-card-header a .arr { transition: transform 0.2s; display: inline-block; }
    .codit-card-header a:hover .arr { transform: translateX(3px); }

    /* Card header gradients per type */
    .ch-class { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #c084fc 100%); }
    .ch-edu { background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%); }
    .ch-hof { background: linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%); }
    .ch-news { background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%); }
    .ch-class::before, .ch-edu::before, .ch-hof::before, .ch-news::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(circle at 85% 50%, rgba(255,255,255,0.12) 0%, transparent 50%);
      pointer-events: none;
    }

    /* ===== LIST ===== */
    .home-list { list-style: none; padding: 0; margin: 0; }
    .home-list li {
      padding: 14px 22px; border-bottom: 1px solid #f5f6f8;
      display: flex; align-items: center; gap: 12px;
      font-size: 14px; transition: all 0.2s ease;
      position: relative;
    }
    .home-list li::before {
      content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
      background: transparent; transition: background 0.2s; border-radius: 0 3px 3px 0;
    }
    .home-list li:last-child { border-bottom: none; }
    .home-list li:hover { background: linear-gradient(90deg, #faf5ff, #fff); padding-left: 28px; }
    .home-list li:hover::before { background: linear-gradient(180deg, #7c3aed, #a855f7); }
    .home-list li a {
      color: #1f2937; text-decoration: none; flex: 1;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
      font-weight: 600; font-size: 13.5px; transition: color 0.15s; letter-spacing: -0.2px;
    }
    .home-list li a:hover { color: #7c3aed; }

    .hb {
      font-size: 10px; font-weight: 800;
      padding: 4px 10px; border-radius: 6px;
      white-space: nowrap; flex-shrink: 0; letter-spacing: 0.3px;
      text-transform: uppercase; border: 1px solid transparent;
    }
    .hb-v { background: linear-gradient(135deg, #f3f0ff, #ede9fe); color: #7c3aed; border-color: rgba(124,58,237,0.15); }
    .hb-g { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #059669; border-color: rgba(5,150,105,0.15); }
    .hb-r { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #dc2626; border-color: rgba(220,38,38,0.15); animation: pulseImportant 2s ease-in-out infinite; }
    .hb-b { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; border-color: rgba(37,99,235,0.15); }
    @keyframes pulseImportant { 0%,100% { opacity: 1; } 50% { opacity: 0.7; } }

    /* ===== GRID ===== */
    .grid-2 {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 16px; margin-bottom: 16px;
    }
    @media (max-width: 780px) { .grid-2 { grid-template-columns: 1fr; } }
    @media (max-width: 600px) {
      .hero { padding: 36px 16px 28px; }
      .hero h1 { font-size: 32px; }
      .hero-sub { font-size: 13px; }
      .hero-tags { gap: 6px; }
      .hero-tags span { padding: 4px 10px; font-size: 11px; }
      .hero-code-l, .hero-code-r { width: 100px; font-size: 9px; }
      .codit-main { padding: 16px 12px 40px; }
      .home-list li { padding: 12px 14px; gap: 8px; }
      .home-list li a { font-size: 13px; }
      .hb { font-size: 9px; padding: 3px 8px; }
      .nt { font-size: 10px; padding: 2px 8px; }
      .hof-item { padding: 11px 14px; gap: 10px; }
      .hof-medal { font-size: 18px; min-width: 26px; }
      .hof-name { font-size: 13px; }
      .hof-score-num { font-size: 16px; }
      .codit-card-header-inner { padding: 12px 14px; }
      .codit-card-header h3 { font-size: 14px; }
    }

    /* Left stacked cards */
    .col-l { display: flex; flex-direction: column; gap: 0; }
    .col-l .card-top { border-radius: 16px 16px 0 0; border-bottom: none; }
    .col-l .card-top:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06); transform: none; }
    .col-l .card-bottom { border-radius: 0 0 16px 16px; border-top: 1px solid #e5e7eb; }
    .col-l .card-bottom:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06); transform: none; }
    .hof-card { display: flex; flex-direction: column; }
    .hof-card .hof-bg { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .hof-card .rk-list { }

    /* ===== RANK - Hall of Fame ===== */
    .hof-list { padding: 0; }
    .hof-item {
      display: flex; align-items: center; gap: 14px;
      padding: 13px 22px; border-bottom: 1px solid #f5f6f8;
      transition: all 0.15s; text-decoration: none; color: inherit;
    }
    .hof-item:last-child { border-bottom: none; }
    .hof-item:hover { background: #faf5ff; }
    .hof-medal {
      font-size: 22px; min-width: 32px; text-align: center; flex-shrink: 0;
    }
    .hof-num {
      font-size: 13px; font-weight: 800; color: #7c3aed;
      min-width: 32px; height: 32px; line-height: 32px;
      text-align: center; border-radius: 50%;
      background: #f0e6ff; flex-shrink: 0;
    }
    .hof-info { flex: 1; min-width: 0; }
    .hof-name {
      font-size: 14px; font-weight: 700; color: #1f2937;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .hof-item:hover .hof-name { color: #7c3aed; }
    .hof-uid { font-size: 11px; color: #b0b5bf; font-weight: 500; margin-left: 4px; }
    .hof-score { flex-shrink: 0; text-align: right; display: flex; align-items: baseline; gap: 3px; }
    .hof-score-num { font-size: 18px; font-weight: 900; color: #7c3aed; line-height: 1; }
    .hof-score-label { font-size: 10px; color: #b0b5bf; font-weight: 700; }
    /* top 3 highlight */
    .hof-item.hof-gold { background: linear-gradient(90deg, rgba(254,243,199,0.4), transparent); }
    .hof-item.hof-gold .hof-score-num { color: #b45309; }
    .hof-item.hof-gold .hof-name { font-size: 15px; font-weight: 800; }
    .hof-item.hof-silver { background: linear-gradient(90deg, rgba(243,244,246,0.5), transparent); }
    .hof-item.hof-silver .hof-score-num { color: #6b7280; }
    .hof-item.hof-bronze { background: linear-gradient(90deg, rgba(254,243,199,0.25), transparent); }
    .hof-item.hof-bronze .hof-score-num { color: #92400e; }

    .nt {
      margin-left: auto; font-size: 11px; flex-shrink: 0; font-weight: 600;
      color: #9ca3af; background: #f9fafb; padding: 3px 10px; border-radius: 12px;
      border: 1px solid #f3f4f6; letter-spacing: -0.2px;
    }

    .empty-msg {
      padding: 40px 32px; text-align: center; color: #b0b5bf; font-size: 13px;
      font-weight: 500; letter-spacing: -0.2px;
    }
    .empty-msg::before {
      content: '📭'; display: block; font-size: 28px; margin-bottom: 8px; opacity: 0.6;
    }
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
    <p class="hero-sub">Chungju Highschool <span class="oj">Online Judge</span></p>
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
  <div style="margin-bottom:16px"><?php echo $view_homebanner; ?></div>
  <?php endif; ?>

  <!-- Row 1: 수업목록+교안 (왼) | 명예의전당 (오) -->
  <div class="grid-2">
    <div class="col-l">
      <div class="codit-card card-top">
        <div class="codit-card-header ch-class">
          <div class="codit-card-header-inner">
            <h3><span class="sec-icon">📚</span> 수업 목록</h3>
            <a href="classop.php">전체 보기 <span class="arr">→</span></a>
          </div>
        </div>
        <ul class="home-list">
        <?php
          $__my_school = isset($_SESSION[$OJ_NAME.'_user_id']) ? (pdo_query("SELECT school FROM users WHERE user_id=?", $_SESSION[$OJ_NAME.'_user_id'])[0]['school'] ?? '') : '';
          if($__my_school !== '') {
            $class_rows = pdo_query("SELECT class_id, title FROM class WHERE defunct='N' AND (tag=? OR tag='') ORDER BY class_id DESC LIMIT 4", $__my_school);
          } else {
            $class_rows = pdo_query("SELECT class_id, title FROM class WHERE defunct='N' AND tag='' ORDER BY class_id DESC LIMIT 4");
          }
          if($class_rows && count($class_rows) > 0):
            foreach($class_rows as $cl):
        ?>
        <li>
          <span class="hb hb-v">수업</span>
          <a href="classop.php?action=view&id=<?php echo $cl['class_id']?>"><?php echo htmlspecialchars($cl['title'])?></a>
        </li>
        <?php endforeach; else: ?>
        <li class="empty-msg">등록된 수업이 없습니다.</li>
        <?php endif; ?>
        </ul>
      </div>
      <div class="codit-card card-bottom">
        <div class="codit-card-header ch-edu">
          <div class="codit-card-header-inner">
            <h3><span class="sec-icon">📖</span> 교안</h3>
            <a href="edulist.php">전체 보기 <span class="arr">→</span></a>
          </div>
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
        <li class="empty-msg">등록된 교안이 없습니다.</li>
        <?php endif; ?>
        </ul>
      </div>
    </div>

    <div class="codit-card hof-card">
      <div class="codit-card-header ch-hof">
        <div class="codit-card-header-inner">
          <h3><span class="sec-icon">🏆</span> 명예의 전당</h3>
          <a href="ranklist.php">전체 보기 <span class="arr">→</span></a>
        </div>
      </div>
      <?php
        $rank_rows = pdo_query("SELECT u.user_id, u.nick, COUNT(DISTINCT s.problem_id) as solved FROM users u JOIN solution s ON u.user_id=s.user_id WHERE s.result=4 AND u.defunct='N' AND u.user_id NOT IN ('admin') GROUP BY u.user_id ORDER BY solved DESC LIMIT 8");
        if($rank_rows && count($rank_rows) > 0):
          $medals = ['🥇','🥈','🥉'];
          $hof_classes = ['hof-gold','hof-silver','hof-bronze'];
      ?>
      <div class="hof-list">
        <?php foreach($rank_rows as $ri => $ru):
          $is_top3 = $ri < 3;
          $cls = $is_top3 ? $hof_classes[$ri] : '';
        ?>
        <a class="hof-item <?php echo $cls?>" href="userinfo.php?user=<?php echo htmlspecialchars($ru['user_id'])?>">
          <?php if($is_top3): ?>
            <span class="hof-medal"><?php echo $medals[$ri]?></span>
          <?php else: ?>
            <span class="hof-num"><?php echo $ri+1?></span>
          <?php endif; ?>
          <div class="hof-info">
            <div class="hof-name"><?php echo htmlspecialchars($ru['nick'] ?: $ru['user_id'])?><?php if($is_top3 && $ru['nick']): ?> <span class="hof-uid"><?php echo htmlspecialchars($ru['user_id'])?></span><?php endif; ?></div>
          </div>
          <div class="hof-score">
            <span class="hof-score-num"><?php echo $ru['solved']?></span>
            <span class="hof-score-label">문제</span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="empty-msg">아직 문제를 푼 사용자가 없습니다.</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Row 2: 공지사항 (풀폭) -->
  <div class="codit-card">
    <div class="codit-card-header ch-news">
      <div class="codit-card-header-inner">
        <h3><span class="sec-icon">📢</span> 공지사항</h3>
      </div>
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
      <li class="empty-msg">등록된 공지사항이 없습니다.</li>
    <?php endif; ?>
    </ul>
  </div>

</div>

<div class="codit-global-footer"><div class="cgf-inner"><span>&copy; <?php echo date("Y")?> Codit &middot; <a href="https://school.cbe.go.kr/chungju-h/" target="_blank">충주고등학교</a> 정보교사 박지훈</span><span class="cgf-sep">|</span><a href="privacy.php">개인정보처리방침</a><span class="cgf-sep">|</span><a href="mailto:wlgnsdl122@naver.com">wlgnsdl122@naver.com</a></div></div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>window.addEventListener("pageshow",function(e){if(e.persisted)location.reload();});</script>
</body>
</html>
