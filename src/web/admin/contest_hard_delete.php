<?php
/**
 * [2026-04-21] 대회 영구 삭제 (매우 위험)
 * - 관련된 모든 데이터를 DB에서 완전히 제거
 * - solution, source_code, privilege 등 cascade 삭제
 * - 반드시 confirm 파라미터로 이중 확인 필요
 */
require_once("admin-header.php");
require_once("../include/check_get_key.php");

$cid = intval($_GET['cid']);
$confirm = isset($_GET['confirm']) ? trim($_GET['confirm']) : '';

// 관리자 또는 대회 매니저만 가능
if (!(isset($_SESSION[$OJ_NAME.'_'."m$cid"])
    || isset($_SESSION[$OJ_NAME.'_'.'administrator'])
    || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))) {
    echo "권한이 없습니다.";
    exit();
}

// 대회 존재 확인
$row = pdo_query("SELECT title, contest_id FROM contest WHERE contest_id=?", $cid);
if (empty($row)) {
    echo "<h3>존재하지 않는 대회입니다.</h3>";
    exit(0);
}
$title = $row[0]["title"];

// 통계 (삭제 전 보여줄 영향 범위)
$st_sol = pdo_query("SELECT COUNT(*) c, COUNT(DISTINCT user_id) u FROM solution WHERE contest_id=?", $cid);
$sol_cnt = intval($st_sol[0]["c"]);
$usr_cnt = intval($st_sol[0]["u"]);
$prob_cnt = intval(pdo_query("SELECT COUNT(*) c FROM contest_problem WHERE contest_id=?", $cid)[0]["c"]);

