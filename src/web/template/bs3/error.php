<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<?php
// ── variant 감지 ─────────────────────────────────────
$_err_variant = isset($err_variant) ? $err_variant : 'default';

// 콘텐츠로 자동 감지: "대회가 진행 중" 포함 시 → contest_active variant
if ($_err_variant === 'default' && isset($view_errors) && strpos($view_errors, '대회가 진행 중') !== false) {
    $_err_variant = 'contest_active';
}

// 기본값: 일반 오류 (부드러운 danger 톤)
$_err_title = '페이지를 표시할 수 없습니다';
$_err_subtitle = 'PAGE UNAVAILABLE';
$_err_accent_class = 'err-accent-danger';
$_err_icon_wrap_class = 'err-icon-danger';
$_err_icon_svg = '<path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>';

if ($_err_variant === 'contest_auth') {
    // 대회 비밀번호 입력 (보라)
    $_err_title = '';
    $_err_subtitle = '';
    $_err_accent_class = 'err-accent-info';
    $_err_icon_wrap_class = 'err-icon-info';
    $_err_icon_svg = '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>';
} elseif ($_err_variant === 'contest_active') {
    // 대회 진행 중 접근 차단 (앰버 — 타이틀 + 본문 카드)
    $_err_title = '잠시 후 이용할 수 있어요';
    $_err_subtitle = ''; // 서브타이틀만 제거
    $_err_accent_class = 'err-accent-warning';
    $_err_icon_wrap_class = 'err-icon-warning';
    // 시계 아이콘
    $_err_icon_svg = '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>';
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $_err_title ?: '대회 입장'?> - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;600;700;800;900&family=Inter:wght@500;600;700;800&display=swap');

/* ═══ Design Tokens ═══ */
:root {
  --err-bg: #f8f9fc;
  --err-card: rgba(255, 255, 255, 0.75);
  --err-border: rgba(226, 232, 240, 0.8);
  --err-text: #0f172a;
  --err-text-2: #334155;
  --err-text-3: #64748b;
  --err-text-mute: #94a3b8;
  --err-divider: #eef0f5;
  --err-radius: 24px;
  --err-radius-md: 14px;
  --err-radius-sm: 10px;

  /* Info (보라) */
  --err-info: #7c3aed;
  --err-info-dark: #6d28d9;
  --err-info-darker: #5b21b6;
  --err-info-light: #a78bfa;
  --err-info-lighter: #c4b5fd;
  --err-info-softer: #ede9fe;
  --err-info-soft: #f5f3ff;

  /* Danger (빨강) */
  --err-danger: #ef4444;
  --err-danger-dark: #dc2626;
  --err-danger-light: #f87171;
  --err-danger-lighter: #fca5a5;
  --err-danger-softer: #fee2e2;
  --err-danger-soft: #fef2f2;

  /* Warning (앰버 - 대회 진행 중) */
  --err-warning: #f59e0b;
  --err-warning-dark: #d97706;
  --err-warning-light: #fbbf24;
  --err-warning-lighter: #fcd34d;
  --err-warning-softer: #fef3c7;
  --err-warning-soft: #fffbeb;

  --err-ease: cubic-bezier(0.4, 0, 0.2, 1);
  --err-ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
}

* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body {
  font-family: 'Noto Sans KR', 'Inter', 'Malgun Gothic', sans-serif;
  background: var(--err-bg);
  color: var(--err-text);
  min-height: 100vh;
  position: relative;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* ═══ Mesh Gradient 배경 ═══ */
body::before {
  content: '';
  position: fixed; inset: 0;
  pointer-events: none; z-index: 0;
  background:
    radial-gradient(ellipse 90% 60% at 15% 20%, rgba(167, 139, 250, 0.12), transparent 50%),
    radial-gradient(ellipse 70% 50% at 85% 30%, rgba(244, 114, 182, 0.08), transparent 50%),
    radial-gradient(ellipse 80% 80% at 50% 100%, rgba(124, 58, 237, 0.06), transparent 55%);
  animation: meshFloat 20s ease-in-out infinite alternate;
}
@keyframes meshFloat {
  0%   { transform: translate(0, 0) scale(1); }
  50%  { transform: translate(-20px, 10px) scale(1.05); }
  100% { transform: translate(10px, -15px) scale(0.98); }
}

/* ═══ Wrap ═══ */
.err-wrap {
  position: relative; z-index: 1;
  max-width: 540px;
  margin: 80px auto 60px;
  padding: 0 20px;
  animation: fadeUp 0.6s var(--err-ease);
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ═══ Card (Glassmorphism) ═══ */
.err-card {
  position: relative;
  background: var(--err-card);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border: 1px solid var(--err-border);
  border-radius: var(--err-radius);
  box-shadow:
    0 0 0 1px rgba(255, 255, 255, 0.6) inset,
    0 2px 6px rgba(15, 23, 42, 0.03),
    0 20px 60px rgba(124, 58, 237, 0.08);
  overflow: hidden;
  transition: transform 0.35s var(--err-ease), box-shadow 0.35s var(--err-ease);
}
.err-card:hover {
  transform: translateY(-2px);
  box-shadow:
    0 0 0 1px rgba(255, 255, 255, 0.6) inset,
    0 4px 10px rgba(15, 23, 42, 0.04),
    0 28px 80px rgba(124, 58, 237, 0.12);
}

/* ═══ Accent bar (Shimmer) ═══ */
.err-accent { height: 5px; position: relative; overflow: hidden; }
.err-accent-info {
  background: linear-gradient(90deg, var(--err-info-light) 0%, var(--err-info) 50%, var(--err-info-dark) 100%);
}
.err-accent-danger {
  background: linear-gradient(90deg, var(--err-danger-light) 0%, var(--err-danger) 50%, var(--err-danger-dark) 100%);
}
.err-accent-warning {
  background: linear-gradient(90deg, var(--err-warning-light) 0%, var(--err-warning) 50%, var(--err-warning-dark) 100%);
}
.err-accent::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.5) 50%, transparent 100%);
  transform: translateX(-100%);
  animation: accentShine 3.5s ease-in-out infinite;
}
@keyframes accentShine {
  0%, 100% { transform: translateX(-100%); }
  50% { transform: translateX(100%); }
}

