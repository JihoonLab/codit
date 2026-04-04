<?php
require_once(dirname(__FILE__)."/../../include/memcache.php");
function get_menu_news()
{
  $result = "";
  $sql_news_menu = "SELECT `news_id`,`title` FROM `news` WHERE `menu`=1 AND `title`!='faqs.cn' ORDER BY `importance` ASC,`time` DESC LIMIT 10";
  $sql_news_menu_result = mysql_query_cache($sql_news_menu);
  if($sql_news_menu_result)
  {
    foreach($sql_news_menu_result as $row)
    {
      $result .= '<li><a href="/viewnews.php?id='.$row['news_id'].'">'.$row['title'].'</a></li>';
    }
  }
  return $result;
}

if(stripos($_SERVER['REQUEST_URI'],"template")!==false) exit();

$url = basename(strtok($_SERVER['REQUEST_URI'], '?'));
$dir = basename(getcwd());
if($dir=="discuss3" || $dir=="admin") $path_fix = "../";
else $path_fix = "";

if(isset($OJ_NEED_LOGIN) && $OJ_NEED_LOGIN && ($url!='loginpage.php' && $url!='lostpassword.php' && $url!='lostpassword2.php' && $url!='registerpage.php') && !isset($_SESSION[$OJ_NAME.'_'.'user_id']))
{
  header("location:/loginpage.php");
  exit();
}

$_SESSION[$OJ_NAME.'_'.'profile_csrf'] = rand();

if($OJ_ONLINE) {
  require_once($path_fix.'include/online.php');
  $on = new online();
}

$sql_news_menu_result_html = "";
if($OJ_MENU_NEWS) {
  if($OJ_REDIS) {
    $redis = new Redis();
    $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
    if(isset($OJ_REDISAUTH)) $redis->auth($OJ_REDISAUTH);
    $redisDataKey = $OJ_REDISQNAME.'_MENU_NEWS_CACHE';
    if($redis->exists($redisDataKey)) {
      $sql_news_menu_result_html = $redis->get($redisDataKey);
    } else {
      $sql_news_menu_result_html = get_menu_news();
      $redis->set($redisDataKey, $sql_news_menu_result_html);
      $redis->expire($redisDataKey, 300);
    }
    $redis->close();
  } else {
    $sessionDataKey = $OJ_NAME.'_'."_MENU_NEWS_CACHE";
    if(isset($_SESSION[$sessionDataKey])) {
      $sql_news_menu_result_html = $_SESSION[$sessionDataKey];
    } else {
      $sql_news_menu_result_html = get_menu_news();
      $_SESSION[$sessionDataKey] = $sql_news_menu_result_html;
    }
  }
}

/* 로그인 여부 확인 */
$is_logged_in = isset($_SESSION[$OJ_NAME.'_'.'user_id']);
$logged_user  = $is_logged_in ? $_SESSION[$OJ_NAME.'_'.'user_id'] : '';