// 1차 확인 화면
if ($confirm !== ("DELETE-$cid")) {
    ?>
    <!DOCTYPE html>
    <html lang="ko"><head><meta charset="utf-8"><title>대회 영구 삭제 확인</title>
    <style>
    body { font-family: "Noto Sans KR", sans-serif; background: #f6f7fb; margin: 0; padding: 40px 20px; }
    .del-wrap { max-width: 620px; margin: 20px auto; background: #fff; border-radius: 16px;
                box-shadow: 0 4px 20px rgba(220,38,38,.15); overflow: hidden; }
    .del-hdr { background: linear-gradient(135deg, #dc2626, #991b1b); color: #fff;
               padding: 24px 32px; font-size: 20px; font-weight: 800; }
    .del-body { padding: 28px 32px; }
    .del-stat { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px;
                padding: 16px 20px; margin-bottom: 20px; }
    .del-stat strong { color: #dc2626; font-size: 18px; font-weight: 900; }
    .del-title { font-size: 17px; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
    .del-caution { color: #b91c1c; font-weight: 700; margin: 16px 0; line-height: 1.6; }
    .del-input-wrap { margin: 20px 0; }
    .del-input-wrap label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; }
    .del-input-wrap input { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb;
                            border-radius: 10px; font-size: 15px; font-family: monospace;
                            font-weight: 700; transition: border-color .15s; }
    .del-input-wrap input:focus { outline: none; border-color: #dc2626; }
    .del-actions { display: flex; gap: 10px; margin-top: 24px; }
    .del-btn { padding: 12px 24px; border: none; border-radius: 10px; font-size: 14px;
               font-weight: 800; cursor: pointer; flex: 1; transition: all .15s; }
    .del-btn-cancel { background: #f1f5f9; color: #475569; }
    .del-btn-cancel:hover { background: #e2e8f0; }
    .del-btn-delete { background: #dc2626; color: #fff; }
    .del-btn-delete:hover { background: #991b1b; }
    .del-btn-delete:disabled { background: #fca5a5; cursor: not-allowed; }
    </style></head>
    <body>
    <div class="del-wrap">
      <div class="del-hdr">⚠️ 대회 영구 삭제 확인</div>
      <div class="del-body">
        <div class="del-title"><?php echo htmlspecialchars($title); ?> (ID: <?php echo $cid; ?>)</div>

        <div class="del-stat">
          이 대회를 삭제하면 다음 데이터가 <strong>영구 소멸</strong>됩니다:
          <ul style="margin: 10px 0 0 20px; line-height: 1.9;">
            <li>학생 제출 <strong><?php echo $sol_cnt; ?>건</strong> (참가자 <?php echo $usr_cnt; ?>명)</li>
            <li>문제 매핑 <strong><?php echo $prob_cnt; ?>건</strong></li>
            <li>관련 소스코드, 실행 로그, 컴파일 에러 전부</li>
            <li>관리자 권한(m<?php echo $cid; ?>), 접속 기록</li>
          </ul>
        </div>

        <p class="del-caution">
          🚨 이 작업은 <strong>되돌릴 수 없습니다.</strong><br>
          단순히 대회를 숨기고 싶다면 <strong>"공개 ↔ 숨김 토글"</strong>을 사용하세요.<br>
          (그건 데이터를 보존합니다.)
        </p>

        <form method="get" action="contest_hard_delete.php" onsubmit="return validateDelete();">
          <input type="hidden" name="cid" value="<?php echo $cid; ?>">
          <input type="hidden" name="getkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'getkey']; ?>">

          <div class="del-input-wrap">
            <label>삭제를 확정하려면 아래 칸에 <code style="background:#fee;padding:2px 6px;border-radius:4px">DELETE-<?php echo $cid; ?></code> 를 정확히 입력하세요</label>
            <input type="text" name="confirm" id="confirm-input" autocomplete="off"
                   placeholder="DELETE-<?php echo $cid; ?>" required>
          </div>

          <div class="del-actions">
            <button type="button" class="del-btn del-btn-cancel" onclick="history.back()">← 취소</button>
            <button type="submit" class="del-btn del-btn-delete" id="del-submit-btn">🗑️ 영구 삭제</button>
          </div>
        </form>
      </div>
    </div>
    <script>
    function validateDelete() {
      var input = document.getElementById("confirm-input").value.trim();
      if (input !== "DELETE-<?php echo $cid; ?>") {
        alert("확인 문구가 일치하지 않습니다.");
        return false;
      }
      return confirm("정말로 \"<?php echo addslashes($title); ?>\"을(를) 영구 삭제합니다.\n\n이 작업은 되돌릴 수 없습니다. 계속하시겠습니까?");
    }
    </script>
    </body></html>
    <?php
    exit(0);
}

// ════════════════════════════════════════════════
// 실제 삭제 로직 (confirm 검증 통과 후)
// ════════════════════════════════════════════════

// 1. 대회 관련 solutions + cascade
$sol_rows = pdo_query("SELECT solution_id FROM solution WHERE contest_id=?", $cid);
if (!empty($sol_rows)) {
    $sol_ids = array_column($sol_rows, 'solution_id');
    $sol_ids_str = implode(',', array_map('intval', $sol_ids));
    pdo_query("DELETE FROM source_code WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM source_code_user WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM runtimeinfo WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM compileinfo WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM sim WHERE s_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM solution_ai_answer WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM custominput WHERE solution_id IN ($sol_ids_str)");
    pdo_query("DELETE FROM solution WHERE contest_id=?", $cid);
}

// 2. 대회 관련 테이블
pdo_query("DELETE FROM contest_problem WHERE contest_id=?", $cid);
pdo_query("DELETE FROM balloon WHERE cid=?", $cid);
pdo_query("DELETE FROM privilege WHERE rightstr=?", "m$cid");
pdo_query("DELETE FROM privilege WHERE rightstr=?", "c$cid");
pdo_query("DELETE FROM loginlog WHERE password=?", "c$cid");
pdo_query("DELETE FROM contest WHERE contest_id=?", $cid);

// 성공 후 contest_list로 돌아가기
?>
<!DOCTYPE html>
<html lang="ko"><head><meta charset="utf-8"><title>삭제 완료</title>
<style>
body { font-family: "Noto Sans KR", sans-serif; background: #f6f7fb; text-align: center; padding: 80px 20px; }
.done { max-width: 420px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 16px;
         box-shadow: 0 4px 20px rgba(0,0,0,.08); }
.done h2 { color: #16a34a; margin: 0 0 12px; font-size: 22px; }
.done p { color: #64748b; font-size: 14px; margin: 0 0 24px; }
.done a { display: inline-block; padding: 10px 24px; background: #7c3aed; color: #fff;
          text-decoration: none; border-radius: 8px; font-weight: 700; }
</style></head>
<body>
<div class="done">
  <h2>✓ 대회 삭제 완료</h2>
  <p><?php echo htmlspecialchars($title); ?>이(가) 영구 삭제되었습니다.</p>
  <a href="contest_list.php">대회 목록으로</a>
</div>
</body></html>