/* ═══ Body ═══ */
.err-body {
  padding: 56px 48px 44px;
  text-align: center;
  position: relative;
}

/* ═══ Icon + 펄싱 헤일로 ═══ */
.err-icon-wrap {
  display: inline-flex;
  flex-direction: column; align-items: center;
  margin-bottom: 26px;
  position: relative;
}
.err-icon-circle {
  position: relative;
  width: 86px; height: 86px;
  display: inline-flex; align-items: center; justify-content: center;
  border-radius: 50%;
  z-index: 1;
}
/* 내부 그라디언트 배경 */
.err-icon-circle::before {
  content: '';
  position: absolute; inset: 0;
  border-radius: 50%;
  z-index: 0;
}
.err-icon-info::before {
  background: radial-gradient(circle at 30% 30%, var(--err-info-softer) 0%, var(--err-info-soft) 80%);
  box-shadow: inset 0 -4px 12px rgba(124, 58, 237, 0.08);
}
.err-icon-danger::before {
  background: radial-gradient(circle at 30% 30%, var(--err-danger-softer) 0%, var(--err-danger-soft) 80%);
  box-shadow: inset 0 -4px 12px rgba(239, 68, 68, 0.08);
}
.err-icon-warning::before {
  background: radial-gradient(circle at 30% 30%, var(--err-warning-softer) 0%, var(--err-warning-soft) 80%);
  box-shadow: inset 0 -4px 12px rgba(245, 158, 11, 0.12);
}
/* 펄스 헤일로 */
.err-icon-circle::after {
  content: '';
  position: absolute; inset: -8px;
  border-radius: 50%;
  border: 2.5px solid;
  opacity: 0;
  animation: errPulse 2.4s cubic-bezier(0, 0, 0.2, 1) infinite;
}
.err-icon-info::after    { border-color: var(--err-info-light); }
.err-icon-danger::after  { border-color: var(--err-danger-light); }
.err-icon-warning::after { border-color: var(--err-warning-light); }
@keyframes errPulse {
  0%   { transform: scale(1);   opacity: 0.7; }
  70%  { transform: scale(1.3); opacity: 0;   }
  100% { transform: scale(1.3); opacity: 0;   }
}
/* 두 번째 링 */
.err-icon-wrap::before {
  content: '';
  position: absolute; top: 50%; left: 50%;
  width: 86px; height: 86px;
  margin-top: -13px;
  transform: translate(-50%, -50%);
  border-radius: 50%;
  border: 1.5px dashed;
  opacity: 0.25;
  animation: errRing 30s linear infinite;
}
.err-icon-wrap:has(.err-icon-info)::before    { border-color: var(--err-info-light); }
.err-icon-wrap:has(.err-icon-danger)::before  { border-color: var(--err-danger-light); }
.err-icon-wrap:has(.err-icon-warning)::before { border-color: var(--err-warning-light); }
@keyframes errRing { to { transform: translate(-50%, -50%) rotate(360deg); } }

