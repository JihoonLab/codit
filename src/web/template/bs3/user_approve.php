<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>사용자 승인 관리 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
*{box-sizing:border-box}
body{font-family:'Noto Sans KR',sans-serif;background:#f4f6f9;margin:0;color:#333}

.ua-wrap{max-width:1200px;margin:36px auto;padding:0 16px}
.ua-header{background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border-radius:14px;padding:32px;margin-bottom:28px;box-shadow:0 4px 20px rgba(124,58,237,.25)}
.ua-header h1{font-size:24px;font-weight:800;margin:0 0 6px}
.ua-header p{opacity:.85;font-size:14px;margin:0}
.ua-badge{display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.25);color:#fff;font-size:14px;font-weight:700;padding:4px 14px;border-radius:20px;margin-left:12px}

.ua-alert{border-radius:10px;padding:14px 20px;margin-bottom:20px;font-size:14px;font-weight:500}
.ua-alert-success{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
.ua-alert-danger{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.ua-alert-warning{background:#fef3c7;color:#92400e;border:1px solid #fcd34d}

.ua-toolbar{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center}
.ua-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:8px;border:none;font-size:14px;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit}
.ua-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.15)}
.ua-btn-approve{background:#7c3aed;color:#fff}
.ua-btn-approve:hover{background:#6d28d9}
.ua-btn-reject{background:#ef4444;color:#fff}
.ua-btn-reject:hover{background:#dc2626}
.ua-btn-sm{padding:6px 14px;font-size:12px;border-radius:6px}
.ua-btn:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}

.ua-table-wrap{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid #e8f0fe;overflow:hidden}
.ua-table{width:100%;border-collapse:collapse}
.ua-table thead tr{background:#7c3aed;color:#fff}
.ua-table th{padding:12px 16px;font-size:13px;font-weight:600;text-align:center;white-space:nowrap}
.ua-table td{padding:14px 16px;font-size:14px;border-bottom:1px solid #f0f3f7;text-align:center}
.ua-table tbody tr{transition:background .1s}
.ua-table tbody tr:hover{background:#f5f0ff}
.ua-table tbody tr:last-child td{border-bottom:none}

.ua-table th:first-child,.ua-table td:first-child{width:40px;text-align:center}
.ua-table input[type="checkbox"]{width:18px;height:18px;accent-color:#7c3aed;cursor:pointer}

.ua-school{display:inline-block;background:#f3e8ff;color:#7c3aed;padding:2px 10px;border-radius:6px;font-size:13px;font-weight:600}
.ua-userid{font-weight:700;color:#333}
.ua-nick{color:#555}
.ua-email{color:#6b7280;font-size:13px}
.ua-ip{color:#9ca3af;font-size:12px;font-family:monospace}
.ua-time{color:#6b7280;font-size:13px;white-space:nowrap}

.ua-empty{text-align:center;padding:60px;color:#aaa;font-size:16px}
.ua-empty-icon{font-size:48px;margin-bottom:12px;display:block}

.ua-selected-count{font-size:14px;color:#6b7280;margin-left:auto}
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ua-wrap">
  <div class="ua-header">
    <h1>사용자 승인 관리<span class="ua-badge"><?php echo $pending_count?>명 대기중</span></h1>
    <p>회원가입 후 관리자 승인을 기다리는 사용자 목록입니다.</p>
  </div>

  <?php if ($message !== ''): ?>
  <div class="ua-alert ua-alert-<?php echo htmlspecialchars($msg_type)?>"><?php echo htmlspecialchars($message)?></div>
  <?php endif; ?>

  <?php if ($pending_count === 0): ?>
  <div class="ua-table-wrap">
    <div class="ua-empty">
      <span class="ua-empty-icon">&#10003;</span>
      승인 대기중인 사용자가 없습니다.
    </div>
  </div>
  <?php else: ?>

  <form id="approveForm" method="post" action="">
    <input type="hidden" name="action" id="formAction" value="">

    <div class="ua-toolbar">
      <button type="button" class="ua-btn ua-btn-approve" onclick="bulkAction('approve')">전체 승인</button>
      <button type="button" class="ua-btn ua-btn-reject" onclick="bulkAction('reject')">전체 거절</button>
      <span class="ua-selected-count" id="selectedCount">0명 선택됨</span>
    </div>

    <div class="ua-table-wrap">
      <table class="ua-table">
        <thead>
          <tr>
            <th><input type="checkbox" id="checkAll" title="전체 선택"></th>
            <th>아이디</th>
            <th>이름</th>
            <th>이메일</th>
            <th>학년/반</th>
            <th>가입일시</th>
            <th>IP</th>
            <th>관리</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pending_users as $user):
            $school_display = '';
            if (!empty($user['school'])) {
                $parts = explode('-', $user['school']);
                if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                    $school_display = $parts[0] . '학년 ' . $parts[1] . '반';
                } else {
                    $school_display = htmlspecialchars($user['school']);
                }
            }
          ?>
          <tr>
            <td><input type="checkbox" name="user_ids[]" value="<?php echo htmlspecialchars($user['user_id'])?>" class="user-check"></td>
            <td><span class="ua-userid"><?php echo htmlspecialchars($user['user_id'])?></span></td>
            <td><span class="ua-nick"><?php echo htmlspecialchars($user['nick'] ?: '-')?></span></td>
            <td><span class="ua-email"><?php echo htmlspecialchars($user['email'] ?: '-')?></span></td>
            <td><?php if ($school_display): ?><span class="ua-school"><?php echo $school_display?></span><?php else: ?>-<?php endif; ?></td>
            <td><span class="ua-time"><?php echo htmlspecialchars($user['reg_time'] ?: '-')?></span></td>
            <td><span class="ua-ip"><?php echo htmlspecialchars($user['ip'] ?: '-')?></span></td>
            <td>
              <button type="button" class="ua-btn ua-btn-approve ua-btn-sm" onclick="singleAction('approve','<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES)?>')">승인</button>
              <button type="button" class="ua-btn ua-btn-reject ua-btn-sm" onclick="singleAction('reject','<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES)?>')">거절</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </form>
  <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var checkAll = document.getElementById('checkAll');
  var checkboxes = document.querySelectorAll('.user-check');
  var countEl = document.getElementById('selectedCount');

  function updateCount() {
    var checked = document.querySelectorAll('.user-check:checked').length;
    if (countEl) countEl.textContent = checked + '명 선택됨';
  }

  if (checkAll) {
    checkAll.addEventListener('change', function() {
      checkboxes.forEach(function(cb) { cb.checked = checkAll.checked; });
      updateCount();
    });
  }

  checkboxes.forEach(function(cb) {
    cb.addEventListener('change', function() {
      if (checkAll) checkAll.checked = document.querySelectorAll('.user-check:checked').length === checkboxes.length;
      updateCount();
    });
  });
});

function bulkAction(action) {
  var checked = document.querySelectorAll('.user-check:checked');
  if (checked.length === 0) {
    alert('사용자를 선택해주세요.');
    return;
  }
  var msg = action === 'approve'
    ? checked.length + '명의 사용자를 승인하시겠습니까?'
    : checked.length + '명의 사용자를 거절(삭제)하시겠습니까?\n\n이 작업은 되돌릴 수 없습니다.';
  if (!confirm(msg)) return;
  document.getElementById('formAction').value = action;
  document.getElementById('approveForm').submit();
}

function singleAction(action, userId) {
  if (action === 'reject') {
    if (!confirm('\'' + userId + '\' 사용자를 거절(삭제)하시겠습니까?\n\n이 작업은 되돌릴 수 없습니다.')) return;
  }
  // Uncheck all, then check only the target
  document.querySelectorAll('.user-check').forEach(function(cb) { cb.checked = false; });
  var target = document.querySelector('.user-check[value="' + userId + '"]');
  if (target) target.checked = true;
  document.getElementById('formAction').value = action;
  document.getElementById('approveForm').submit();
}
</script>
</body>
</html>
