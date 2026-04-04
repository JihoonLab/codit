<?php
$db = new PDO('mysql:host=localhost;dbname=jol;charset=utf8mb4', 'hustoj', 'eqAHtdiYFUvCWbABBMqurEIKezlZF0');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$deadline = strtotime('2026-03-31 17:00:00');
$is_closed = time() >= $deadline;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && !$is_closed) {
    $name = trim($_POST['name']);
    $item = trim($_POST['item']);
    $url  = trim($_POST['purchase_url']);
    $price = intval($_POST['price']);
    $purpose = $_POST['purpose'] === '수업용품' ? '수업용품' : '업무처리';

    if ($price > 50000) {
        $msg = 'over';
    } else if ($name === '' || $item === '') {
        $msg = 'error';
    } else {
        $stmt = $db->prepare("INSERT INTO supply_request (name, item, purchase_url, price, purpose) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $item, $url, $price, $purpose]);
        $msg = 'success';
        $s_name = htmlspecialchars($name);
        $s_item = htmlspecialchars($item);
        $s_price = number_format($price);
        $s_purpose = htmlspecialchars($purpose);
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>교육용 소모품 수요조사</title>
<link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Pretendard', -apple-system, sans-serif; background: #f0f2f5; color: #333; min-height: 100vh; }

/* Header */
.header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff; padding: 48px 20px 40px; text-align: center;
    position: relative; overflow: hidden;
}
.header::after {
    content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 30px;
    background: #f0f2f5; border-radius: 50% 50% 0 0 / 100% 100% 0 0;
}
.header .badge {
    display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 14px;
    border-radius: 20px; font-size: 0.8rem; margin-bottom: 12px; letter-spacing: 0.5px;
}
.header h1 { font-size: 2rem; font-weight: 700; margin-bottom: 8px; }
.header p { font-size: 1.05rem; opacity: 0.85; }

.container { max-width: 720px; margin: -10px auto 0; padding: 0 16px 40px; position: relative; z-index: 1; }

/* Info Cards */
.info-section { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.info-card {
    flex: 1; min-width: 280px; background: #fff; border-radius: 12px; padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.info-card h3 { font-size: 1rem; color: #667eea; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
.info-card ul { list-style: none; padding: 0; }
.info-card li { font-size: 0.85rem; padding: 3px 0; color: #555; line-height: 1.5; }
.info-card li::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; margin-right: 8px; vertical-align: middle; }
.info-card.ok li::before { background: #22c55e; }
.info-card.no li::before { background: #ef4444; }

/* Notice */
.notice {
    background: linear-gradient(135deg, #fff9e6, #fff3cd); border-left: 4px solid #f59e0b;
    padding: 16px 20px; margin-bottom: 20px; border-radius: 0 12px 12px 0;
    font-size: 0.85rem; line-height: 1.7; color: #92400e;
}

/* Form Card */
.card {
    background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 28px; margin-bottom: 20px;
}
.card h2 {
    font-size: 1.2rem; margin-bottom: 20px; color: #1a1a2e;
    display: flex; align-items: center; gap: 8px;
}
.card h2::before { content: ''; width: 4px; height: 20px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 2px; }

.form-row { display: flex; gap: 14px; margin-bottom: 16px; flex-wrap: wrap; }
.form-group { display: flex; flex-direction: column; flex: 1; min-width: 150px; }
.form-group label { font-size: 0.8rem; font-weight: 600; margin-bottom: 6px; color: #555; }
.form-group label .req { color: #ef4444; }
.form-group input, .form-group select {
    padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem;
    transition: all 0.2s; background: #fafafa; font-family: inherit;
}
.form-group input:focus, .form-group select:focus {
    outline: none; border-color: #667eea; background: #fff;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
}
.form-group.full { flex: 1 1 100%; }

.btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff; border: none; padding: 14px 40px; border-radius: 10px;
    font-size: 1rem; cursor: pointer; font-weight: 600; font-family: inherit;
    transition: all 0.2s; box-shadow: 0 4px 15px rgba(102,126,234,0.3);
}
.btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(102,126,234,0.4); }
.btn:active { transform: translateY(0); }

/* Success/Error Messages */
.result-card {
    background: #fff; border-radius: 16px; padding: 32px; text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 20px;
}
.result-card.success { border-top: 4px solid #22c55e; }
.result-card.error { border-top: 4px solid #ef4444; }
.result-icon { font-size: 2.5rem; margin-bottom: 12px; }
.result-card h3 { font-size: 1.25rem; margin-bottom: 16px; }
.result-card .detail {
    background: #f8fafb; border-radius: 10px; padding: 16px; text-align: left;
    display: inline-block; min-width: 280px;
}
.result-card .detail .row { display: flex; padding: 6px 0; font-size: 0.9rem; }
.result-card .detail .label { color: #888; width: 80px; flex-shrink: 0; }
.result-card .detail .value { color: #333; font-weight: 500; }
.result-card .back-btn {
    display: inline-block; margin-top: 20px; color: #667eea; text-decoration: none;
    font-size: 0.9rem; font-weight: 600;
}
.result-card .back-btn:hover { text-decoration: underline; }

.footer { text-align: center; padding: 20px; font-size: 0.78rem; color: #aaa; }

@media (max-width: 600px) {
    .header { padding: 36px 16px 32px; }
    .header h1 { font-size: 1.3rem; }
    .form-row { flex-direction: column; gap: 12px; }
    .form-group { min-width: 100%; }
    .container { padding: 0 12px 30px; }
    .card { padding: 20px; }
    .info-section { flex-direction: column; }
}
</style>
</head>
<body>

<div class="header">
    <div class="badge">2026 충주고등학교 교육정보부</div>
    <h1>정보화지원 교육용 소모품 수요조사</h1>
    <p>업무 및 수업에 필요한 정보화기기 소모품을 신청해주세요</p>
</div>

<div class="container">

<?php if ($msg === 'success'): ?>
<div class="result-card success">
    <div class="result-icon">&#10004;&#65039;</div>
    <h3>신청이 완료되었습니다!</h3>
    <div class="detail">
        <div class="row"><span class="label">성함</span><span class="value"><?= $s_name ?></span></div>
        <div class="row"><span class="label">소모품</span><span class="value"><?= $s_item ?></span></div>
        <div class="row"><span class="label">금액</span><span class="value"><?= $s_price ?>원</span></div>
        <div class="row"><span class="label">목적</span><span class="value"><?= $s_purpose ?></span></div>
    </div>
    <br>
    <a class="back-btn" href="index.php">추가 신청하기 &rarr;</a>
</div>
<?php endif; ?>

<?php if ($msg === 'error'): ?>
<div class="result-card error">
    <div class="result-icon">&#9888;&#65039;</div>
    <h3>성함과 소모품명은 필수입니다.</h3>
    <a class="back-btn" href="index.php">다시 작성하기 &rarr;</a>
</div>
<?php endif; ?>

<?php if ($msg === 'over'): ?>
<div class="result-card error">
    <div class="result-icon">&#9888;&#65039;</div>
    <h3>1건당 최대 50,000원까지 신청 가능합니다.</h3>
    <a class="back-btn" href="index.php">다시 작성하기 &rarr;</a>
</div>
<?php endif; ?>

<div class="info-section">
    <div class="info-card ok">
        <h3>&#128204; 신청 가능 품목</h3>
        <ul>
            <li>모니터 보안필름, 받침대</li>
            <li>USB 허브, 랜포트, 도킹스테이션</li>
            <li>마우스, 키보드</li>
            <li>멀티탭, 충전 케이블</li>
            <li>노트북 거치대, 파우치</li>
            <li>HDMI / DP 변환 어댑터</li>
            <li>웹캠</li>
        </ul>
    </div>
    <div class="info-card no">
        <h3>&#128683; 신청 불가 품목</h3>
        <ul>
            <li>USB 메모리, 외장하드 (보안)</li>
            <li>모니터, PC, 노트북 (자산)</li>
            <li>스마트폰, 태블릿 (자산)</li>
            <li>소프트웨어 라이선스 (별도 절차)</li>
        </ul>
    </div>
</div>

<div class="notice">
    <strong style="font-size:1rem; color:#b91c1c;">&#9888; 신청기한: 3/30(월) ~ 3/31(화) 17:00까지</strong><br>
    <strong style="font-size:1rem; color:#b91c1c;">&#9888; 1인당 신청 금액은 최대 50,000원(배송비 포함)입니다.</strong><br><br>
    <strong style="font-size:1rem; color:#b91c1c;">&#9888; 구매처는 쿠팡, 지마켓, 11번가만 가능합니다. (네이버 불가)</strong><br><br>
    ※ 예산이 한정적이기에 모든 물품을 구매할 수는 없습니다.<br>
    ※ 위 신청 가능 품목 외 문의사항이 있으시면 쪽지 또는 내선(<b>741</b>)으로 문의해주세요.
</div>

<?php if ($is_closed): ?>
<div class="result-card error">
    <div class="result-icon">&#128683;</div>
    <h3>신청이 마감되었습니다.</h3>
    <p style="color:#888; font-size:0.9rem;">마감일: 3/31(화) 17:00</p>
</div>
<?php elseif ($msg !== 'success'): ?>
<div class="card">
    <h2>소모품 신청</h2>
    <form method="post">
        <div class="form-row">
            <div class="form-group">
                <label>성함 <span class="req">*</span></label>
                <input type="text" name="name" required placeholder="박지훈">
            </div>
            <div class="form-group">
                <label>금액 (배송비 포함)</label>
                <input type="number" name="price" placeholder="32500" min="0" max="50000">
            </div>
            <div class="form-group">
                <label>사용 목적</label>
                <select name="purpose">
                    <option value="업무처리">업무처리</option>
                    <option value="수업용품">수업용품</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group full">
                <label>필요하신 정보화기기 소모품 <span class="req">*</span></label>
                <input type="text" name="item" required placeholder="ex) 아이리버 버티컬 무선 마우스">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group full">
                <label>구매처 (URL) <span class="req">*</span></label>
                <input type="url" name="purchase_url" required placeholder="https://www.coupang.com/vp/products/4543878205?itemId=5504186376&vendorItemId=72803777586">
            </div>
        </div>
        <div style="text-align:center; margin-top:12px;">
            <button type="submit" class="btn">신청하기</button>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="footer"><a href="admin.php" style="color:#aaa; text-decoration:none;">충주고등학교 교육정보부 박지훈</a></div>

</div>
</body>
</html>