.err-icon-svg {
  width: 38px; height: 38px;
  position: relative; z-index: 2;
  filter: drop-shadow(0 2px 6px rgba(15, 23, 42, 0.12));
}
.err-icon-info .err-icon-svg    { color: var(--err-info); }
.err-icon-danger .err-icon-svg  { color: var(--err-danger-dark); }
.err-icon-warning .err-icon-svg { color: var(--err-warning-dark); }

/* ═══ Typography ═══ */
.err-title {
  font-size: 28px; font-weight: 800;
  background: linear-gradient(135deg, var(--err-text) 0%, var(--err-text-2) 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 0 0 10px;
  letter-spacing: -0.7px;
  line-height: 1.25;
  word-break: keep-all;
}
.err-subtitle {
  font-family: 'Inter', sans-serif;
  font-size: 11.5px;
  color: var(--err-text-mute);
  margin: 0 0 24px;
  font-weight: 600;
  letter-spacing: 1.5px;
  text-transform: uppercase;
}

.err-message {
  font-size: 15px;
  color: var(--err-text-2);
  line-height: 1.75;
  word-break: keep-all;
  font-weight: 500;
}

/* ═══ $view_errors 내부 요소 ═══ */
.err-message center { display: block; }
/* center 안의 첫 <br> 만 숨기되, MSG_NOIP_WARNING의 줄바꿈 <br>는 유지되도록 표시 */
.err-message > br, .err-message > center > br:first-child { display: none; }
.err-message br { display: inline; }

/* 기본 h2 스타일 (contest_auth 에서는 대회명 제목으로 사용) */
.err-message h2 {
  font-size: 24px; font-weight: 900;
  color: var(--err-text);
  margin: 0 0 8px;
  letter-spacing: -0.5px;
  line-height: 1.3;
}

/* contest_active / default: h2는 본문 메시지 — 순수 타이포그래피 (카드 제거) */
body.err-variant-contest_active .err-message h2,
body.err-variant-default .err-message h2 {
  display: block;
  background: transparent;
  border: none;
  box-shadow: none;
  padding: 0;
  margin: 0 auto;
  max-width: 380px;
  font-size: 15px;
  font-weight: 500;
  color: var(--err-text-3);
  line-height: 1.9;
  letter-spacing: -0.1px;
  text-align: center;
  /* 한글 어절 단위 줄바꿈 */
  word-break: keep-all;
  overflow-wrap: break-word;
  line-break: strict;
}

body.err-variant-contest_active .err-message h2::before,
body.err-variant-default .err-message h2::before { content: none; }

/* 타이틀 아래 약간의 여백 (본문이 자연스럽게 이어지도록) */
body.err-variant-contest_active .err-title,
body.err-variant-default .err-title {
  margin-bottom: 18px;
}
body.err-variant-contest_active .err-message,
body.err-variant-default .err-message {
  font-size: 15px;
  color: var(--err-text-3);
}
.err-message h3 {
  font-size: 13.5px; font-weight: 600;
  color: var(--err-text-mute);
  margin: 0 0 8px;
  letter-spacing: 0.3px;
}
.err-message p {
  margin: 0 0 18px;
  font-size: 13px;
  color: var(--err-text-mute);
  font-weight: 500;
  letter-spacing: 0.2px;
}
.err-message a:not(.btn):not(.err-btn):not(.form-control):not(.err-back) {
  color: var(--err-info);
  font-weight: 700;
  text-decoration: none;
  transition: color 0.15s;
}
.err-message a:not(.btn):not(.err-btn):not(.form-control):not(.err-back):hover {
  color: var(--err-info-dark);
  text-decoration: underline;
  text-underline-offset: 3px;
}

/* ═══ 경고 배지 (빨간 danger) ═══ */
.err-message .text-danger {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 11px 20px;
  background: linear-gradient(135deg, var(--err-danger-soft), var(--err-danger-softer));
  color: var(--err-danger-dark) !important;
  border: 1px solid var(--err-danger-softer);
  border-radius: 100px;
  font-size: 13px; font-weight: 700;
  margin: 8px 0 22px !important;
  box-shadow: 0 3px 12px rgba(239, 68, 68, 0.08);
  letter-spacing: 0.1px;
}
.err-message .text-danger::before { content: '⚠'; font-size: 14px; }

/* ═══ 정보 배지 (보라 info - 대회 비밀번호 안내용) ═══ */
.err-message .err-info-note {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 13px 22px;
  background: linear-gradient(135deg, var(--err-info-soft), var(--err-info-softer));
  color: var(--err-info-dark);
  border: 1px solid var(--err-info-softer);
  border-radius: 100px;
  font-size: 13.5px; font-weight: 700;
  margin: 8px 0 24px;
  box-shadow: 0 3px 12px rgba(124, 58, 237, 0.08);
  letter-spacing: 0.1px;
}
.err-message .err-info-note::before { content: '🔑'; font-size: 15px; }

/* ═══ 비밀번호 폼 (모던 플로팅) ═══ */
.err-message .form-inline {
  display: flex !important;
  gap: 8px !important;
  align-items: stretch !important;
  padding: 6px !important;
  background: linear-gradient(135deg, #fafbff, #f5f7ff) !important;
  border: 1.5px solid var(--err-divider) !important;
  border-radius: 16px !important;
  transition: all 0.25s var(--err-ease) !important;
  max-width: 380px !important;
  margin: 4px auto !important;
  box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02) !important;
}
.err-message .form-inline:focus-within {
  border-color: var(--err-info) !important;
  box-shadow:
    0 0 0 5px rgba(124, 58, 237, 0.10),
    inset 0 1px 2px rgba(15, 23, 42, 0.02) !important;
  background: #fff !important;
  transform: translateY(-1px) !important;
}
.err-message .form-inline input[type=password],
.err-message .input-mini {
  flex: 1 !important; min-width: 0 !important;
  padding: 12px 18px !important;
  font-size: 15px !important;
  line-height: 1.4 !important;
  border: none !important;
  background: transparent !important;
  color: var(--err-text) !important;
  font-family: 'Noto Sans KR', 'Inter', sans-serif !important;
  outline: none !important;
  font-weight: 700 !important;
  letter-spacing: 1.5px !important;
  box-shadow: none !important;
  height: auto !important;
  min-height: 0 !important;
  width: auto !important;
  margin: 0 !important;
}
.err-message .form-inline input[type=password]::placeholder {
  color: var(--err-text-mute);
  font-weight: 500; letter-spacing: 0.3px;
}
.err-message .form-inline button,
.err-message .form-control {
  /* Bootstrap 3 .form-control height:34px 고정 덮어쓰기 */
  height: auto !important;
  min-height: 0 !important;
  line-height: 1.2 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  padding: 0 24px !important;
  background: linear-gradient(135deg, var(--err-info), var(--err-info-dark)) !important;
  background-image: linear-gradient(135deg, var(--err-info), var(--err-info-dark)) !important;
  color: #fff !important;
  border: none !important;
  border-radius: 12px !important;
  font-size: 14.5px !important;
  font-weight: 800 !important;
  cursor: pointer !important;
  transition: all 0.2s var(--err-ease) !important;
  font-family: 'Noto Sans KR', sans-serif !important;
  white-space: nowrap !important;
  box-shadow: 0 4px 14px rgba(124, 58, 237, 0.30) !important;
  letter-spacing: 0.3px !important;
  text-shadow: none !important;
  width: auto !important;
  margin: 0 !important;
  vertical-align: middle !important;
}
/* 입력 필드와 동일 높이로 stretch 유지 (최소값 보장) */
.err-message .form-inline button {
  align-self: stretch !important;
  min-width: 100px !important;
}
.err-message .form-inline button:hover,
.err-message .form-control:hover {
  background: linear-gradient(135deg, var(--err-info-dark), var(--err-info-darker)) !important;
  background-image: linear-gradient(135deg, var(--err-info-dark), var(--err-info-darker)) !important;
  box-shadow: 0 8px 24px rgba(124, 58, 237, 0.40) !important;
  transform: translateY(-1px) !important;
}
.err-message .form-inline button:active,
.err-message .form-control:active { transform: translateY(0) !important; }

/* 테이블 래퍼 초기화 */
.err-message table {
  width: auto !important;
  margin: 0 auto !important;
  border: none !important;
  background: transparent !important;
  border-collapse: collapse !important;
}
.err-message table td {
  border: none !important;
  padding: 0 !important;
  background: transparent !important;
}

/* ═══ Divider (가운데 dot) ═══ */
.err-divider {
  position: relative;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--err-divider) 50%, transparent);
  margin: 36px 0 26px;
}
.err-divider::after {
  content: '';
  position: absolute; left: 50%; top: 50%;
  width: 5px; height: 5px;
  background: #fff;
  border: 1.5px solid var(--err-divider);
  border-radius: 50%;
  transform: translate(-50%, -50%);
}

