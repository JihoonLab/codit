<?php
session_start();
$ADMIN_PW = 'cjh122!';

// 로그아웃
if (isset($_GET['logout'])) {
    unset($_SESSION['supply_admin']);
    header('Location: admin.php');
    exit;
}

// 로그인 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pw'])) {
    if ($_POST['pw'] === $ADMIN_PW) {
        $_SESSION['supply_admin'] = true;
    } else {
        $login_error = true;
    }
}

// 로그인 안 됐으면 로그인 폼
if (!isset($_SESSION['supply_admin'])) {
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>관리자 로그인</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Pretendard', -apple-system, 'Malgun Gothic', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
.login-box { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 340px; text-align: center; }
.login-box h2 { margin-bottom: 24px; color: #1a1a2e; }
.login-box input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; margin-bottom: 12px; }
.login-box input:focus { outline: none; border-color: #7c3aed; }
.login-box button { width: 100%; padding: 12px; background: #7c3aed; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; }
.login-box button:hover { background: #6d28d9; }
.error { color: #dc3545; font-size: 0.85rem; margin-bottom: 12px; }
</style>
</head>
<body>
<div class="login-box">
    <h2>관리자 인증</h2>
    <?php if (isset($login_error)): ?><div class="error">비밀번호가 틀렸습니다.</div><?php endif; ?>
    <form method="post">
        <input type="password" name="pw" placeholder="비밀번호" autofocus required>
        <button type="submit">확인</button>
    </form>
</div>
</body>
</html>
<?php
    exit;
}

// 관리자 페이지
$db = new PDO('mysql:host=localhost;dbname=jol;charset=utf8mb4', 'hustoj', 'eqAHtdiYFUvCWbABBMqurEIKezlZF0');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 엑셀 다운로드
if (isset($_GET['export'])) {
    $rows = $db->query("SELECT * FROM supply_request ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="소모품_수요조사_' . date('Ymd') . '.xls"');
    echo "\xEF\xBB\xBF";
    echo "<table border='1'>";
    echo "<tr><th>No</th><th>성함</th><th>소모품</th><th>구매처</th><th>금액</th><th>목적</th><th>신청일</th></tr>";
    $total_e = 0;
    foreach ($rows as $i => $r) {
        $total_e += $r['price'];
        echo "<tr>";
        echo "<td>" . ($i + 1) . "</td>";
        echo "<td>" . htmlspecialchars($r['name']) . "</td>";
        echo "<td>" . htmlspecialchars($r['item']) . "</td>";
        echo "<td>" . htmlspecialchars($r['purchase_url']) . "</td>";
        echo "<td>" . number_format($r['price']) . "</td>";
        echo "<td>" . htmlspecialchars($r['purpose']) . "</td>";
        echo "<td>" . substr($r['created_at'], 0, 10) . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='4' style='text-align:right;font-weight:bold;'>합계</td><td style='font-weight:bold;'>" . number_format($total_e) . "</td><td colspan='2'></td></tr>";
    echo "</table>";
    exit;
}

// 삭제 처리
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $db->prepare("DELETE FROM supply_request WHERE id=?")->execute([$del_id]);
    header('Location: admin.php');
    exit;
}

$rows = $db->query("SELECT * FROM supply_request ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
foreach ($rows as $r) $total += $r['price'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>소모품 수요조사 - 관리자</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Pretendard', -apple-system, 'Malgun Gothic', sans-serif; background: #f5f5f5; color: #333; }

.header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #fff; padding: 24px 20px; display: flex; justify-content: space-between; align-items: center;
}
.header h1 { font-size: 1.3rem; }
.header .actions { display: flex; gap: 10px; }
.header a { color: #fff; text-decoration: none; background: rgba(255,255,255,0.15); padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; }
.header a:hover { background: rgba(255,255,255,0.25); }

.container { max-width: 1100px; margin: 0 auto; padding: 20px; }

.summary {
    display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
}
.summary .stat {
    background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    flex: 1; min-width: 150px; text-align: center;
}
.stat .num { font-size: 1.8rem; font-weight: 700; color: #7c3aed; }
.stat .label { font-size: 0.85rem; color: #888; margin-top: 4px; }

.card {
    background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 24px; margin-bottom: 24px;
}

table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
th { background: #7c3aed; color: #fff; padding: 10px 8px; text-align: center; white-space: nowrap; }
td { padding: 10px 8px; border-bottom: 1px solid #eee; text-align: center; }
tr:hover { background: #f8f5ff; }
tr.checked-row { background: #f0ebff; }
td.left { text-align: left; }
td a { color: #7c3aed; text-decoration: none; }
td a:hover { text-decoration: underline; }
.del-btn { color: #dc3545; cursor: pointer; font-size: 0.8rem; }
.del-btn:hover { text-decoration: underline; }
.total-row { font-weight: 700; background: #f0ebff !important; }
.empty { text-align: center; color: #999; padding: 30px; }
.date { font-size: 0.75rem; color: #999; }

/* 체크박스 */
input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: #7c3aed; }

/* 품의요청 바 */
.action-bar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px; flex-wrap: wrap; gap: 10px;
}
.action-bar .left-actions { display: flex; gap: 10px; align-items: center; }
.action-bar .selected-info { font-size: 0.9rem; color: #666; }
.action-bar .selected-info strong { color: #7c3aed; }
.btn-request {
    background: #2563eb; color: #fff; border: none; border-radius: 8px;
    padding: 10px 24px; font-size: 0.9rem; font-weight: 600; cursor: pointer;
    transition: all 0.15s; display: none;
}
.btn-request:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
.btn-select-all {
    background: #f3f4f6; color: #333; border: 1px solid #ddd; border-radius: 6px;
    padding: 8px 14px; font-size: 0.8rem; cursor: pointer;
}
.btn-select-all:hover { background: #e5e7eb; }

/* 품의요청서 모달 */
.modal-overlay {
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;
}
.modal-overlay.active { display: flex; }
.modal {
    background: #fff; border-radius: 12px; max-width: 800px; width: 95%;
    max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.modal-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 24px; border-bottom: 1px solid #eee;
}
.modal-header h2 { font-size: 1.2rem; color: #1a1a2e; }
.modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999; }
.modal-close:hover { color: #333; }
.modal-body { padding: 24px; }
.modal-body table { margin-top: 12px; }
.modal-body table th { background: #1a1a2e; font-size: 0.8rem; }
.modal-footer {
    padding: 16px 24px; border-top: 1px solid #eee;
    display: flex; justify-content: flex-end; gap: 10px;
}
.btn-print {
    background: #7c3aed; color: #fff; border: none; border-radius: 8px;
    padding: 10px 20px; font-size: 0.9rem; font-weight: 600; cursor: pointer;
}
.btn-print:hover { background: #6d28d9; }
.btn-cancel {
    background: #f3f4f6; color: #333; border: 1px solid #ddd; border-radius: 8px;
    padding: 10px 20px; font-size: 0.9rem; cursor: pointer;
}
.btn-cancel:hover { background: #e5e7eb; }
.doc-title { text-align: center; font-size: 1.4rem; font-weight: 700; margin-bottom: 8px; }
.doc-meta { text-align: center; color: #666; font-size: 0.85rem; margin-bottom: 20px; }

@media print {
    body * { visibility: hidden; }
    .modal, .modal * { visibility: visible; }
    .modal { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; }
    .modal-header, .modal-footer { display: none !important; }
}

@media (max-width: 600px) {
    .header { flex-direction: column; gap: 10px; text-align: center; }
    table { font-size: 0.75rem; }
    th, td { padding: 8px 4px; }
}
</style>
</head>
<body>

<div class="header">
    <h1>소모품 수요조사 관리</h1>
    <div class="actions">
        <a href="admin.php?export=1">엑셀 다운로드</a>
        <a href="index.php">신청 페이지</a>
        <a href="admin.php?logout=1">로그아웃</a>
    </div>
</div>

<div class="container">

<div class="summary">
    <div class="stat"><div class="num"><?= count($rows) ?></div><div class="label">총 신청 건수</div></div>
    <div class="stat"><div class="num"><?= number_format($total) ?>원</div><div class="label">총 금액</div></div>
</div>

<div class="card">
    <?php if (empty($rows)): ?>
        <div class="empty">아직 신청 내역이 없습니다.</div>
    <?php else: ?>

    <div class="action-bar">
        <div class="left-actions">
            <button class="btn-select-all" onclick="toggleAll()">전체 선택</button>
            <span class="selected-info">선택: <strong id="selCount">0</strong>건 / <strong id="selTotal">0</strong>원</span>
        </div>
        <button class="btn-request" id="btnRequest" onclick="openRequest()">품의요청서 생성</button>
    </div>

    <div style="overflow-x:auto;">
    <table>
        <tr>
            <th style="width:36px;"><input type="checkbox" id="chkAll" onchange="toggleAll()"></th>
            <th>No</th>
            <th>성함</th>
            <th>소모품</th>
            <th>구매처</th>
            <th>금액</th>
            <th>목적</th>
            <th>신청일</th>
            <th>삭제</th>
        </tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr id="row-<?= $r['id'] ?>">
            <td><input type="checkbox" class="chk-item" value="<?= $r['id'] ?>" data-name="<?= htmlspecialchars($r['name']) ?>" data-item="<?= htmlspecialchars($r['item']) ?>" data-url="<?= htmlspecialchars($r['purchase_url']) ?>" data-price="<?= $r['price'] ?>" data-purpose="<?= htmlspecialchars($r['purpose']) ?>" data-date="<?= substr($r['created_at'], 0, 10) ?>" onchange="updateSelection()"></td>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td class="left"><?= htmlspecialchars($r['item']) ?></td>
            <td><?php if ($r['purchase_url']): ?><a href="<?= htmlspecialchars($r['purchase_url']) ?>" target="_blank">링크</a><?php else: ?>-<?php endif; ?></td>
            <td><?= number_format($r['price']) ?>원</td>
            <td><?= htmlspecialchars($r['purpose']) ?></td>
            <td class="date"><?= substr($r['created_at'], 0, 10) ?></td>
            <td><a class="del-btn" href="admin.php?del=<?= $r['id'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td></td>
            <td colspan="4" style="text-align:right;">합계</td>
            <td><?= number_format($total) ?>원</td>
            <td colspan="3"></td>
        </tr>
    </table>
    </div>
    <?php endif; ?>
</div>

</div>

<!-- 품의요청서 모달 -->
<div class="modal-overlay" id="modalOverlay">
<div class="modal">
    <div class="modal-header">
        <h2>품의요청서 미리보기</h2>
        <button class="modal-close" onclick="closeRequest()">&times;</button>
    </div>
    <div class="modal-body" id="modalBody"></div>
    <div class="modal-footer">
        <button class="btn-cancel" onclick="closeRequest()">닫기</button>
        <button class="btn-print" onclick="window.print()">인쇄하기</button>
    </div>
</div>
</div>

<script>
function updateSelection() {
    var checks = document.querySelectorAll('.chk-item:checked');
    var count = checks.length;
    var total = 0;
    checks.forEach(function(c) { total += parseInt(c.dataset.price); });

    document.getElementById('selCount').textContent = count;
    document.getElementById('selTotal').textContent = total.toLocaleString();
    document.getElementById('btnRequest').style.display = count > 0 ? 'inline-block' : 'none';

    // 행 하이라이트
    document.querySelectorAll('.chk-item').forEach(function(c) {
        c.closest('tr').classList.toggle('checked-row', c.checked);
    });

    // 전체선택 체크박스 상태
    var all = document.querySelectorAll('.chk-item');
    document.getElementById('chkAll').checked = (count === all.length && count > 0);
}

function toggleAll() {
    var all = document.querySelectorAll('.chk-item');
    var chkAll = document.getElementById('chkAll');
    var anyUnchecked = Array.from(all).some(function(c) { return !c.checked; });
    all.forEach(function(c) { c.checked = anyUnchecked; });
    chkAll.checked = anyUnchecked;
    updateSelection();
}

function openRequest() {
    var checks = document.querySelectorAll('.chk-item:checked');
    if (checks.length === 0) return;

    var total = 0;
    var rows = '';
    var idx = 0;
    checks.forEach(function(c) {
        idx++;
        var price = parseInt(c.dataset.price);
        total += price;
        rows += '<tr>'
            + '<td>' + idx + '</td>'
            + '<td>' + c.dataset.name + '</td>'
            + '<td class="left">' + c.dataset.item + '</td>'
            + '<td>' + (c.dataset.url ? '<a href="' + c.dataset.url + '" target="_blank" style="color:#2563eb;">링크</a>' : '-') + '</td>'
            + '<td>' + price.toLocaleString() + '원</td>'
            + '<td>' + c.dataset.purpose + '</td>'
            + '</tr>';
    });

    var today = new Date();
    var dateStr = today.getFullYear() + '.' + String(today.getMonth()+1).padStart(2,'0') + '.' + String(today.getDate()).padStart(2,'0');

    var html = '<div class="doc-title">정보화기기 소모품 구매 품의요청서</div>'
        + '<div class="doc-meta">충주고등학교 교육정보부 | ' + dateStr + '</div>'
        + '<table>'
        + '<tr><th>No</th><th>신청자</th><th>소모품</th><th>구매처</th><th>금액</th><th>목적</th></tr>'
        + rows
        + '<tr class="total-row"><td colspan="4" style="text-align:right;">합계</td><td>' + total.toLocaleString() + '원</td><td></td></tr>'
        + '</table>'
        + '<div style="margin-top:24px;font-size:0.85rem;color:#666;">총 <b>' + checks.length + '건</b>, 합계 <b>' + total.toLocaleString() + '원</b></div>';

    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('modalOverlay').classList.add('active');
}

function closeRequest() {
    document.getElementById('modalOverlay').classList.remove('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRequest();
});
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeRequest();
});
</script>

</div>
</body>
</html>
