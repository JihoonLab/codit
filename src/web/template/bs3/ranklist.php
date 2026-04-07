<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>랭킹 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    * { box-sizing: border-box; }
    body { font-family: 'Inter','Noto Sans KR',sans-serif; background: #f0f2f5; }
    .rl-wrap { max-width: 960px; margin: 0 auto; padding: 32px 20px 48px; }

    /* Header */
    .rl-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
    }
    .rl-header h2 { font-size: 26px; font-weight: 800; color: #1a1a2e; margin: 0; }
    .rl-header h2 em { color: #7c3aed; font-style: normal; }
    .rl-scope { display: flex; gap: 6px; }
    .rl-scope a {
      padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 700;
      border: 1.5px solid #e5e7eb; color: #6b7280; text-decoration: none;
      transition: all 0.15s;
    }
    .rl-scope a:hover { border-color: #7c3aed; color: #7c3aed; }
    .rl-scope a.active { background: #7c3aed; color: #fff; border-color: #7c3aed; }

    /* ===== Podium ===== */
    .podium-section {
      background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 30%, #ede9fe 60%, #faf5ff 100%);
      border-radius: 20px; padding: 36px 20px 24px; margin-bottom: 24px;
      position: relative; overflow: hidden;
      border: 1px solid rgba(124,58,237,0.08);
      box-shadow: 0 4px 24px rgba(124,58,237,0.06);
    }
    .podium-title {
      text-align: center; font-size: 13px; font-weight: 800;
      color: #7c3aed; letter-spacing: 2px; text-transform: uppercase;
      margin-bottom: 20px; position: relative; z-index: 1;
    }
    .podium-section::after {
      content: '';
      position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: shimmer 5s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes shimmer { 0%,100%{ left:-100%; } 50%{ left:140%; } }

    /* sparkles */
    .sparkles { position: absolute; inset: 0; pointer-events: none; }
    .sparkle {
      position: absolute; border-radius: 50%;
      background: rgba(251,191,36,0.35);
      animation: sparkleAnim ease-in-out infinite;
    }
    @keyframes sparkleAnim { 0%,100%{ transform:scale(0); opacity:0; } 50%{ transform:scale(1); opacity:1; } }

    .podium {
      display: flex; align-items: flex-end; justify-content: center;
      gap: 20px; position: relative; z-index: 1;
    }
    .podium-item {
      display: flex; flex-direction: column; align-items: center;
      text-decoration: none;
      transition: transform 0.3s cubic-bezier(.34,1.56,.64,1);
    }
    .podium-item:hover { transform: translateY(-6px) scale(1.02); }

    .podium-trophy {
      font-size: 36px; margin-bottom: 8px;
      filter: drop-shadow(0 3px 8px rgba(251,191,36,0.3));
      animation: trophyFloat 3s ease-in-out infinite;
    }
    .p-1 .podium-trophy { font-size: 52px; filter: drop-shadow(0 4px 12px rgba(251,191,36,0.4)); }
    @keyframes trophyFloat {
      0%,100% { transform: translateY(0) rotate(0deg); }
      25% { transform: translateY(-6px) rotate(-2deg); }
      75% { transform: translateY(-3px) rotate(2deg); }
    }
    .p-1 .podium-icon::after {
      content: ''; position: absolute; top: 50%; left: 50%;
      transform: translate(-50%,-50%);
      width: 80px; height: 80px; border-radius: 50%;
      background: radial-gradient(circle, rgba(251,191,36,0.15) 0%, transparent 70%);
      animation: glowP 2s ease-in-out infinite; z-index: -1;
    }
    @keyframes glowP { 0%,100%{ transform:translate(-50%,-50%) scale(1); opacity:.5; } 50%{ transform:translate(-50%,-50%) scale(1.4); opacity:1; } }
    .podium-icon { position: relative; }

    .podium-pedestal {
      border-radius: 14px; padding: 16px 12px 14px;
      display: flex; flex-direction: column; align-items: center;
      backdrop-filter: blur(8px);
    }
    .p-1 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(254,243,199,0.6));
      border: 1.5px solid rgba(251,191,36,0.25);
      box-shadow: 0 6px 24px rgba(251,191,36,0.12);
      min-width: 160px; min-height: 120px; justify-content: center;
    }
    .p-2 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(243,244,246,0.6));
      border: 1.5px solid rgba(156,163,175,0.2);
      box-shadow: 0 4px 16px rgba(0,0,0,0.04);
      min-width: 140px; min-height: 95px; justify-content: center;
    }
    .p-3 .podium-pedestal {
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(254,243,199,0.3));
      border: 1.5px solid rgba(217,119,6,0.15);
      box-shadow: 0 4px 16px rgba(217,119,6,0.06);
      min-width: 140px; min-height: 85px; justify-content: center;
    }
    .podium-badge {
      font-size: 10px; font-weight: 800; padding: 3px 12px;
      border-radius: 12px; margin-bottom: 6px; letter-spacing: 0.5px;
    }
    .p-1 .podium-badge { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #78350f; }
    .p-2 .podium-badge { background: linear-gradient(135deg, #d1d5db, #9ca3af); color: #374151; }
    .p-3 .podium-badge { background: linear-gradient(135deg, #fbbf24, #d97706); color: #78350f; }
    .podium-name {
      font-size: 16px; font-weight: 800; color: #1f2937;
      text-align: center; margin-bottom: 2px; word-break: keep-all;
    }
    .podium-name a { color: inherit; text-decoration: none; }
    .podium-name a:hover { color: #7c3aed; }
    .podium-solved { font-size: 12px; color: #9ca3af; margin-bottom: 6px; }
    .podium-score-num { font-weight: 900; line-height: 1; }
    .p-1 .podium-score-num { font-size: 36px; color: #7c3aed; }
    .p-2 .podium-score-num { font-size: 28px; color: #6b7280; }
    .p-3 .podium-score-num { font-size: 28px; color: #92400e; }
    .podium-score-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-top: 2px; }

    /* ===== Table ===== */
    .rl-card {
      background: #fff; border-radius: 16px; overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
      border: 1px solid #e5e7eb;
      overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    .rl-table { width: 100%; border-collapse: collapse; }
    .rl-table thead tr { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    .rl-table th {
      padding: 14px 16px; font-size: 12px; font-weight: 700;
      text-align: center; color: #fff; letter-spacing: 0.3px;
      text-transform: uppercase;
    }
    .rl-table td {
      padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #f3f4f6;
      text-align: center; vertical-align: middle; color: #374151;
    }
    .rl-table tbody tr { transition: all 0.15s; }
    .rl-table tbody tr:hover { background: #faf5ff; }
    .rl-table tbody tr:last-child td { border-bottom: none; }
    .rl-table td a { color: #7c3aed; text-decoration: none; font-weight: 600; }
    .rl-table td a:hover { text-decoration: underline; }

    /* Medal rows */
    .rl-table .row-gold { background: linear-gradient(90deg, #fef9e7, #fffdf5); }
    .rl-table .row-gold td { font-weight: 700; color: #92400e; }
    .rl-table .row-gold td a { color: #b45309; }
    .rl-table .row-gold td.td-solved { color: #d97706; }
    .rl-table .row-silver { background: linear-gradient(90deg, #f3f4f8, #fafafa); }
    .rl-table .row-silver td { font-weight: 700; color: #4b5563; }
    .rl-table .row-silver td a { color: #6b7280; }
    .rl-table .row-silver td.td-solved { color: #6b7280; }
    .rl-table .row-bronze { background: linear-gradient(90deg, #fdf6ee, #fffcf8); }
    .rl-table .row-bronze td { font-weight: 700; color: #78350f; }
    .rl-table .row-bronze td a { color: #92400e; }
    .rl-table .row-bronze td.td-solved { color: #b45309; }
    .rl-table .row-me {
      background: linear-gradient(90deg, #7c3aed, #6d28d9);
      position: relative;
    }
    .rl-table .row-me td {
      font-weight: 800; color: #fff;
      border-bottom-color: rgba(255,255,255,0.1);
    }
    .rl-table .row-me td a { color: #e9d5ff !important; }
    .rl-table .row-me td a:hover { color: #fff !important; }
    .rl-table .row-me td.td-solved { color: #a5f3fc; font-size: 18px; }
    .rl-table .row-me td.td-submit { color: rgba(255,255,255,0.7); }
    .rl-table .row-me .td-rate { color: #fde68a !important; }
    .rl-table .row-me .rank-num { color: #fff; }
    .rl-table .row-me .mini-bar { background: rgba(255,255,255,0.2); }
    .rl-table .row-me td:first-child::after {
      content: '← ME'; font-size: 10px; color: #fde68a; font-weight: 900;
      margin-left: 6px; letter-spacing: 1px;
    }
    .rl-table .row-me:hover { background: linear-gradient(90deg, #6d28d9, #5b21b6); }
    .rank-medal { font-size: 22px; }
    .rank-num { font-weight: 800; color: #9ca3af; font-size: 15px; }

    /* Solved / Submit — 문제 목록과 동일 톤 */
    .td-solved { font-size: 16px; font-weight: 900; color: #16a34a; }
    .td-solved a { color: #16a34a !important; }
    .td-submit { font-size: 14px; font-weight: 600; color: #64748b; }
    .td-submit a { color: #64748b !important; }
    .td-rate { font-weight: 700; font-size: 13px; }

    /* Progress bar in table */
    .td-bar { display: flex; flex-direction: column; align-items: center; gap: 2px; }
    .mini-bar { width: 80px; height: 6px; border-radius: 3px; background: #e5e7eb; overflow: hidden; flex-shrink: 0; }
    .mini-bar-fill { height: 100%; border-radius: 3px; transition: width 0.5s ease; }

    /* Pagination */
    .rl-page { display: flex; justify-content: center; gap: 6px; margin-top: 24px; flex-wrap: wrap; }
    .rl-page a {
      padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
      border: 1.5px solid #e5e7eb; color: #6b7280; text-decoration: none;
      transition: all 0.15s;
    }
    .rl-page a:hover { border-color: #7c3aed; color: #7c3aed; }
    .rl-page a.pg-active { background: #7c3aed; color: #fff; border-color: #7c3aed; }

    /* 학년 탭 */
    .rl-grade-tabs {
      display: flex; gap: 0; margin-bottom: 16px;
      background: #fff; border-radius: 12px; overflow: hidden;
      border: 1.5px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
      width: fit-content;
    }
    .rl-grade-tab {
      padding: 10px 24px; font-size: 14px; font-weight: 700;
      color: #6b7280; text-decoration: none;
      transition: all 0.15s; border-right: 1px solid #e5e7eb;
    }
    .rl-grade-tab:last-child { border-right: none; }
    .rl-grade-tab:hover { color: #7c3aed; background: #faf5ff; }
    .rl-grade-tab.active { background: #7c3aed; color: #fff; }

    /* 개인/반별 탭 */
    .rl-mode-tabs {
      display: flex; gap: 0; margin-bottom: 24px;
      background: #fff; border-radius: 12px; overflow: hidden;
      border: 1.5px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
      width: fit-content;
    }
    .rl-mode-tab {
      padding: 10px 28px; font-size: 14px; font-weight: 700;
      color: #6b7280; cursor: pointer; transition: all 0.2s;
      border: none; background: none; position: relative;
    }
    .rl-mode-tab:hover { color: #7c3aed; background: #faf5ff; }
    .rl-mode-tab.active { background: #7c3aed; color: #fff; }

    /* ===== 반별 랭킹 시상대 ===== */
    .cr-podium-section {
      background: linear-gradient(135deg, #ede9fe 0%, #e0e7ff 40%, #dbeafe 70%, #ede9fe 100%);
      border-radius: 24px; padding: 40px 20px 32px; margin-bottom: 28px;
      position: relative; overflow: hidden;
      border: 1px solid rgba(99,102,241,0.1);
      box-shadow: 0 8px 32px rgba(99,102,241,0.08);
    }
    .cr-podium-section::before {
      content: '';
      position: absolute; top: -50%; right: -30%; width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(167,139,250,0.15) 0%, transparent 70%);
      pointer-events: none;
    }
    .cr-podium-section::after {
      content: '';
      position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      animation: shimmer 6s ease-in-out infinite;
      pointer-events: none;
    }
    .cr-podium-title {
      text-align: center; font-size: 13px; font-weight: 800;
      color: #6366f1; letter-spacing: 2px; text-transform: uppercase;
      margin-bottom: 28px; position: relative; z-index: 1;
    }
    .cr-podium {
      display: flex; align-items: flex-end; justify-content: center;
      gap: 16px; position: relative; z-index: 1;
    }
    .cr-podium-item {
      display: flex; flex-direction: column; align-items: center;
      text-decoration: none; color: inherit;
      transition: transform 0.3s cubic-bezier(.34,1.56,.64,1);
    }
    .cr-podium-item:hover { transform: translateY(-8px) scale(1.03); }
    .cr-podium-emoji {
      font-size: 40px; margin-bottom: 8px;
      filter: drop-shadow(0 4px 12px rgba(0,0,0,0.1));
      animation: trophyFloat 3s ease-in-out infinite;
    }
    .cr-p1 .cr-podium-emoji { font-size: 56px; animation-delay: 0s; }
    .cr-p2 .cr-podium-emoji { animation-delay: 0.5s; }
    .cr-p3 .cr-podium-emoji { animation-delay: 1s; }
    .cr-podium-box {
      border-radius: 18px; padding: 20px 24px 18px;
      display: flex; flex-direction: column; align-items: center;
      backdrop-filter: blur(12px); min-width: 150px;
    }
    .cr-p1 .cr-podium-box {
      background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(254,240,138,0.4));
      border: 2px solid rgba(251,191,36,0.3);
      box-shadow: 0 8px 32px rgba(251,191,36,0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
      min-width: 180px; min-height: 140px; justify-content: center;
    }
    .cr-p2 .cr-podium-box {
      background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(226,232,240,0.4));
      border: 2px solid rgba(148,163,184,0.25);
      box-shadow: 0 6px 24px rgba(0,0,0,0.06), 0 0 0 1px rgba(255,255,255,0.5) inset;
      min-width: 155px; min-height: 110px; justify-content: center;
    }
    .cr-p3 .cr-podium-box {
      background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(253,230,138,0.3));
      border: 2px solid rgba(217,119,6,0.2);
      box-shadow: 0 6px 24px rgba(217,119,6,0.08), 0 0 0 1px rgba(255,255,255,0.5) inset;
      min-width: 155px; min-height: 100px; justify-content: center;
    }
    .cr-podium-label {
      font-size: 20px; font-weight: 900; color: #1e293b;
      margin-bottom: 4px; letter-spacing: -0.5px;
    }
    .cr-podium-meta { font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 10px; }
    .cr-podium-score { font-weight: 900; line-height: 1; }
    .cr-p1 .cr-podium-score { font-size: 40px; color: #7c3aed; }
    .cr-p2 .cr-podium-score { font-size: 30px; color: #64748b; }
    .cr-p3 .cr-podium-score { font-size: 30px; color: #b45309; }
    .cr-podium-unit { font-size: 11px; color: #a1a1aa; font-weight: 700; letter-spacing: 0.5px; margin-top: 4px; }

    /* ===== 반별 랭킹 리스트 ===== */
    .cr-list { display: flex; flex-direction: column; gap: 10px; }
    .cr-item {
      background: #fff; border-radius: 16px;
      border: 1.5px solid #f1f5f9;
      box-shadow: 0 2px 8px rgba(0,0,0,0.03);
      padding: 0; overflow: hidden;
      text-decoration: none; color: inherit;
      transition: all 0.25s cubic-bezier(.4,0,.2,1);
      display: block;
    }
    .cr-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 36px rgba(99,102,241,0.12);
      border-color: #c7d2fe;
    }
    .cr-item-inner {
      display: flex; align-items: center; padding: 18px 24px; gap: 16px;
      position: relative;
    }
    .cr-item-rank {
      font-size: 18px; font-weight: 900; color: #cbd5e1;
      min-width: 36px; text-align: center;
    }
    .cr-item:nth-child(1) .cr-item-rank { color: #f59e0b; font-size: 20px; }
    .cr-item:nth-child(2) .cr-item-rank { color: #94a3b8; font-size: 20px; }
    .cr-item:nth-child(3) .cr-item-rank { color: #d97706; font-size: 20px; }
    .cr-item-name {
      font-size: 17px; font-weight: 800; color: #1e293b;
      min-width: 110px; letter-spacing: -0.3px;
    }
    .cr-item-members {
      background: #f1f5f9; border-radius: 20px;
      padding: 4px 12px; font-size: 12px; font-weight: 700;
      color: #64748b; white-space: nowrap;
    }
    .cr-item-stats {
      display: flex; gap: 20px; flex: 1; justify-content: flex-end;
      align-items: center;
    }
    .cr-stat-block { text-align: center; min-width: 60px; }
    .cr-stat-block .num {
      font-size: 20px; font-weight: 900;
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .cr-stat-block .label { font-size: 10px; color: #a1a1aa; font-weight: 700; letter-spacing: 0.3px; margin-top: 1px; }
    .cr-rate-block { text-align: right; min-width: 90px; }
    .cr-rate-num { font-size: 16px; font-weight: 800; margin-bottom: 4px; }
    .cr-rate-bar {
      width: 90px; height: 6px; border-radius: 3px;
      background: #f1f5f9; overflow: hidden;
    }
    .cr-rate-bar-fill {
      height: 100%; border-radius: 3px;
      transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }

    /* 반별 상위 3개 하이라이트 */
    .cr-item:nth-child(1) { border-color: rgba(251,191,36,0.2); background: linear-gradient(90deg, #fffbeb, #fff); }
    .cr-item:nth-child(2) { border-color: rgba(148,163,184,0.15); background: linear-gradient(90deg, #f8fafc, #fff); }
    .cr-item:nth-child(3) { border-color: rgba(217,119,6,0.15); background: linear-gradient(90deg, #fffbf1, #fff); }

    /* 진입 애니메이션 */
    .cr-item { opacity: 0; transform: translateY(20px); animation: crSlideIn 0.5s ease forwards; }
    .cr-item:nth-child(1) { animation-delay: 0.05s; }
    .cr-item:nth-child(2) { animation-delay: 0.1s; }
    .cr-item:nth-child(3) { animation-delay: 0.15s; }
    .cr-item:nth-child(4) { animation-delay: 0.2s; }
    .cr-item:nth-child(5) { animation-delay: 0.25s; }
    .cr-item:nth-child(6) { animation-delay: 0.3s; }
    .cr-item:nth-child(7) { animation-delay: 0.35s; }
    @keyframes crSlideIn { to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 600px) {
      .rl-wrap { padding: 0 12px; margin: 20px auto; }
      .rl-header h2 { font-size: 20px; }
      .podium { gap: 8px; }
      .p-1 .podium-pedestal { min-width: 100px; }
      .p-2 .podium-pedestal, .p-3 .podium-pedestal { min-width: 85px; }
      .podium-label { font-size: 14px; }
      .podium-score { font-size: 20px; }
      .podium-unit { font-size: 9px; }
      .cr-podium { gap: 6px; }
      .cr-p1 .cr-podium-box { min-width: 110px; min-height: 120px; }
      .cr-p2 .cr-podium-box, .cr-p3 .cr-podium-box { min-width: 90px; min-height: 90px; }
      .cr-podium-label { font-size: 16px; }
      .cr-p1 .cr-podium-score { font-size: 28px; }
      .cr-p2 .cr-podium-score, .cr-p3 .cr-podium-score { font-size: 22px; }
      .cr-item-inner { flex-wrap: wrap; gap: 10px; padding: 14px 16px; }
      .cr-item-stats { gap: 12px; flex: none; width: 100%; justify-content: space-around; }
      .cr-rate-block { min-width: auto; }
      .rk-item { padding: 12px 14px; gap: 10px; }
      .rk-nick { font-size: 14px; }
      .rk-stats { gap: 14px; }
      .rk-stat-num { font-size: 15px; }
      .tab-bar { gap: 4px; }
      .tab-btn { padding: 8px 14px; font-size: 13px; }
      .rl-table { min-width: 580px; }
      .rl-table th { padding: 10px 8px; font-size: 11px; }
      .rl-table td { padding: 10px 8px; font-size: 12px; }
      .rank-medal { font-size: 18px; }
      .td-solved { font-size: 14px; }
      .td-submit { font-size: 12px; }
      .mini-bar { width: 50px; }
    }
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rl-wrap">
  <div class="rl-header">
    <h2>🏆 <em>랭킹</em></h2>
    <?php $gq = ($view_grade !== '') ? '&grade='.$view_grade : ''; ?>
    <div class="rl-scope">
      <a href="ranklist.php?<?php echo trim('grade='.($view_grade?:('all')))?>" <?php if(!$scope) echo 'class="active"'?>>전체</a>
      <a href="ranklist.php?scope=d<?php echo $gq?>" <?php if($scope=='d') echo 'class="active"'?>>오늘</a>
      <a href="ranklist.php?scope=w<?php echo $gq?>" <?php if($scope=='w') echo 'class="active"'?>>이번주</a>
      <a href="ranklist.php?scope=m<?php echo $gq?>" <?php if($scope=='m') echo 'class="active"'?>>이번달</a>
      <a href="ranklist.php?scope=y<?php echo $gq?>" <?php if($scope=='y') echo 'class="active"'?>>올해</a>
    </div>
  </div>

  <?php
    $grade_qs = '';
    if(isset($scope) && $scope !== '') $grade_qs .= '&scope='.htmlspecialchars($scope);
  ?>
  <div class="rl-grade-tabs">
    <a class="rl-grade-tab <?php if($view_grade==='') echo 'active'?>" href="ranklist.php?grade=all<?php echo $grade_qs?>">전체</a>
    <a class="rl-grade-tab <?php if($view_grade==='2') echo 'active'?>" href="ranklist.php?grade=2<?php echo $grade_qs?>">2학년</a>
    <a class="rl-grade-tab <?php if($view_grade==='3') echo 'active'?>" href="ranklist.php?grade=3<?php echo $grade_qs?>">3학년</a>
  </div>

  <div class="rl-mode-tabs">
    <button class="rl-mode-tab active" onclick="showTab('individual')">👤 개인별</button>
    <button class="rl-mode-tab" onclick="showTab('classrank')">🏫 반별</button>
  </div>

  <div id="tab-individual">
  <?php
  // 반 필터 배지
  if(isset($view_school_filter) && !empty($view_school_filter)):
    $sf_parts = explode('-', $view_school_filter);
    $sf_label = (count($sf_parts)===2 && is_numeric($sf_parts[0]) && is_numeric($sf_parts[1]))
      ? $sf_parts[0].'학년 '.$sf_parts[1].'반' : htmlspecialchars($view_school_filter);
  ?>
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <span style="background:linear-gradient(135deg,#7c3aed,#6366f1);color:#fff;padding:8px 18px;border-radius:10px;font-size:15px;font-weight:800;letter-spacing:-0.3px;">
      🏫 <?php echo $sf_label?> 랭킹
    </span>
    <a href="ranklist.php" style="background:#f1f5f9;color:#64748b;padding:7px 16px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;border:1.5px solid #e2e8f0;transition:all 0.15s;">
      ✕ 전체 보기
    </a>
  </div>
  <?php endif; ?>
  <?php
  $all_rows = array_values($view_rank);
  $start_offset = isset($_GET['start']) ? intval($_GET['start']) : 0;

  // 시상대 (첫 페이지만)
  if($start_offset == 0 && count($all_rows) >= 1):
    $p = [null, null, null];
    foreach([0,1,2] as $idx) {
      if(isset($all_rows[$idx])) {
        $c = array_values((array)$all_rows[$idx]);
        $p[$idx] = [
          'id'=> strip_tags($c[1]),
          'nick'=> strip_tags($c[2]),
          'solved'=> preg_replace('/[^0-9]/', '', strip_tags($c[4])),
          'submit'=> preg_replace('/[^0-9]/', '', strip_tags($c[5]))
        ];
      }
    }
    $trophies = ['🏆','🥈','🥉'];
    $badges = ['1ST','2ND','3RD'];
    $order = [1,0,2]; // 2등, 1등, 3등 순서
  ?>
  <div class="podium-section">
    <div class="podium-title"><?php echo $view_grade !== '' ? $view_grade.'학년 ' : ''?>INDIVIDUAL RANKING</div>
    <div class="sparkles">
      <?php for($sp=0;$sp<10;$sp++): $sz=rand(2,4); ?>
      <div class="sparkle" style="width:<?php echo $sz?>px;height:<?php echo $sz?>px;left:<?php echo rand(5,95)?>%;top:<?php echo rand(10,80)?>%;animation-duration:<?php echo rand(2,5)?>s;animation-delay:<?php echo rand(0,4)?>s;"></div>
      <?php endfor; ?>
    </div>
    <div class="podium">
      <?php foreach($order as $idx):
        if(!$p[$idx]) continue;
        $u = $p[$idx];
        $ri = $idx + 1;
      ?>
      <a class="podium-item p-<?php echo $ri?>" href="userinfo.php?user=<?php echo htmlspecialchars($u['id'])?>" style="text-decoration:none">
        <div class="podium-icon">
          <div class="podium-trophy"><?php echo $trophies[$idx]?></div>
        </div>
        <div class="podium-pedestal">
          <div class="podium-badge"><?php echo $badges[$idx]?></div>
          <div class="podium-name"><?php echo htmlspecialchars($u['nick']?:$u['id'])?></div>
          <div class="podium-solved"><?php echo $u['solved']?>해결 · <?php echo $u['submit']?>도전</div>
          <div class="podium-score-num"><?php echo $u['solved']?></div>
          <div class="podium-score-label">solved</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="rl-card">
    <table class="rl-table">
      <thead>
        <tr>
          <th style="width:60px">순위</th>
          <th>사용자ID</th>
          <th>이름</th>
          <th>학년/반</th>
          <th style="width:90px">도전</th>
          <th style="width:90px">해결</th>
          <th style="width:100px">정답률</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rank = $start_offset + 1;
        $max_solved = 1;
        if(count($all_rows) > 0) {
          $first_cells = array_values((array)$all_rows[0]);
          $max_solved = max(1, intval(preg_replace('/[^0-9]/', '', strip_tags($first_cells[4]))));
        }
        $my_uid = isset($_SESSION[$OJ_NAME.'_'.'user_id']) ? $_SESSION[$OJ_NAME.'_'.'user_id'] : '';
        foreach($view_rank as $row):
          $cells = array_values((array)$row);
          $solved_n = intval(preg_replace('/[^0-9]/', '', strip_tags($cells[4])));
          $row_uid = strip_tags($cells[1]);
          $is_me = ($my_uid !== '' && $row_uid === $my_uid);
          $row_class = '';
          if($is_me) $row_class = 'row-me';
          elseif($rank == 1) $row_class = 'row-gold';
          elseif($rank == 2) $row_class = 'row-silver';
          elseif($rank == 3) $row_class = 'row-bronze';
          $pct = round(($solved_n / $max_solved) * 100);
        ?>
        <tr class="<?php echo $row_class?>">
          <td>
            <?php if($rank <= 3): ?>
              <span class="rank-medal"><?php echo ['🥇','🥈','🥉'][$rank-1]?></span>
            <?php else: ?>
              <span class="rank-num"><?php echo $rank?></span>
            <?php endif; ?>
          </td>
          <td><?php echo $cells[1]?></td>
          <td style="font-weight:600"><?php echo $cells[2]?></td>
          <td><?php echo $cells[3]?></td>
          <td class="td-submit"><?php echo $cells[5]?></td>
          <td class="td-solved"><?php echo $cells[4]?></td>
          <td>
            <div style="text-align:center">
              <?php
                $r = floatval($cells[6]);
                if ($r >= 70) { $barGrad = '#86efac,#22c55e'; $rateColor = '#16a34a'; }
                elseif ($r >= 40) { $barGrad = '#fde68a,#f59e0b'; $rateColor = '#d97706'; }
                elseif ($r > 0)   { $barGrad = '#fca5a5,#ef4444'; $rateColor = '#dc2626'; }
                else              { $barGrad = '#c4b5fd,#7c3aed'; $rateColor = '#7c3aed'; }
              ?>
              <span class="td-rate" style="color:<?php echo $rateColor?>"><?php echo $cells[6]?></span>
              <div class="mini-bar" style="width:80px;margin:4px auto 0"><div class="mini-bar-fill" style="width:<?php echo max(min($r,100),2)?>%;background:linear-gradient(90deg,<?php echo $barGrad?>)"></div></div>
            </div>
          </td>
        </tr>
        <?php $rank++; endforeach;?>
      </tbody>
    </table>
  </div>

  <div class="rl-page">
    <?php
    $qs = "";
    if(isset($scope) && $scope !== '') $qs .= "&scope=".htmlspecialchars($scope);
    if($view_grade !== '') $qs .= "&grade=".htmlspecialchars($view_grade);
    for($i=0; $i<$view_total; $i+=$page_size):
      $active = $start_offset == $i;
    ?>
    <a href="ranklist.php?start=<?php echo $i.$qs?>" <?php if($active) echo 'class="pg-active"'?>>
      <?php echo ($i+1)."-".($i+$page_size)?>
    </a>
    <?php endfor;?>
  </div>
  </div><!-- /tab-individual -->

  <div id="tab-classrank" style="display:none">
    <?php if(!empty($view_class_rank)):
      $cr_top3 = array_slice($view_class_rank, 0, 3);
      $cr_rest = $view_class_rank;
      $cr_emojis = ['🏆','🥈','🥉'];
    ?>

    <!-- 시상대 (상위 3반) -->
    <?php if(count($cr_top3) >= 1): ?>
    <div class="cr-podium-section">
      <div class="cr-podium-title"><?php echo $view_grade !== '' ? $view_grade.'학년 ' : ''?>CLASS RANKING</div>
      <div class="cr-podium">
        <?php
          $cr_order = [1,0,2]; // 2등, 1등, 3등
          foreach($cr_order as $ci):
            if(!isset($cr_top3[$ci])) continue;
            $cr = $cr_top3[$ci];
            $pi = $ci + 1;
        ?>
        <div class="cr-podium-item cr-p<?php echo $pi?>">
          <div class="cr-podium-emoji"><?php echo $cr_emojis[$ci]?></div>
          <div class="cr-podium-box">
            <div class="cr-podium-label"><?php echo htmlspecialchars($cr['label'])?></div>
            <div class="cr-podium-meta"><?php echo $cr['members']?>명 · <?php echo $cr['submit']?>도전</div>
            <div class="cr-podium-score"><?php echo $cr['solved']?></div>
            <div class="cr-podium-unit">SOLVED</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- 전체 리스트 -->
    <div class="cr-list">
      <?php $ci = 1; foreach($cr_rest as $cr):
        $r = $cr['rate'];
        if ($r >= 70) { $barGrad = '#86efac,#22c55e'; $rateColor = '#16a34a'; }
        elseif ($r >= 40) { $barGrad = '#fde68a,#f59e0b'; $rateColor = '#d97706'; }
        elseif ($r > 0)   { $barGrad = '#fca5a5,#ef4444'; $rateColor = '#dc2626'; }
        else              { $barGrad = '#c4b5fd,#7c3aed'; $rateColor = '#7c3aed'; }
      ?>
      <a class="cr-item" href="ranklist.php?school=<?php echo urlencode($cr['school'])?>">
        <div class="cr-item-inner">
          <div class="cr-item-rank">
            <?php if($ci<=3) echo $cr_emojis[$ci-1]; else echo $ci; ?>
          </div>
          <div class="cr-item-name"><?php echo htmlspecialchars($cr['label'])?></div>
          <span class="cr-item-members"><?php echo $cr['members']?>명</span>
          <div class="cr-item-stats">
            <div class="cr-stat-block">
              <div class="num"><?php echo $cr['solved']?></div>
              <div class="label">총 해결</div>
            </div>
            <div class="cr-stat-block">
              <div class="num"><?php echo $cr['avg_solved']?></div>
              <div class="label">1인당 평균</div>
            </div>
            <div class="cr-rate-block">
              <div class="cr-rate-num" style="color:<?php echo $rateColor?>"><?php echo number_format($r,1)?>%</div>
              <div class="cr-rate-bar"><div class="cr-rate-bar-fill" style="width:<?php echo max(min($r,100),2)?>%;background:linear-gradient(90deg,<?php echo $barGrad?>)"></div></div>
            </div>
          </div>
        </div>
      </a>
      <?php $ci++; endforeach; ?>
    </div>

    <?php else: ?>
    <div style="text-align:center;padding:60px 20px;color:#9ca3af;font-size:15px;">반별 데이터가 없습니다.</div>
    <?php endif; ?>
  </div><!-- /tab-classrank -->

</div>
<script>
function showTab(tab) {
  document.getElementById('tab-individual').style.display = tab === 'individual' ? '' : 'none';
  document.getElementById('tab-classrank').style.display = tab === 'classrank' ? '' : 'none';
  var tabs = document.querySelectorAll('.rl-mode-tab');
  tabs[0].className = 'rl-mode-tab' + (tab === 'individual' ? ' active' : '');
  tabs[1].className = 'rl-mode-tab' + (tab === 'classrank' ? ' active' : '');
}
</script>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