/* ═══ 뒤로가기 버튼 (ghost style) ═══ */
.err-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 22px;
  background: transparent;
  color: var(--err-text-mute);
  border: none;
  border-radius: 100px;
  font-size: 13.5px; font-weight: 600;
  text-decoration: none;
  transition: all 0.2s var(--err-ease);
  font-family: 'Noto Sans KR', sans-serif;
  letter-spacing: 0.2px;
  cursor: pointer;
}
.err-back span.arrow {
  display: inline-block;
  transition: transform 0.25s var(--err-ease-spring);
  font-weight: 400;
}
.err-back:hover {
  color: var(--err-text-2);
  background: rgba(15, 23, 42, 0.04);
  text-decoration: none;
}
.err-back:hover span.arrow { transform: translateX(-4px); }

/* ═══ Footer 한줄 ═══ */
.err-footnote {
  text-align: center;
  margin-top: 18px;
  font-family: 'Inter', sans-serif;
  font-size: 11.5px;
  color: var(--err-text-mute);
  font-weight: 500;
  letter-spacing: 0.5px;
}
.err-footnote strong { color: var(--err-text-3); font-weight: 700; }

/* Mobile */
@media (max-width: 560px) {
  .err-wrap { margin: 40px auto 28px; padding: 0 14px; }
  .err-body { padding: 38px 24px 30px; }
  .err-icon-circle { width: 74px; height: 74px; }
  .err-icon-svg { width: 32px; height: 32px; }
  .err-title { font-size: 22px; }
  .err-subtitle { font-size: 10.5px; }
  .err-message { font-size: 14px; }
  .err-message h2 { font-size: 20px; }
  .err-message .form-inline { max-width: 100%; }
}
</style>
</head>
<body class="err-variant-<?php echo htmlspecialchars($_err_variant, ENT_QUOTES)?>">
<?php if(isset($OJ_MEMCACHE)) include("template/$OJ_TEMPLATE/nav.php");?>
<div class="err-wrap">
  <div class="err-card">
    <div class="err-accent <?php echo $_err_accent_class?>"></div>
    <div class="err-body">
      <div class="err-icon-wrap">
        <div class="err-icon-circle <?php echo $_err_icon_wrap_class?>">
          <svg class="err-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <?php echo $_err_icon_svg?>
          </svg>
        </div>
      </div>
      <?php if(!empty($_err_title)): ?>
      <h2 class="err-title"><?php echo $_err_title?></h2>
      <?php endif; ?>
      <?php if(!empty($_err_subtitle)): ?>
      <div class="err-subtitle"><?php echo $_err_subtitle?></div>
      <?php endif; ?>
      <div class="err-message">
        <?php echo $view_errors?>
      </div>
      <div class="err-divider"></div>
      <a href="javascript:history.back()" class="err-back"><span class="arrow">←</span> 뒤로 가기</a>
    </div>
  </div>
  <div class="err-footnote">
    Powered by <strong>Codit</strong> · 충주고등학교
  </div>
</div>
<iframe src="refresh-privilege.php" height="0" width="0" style="display:none"></iframe>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
