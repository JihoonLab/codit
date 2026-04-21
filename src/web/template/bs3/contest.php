<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($view_title)?> - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;600;700;800;900&display=swap');
    * { box-sizing: border-box; }
    body {
      font-family: 'Noto Sans KR', sans-serif;
      background: #f6f7fb;
      margin: 0;
      color: #1a1a2e;
    }

    .ct-wrap { max-width: 1040px; margin: 32px auto; padding: 0 20px 60px; }

    /* ═════ HERO CARD — 멀티레이어 메쉬 그라디언트 + 플로팅 오브 ═════ */
    .ct-hero {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      background:
        radial-gradient(ellipse 80% 60% at 85% 0%, rgba(236, 72, 153, 0.45) 0%, transparent 50%),
        radial-gradient(ellipse 70% 80% at 0% 100%, rgba(6, 182, 212, 0.35) 0%, transparent 50%),
        radial-gradient(ellipse 100% 100% at 50% 50%, rgba(167, 139, 250, 0.25) 0%, transparent 70%),
        linear-gradient(135deg, #7c3aed 0%, #6d28d9 50%, #5b21b6 100%);
      color: #fff;
      box-shadow:
        0 1px 3px rgba(16,24,40,0.04),
        0 20px 60px rgba(124,58,237,0.25),
        0 0 0 1px rgba(255,255,255,0.08) inset;
      margin-bottom: 20px;
      animation: heroFloat 22s ease-in-out infinite alternate;
    }
    @keyframes heroFloat {
      0%   { background-position: 0% 0%, 0% 0%, 0% 0%, 0% 0%; }
      50%  { background-position: 2% 1%, -2% -1%, 0% 2%, 0% 0%; }
      100% { background-position: -1% 2%, 1% -2%, 1% 0%, 0% 0%; }
    }

    /* 플로팅 오브 (움직이는 빛 덩어리) */
    .ct-hero::before {
      content: '';
      position: absolute;
      top: -100px; right: -80px;
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(255,255,255,0.14) 0%, transparent 65%);
      border-radius: 50%;
      pointer-events: none;
      animation: orbFloat1 14s ease-in-out infinite;
      filter: blur(2px);
    }
    .ct-hero::after {
      content: '';
      position: absolute;
      bottom: -120px; left: -60px;
      width: 260px; height: 260px;
      background: radial-gradient(circle, rgba(236, 72, 153, 0.18) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
      animation: orbFloat2 18s ease-in-out infinite;
      filter: blur(4px);
    }
    @keyframes orbFloat1 {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50%      { transform: translate(-30px, 20px) scale(1.15); }
    }
    @keyframes orbFloat2 {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50%      { transform: translate(40px, -30px) scale(1.1); }
    }
    /* 세 번째 부유 오브 */
    .ct-hero-body::before {
      content: '';
      position: absolute;
      top: 20%; right: 30%;
      width: 180px; height: 180px;
      background: radial-gradient(circle, rgba(139, 92, 246, 0.25) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
      animation: orbFloat3 20s ease-in-out infinite;
      filter: blur(3px);
      z-index: 0;
    }
    @keyframes orbFloat3 {
      0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
      33%      { transform: translate(30px, 40px) scale(1.2); opacity: 0.85; }
      66%      { transform: translate(-40px, 20px) scale(0.9); opacity: 0.5; }
    }
    .ct-hero-body {
      position: relative;
      z-index: 1;
      padding: 36px 40px 30px;
    }
    .ct-hero h1 {
      font-size: 32px; font-weight: 900;
      margin: 0 0 14px;
      line-height: 1.2;
      letter-spacing: -0.8px;
      text-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
      background: linear-gradient(180deg, #fff 0%, rgba(255,255,255,0.88) 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .ct-hero-badges { display: flex; gap: 8px; flex-wrap: wrap; }
    .ct-badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 13px; border-radius: 100px;
      font-size: 12px; font-weight: 700;
      backdrop-filter: blur(8px);
    }
    .badge-running  { background: rgba(255,255,255,0.22); color: #fff; border: 1px solid rgba(255,255,255,0.35); }
    .badge-ended    { background: rgba(0,0,0,0.22); color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.18); }
    .badge-upcoming { background: rgba(34,197,94,0.30); color: #bbf7d0; border: 1px solid rgba(34,197,94,0.45); }
    .badge-public   { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.90); border: 1px solid rgba(255,255,255,0.20); }
    .badge-private  { background: rgba(239,68,68,0.28); color: #fecaca; border: 1px solid rgba(239,68,68,0.42); }
    .badge-dot {
      display: inline-block; width: 7px; height: 7px;
      border-radius: 50%; background: currentColor;
      box-shadow: 0 0 8px currentColor;
    }
    .badge-running .badge-dot { background: #ff6b6b; box-shadow: 0 0 8px #ff6b6b; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.35; } }

    /* ═════ 카운트다운 카드 (premium glassmorphism) ═════ */
    .ct-countdown {
      display: grid;
      grid-template-columns: 1.7fr 1fr;
      gap: 20px;
      align-items: stretch;
      padding: 28px 34px;
      margin: 22px 36px 0;
      background:
        linear-gradient(135deg, rgba(255,255,255,0.16) 0%, rgba(255,255,255,0.06) 100%);
      backdrop-filter: blur(18px) saturate(160%);
      -webkit-backdrop-filter: blur(18px) saturate(160%);
      border-radius: 18px;
      border: 1px solid rgba(255,255,255,0.28);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.25),
        inset 0 -1px 0 rgba(0,0,0,0.05),
        0 8px 24px rgba(0,0,0,0.08);
      position: relative;
      overflow: hidden;
    }
    /* 카운트다운 카드 내부 은은한 shine */
    .ct-countdown::before {
      content: '';
      position: absolute;
      top: -50%; left: -20%;
      width: 40%; height: 200%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
      transform: rotate(25deg);
      animation: cdShine 8s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes cdShine {
      0%, 100% { left: -40%; opacity: 0; }
      50%      { left: 100%; opacity: 1; }
    }
    .ct-cd-left { position: relative; display: flex; flex-direction: column; justify-content: center; }
    .ct-cd-label {
      font-size: 12.5px; font-weight: 800;
      opacity: 0.9;
      letter-spacing: 1px;
      margin-bottom: 10px;
      display: flex; align-items: center; gap: 7px;
    }
    .ct-cd-label::before {
      content: ''; width: 7px; height: 7px;
      border-radius: 50%; background: #fbbf24;
      box-shadow: 0 0 10px #fbbf24;
      animation: cdPulse 1.6s ease-in-out infinite;
    }
    @keyframes cdPulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.85); }
    }
    .ct-cd-value {
      font-size: 44px;
      font-weight: 900;
      font-variant-numeric: tabular-nums;
      letter-spacing: -1.5px;
      line-height: 1;
      text-shadow: 0 2px 14px rgba(0,0,0,0.18);
    }
    .ct-cd-value.ended    { opacity: 0.7; font-size: 30px; letter-spacing: -0.4px; }
    .ct-cd-value.upcoming { color: #fbbf24; }

    /* 긴급 모드: 10분 이하부터 단계별 경고 */
    .ct-cd-value.urgent-10 { color: #fde047; text-shadow: 0 0 20px rgba(253, 224, 71, 0.6); }
    .ct-cd-value.urgent-5  { color: #ffedd5; text-shadow: 0 0 22px rgba(255, 237, 213, 0.9); animation: urgentPulse 1.3s ease-in-out infinite; }
    .ct-cd-value.urgent-1  { color: #fee2e2; text-shadow: 0 0 28px rgba(254, 226, 226, 1); animation: urgentShake 0.5s ease-in-out infinite; }
    @keyframes urgentPulse {
      0%, 100% { transform: scale(1); }
      50%      { transform: scale(1.05); }
    }
    @keyframes urgentShake {
      0%, 100% { transform: translateX(0) scale(1); }
      20%      { transform: translateX(-3px) scale(1.03); }
      40%      { transform: translateX(3px) scale(1.03); }
      60%      { transform: translateX(-3px) scale(1.03); }
      80%      { transform: translateX(3px) scale(1.03); }
    }

    /* ═════ HERO 전체 Urgent 모드 ═════ */
    /* 5분 이하: 주황 무드 */
    .ct-hero.hero-urgent-5 {
      background:
        radial-gradient(ellipse 90% 80% at 85% 0%, rgba(251, 146, 60, 0.55) 0%, transparent 55%),
        radial-gradient(ellipse 80% 80% at 0% 100%, rgba(251, 191, 36, 0.35) 0%, transparent 55%),
        radial-gradient(ellipse 100% 100% at 50% 50%, rgba(217, 70, 239, 0.2) 0%, transparent 70%),
        linear-gradient(135deg, #c2410c 0%, #9a3412 50%, #7c2d12 100%) !important;
      box-shadow:
        0 1px 3px rgba(16,24,40,0.04),
        0 20px 60px rgba(251, 146, 60, 0.35),
        0 0 0 1px rgba(255, 237, 213, 0.15) inset !important;
    }
    .ct-hero.hero-urgent-5 .ct-progress-fill {
      background: linear-gradient(90deg, #fed7aa 0%, #fb923c 40%, #fdba74 100%) !important;
      background-size: 200% 100% !important;
      box-shadow: 0 0 18px rgba(251, 146, 60, 0.8), 0 0 28px rgba(251, 146, 60, 0.4) !important;
    }
    .ct-hero.hero-urgent-5 .ct-next-cta {
      background: linear-gradient(135deg, #fb923c 0%, #f97316 50%, #ea580c 100%) !important;
      background-size: 200% 200% !important;
      box-shadow:
        0 8px 24px rgba(249, 115, 22, 0.5),
        0 0 0 1px rgba(255, 237, 213, 0.2) inset !important;
    }
    .ct-hero.hero-urgent-5 .ct-countdown {
      border-color: rgba(255, 237, 213, 0.35) !important;
    }
    .ct-hero.hero-urgent-5 .ct-cd-label::before {
      background: #fb923c !important;
      box-shadow: 0 0 12px #fb923c !important;
    }

    /* 1분 이하: 빨강 무드 + shake */
    .ct-hero.hero-urgent-1 {
      background:
        radial-gradient(ellipse 100% 100% at 50% 0%, rgba(239, 68, 68, 0.7) 0%, transparent 60%),
        radial-gradient(ellipse 90% 90% at 0% 100%, rgba(220, 38, 38, 0.4) 0%, transparent 55%),
        linear-gradient(135deg, #991b1b 0%, #7f1d1d 50%, #450a0a 100%) !important;
      animation: heroShake 0.6s ease-in-out infinite, heroFloat 22s ease-in-out infinite alternate !important;
      box-shadow:
        0 1px 3px rgba(16,24,40,0.04),
        0 20px 60px rgba(239, 68, 68, 0.5),
        0 0 0 2px rgba(254, 226, 226, 0.3) inset !important;
    }
    @keyframes heroShake {
      0%, 100% { transform: translateX(0); }
      25%      { transform: translateX(-2px); }
      75%      { transform: translateX(2px); }
    }
    .ct-hero.hero-urgent-1 .ct-progress-fill {
      background: linear-gradient(90deg, #fecaca 0%, #ef4444 40%, #fca5a5 100%) !important;
      background-size: 200% 100% !important;
      box-shadow: 0 0 20px rgba(239, 68, 68, 0.9), 0 0 32px rgba(239, 68, 68, 0.5) !important;
      animation: progressGlow 1.5s ease-in-out infinite !important;
    }
    .ct-hero.hero-urgent-1 .ct-next-cta {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #991b1b 100%) !important;
      background-size: 200% 200% !important;
      box-shadow:
        0 8px 24px rgba(239, 68, 68, 0.6),
        0 0 0 1px rgba(254, 226, 226, 0.25) inset !important;
      animation: ctaPulse 2s ease-in-out infinite !important;
    }
    .ct-hero.hero-urgent-1 .ct-countdown {
      border-color: rgba(254, 226, 226, 0.5) !important;
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.08) 100%) !important;
    }
    .ct-hero.hero-urgent-1 .ct-cd-label::before {
      background: #ef4444 !important;
      box-shadow: 0 0 16px #ef4444 !important;
      animation: cdPulse 0.6s ease-in-out infinite !important;
    }

    /* ═════ Urgent Alert Banner (5분 이하 표시) ═════ */
    .ct-urgent-alert {
      display: none;
      align-items: center; gap: 10px;
      padding: 10px 18px;
      margin: 14px 36px 0;
      background: linear-gradient(135deg, rgba(251, 146, 60, 0.25) 0%, rgba(249, 115, 22, 0.15) 100%);
      border: 1px solid rgba(251, 146, 60, 0.45);
      border-radius: 12px;
      color: #fff;
      font-size: 13.5px; font-weight: 700;
      letter-spacing: -0.1px;
      animation: alertSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .ct-urgent-alert.show { display: flex; }
    .ct-urgent-alert .alert-icon {
      font-size: 18px; line-height: 1;
      animation: alertBlink 0.8s ease-in-out infinite;
    }
    @keyframes alertBlink {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%      { opacity: 0.6; transform: scale(0.9); }
    }
    @keyframes alertSlideIn {
      from { opacity: 0; transform: translateY(-10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    /* 1분 이하 - 빨간 톤 */
    .ct-hero.hero-urgent-1 .ct-urgent-alert {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.35) 0%, rgba(220, 38, 38, 0.2) 100%);
      border-color: rgba(254, 226, 226, 0.55);
      animation: alertSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), alertThrob 0.8s ease-in-out infinite;
    }
    @keyframes alertThrob {
      0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
      50%      { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.15); }
    }

    /* 시간 정보 (시작~종료) — compact */
    .ct-schedule {
      display: flex; flex-direction: column;
      justify-content: center;
      gap: 10px;
      padding-left: 22px;
      border-left: 1px solid rgba(255,255,255,0.22);
    }
    /* 날짜 (최상단) */
    .ct-sched-date-top {
      font-size: 12.5px; font-weight: 700;
      opacity: 0.85;
      letter-spacing: 0.1px;
      display: inline-flex; align-items: center; gap: 5px;
    }
    .ct-sched-date-top::before { content: '📅'; font-size: 13px; }

    /* 시작→종료 row */
    .ct-sched-times {
      display: flex; flex-direction: column; gap: 4px;
    }
    .ct-sched-row {
      display: flex; align-items: baseline; gap: 8px;
      font-variant-numeric: tabular-nums;
    }
    .ct-sched-label {
      font-size: 11px; font-weight: 700;
      opacity: 0.7;
      letter-spacing: 0.3px;
      min-width: 26px;
    }
    .ct-sched-time {
      font-size: 17px; font-weight: 800;
      letter-spacing: -0.4px;
    }

    /* 지속 시간 pill */
    .ct-sched-duration {
      margin-top: 2px;
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 11.5px; font-weight: 700;
      padding: 3px 10px;
      background: rgba(255,255,255,0.14);
      border: 1px solid rgba(255,255,255,0.18);
      border-radius: 100px;
      align-self: flex-start;
      letter-spacing: -0.1px;
    }
    .ct-sched-duration::before { content: '⏱'; font-size: 11px; }
    .ct-sched-duration strong {
      font-weight: 900; letter-spacing: -0.2px;
    }

    /* ═════ 남은 시간 프로그레스 ═════ */
    .ct-progress-wrap {
      padding: 18px 36px 28px;
    }
    .ct-progress-meta {
      display: flex; justify-content: space-between; align-items: center;
      font-size: 12.5px; font-weight: 800;
      opacity: 0.9; margin-bottom: 10px;
      letter-spacing: 0.3px;
    }
    .ct-progress-meta .ct-progress-pct {
      font-size: 16px; font-weight: 900;
      letter-spacing: -0.3px;
      font-variant-numeric: tabular-nums;
    }
    .ct-progress-bar {
      height: 10px; background: rgba(0,0,0,0.25);
      border-radius: 100px; overflow: hidden;
      position: relative;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);
    }
    /* INVERTED: fill shrinks from right as time passes (보여주는 건 남은 시간) */
    .ct-progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #f0abfc 0%, #c084fc 30%, #e9d5ff 60%, #f0abfc 100%);
      background-size: 200% 100%;
      border-radius: 100px;
      box-shadow:
        0 0 14px rgba(192, 132, 252, 0.7),
        0 0 24px rgba(236, 72, 153, 0.3);
      transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      animation: progressGlow 4s ease-in-out infinite;
    }
    @keyframes progressGlow {
      0%, 100% { background-position: 0% 0%; }
      50%      { background-position: 100% 0%; }
    }
    .ct-progress-fill::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
      animation: shimmer 2.2s infinite;
    }
    @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
    /* 종료됨 상태 */
    .ct-progress-fill.fill-ended { background: rgba(255,255,255,0.18); box-shadow: none; animation: none; }
    /* 예정 상태 - 시작까지 카운트다운 (파스텔 시안) */
    .ct-progress-fill.fill-upcoming {
      background: linear-gradient(90deg, #67e8f9, #22d3ee);
      box-shadow: 0 0 14px rgba(34, 211, 238, 0.7);
      animation: none;
    }

    /* ═════ INFO CARD ═════ */
    .ct-info-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 1px 3px rgba(16,24,40,0.04), 0 8px 24px rgba(16,24,40,0.04);
      border: 1px solid #eef0f5;
      padding: 24px 28px;
      margin-bottom: 20px;
    }
    .ct-desc {
      font-size: 14px;
      color: #4b5563;
      line-height: 1.75;
      padding-bottom: 18px;
      margin-bottom: 18px;
      border-bottom: 1px dashed #eef0f5;
    }
    .ct-desc:empty { display: none; padding: 0; margin: 0; border: none; }

    .ct-server-clock {
      display: inline-flex; align-items: center; gap: 6px;
      background: #f8f9fc;
      color: #9ca3af;
      padding: 4px 12px;
      border-radius: 100px;
      font-size: 11.5px;
      font-weight: 600;
      margin-bottom: 16px;
      border: 1px solid #eef0f5;
      font-variant-numeric: tabular-nums;
    }
    .ct-server-clock::before { content: '🕐'; font-size: 12px; }

    /* ═════ 내 진척도 요약 (glassmorphism card) ═════ */
    .ct-my-progress {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 14px;
      padding: 16px 22px;
      margin: 14px 36px 0;
      background:
        linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%);
      backdrop-filter: blur(14px) saturate(140%);
      -webkit-backdrop-filter: blur(14px) saturate(140%);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 14px;
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.22),
        0 4px 16px rgba(0, 0, 0, 0.05);
    }
    .ct-mp-item {
      display: flex; flex-direction: column; gap: 6px;
      position: relative;
    }
    .ct-mp-item:not(:last-child)::after {
      content: '';
      position: absolute; right: -7px; top: 50%;
      width: 1px; height: 32px;
      background: linear-gradient(180deg, transparent, rgba(255,255,255,0.3), transparent);
      transform: translateY(-50%);
    }
    .ct-mp-item .mp-label {
      font-size: 12px; font-weight: 700;
      opacity: 0.8;
      letter-spacing: 0.1px;
      display: flex; align-items: center; gap: 5px;
    }
    .ct-mp-item .mp-value {
      font-size: 24px; font-weight: 900;
      letter-spacing: -0.6px;
      font-variant-numeric: tabular-nums;
      line-height: 1.1;
    }
    .ct-mp-item .mp-value small {
      font-size: 14px; font-weight: 700;
      opacity: 0.65;
      margin-left: 2px;
      letter-spacing: -0.2px;
    }
    /* 예상 점수: 보라 단색 + 은은한 글로우 (가독성 우선) */
    .ct-mp-score .mp-value {
      font-size: 26px;
      color: #e9d5ff;
      text-shadow:
        0 0 12px rgba(196, 181, 253, 0.55),
        0 0 24px rgba(167, 139, 250, 0.25);
      letter-spacing: -0.8px;
    }
    .ct-mp-score .mp-value small {
      color: rgba(221, 214, 254, 0.7);
      text-shadow: none;
    }
    /* 진행 중 대회에서 쨍한 색조 (화이트 → 라일락) */
    .ct-hero:not(.ct-ended) .ct-mp-score .mp-value {
      color: #fdf4ff;
      text-shadow:
        0 0 14px rgba(240, 171, 252, 0.6),
        0 0 28px rgba(217, 70, 239, 0.3);
    }
    .ct-hero:not(.ct-ended) .ct-mp-score .mp-value small {
      color: rgba(253, 244, 255, 0.65);
    }

    /* ═════ 다음 문제 CTA 버튼 (핑크-바이올렛 그라디언트) ═════ */
    .ct-next-cta {
      display: flex; align-items: center; justify-content: space-between; gap: 14px;
      padding: 18px 26px 18px 24px;
      margin: 14px 36px 0;
      background:
        linear-gradient(135deg, #ec4899 0%, #d946ef 40%, #a855f7 100%);
      background-size: 200% 200%;
      color: #fff;
      border-radius: 16px;
      text-decoration: none;
      font-weight: 800;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow:
        0 8px 24px rgba(217, 70, 239, 0.35),
        0 0 0 1px rgba(255, 255, 255, 0.15) inset;
      position: relative;
      overflow: hidden;
      animation: ctaPulse 5s ease-in-out infinite;
    }
    @keyframes ctaPulse {
      0%, 100% { background-position: 0% 50%; }
      50%      { background-position: 100% 50%; }
    }
    /* 빛 스윕 애니메이션 (자동) */
    .ct-next-cta::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.35) 50%, transparent 100%);
      transform: translateX(-100%);
      animation: ctaShine 3.5s ease-in-out infinite;
    }
    @keyframes ctaShine {
      0%, 40%, 100% { transform: translateX(-100%); }
      60%           { transform: translateX(100%); }
    }
    .ct-next-cta:hover {
      transform: translateY(-3px);
      box-shadow:
        0 14px 36px rgba(217, 70, 239, 0.50),
        0 0 0 1px rgba(255, 255, 255, 0.25) inset;
      color: #fff; text-decoration: none;
    }
    .ct-next-cta .cta-content {
      display: flex; align-items: center; gap: 14px;
      z-index: 1; position: relative;
    }
    .ct-next-cta .cta-icon {
      font-size: 28px; line-height: 1;
      filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
      animation: iconBounce 2.5s ease-in-out infinite;
    }
    @keyframes iconBounce {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50%      { transform: translateY(-3px) rotate(-8deg); }
    }
    .ct-next-cta:hover .cta-icon { animation-duration: 1s; }
    .ct-next-cta .cta-text { display: flex; flex-direction: column; gap: 2px; }
    .ct-next-cta .cta-title {
      font-size: 16px; font-weight: 900;
      letter-spacing: -0.3px;
      text-shadow: 0 1px 2px rgba(0,0,0,0.15);
      display: flex; align-items: center; gap: 8px;
      flex-wrap: wrap;
    }
    .ct-next-cta .cta-pid {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 26px; height: 24px;
      padding: 0 8px;
      background: rgba(255,255,255,0.25);
      border: 1px solid rgba(255,255,255,0.35);
      border-radius: 7px;
      font-size: 13px; font-weight: 900;
      letter-spacing: 0.3px;
      backdrop-filter: blur(8px);
    }
    .ct-next-cta .cta-problem-title {
      font-size: 15px; font-weight: 700;
      color: rgba(255,255,255,0.95);
      letter-spacing: -0.3px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-width: 360px;
    }
    .ct-next-cta .cta-sub {
      font-size: 12px; font-weight: 600;
      opacity: 0.9;
      letter-spacing: 0.1px;
    }
    .ct-next-cta .cta-arrow {
      font-size: 22px; font-weight: 900;
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      z-index: 1; position: relative;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
    }
    .ct-next-cta:hover .cta-arrow { transform: translateX(8px); }
    /* 모두 풀었을 때 - 에메랄드 톤 (축하 느낌) */
    .ct-next-cta.all-done {
      background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #06b6d4 100%);
      background-size: 200% 200%;
      color: #fff;
      box-shadow:
        0 8px 24px rgba(16, 185, 129, 0.35),
        0 0 0 1px rgba(255, 255, 255, 0.15) inset;
      animation: ctaPulse 5s ease-in-out infinite;
    }
    .ct-next-cta.all-done:hover { color: #fff; }

    /* ═════ Sticky 카운트다운 (스크롤 시) ═════ */
    .ct-sticky {
      position: fixed;
      top: -100px; left: 50%; transform: translateX(-50%);
      max-width: 560px; width: calc(100% - 40px);
      padding: 12px 20px;
      background: rgba(124, 58, 237, 0.95);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      color: #fff;
      border-radius: 100px;
      box-shadow: 0 10px 40px rgba(124, 58, 237, 0.35);
      display: flex; align-items: center; justify-content: space-between; gap: 16px;
      z-index: 1000;
      transition: top 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      font-weight: 700;
    }
    .ct-sticky.show { top: 16px; }
    .ct-sticky .sticky-time {
      display: flex; align-items: center; gap: 8px;
      font-size: 15px; font-weight: 900;
      font-variant-numeric: tabular-nums;
      letter-spacing: -0.3px;
    }
    .ct-sticky .sticky-time::before {
      content: ''; width: 8px; height: 8px;
      border-radius: 50%; background: #fbbf24;
      box-shadow: 0 0 10px #fbbf24;
      animation: cdPulse 1.6s ease-in-out infinite;
    }
    .ct-sticky .sticky-progress {
      display: flex; align-items: center; gap: 6px;
      font-size: 13px; font-weight: 700;
      opacity: 0.95;
    }
    .ct-sticky .sticky-divider {
      width: 1px; height: 16px;
      background: rgba(255,255,255,0.3);
    }
    .ct-sticky.urgent {
      background: rgba(239, 68, 68, 0.95);
      box-shadow: 0 10px 40px rgba(239, 68, 68, 0.5);
    }
    .ct-sticky.urgent .sticky-time::before {
      background: #fef2f2; box-shadow: 0 0 14px #fff;
    }

    /* ═════ 액션 버튼 (대형 카드 그리드) ═════ */
    .ct-actions {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }
    .ct-btn-card {
      display: flex; flex-direction: column; align-items: center;
      gap: 8px;
      padding: 22px 14px 18px;
      background: #fff;
      border: 1.5px solid #e5e9f0;
      border-radius: 14px;
      text-decoration: none;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    .ct-btn-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, #c4b5fd, transparent);
      transform: translateX(-100%);
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .ct-btn-card:hover {
      border-color: #c4b5fd;
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(124, 58, 237, 0.15);
      background: #faf9fd;
      text-decoration: none;
    }
    .ct-btn-card:hover::before { transform: translateX(0); }
    .ct-btn-card .ct-btn-icon {
      font-size: 28px; line-height: 1;
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .ct-btn-card:hover .ct-btn-icon { transform: scale(1.1) rotate(-4deg); }
    .ct-btn-card .ct-btn-label {
      font-size: 14.5px; font-weight: 800;
      color: #1a1a2e;
      letter-spacing: -0.2px;
    }
    .ct-btn-card .ct-btn-sub {
      font-size: 11.5px; font-weight: 500;
      color: #9ca3af;
      letter-spacing: 0.1px;
    }
    /* Primary variant (현재 페이지) */
    .ct-btn-card.active {
      background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
      border-color: transparent;
      box-shadow: 0 8px 22px rgba(124,58,237,0.25);
    }
    .ct-btn-card.active .ct-btn-label { color: #fff; }
    .ct-btn-card.active .ct-btn-sub { color: rgba(255,255,255,0.8); }
    .ct-btn-card.active:hover {
      box-shadow: 0 12px 30px rgba(124,58,237,0.35);
      background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
    }
    /* Admin 버튼 */
    .ct-admin-actions {
      display: flex; gap: 8px; flex-wrap: wrap;
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px dashed #eef0f5;
    }
    .ct-btn-admin {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 14px;
      background: #f5f3ff;
      color: #6d28d9 !important;
      border: 1.5px solid #ddd6fe;
      border-radius: 10px;
      font-size: 12.5px; font-weight: 700;
      text-decoration: none;
      transition: all 0.15s;
    }
    .ct-btn-admin:hover {
      background: #ede9fe;
      border-color: #a78bfa;
      text-decoration: none;
      transform: translateY(-1px);
    }

    /* ═════ 문제 목록 ═════ */
    .ct-table-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 1px 3px rgba(16,24,40,0.04), 0 8px 24px rgba(16,24,40,0.04);
      border: 1px solid #eef0f5;
      overflow: hidden;
    }
    .ct-table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 28px;
      border-bottom: 1px solid #eef0f5;
    }
    .ct-table-header h3 {
      font-size: 17px;
      font-weight: 800;
      margin: 0;
      color: #1a1a2e;
      letter-spacing: -0.3px;
    }
    .ct-table-header h3::before { content: '📋 '; }
    .ct-table-header .ct-count {
      font-size: 12px;
      font-weight: 600;
      color: #7c3aed;
      background: #f3f0ff;
      padding: 4px 12px;
      border-radius: 100px;
    }
    .ct-table { width: 100%; border-collapse: collapse; }
    .ct-table thead tr {
      background: #fafbfc;
      border-bottom: 1px solid #eef0f5;
    }
    .ct-table thead th {
      padding: 12px 16px;
      font-size: 11.5px;
      font-weight: 700;
      color: #6b7280;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .ct-table thead th.th-title { text-align: left; padding-left: 8px; }
    .ct-table tbody tr {
      border-bottom: 1px solid #f3f4f6;
      transition: background 0.12s;
    }
    .ct-table tbody tr:last-child { border-bottom: none; }
    .ct-table tbody tr:hover { background: #fafbfd; }
    .ct-table tbody tr.row-solved { background: #f0fdf4; }
    .ct-table tbody tr.row-solved:hover { background: #dcfce7; }
    .ct-table td {
      padding: 14px 16px;
      font-size: 14px;
      text-align: center;
      vertical-align: middle;
    }
    .ct-table td.td-title { text-align: left; padding-left: 8px; }
    .ct-table td.td-title a {
      color: #1a1a2e;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.12s;
    }
    .ct-table td.td-title a:hover { color: #7c3aed; }
    .row-solved .td-title a { color: #16a34a; }

    /* 상태 아이콘 */
    .ct-status-icon {
      display: inline-flex;
      align-items: center; justify-content: center;
      width: 28px; height: 28px;
      border-radius: 50%;
      font-size: 14px;
      font-weight: 800;
    }
    .ct-status-icon.solved  { background: #dcfce7; color: #16a34a; }
    .ct-status-icon.tried   { background: #fef3c7; color: #d97706; }
    .ct-status-icon.empty   { background: #f3f4f6; color: #cbd5e1; }

    /* 내 상태 컬럼 (시도 횟수) */
    .ct-my-state {
      display: inline-flex;
      align-items: center; gap: 4px;
      padding: 4px 10px;
      border-radius: 100px;
      font-size: 11.5px; font-weight: 700;
      white-space: nowrap;
    }
    .ct-my-state.my-solved { background: #dcfce7; color: #15803d; }
    .ct-my-state.my-tried  { background: #fef3c7; color: #a16207; }
    .ct-my-state.my-none   { background: #f3f4f6; color: #9ca3af; font-weight: 500; }
    .ct-my-state .my-count { opacity: 0.75; font-weight: 600; }

    /* 문제 번호 배지 (A,B,C,...) */
    .ct-pid-badge {
      display: inline-flex;
      align-items: center; justify-content: center;
      min-width: 30px; height: 28px;
      padding: 0 9px;
      background: linear-gradient(135deg, #ede9fe, #ddd6fe);
      color: #6d28d9;
      border-radius: 7px;
      font-weight: 800;
      font-size: 13px;
      letter-spacing: 0.3px;
    }
    .row-solved .ct-pid-badge {
      background: linear-gradient(135deg, #dcfce7, #bbf7d0);
      color: #16a34a;
    }

    .ct-ac-num {
      color: #16a34a;
      font-weight: 800;
      font-size: 14px;
      font-variant-numeric: tabular-nums;
    }
    .ct-sub-num {
      color: #9ca3af;
      font-weight: 600;
      font-variant-numeric: tabular-nums;
    }
    .ct-rate-pill {
      display: inline-block;
      padding: 2px 9px;
      background: #f3f4f6;
      color: #6b7280;
      border-radius: 100px;
      font-size: 11.5px;
      font-weight: 700;
      margin-left: 6px;
    }

    /* 빈 상태 */
    .ct-empty {
      padding: 60px 24px;
      text-align: center;
      color: #9ca3af;
    }
    .ct-empty .emoji { font-size: 40px; margin-bottom: 12px; }
    .ct-empty p { margin: 0; font-size: 14px; }

    /* ═════ 메타 정보 바 (참가자/언어/출제자) — 프리미엄 chip ═════ */
    .ct-meta-bar {
      display: flex; flex-wrap: wrap; gap: 7px; align-items: center;
      margin-top: 14px;
    }
    .ct-meta-chip {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 6px 13px 6px 11px;
      background:
        linear-gradient(135deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.10) 100%);
      backdrop-filter: blur(12px) saturate(160%);
      -webkit-backdrop-filter: blur(12px) saturate(160%);
      color: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.22);
      border-radius: 100px;
      font-size: 11.5px; font-weight: 600;
      letter-spacing: 0.1px;
      transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.18),
        0 2px 6px rgba(0,0,0,0.05);
      cursor: default;
    }
    .ct-meta-chip:hover {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.30) 0%, rgba(255, 255, 255, 0.15) 100%);
      transform: translateY(-1px);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.28),
        0 4px 12px rgba(0,0,0,0.08);
    }
    .ct-meta-chip .meta-icon { font-size: 13px; opacity: 0.90; line-height: 1; }
    .ct-meta-chip .meta-val { font-weight: 800; letter-spacing: -0.2px; color: #fff; }
    .ct-meta-chip .meta-lang-list {
      font-family: 'SF Mono','JetBrains Mono','D2Coding',ui-monospace,monospace;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: -0.1px;
    }
    /* 클럭 chip 특별 스타일 */
    .ct-meta-chip.meta-clock {
      background: linear-gradient(135deg, rgba(196, 181, 253, 0.25) 0%, rgba(167, 139, 250, 0.15) 100%);
      border-color: rgba(196, 181, 253, 0.35);
    }
    .ct-meta-chip.meta-clock #nowdate {
      font-family: 'SF Mono','JetBrains Mono','D2Coding',ui-monospace,monospace;
      font-weight: 800;
      font-size: 11.5px;
    }

    /* ═════ 종료된 대회 전용 UI (회고형 아카이브 톤) ═════ */
    .ct-hero.ct-ended {
      background:
        radial-gradient(ellipse 85% 60% at 85% 0%, rgba(139, 92, 246, 0.22) 0%, transparent 55%),
        radial-gradient(ellipse 70% 80% at 0% 100%, rgba(71, 85, 105, 0.30) 0%, transparent 55%),
        radial-gradient(ellipse 100% 100% at 50% 50%, rgba(100, 116, 139, 0.18) 0%, transparent 70%),
        linear-gradient(135deg, #475569 0%, #334155 50%, #1e293b 100%);
      box-shadow:
        0 1px 3px rgba(16,24,40,0.05),
        0 20px 60px rgba(51, 65, 85, 0.28),
        0 0 0 1px rgba(255,255,255,0.06) inset;
    }
    .ct-hero.ct-ended::before { opacity: 0.35; animation-duration: 28s; }
    .ct-hero.ct-ended::after  { opacity: 0.45; animation-duration: 32s; background: radial-gradient(circle, rgba(148,163,184,0.20) 0%, transparent 70%); }
    .ct-hero.ct-ended .ct-hero-body::before { opacity: 0.3; }
    /* 종료 대회 히어로에 은은한 트로피 백그라운드 */
    .ct-hero.ct-ended .ct-hero-body {
      position: relative;
    }
    .ct-hero.ct-ended .ct-hero-body::after {
      content: '🏁';
      position: absolute;
      top: 24px; right: 32px;
      font-size: 96px;
      opacity: 0.07;
      transform: rotate(-8deg);
      pointer-events: none;
      filter: saturate(0.4) brightness(1.5);
      z-index: 0;
    }

    /* 종료 카운트다운 카드 (회색 톤 + 결과 요약 강조) */
    .ct-hero.ct-ended .ct-countdown {
      background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.04) 100%);
      border-color: rgba(255,255,255,0.18);
    }
    .ct-hero.ct-ended .ct-cd-value {
      font-size: 28px;
      letter-spacing: -0.6px;
      background: linear-gradient(180deg, #f1f5f9 0%, #cbd5e1 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      opacity: 0.95;
    }
    .ct-hero.ct-ended .ct-sched-duration strong { color: #e2e8f0; }

    /* 종료 대회의 내 진척도 → "최종 성적" 카드로 변형 */
    .ct-hero.ct-ended .ct-my-progress {
      background:
        linear-gradient(135deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.06) 100%);
      border-color: rgba(255, 255, 255, 0.22);
      position: relative;
    }
    /* 종료 대회에서 진척도 라벨 앞에 "최종" 프리픽스 */
    .ct-hero.ct-ended .ct-my-progress .mp-label::before {
      content: '최종 ';
      color: rgba(203, 213, 225, 0.7);
      font-weight: 800;
      font-size: 10px;
      letter-spacing: 0.5px;
    }
    /* 종료 대회의 ct-mp-item 아이콘은 약간 muted */
    .ct-hero.ct-ended .ct-my-progress .mp-label { opacity: 0.85; }

    /* 종료 진행바 → 아카이브 패턴 */
    .ct-hero.ct-ended .ct-progress-wrap { opacity: 0.7; }
    .ct-hero.ct-ended .ct-progress-meta span:first-child { color: #cbd5e1 !important; }

    /* ═════ 종료된 대회 전용 순위표 CTA (학생) ═════ */
    .ct-ended-banner {
      position: relative;
      display: flex; align-items: center; gap: 20px;
      padding: 26px 30px;
      margin-bottom: 8px;
      background:
        radial-gradient(ellipse 100% 90% at 85% 0%, rgba(167, 139, 250, 0.25) 0%, transparent 60%),
        linear-gradient(135deg, #7c3aed 0%, #6d28d9 45%, #5b21b6 100%);
      border-radius: 18px;
      box-shadow:
        0 10px 32px rgba(124, 58, 237, 0.28),
        0 0 0 1px rgba(255,255,255,0.10) inset;
      color: #fff;
      text-decoration: none !important;
      overflow: hidden;
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1),
                  box-shadow 0.25s ease;
    }
    .ct-ended-banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
      transform: translateX(-100%);
      animation: endedBannerShine 5s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes endedBannerShine {
      0%, 50%, 100% { transform: translateX(-100%); }
      75%           { transform: translateX(100%); }
    }
    .ct-ended-banner:hover {
      transform: translateY(-2px);
      box-shadow:
        0 16px 44px rgba(124, 58, 237, 0.42),
        0 0 0 1px rgba(255,255,255,0.18) inset;
      color: #fff;
      text-decoration: none !important;
    }
    .ct-ended-banner .eb-icon {
      font-size: 48px; line-height: 1;
      filter: drop-shadow(0 4px 10px rgba(0,0,0,0.25));
      animation: ebBounce 3s ease-in-out infinite;
    }
    @keyframes ebBounce {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50%      { transform: translateY(-4px) rotate(-6deg); }
    }
    .ct-ended-banner .eb-text { flex: 1; z-index: 1; }
    .ct-ended-banner .eb-title {
      font-size: 20px; font-weight: 900;
      letter-spacing: -0.4px;
      margin-bottom: 4px;
      text-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .ct-ended-banner .eb-sub {
      font-size: 13.5px; font-weight: 600;
      opacity: 0.92;
      letter-spacing: 0.05px;
    }
    .ct-ended-banner .eb-arrow {
      font-size: 26px; font-weight: 900;
      padding: 10px 16px;
      background: rgba(255,255,255,0.18);
      border: 1.5px solid rgba(255,255,255,0.3);
      border-radius: 100px;
      backdrop-filter: blur(8px);
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      z-index: 1;
    }
    .ct-ended-banner:hover .eb-arrow { transform: translateX(6px); }
    @media (max-width: 600px) {
      .ct-ended-banner { padding: 20px 22px; gap: 14px; }
      .ct-ended-banner .eb-icon { font-size: 38px; }
      .ct-ended-banner .eb-title { font-size: 16px; }
      .ct-ended-banner .eb-sub { font-size: 12px; }
      .ct-ended-banner .eb-arrow { font-size: 20px; padding: 8px 12px; }
    }

    /* ═════ 관리자 도구 (collapsible toolbar) ═════ */
    .ct-admin-toolbar {
      display: flex; align-items: center; gap: 10px;
      margin-top: 14px;
      padding: 10px 14px;
      background: linear-gradient(135deg, #faf5ff 0%, #f5f3ff 100%);
      border: 1px solid #e9d5ff;
      border-radius: 12px;
      flex-wrap: wrap;
    }
    .ct-admin-toolbar-label {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 11px; font-weight: 800;
      color: #7c3aed;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      padding-right: 10px;
      border-right: 1px solid #e9d5ff;
    }
    .ct-admin-toolbar-label::before { content: '🛡️'; font-size: 13px; }

    @media (max-width: 860px) {
      .ct-actions { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 720px) {
      .ct-wrap { margin: 20px auto; padding: 0 12px 40px; }
      .ct-hero-body { padding: 24px 24px 20px; }
      .ct-hero h1 { font-size: 22px; }
      .ct-countdown {
        grid-template-columns: 1fr;
        margin: 16px 24px 0;
        padding: 20px 22px;
        gap: 16px;
      }
      .ct-cd-value { font-size: 28px; letter-spacing: -0.8px; }
      .ct-schedule {
        padding-left: 0; padding-top: 16px;
        border-left: none; border-top: 1px solid rgba(255,255,255,0.18);
      }
      .ct-progress-wrap { padding: 14px 24px 22px; }
      .ct-info-card { padding: 20px 18px; }
      .ct-btn-card { padding: 18px 10px 14px; }
      .ct-btn-card .ct-btn-icon { font-size: 24px; }
      .ct-btn-card .ct-btn-label { font-size: 13.5px; }
      .ct-btn-card .ct-btn-sub { font-size: 10.5px; }
      .ct-table-header { padding: 16px 18px; }
      .ct-table thead th { padding: 10px 8px; font-size: 10.5px; }
      .ct-table td { padding: 12px 8px; font-size: 13px; }
      .ct-pid-badge { min-width: 26px; height: 26px; font-size: 12px; }
      .ct-status-icon { width: 24px; height: 24px; font-size: 12px; }
    }
    @media (max-width: 600px) {
      .ct-wrap { padding: 0 10px 32px; margin: 14px auto; }
      .ct-hero-body { padding: 20px 20px 16px; }
      .ct-hero h1 { font-size: 19px; line-height: 1.25; letter-spacing: -0.5px; }
      .ct-meta-bar { gap: 6px; }
      .ct-meta-chip { font-size: 11px; padding: 4px 9px; }
      .ct-countdown { margin: 14px 20px 0; padding: 16px 18px; gap: 12px; }
      .ct-cd-label { font-size: 11px; }
      .ct-cd-value { font-size: 24px; }
      .ct-hero.ct-ended .ct-cd-value { font-size: 22px; }
      .ct-sched-date-top { font-size: 12px; }
      .ct-sched-time { font-size: 14px; }
      .ct-sched-duration { font-size: 11px; }
      .ct-progress-wrap { padding: 12px 20px 18px; margin-top: 8px; }
      .ct-my-progress {
        grid-template-columns: 1fr;
        margin: 12px 20px 0;
        padding: 14px 18px;
        gap: 10px;
      }
      .ct-mp-item:not(:last-child)::after {
        right: 0; top: auto; bottom: -5px;
        width: 100%; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
        transform: none;
      }
      .ct-mp-item .mp-value { font-size: 22px; }
      .ct-next-cta { margin: 12px 20px 0; padding: 14px 18px; }
      .ct-next-cta .cta-icon { font-size: 24px; }
      .ct-next-cta .cta-title { font-size: 14px; gap: 6px; }
      .ct-next-cta .cta-pid { min-width: 22px; height: 20px; font-size: 11px; padding: 0 6px; }
      .ct-next-cta .cta-problem-title { font-size: 13px; max-width: 180px; }
      .ct-next-cta .cta-sub { font-size: 11px; }
      .ct-sticky { max-width: 360px; padding: 10px 14px; font-size: 13px; }
      .ct-sticky .sticky-time { font-size: 13px; }
      .ct-info-card { padding: 18px 16px; }
      .ct-actions { gap: 8px; }
      .ct-btn-card { padding: 16px 8px 12px; gap: 6px; }
      .ct-btn-card .ct-btn-icon { font-size: 22px; }
      .ct-btn-card .ct-btn-label { font-size: 12.5px; }
      .ct-btn-card .ct-btn-sub { font-size: 10px; }
      .ct-admin-toolbar { padding: 8px 10px; gap: 6px; }
      .ct-admin-toolbar-label { font-size: 10px; padding-right: 6px; }
      .ct-btn-admin { padding: 6px 10px; font-size: 11.5px; }
      .ct-table-card { border-radius: 12px; }
      .ct-table-header { padding: 14px 14px; }
      .ct-table-header h3 { font-size: 15px; }
      .ct-table-header .ct-count { font-size: 11px; padding: 3px 9px; }
      .ct-table thead th { padding: 9px 5px; font-size: 10px; letter-spacing: 0.3px; }
      .ct-table td { padding: 11px 5px; font-size: 12.5px; }
      .ct-table td.td-title { font-size: 13px; }
      .ct-my-state { font-size: 10.5px; padding: 3px 8px; }
      .ct-my-state .my-count { display: none; }
      .ct-pid-badge { min-width: 24px; height: 24px; font-size: 11.5px; padding: 0 7px; }
    }
    @media (max-width: 420px) {
      .ct-hero h1 { font-size: 17px; }
      .ct-cd-value { font-size: 21px; }
      .ct-mp-item .mp-value { font-size: 20px; }
      .ct-table thead th:nth-child(4),
      .ct-table thead th:nth-child(5),
      .ct-table tbody td:nth-child(4),
      .ct-table tbody td:nth-child(5) { display: none; }
    }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>

<div class="ct-wrap">
<?php if(isset($cid)):
  $is_running_now   = ($now < $end_time && $now >= $start_time);
  $is_ended         = ($now > $end_time);
  $is_upcoming      = ($now < $start_time);
  $duration_total   = max(1, $end_time - $start_time);
  $elapsed          = max(0, min($duration_total, $now - $start_time));
  $progress_percent = round($elapsed / $duration_total * 100, 1);
?>

  <!-- ═════ HERO ═════ -->
  <div class="ct-hero <?php echo $is_ended ? 'ct-ended' : ''?>">
    <div class="ct-hero-body">
      <h1><?php echo htmlspecialchars($view_title)?></h1>
      <div class="ct-hero-badges">
        <?php if($is_ended): ?>
          <span class="ct-badge badge-ended">🏁 종료</span>
        <?php elseif($is_upcoming): ?>
          <span class="ct-badge badge-upcoming">⏳ 예정</span>
        <?php else: ?>
          <span class="ct-badge badge-running"><span class="badge-dot"></span> 진행 중</span>
        <?php endif; ?>
        <?php if($view_private == '0'): ?>
          <span class="ct-badge badge-public">🌐 공개</span>
        <?php else: ?>
          <span class="ct-badge badge-private">🔒 비공개</span>
        <?php endif; ?>
      </div>
      <?php
        $cm = isset($contest_meta) ? $contest_meta : ['participants'=>0,'languages'=>[],'creator'=>''];
        $has_meta = ($cm['participants'] > 0) || !empty($cm['languages']) || !empty($cm['creator']);
        if ($has_meta):
      ?>
      <div class="ct-meta-bar">
        <?php if($cm['participants'] > 0): ?>
          <span class="ct-meta-chip" title="지금까지 제출한 참가자 수">
            <span class="meta-icon">👥</span>
            <span class="meta-val"><?php echo $cm['participants']?></span>명 참가
          </span>
        <?php endif; ?>
        <?php if(!empty($cm['languages'])):
          $lang_display = count($cm['languages']) <= 3
            ? implode(' · ', $cm['languages'])
            : implode(' · ', array_slice($cm['languages'], 0, 2)) . ' 외 ' . (count($cm['languages']) - 2) . '개';
        ?>
          <span class="ct-meta-chip" title="허용 언어: <?php echo htmlspecialchars(implode(', ', $cm['languages']))?>">
            <span class="meta-icon">💻</span>
            <span class="meta-lang-list"><?php echo htmlspecialchars($lang_display)?></span>
          </span>
        <?php endif; ?>
        <?php if(!empty($cm['creator'])): ?>
          <span class="ct-meta-chip" title="출제자">
            <span class="meta-icon">👤</span>
            <span class="meta-val"><?php echo htmlspecialchars($cm['creator'])?></span>
          </span>
        <?php endif; ?>
        <span class="ct-meta-chip meta-clock" title="서버 시각">
          <span class="meta-icon">🕐</span>
          <span id="nowdate"><?php echo date("H:i:s")?></span>
        </span>
      </div>
      <?php endif; ?>
    </div>

    <!-- 카운트다운 + 스케줄 -->
    <?php
      $duration_min = round(($end_time - $start_time) / 60);
      $duration_hr  = floor($duration_min / 60);
      $duration_rem = $duration_min % 60;
      $duration_text = '';
      if ($duration_hr > 0) $duration_text .= $duration_hr . '시간';
      if ($duration_rem > 0) $duration_text .= ($duration_text ? ' ' : '') . $duration_rem . '분';
      if ($duration_text === '') $duration_text = '0분';
      $start_date_str = date('n월 j일', $start_time);
      $end_date_str   = date('n월 j일', $end_time);
      $same_day = (date('Y-m-d', $start_time) === date('Y-m-d', $end_time));
      $remaining_percent = max(0, round(100 - $progress_percent, 1));
    ?>
    <?php if(!$is_ended && !$is_upcoming): ?>
    <!-- Urgent Alert (JS로 5분/1분 남았을 때 표시) -->
    <div class="ct-urgent-alert" id="ct-urgent-alert">
      <span class="alert-icon">⚠️</span>
      <span id="ct-urgent-text">곧 대회가 종료됩니다!</span>
    </div>
    <?php endif; ?>

    <div class="ct-countdown">
      <div class="ct-cd-left">
        <div class="ct-cd-label">
          <?php if($is_ended): ?>대회 결과
          <?php elseif($is_upcoming): ?>시작까지
          <?php else: ?>남은 시간<?php endif; ?>
        </div>
        <div class="ct-cd-value <?php echo $is_ended?'ended':($is_upcoming?'upcoming':'')?>">
          <?php if($is_ended):
            $ended_ago = $now - $end_time;
            if ($ended_ago < 3600) { $ago_txt = floor($ended_ago/60) . '분 전 종료'; }
            elseif ($ended_ago < 86400) { $ago_txt = floor($ended_ago/3600) . '시간 전 종료'; }
            else { $ago_txt = floor($ended_ago/86400) . '일 전 종료'; }
          ?><?php echo $ago_txt?>
          <?php elseif($is_upcoming): ?><span id="timeleft"><?php echo formatTimeLength($start_time - $now)?></span>
          <?php else: ?><span id="timeleft"><?php echo formatTimeLength($end_time - $now)?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="ct-schedule">
        <div class="ct-sched-date-top"><?php echo $start_date_str?><?php if(!$same_day) echo ' ~ ' . $end_date_str?></div>
        <div class="ct-sched-times">
          <div class="ct-sched-row">
            <span class="ct-sched-label">시작</span>
            <span class="ct-sched-time"><?php echo date('H:i', $start_time)?></span>
          </div>
          <div class="ct-sched-row">
            <span class="ct-sched-label">종료</span>
            <span class="ct-sched-time"><?php echo date('H:i', $end_time)?></span>
          </div>
        </div>
        <div class="ct-sched-duration">
          총 <strong><?php echo $duration_text?></strong>
        </div>
      </div>
    </div>

    <!-- 남은 시간 프로그레스 (INVERTED: shrinks from right) -->
    <?php if(!$is_ended): ?>
    <div class="ct-progress-wrap">
      <div class="ct-progress-meta">
        <span><?php echo $is_upcoming ? '시작 대기' : '남은 시간'?></span>
        <span class="ct-progress-pct" id="progress-text">
          <?php echo $is_upcoming ? '100' : $remaining_percent ?>%
        </span>
      </div>
      <div class="ct-progress-bar">
        <div class="ct-progress-fill <?php echo $is_upcoming?'fill-upcoming':'' ?>"
             id="progress-fill"
             style="width: <?php echo $is_upcoming ? 100 : $remaining_percent ?>%"></div>
      </div>
    </div>
    <?php else:
      // 종료된 대회는 진행바 대신 "완료" 풀 바 표시 (회고형)
    ?>
    <div class="ct-progress-wrap">
      <div class="ct-progress-meta">
        <span>✓ 대회가 정상 종료되었습니다</span>
        <span class="ct-progress-pct" style="color:#94a3b8;font-weight:700">100%</span>
      </div>
      <div class="ct-progress-bar">
        <div class="ct-progress-fill" style="width:100%;background:linear-gradient(90deg,#64748b,#94a3b8,#cbd5e1);opacity:0.7"></div>
      </div>
    </div>
    <?php endif; ?>

    <?php
    // 내 진척도 요약: 진행중/종료 대회 모두 표시 (최종 성적)
    $show_progress = isset($my_progress) && $my_progress['total_count'] > 0 && isset($_SESSION[$OJ_NAME.'_'.'user_id']);
    if ($show_progress && !$is_upcoming):
      $mp_rate = round($my_progress['solved_count'] / $my_progress['total_count'] * 100);
    ?>
    <div class="ct-my-progress">
      <div class="ct-mp-item">
        <span class="mp-label">✓ 내 해결</span>
        <span class="mp-value"><?php echo $my_progress['solved_count']?><small>/<?php echo $my_progress['total_count']?></small></span>
      </div>
      <div class="ct-mp-item">
        <span class="mp-label">📈 달성률</span>
        <span class="mp-value"><?php echo $mp_rate?>%</span>
      </div>
      <div class="ct-mp-item ct-mp-score">
        <span class="mp-label">🏆 예상 점수</span>
        <span class="mp-value"><?php echo $my_progress['exam_score']?><small>/<?php echo $my_progress['exam_max']?>점</small></span>
      </div>
    </div>
    <?php endif; ?>

    <?php
    // 다음 문제 CTA (진행중 + 로그인 + 문제 있을 때만)
    if ($show_progress && !$is_ended && !$is_upcoming):
      $all_done = ($my_progress['next_num'] === null);
    ?>
    <?php if ($all_done): ?>
    <div class="ct-next-cta all-done" style="cursor:default;pointer-events:none">
      <div class="cta-content">
        <div class="cta-icon">🎉</div>
        <div class="cta-text">
          <div class="cta-title">모든 문제를 해결했어요!</div>
          <div class="cta-sub">수고했어요. 혹시 놓친 건 없는지 확인해보세요.</div>
        </div>
      </div>
      <div class="cta-arrow">✓</div>
    </div>
    <?php else:
      // 다음 문제의 제목 추출 ($view_problemset[n][2]는 <a> 태그로 감싸진 제목)
      $next_num = $my_progress['next_num'];
      $next_title_raw = isset($view_problemset[$next_num][2]) ? $view_problemset[$next_num][2] : '';
      $next_title = trim(strip_tags($next_title_raw));
      $remaining = $my_progress['total_count'] - $my_progress['solved_count'];
    ?>
    <a href="problem.php?cid=<?php echo $cid?>&pid=<?php echo $next_num?>" class="ct-next-cta">
      <div class="cta-content">
        <div class="cta-icon">🚀</div>
        <div class="cta-text">
          <div class="cta-title">
            다음 문제 <span class="cta-pid"><?php echo $PID[$next_num]?></span>
            <?php if($next_title !== ''): ?>
              <span class="cta-problem-title"><?php echo htmlspecialchars($next_title)?></span>
            <?php endif; ?>
          </div>
          <div class="cta-sub">아직 해결하지 않은 문제가 <?php echo $remaining?>개 남았어요 · 클릭해서 풀러 가기</div>
        </div>
      </div>
      <div class="cta-arrow">→</div>
    </a>
    <?php endif; ?>
    <?php endif; ?>
  </div>

  <!-- Sticky 카운트다운 (스크롤 시 상단 고정) -->
  <?php if($show_progress && !$is_ended): ?>
  <div class="ct-sticky" id="ct-sticky">
    <div class="sticky-time">
      <span id="sticky-time-text"><?php echo $is_upcoming ? formatTimeLength($start_time - $now) : formatTimeLength($end_time - $now)?></span>
      <span style="font-size:12px;font-weight:600;opacity:0.7;letter-spacing:0.5px;"><?php echo $is_upcoming ? '시작 대기' : '남음'?></span>
    </div>
    <div class="sticky-divider"></div>
    <div class="sticky-progress">
      <span id="sticky-solved"><?php echo $my_progress['solved_count']?></span>/<?php echo $my_progress['total_count']?>
      <span style="opacity:0.7;">해결</span>
    </div>
  </div>
  <?php endif; ?>

  <?php
  $_is_admin_or_creator = isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']) || isset($_SESSION[$OJ_NAME.'_'.'m'.$cid]);
  // [B 옵션 강화] 종료 + 학생 → 순위표로만 유도. 문제/도전현황/통계/문제목록 모두 숨김.
  $_student_ended = $is_ended && !$_is_admin_or_creator;
  ?>

  <!-- ═════ INFO CARD ═════ -->
  <div class="ct-info-card">
    <?php if(!empty(trim(strip_tags($view_description)))): ?>
    <div class="ct-desc"><?php echo $view_description?></div>
    <?php endif; ?>

    <?php if($_student_ended): ?>
    <!-- 종료된 대회 (학생 뷰): 순위표 이동 배너만 노출 -->
    <a href="contestrank.php?cid=<?php echo $view_cid?>" class="ct-ended-banner">
      <div class="eb-icon">🏆</div>
      <div class="eb-text">
        <div class="eb-title">대회가 종료되었어요</div>
        <div class="eb-sub">최종 순위와 본인 성적을 확인하러 가볼까요?</div>
      </div>
      <div class="eb-arrow">→</div>
    </a>
    <?php else: ?>
    <div class="ct-actions">
      <a href="contest.php?cid=<?php echo $cid?>" class="ct-btn-card active">
        <div class="ct-btn-icon">📋</div>
        <div class="ct-btn-label">문제</div>
        <div class="ct-btn-sub"><?php echo count($view_problemset)?>문제</div>
      </a>
      <a href="status.php?cid=<?php echo $view_cid?>" class="ct-btn-card">
        <div class="ct-btn-icon">📊</div>
        <div class="ct-btn-label">도전현황</div>
        <div class="ct-btn-sub">내 제출 기록</div>
      </a>
      <a href="contestrank.php?cid=<?php echo $view_cid?>" class="ct-btn-card">
        <div class="ct-btn-icon">🏆</div>
        <div class="ct-btn-label">순위표</div>
        <div class="ct-btn-sub">실시간 랭킹</div>
      </a>
      <a href="conteststatistics.php?cid=<?php echo $view_cid?>" class="ct-btn-card">
        <div class="ct-btn-icon">📈</div>
        <div class="ct-btn-label">통계</div>
        <div class="ct-btn-sub">상세 분석</div>
      </a>
    </div>
    <?php endif; ?>

    <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])): ?>
    <div class="ct-admin-toolbar">
      <span class="ct-admin-toolbar-label">관리자</span>
      <a href="suspect_list.php?cid=<?php echo $view_cid?>" class="ct-btn-admin">🔍 IP 확인</a>
      <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $view_cid?>" class="ct-btn-admin">⚙️ 대회 수정</a>
    </div>
    <?php endif; ?>
  </div>

  <?php if(!$_student_ended): ?>
  <!-- ═════ 문제 목록 (종료 + 학생일 땐 숨김) ═════ -->
  <div class="ct-table-card">
    <div class="ct-table-header">
      <h3>문제 목록</h3>
      <span class="ct-count">총 <?php echo count($view_problemset)?>문제</span>
    </div>
    <?php if(empty($view_problemset)): ?>
      <div class="ct-empty">
        <div class="emoji">📭</div>
        <p>등록된 문제가 없습니다.</p>
      </div>
    <?php else: ?>
    <table class="ct-table">
      <thead>
        <tr>
          <?php if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])): ?>
          <th style="width:100px">내 상태</th>
          <?php endif; ?>
          <th style="width:70px">번호</th>
          <th class="th-title">문제 제목</th>
          <th style="width:80px">해결</th>
          <th style="width:80px">도전</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($view_problemset as $idx => $row):
          $status_html = $row[0];
          $is_solved = (stripos($status_html, 'label-success') !== false) || (stripos($status_html, '>Y<') !== false);
          $is_tried  = !$is_solved && !empty(trim(strip_tags($status_html)));
          // 내 시도 횟수
          $my_state = isset($my_progress['by_num'][$idx]) ? $my_progress['by_num'][$idx] : null;
        ?>
        <tr class="<?php echo $is_solved ? 'row-solved' : ''?>">
          <?php if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])): ?>
          <td>
            <?php if($my_state && $my_state['ac']): ?>
              <span class="ct-my-state my-solved">✓ 해결 <span class="my-count"><?php echo $my_state['attempts']?>회</span></span>
            <?php elseif($my_state): ?>
              <span class="ct-my-state my-tried">✗ 도전 <span class="my-count"><?php echo $my_state['attempts']?>회</span></span>
            <?php else: ?>
              <span class="ct-my-state my-none">— 미시도</span>
            <?php endif; ?>
          </td>
          <?php endif; ?>
          <td><span class="ct-pid-badge"><?php echo $row[1]?></span></td>
          <td class="td-title"><?php echo $row[2]?></td>
          <td><span class="ct-ac-num"><?php echo isset($row[4]) ? $row[4] : 0 ?></span></td>
          <td><span class="ct-sub-num"><?php echo isset($row[5]) ? $row[5] : 0 ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
  <?php endif; // !$_student_ended ?>

<?php endif; ?>
</div>

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime() - new Date().getTime();
var startTs = <?php echo $start_time?> * 1000;
var endTs   = <?php echo $end_time?> * 1000;
function clock() {
  var now = new Date().getTime() + diff;
  var x = new Date(now);
  var pad = function(n){ return n>=10?n:'0'+n; };
  var str = pad(x.getHours())+':'+pad(x.getMinutes())+':'+pad(x.getSeconds());
  var nowEl = document.getElementById('nowdate');
  if(nowEl) nowEl.textContent = str;

  var el = document.getElementById('timeleft');
  var stickyEl = document.getElementById('sticky-time-text');
  var ctEl = document.querySelector('.ct-countdown');
  var stickyBar = document.getElementById('ct-sticky');
  if(el) {
    var target = (now < startTs) ? startTs : endTs;
    var left = Math.max(0, Math.floor((target - now) / 1000));
    var d = Math.floor(left/86400);
    var h = Math.floor((left%86400)/3600);
    var m = Math.floor((left%3600)/60);
    var s = left%60;
    var pad2 = function(n){ return n>=10?n:'0'+n; };
    var txt;
    if(d > 0) txt = d + '일 ' + pad2(h) + '시간 ' + pad2(m) + '분 ' + pad2(s) + '초';
    else      txt = pad2(h) + '시간 ' + pad2(m) + '분 ' + pad2(s) + '초';
    el.textContent = txt;
    if (stickyEl) stickyEl.textContent = txt;

    // 긴급 모드: 남은 시간이 end로 향할 때만 적용 (시작 전엔 없음)
    var heroEl = document.querySelector('.ct-hero');
    var alertEl = document.getElementById('ct-urgent-alert');
    var alertTxt = document.getElementById('ct-urgent-text');
    if (now >= startTs && now < endTs) {
      el.classList.remove('urgent-10','urgent-5','urgent-1');
      if (ctEl) ctEl.classList.remove('urgent-10','urgent-5','urgent-1');
      if (stickyBar) stickyBar.classList.remove('urgent');
      if (heroEl) heroEl.classList.remove('hero-urgent-5','hero-urgent-1');
      if (alertEl) alertEl.classList.remove('show');

      if (left <= 60) {
        el.classList.add('urgent-1');
        if (ctEl) ctEl.classList.add('urgent-1');
        if (stickyBar) stickyBar.classList.add('urgent');
        if (heroEl) heroEl.classList.add('hero-urgent-1');
        if (alertEl) {
          alertEl.classList.add('show');
          if (alertTxt) alertTxt.textContent = '마지막 ' + left + '초! 제출 마무리하세요';
        }
      } else if (left <= 300) {
        el.classList.add('urgent-5');
        if (ctEl) ctEl.classList.add('urgent-5');
        if (stickyBar) stickyBar.classList.add('urgent');
        if (heroEl) heroEl.classList.add('hero-urgent-5');
        if (alertEl) {
          alertEl.classList.add('show');
          var mLeft = Math.ceil(left / 60);
          if (alertTxt) alertTxt.textContent = '시간이 얼마 남지 않았어요! 약 ' + mLeft + '분 남음';
        }
      } else if (left <= 600) {
        el.classList.add('urgent-10');
      }
    }
  }

  // 남은 시간 프로그레스 (INVERTED - 시간이 흐를수록 줄어듦)
  var fill = document.getElementById('progress-fill');
  var ptxt = document.getElementById('progress-text');
  if(fill && ptxt) {
    var dur = endTs - startTs;
    if (now < startTs) {
      // 아직 시작 전 - 100% 표시 (대기 상태)
      fill.style.width = '100%';
      ptxt.textContent = '100%';
    } else if (now >= endTs) {
      // 종료됨
      fill.style.width = '0%';
      ptxt.textContent = '0%';
    } else {
      var remaining = Math.min(100, Math.max(0, ((endTs - now) / dur) * 100));
      fill.style.width = remaining.toFixed(1) + '%';
      ptxt.textContent = remaining.toFixed(1) + '%';
    }
  }

  setTimeout(clock, 1000);
}
clock();

// ═══ Sticky 카운트다운 스크롤 감지 ═══
(function(){
  var heroEl = document.querySelector('.ct-hero');
  var sticky = document.getElementById('ct-sticky');
  if (!heroEl || !sticky) return;
  function onScroll() {
    var heroBottom = heroEl.getBoundingClientRect().bottom;
    if (heroBottom < 20) sticky.classList.add('show');
    else sticky.classList.remove('show');
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
</script>
</body>
</html>