if(!isset($_GET['spa'])) { ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');

*, *::before, *::after { box-sizing: border-box; }

body {
  font-family: 'Inter', 'Noto Sans KR', sans-serif;
  background: #f5f6f8;
  margin: 0;
  padding-top: 64px;
}

.cn {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 64px;
  background: #fff;
  border-bottom: 1px solid #e0e4ea;
  z-index: 10000;
  display: flex;
  align-items: center;
}

.cn-inner {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
  display: flex;
  align-items: center;
  height: 64px;
}

/* 로고 */
.cn-brand {
  font-family: "Inter","Noto Sans KR",sans-serif;
  font-size: 24px;
  font-weight: 900;
  color: #7c3aed !important;
  text-decoration: none !important;
  letter-spacing: -0.5px;
  margin-right: 28px;
  white-space: nowrap;
  flex-shrink: 0;
}
.cn-brand:hover { color: #6d28d9 !important; }

/* 메뉴 래퍼 */
.cn-menu-wrap {
  display: flex;
  align-items: center;
  flex: 1;
}

/* 메뉴 */
.cn-menu {
  display: flex;
  align-items: center;
  list-style: none;
  margin: 0; padding: 0;
  flex: 1;
}

.cn-menu > li > a {
  display: flex;
  align-items: center;
  gap: 5px;
  height: 56px;
  padding: 0 13px;
  font-size: 14px;
  font-weight: 500;
  color: #444 !important;
  text-decoration: none !important;
  border-bottom: 3px solid transparent;
  transition: color 0.15s, border-color 0.15s;
  white-space: nowrap;
}
.cn-menu > li > a:hover { color: #7c3aed !important; border-bottom-color: #7c3aed; }
.cn-menu > li.active > a { color: #7c3aed !important; font-weight: 700; border-bottom-color: #7c3aed; }

/* 메뉴 아이콘 */
.cn-icon {
  width: 18px; height: 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.cn-icon svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.nav-icon-default { display: inline; }
.nav-icon-active  { display: none; }
.active .nav-icon-default { display: none; }
.active .nav-icon-active  { display: inline; }
/* 오른쪽 버튼 */
.cn-right {
  flex-shrink: 0;
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* 로그인 단일 버튼 */
.cn-login-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  color: #fff !important;
  background: #7c3aed;
  text-decoration: none !important;
  transition: background 0.15s;
  white-space: nowrap;
  border: none;
  cursor: pointer;
}
.cn-login-btn:hover { background: #6d28d9; color: #fff !important; }

/* 로그인 후 유저 드롭다운 */
.cn-user { position: relative; }
.cn-user-toggle {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  color: #7c3aed !important;
  background: #f0f5ff;
  text-decoration: none !important;
  cursor: pointer;
  transition: background 0.15s;
  border: 1px solid #cce0ff;
  white-space: nowrap;
}
.cn-user-toggle:hover { background: #deeaff; color: #7c3aed !important; }
.cn-dropdown {
  display: none;
  position: absolute;
  right: 0; top: calc(100% + 10px);
  background: #fff;
  border: 1px solid #e8ecf2;
  border-radius: 14px;
  box-shadow: 0 12px 32px rgba(0,0,0,0.13);
  min-width: 210px;
  max-height: calc(100vh - 80px);
  overflow-y: auto;
  z-index: 9999;
}

.cn-user.open .cn-dropdown { display: block; animation: dropFade 0.15s ease; }
@keyframes dropFade { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
.cn-drop-header {
  padding: 16px 18px 12px;
  border-bottom: 1px solid #f0f3f7;
  display: flex; align-items: center; gap: 12px;
}
.cn-drop-avatar {
  width: 38px; height: 38px; border-radius: 50%;
  background: linear-gradient(135deg, #7c3aed, #6d28d9);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; font-weight: 900; color: #fff; flex-shrink: 0;
}
.cn-drop-name { font-size: 14px; font-weight: 700; color: #1a1a1a; }
.cn-drop-uid  { font-size: 12px; color: #aaa; margin-top: 1px; }
.cn-dropdown a {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 18px;
  font-size: 14px;
  color: #444 !important;
  text-decoration: none !important;
  transition: background 0.1s;
}
.cn-dropdown a:hover { background: #f5f8ff; color: #7c3aed !important; }
.cn-dropdown a .di { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
.cn-dropdown .sep { height: 1px; background: #f0f3f7; margin: 4px 0; }
.cn-dropdown .logout-link { color: #e74c3c !important; }
.cn-dropdown .logout-link:hover { background: #fff5f5 !important; color: #c0392b !important; }
.cn-dropdown .admin-link { color: #7c3aed !important; }
.cn-dropdown .admin-link:hover { background: #f5f0ff !important; }

/* 햄버거 */
.cn-ham {
  display: none;
  background: none; border: none;
  cursor: pointer; padding: 8px;
  margin-left: auto;
}
.cn-ham span {
  display: block; width: 22px; height: 2px;
  background: #333; margin: 4px 0; border-radius: 2px;
}

@media (max-width: 800px) {
  .cn-ham { display: block; }
  .cn-menu-wrap {
    display: none;
    position: fixed;
    top: 56px; left: 0; right: 0;
    background: #fff;
    border-top: 1px solid #e0e4ea;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    padding: 8px 0 16px;
    z-index: 999;
    flex-direction: column;
    align-items: stretch;
  }
  .cn-menu-wrap.open { display: flex; }
  .cn-menu { flex-direction: column; }
  .cn-menu > li > a { height: auto; padding: 12px 24px; border-bottom: none; border-left: 3px solid transparent; }
  .cn-menu > li.active > a { border-left-color: #7c3aed; border-bottom: none; }
  .cn-right { padding: 8px 24px; }
  .cn-login-btn { width: 100%; justify-content: center; }
}
/* 드롭다운 메뉴 */
.cn-has-drop { position: relative; }
.cn-has-drop > a { cursor: pointer; }
.cn-subdrop {
  display: none;
  list-style: none !important;
  padding-left: 0 !important;
  margin: 0;
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  background: #fff;
  border: 1px solid #e0e4ea;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.10);
  min-width: 150px;
  overflow: hidden;
  z-index: 9999;
  list-style: none;
  margin: 0; padding: 4px 0;
}
.cn-has-drop.open .cn-subdrop { display: block; }
.cn-subdrop { list-style: none !important; }
.cn-subdrop li { list-style: none !important; }
.cn-subdrop li a {
  display: block;
  padding: 11px 18px;
  font-size: 14px;
  color: #333 !important;
  text-decoration: none !important;
  transition: background 0.1s;
  white-space: nowrap;
}
.cn-subdrop li a:hover { background: #f0f5ff; color: #7c3aed !important; }
@media (max-width: 800px) {
  .cn-subdrop { position: static; box-shadow: none; border: none; border-radius: 0; background: #f8faff; padding: 0; }
  .cn-has-drop.open .cn-subdrop { display: block; }
  .cn-subdrop li a { padding: 10px 36px; font-size: 13px; }
}

</style>

<nav class="cn">
  <div class="cn-inner">

    <a class="cn-brand" href="<?php echo $OJ_HOME?>"><?php echo $OJ_NAME?></a>

    <button class="cn-ham" onclick="document.querySelector('.cn-menu-wrap').classList.toggle('open')">
      <span></span><span></span><span></span>
    </button>

    <div class="cn-menu-wrap">
      <ul class="cn-menu">
        <?php if(!isset($OJ_ON_SITE_CONTEST_ID)): ?>

        <li <?php if($url=="problemset.php") echo 'class="active"'; ?>>
          <a href="/problemset.php">
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></span><span class="cn-icon nav-icon-active" style="font-size:16px">📋</span>
            문제
          </a>
        </li>

        <li class="cn-has-drop <?php if($url=='contest.php'||$url=='classop.php') echo 'active'; ?>">
          <a href="#" onclick="toggleDrop(event,'drop-class')">
            <?php if($url=='contest.php'): ?>
            <span class="cn-icon" style="font-size:16px">🎮</span> 대회 ▾
            <?php elseif($url=='classop.php'): ?>
            <span class="cn-icon" style="font-size:16px">📚</span> 수업 목록 ▾
            <?php else: ?>
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> 수업/대회 ▾
            <?php endif; ?>
          </a>
          <ul class="cn-subdrop" id="drop-class">
            <li><a href="/classop.php">📚 수업 목록</a></li>
            <li><a href="/contest.php<?php if(isset($_SESSION[$OJ_NAME."_user_id"])) echo "?my"?>">🎮 대회</a></li>
            <?php if(isset($_SESSION[$OJ_NAME.'_administrator'])): ?>
            <li><a href="/admin/class_report.php">📊 포트폴리오</a></li>
            <?php endif; ?>
          </ul>
        </li>

        <li <?php if($url=="edulist.php") echo 'class="active"'; ?>>
          <a href="/edulist.php">
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span><span class="cn-icon nav-icon-active" style="font-size:16px">📖</span>
            교안
          </a>
        </li>

        <li <?php if($url=="ranklist.php") echo 'class="active"'; ?>>
          <a href="/ranklist.php">
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><path d="M6 9H3V4h3"/><path d="M18 9h3V4h-3"/><path d="M6 4h12v7a6 6 0 0 1-12 0V4z"/><path d="M12 17v4"/><path d="M8 21h8"/></svg></span><span class="cn-icon nav-icon-active" style="font-size:16px">🏆</span>
            랭킹
          </a>
        </li>

        <li <?php if($url=="status.php") echo 'class="active"'; ?>>
          <a href="/status.php">
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg></span><span class="cn-icon nav-icon-active" style="font-size:16px">🚀</span>
            제출현황
          </a>
        </li>

        <li <?php if($url=="faqs.php") echo 'class="active"'; ?>>
          <a href="/faqs.php">
            <span class="cn-icon nav-icon-default"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span><span class="cn-icon nav-icon-active" style="font-size:16px">💡</span>
            도움말
          </a>
        </li>

        <?php else: ?>
        <li <?php if($url=="contest.php") echo 'class="active"'; ?>>
          <a href="/contest.php">
            <span class="cn-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span>
            대회
          </a>
        </li>
        <?php endif; ?>

      </ul>

      <!-- 오른쪽: 로그인 상태에 따라 분기 -->
      <div class="cn-right">
        <?php if($is_logged_in): ?>
        <!-- 로그인 후: 유저명 + 드롭다운 (클릭 토글) -->
        <div class="cn-user" id="cn-user-menu">
          <?php
            $drop_nick = '';
            $drop_res = pdo_query("SELECT nick FROM users WHERE user_id=? LIMIT 1", $logged_user);
            if($drop_res && count($drop_res)>0) $drop_nick = $drop_res[0]['nick'] ?: $logged_user;
            else $drop_nick = $logged_user;
            $drop_initial = mb_substr($drop_nick, 0, 1);
          ?>
          <a href="#" class="cn-user-toggle" id="cn-user-toggle" onclick="toggleUserMenu(event)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <?php echo htmlspecialchars($drop_nick); ?> ▾
          </a>
          <div class="cn-dropdown" id="cn-dropdown">
            <!-- 헤더: 아바타 + 이름 -->
            <div class="cn-drop-header">
              <div class="cn-drop-avatar"><?php echo htmlspecialchars($drop_initial)?></div>
              <div>
                <div class="cn-drop-name"><?php echo htmlspecialchars($drop_nick)?></div>
                <div class="cn-drop-uid">@<?php echo htmlspecialchars($logged_user)?></div>
              </div>
            </div>
            <!-- 메뉴 항목 -->
            <a href="/userinfo.php?user=<?php echo urlencode($logged_user)?>">
              <span class="di">👤</span> 내 프로필
            </a>
            <a href="/modifypage.php">
              <span class="di">✏️</span> 정보 수정
            </a>
            <div class="sep"></div>
            <a href="/status.php?user_id=<?php echo urlencode($logged_user)?>">
              <span class="di">📋</span> 내 제출 기록
            </a>
            <a href="/contest.php?my">
              <span class="di">🎮</span> 내 대회
            </a>
            <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])): ?>
            <div class="sep"></div>
            <a href="/admin/" class="admin-link">
              <span class="di">⚙️</span> 관리자 패널
            </a>
            <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])):
              $pending_cnt = 0;
              $pr = pdo_query("SELECT COUNT(*) FROM users WHERE defunct='Y'");
              if($pr && count($pr)>0) $pending_cnt = intval($pr[0][0]);
            ?>
            <a href="/admin/user_approve.php" class="admin-link">
              <span class="di">✅</span> 가입 승인<?php if($pending_cnt > 0): ?> <span style="background:#ef4444;color:#fff;font-size:11px;font-weight:700;padding:1px 7px;border-radius:10px;margin-left:4px;"><?php echo $pending_cnt?></span><?php endif; ?>
            </a>
            <?php endif; ?>
            <?php endif; ?>
            <div class="sep"></div>
            <a href="/logout.php" class="logout-link">
              <span class="di">🚪</span> 로그아웃
            </a>
          </div>
        </div>
        <?php else: ?>
        <!-- 비로그인: 단일 로그인 버튼 -->
        <a href="/loginpage.php" class="cn-login-btn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          로그인
        </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</nav>
<script>

function toggleDrop(e, id) {
  e.preventDefault();
  var el = document.getElementById(id);
  if (!el) return;
  var li = el.closest('.cn-has-drop');
  var isOpen = li.classList.contains('open');
  document.querySelectorAll('.cn-has-drop.open').forEach(function(x){ x.classList.remove('open'); });
  if (!isOpen) li.classList.add('open');
}
document.addEventListener('click', function(e) {
  if (!e.target.closest('.cn-has-drop')) {
    document.querySelectorAll('.cn-has-drop.open').forEach(function(x){ x.classList.remove('open'); });
  }
});

function toggleUserMenu(e) {
  e.preventDefault();
  document.getElementById('cn-user-menu').classList.toggle('open');
}
// 다른 곳 클릭 시 닫기
document.addEventListener('click', function(e) {
  var menu = document.getElementById('cn-user-menu');
  if (menu && !menu.contains(e.target)) {
    menu.classList.remove('open');
  }
});
</script>
<?php } ?>
