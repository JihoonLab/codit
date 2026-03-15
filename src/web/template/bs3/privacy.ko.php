<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>개인정보처리방침 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap');
    body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; }
    .privacy-wrap { max-width: 900px; margin: 0 auto 40px; padding: 0 16px; }
    .privacy-header { text-align: center; padding: 36px 0 12px; }
    .privacy-header h1 { font-size: 28px; font-weight: 900; color: #1a1a2e; margin: 0 0 8px; }
    .privacy-header p { color: #888; font-size: 14px; margin: 0; }
    .privacy-intro { background: #f0f5ff; border: 1px solid #d0e0f5; border-radius: 10px; padding: 18px 24px; margin: 16px 0 20px; font-size: 14px; line-height: 1.8; color: #334155; }
    .privacy-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 16px; }
    .privacy-section { border-bottom: 1px solid #f0f3f7; padding: 0; }
    .privacy-section:last-child { border-bottom: none; }
    .privacy-section-head { display: flex; align-items: center; gap: 10px; padding: 18px 24px; cursor: pointer; user-select: none; font-size: 15.5px; font-weight: 700; color: #1a1a2e; transition: background 0.15s; }
    .privacy-section-head:hover { background: #f8fafc; }
    .privacy-section-head .ps-num { display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px; padding: 0 6px; background: #7c3aed; color: #fff; border-radius: 8px; font-size: 12px; font-weight: 800; flex-shrink: 0; }
    .privacy-section-head .ps-arrow { margin-left: auto; color: #ccc; transition: transform 0.2s; font-size: 18px; }
    .privacy-section.open .ps-arrow { transform: rotate(180deg); }
    .privacy-section-body { display: none; padding: 0 24px 20px 62px; font-size: 14.5px; line-height: 1.9; color: #444; }
    .privacy-section.open .privacy-section-body { display: block; }
    .privacy-section-body ul { padding-left: 20px; margin: 8px 0; }
    .privacy-section-body li { margin-bottom: 4px; }
    .privacy-section-body strong { color: #1a1a2e; }
    .privacy-table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 13.5px; }
    .privacy-table th { background: #f5f8ff; padding: 10px 14px; text-align: left; font-weight: 700; color: #7c3aed; border: 1px solid #e5e9f0; }
    .privacy-table td { padding: 10px 14px; border: 1px solid #e5e9f0; color: #555; vertical-align: top; }
    .privacy-table td strong { color: #1a1a2e; }
    .privacy-contact { background: #f5f8ff; border-radius: 10px; padding: 20px 24px; margin: 20px 0 0; text-align: center; }
    .privacy-contact a { color: #7c3aed; font-weight: 700; }
    .cookie-box { background: #f8fafc; border: 1px solid #e5e9f0; border-radius: 8px; padding: 14px 18px; margin: 10px 0; font-size: 13px; line-height: 1.8; color: #555; }
    .cookie-box strong { color: #1a1a2e; }
    @media (max-width: 600px) {
      .privacy-wrap { padding: 0 8px; }
      .privacy-section-body { padding: 0 16px 16px 42px; }
      .privacy-table { font-size: 12px; }
      .privacy-table th, .privacy-table td { padding: 8px 8px; }
    }
  </style>
</head>
<body>
<div class="container">
  <?php include("template/$OJ_TEMPLATE/nav.php");?>

  <div class="privacy-wrap">
    <div class="privacy-header">
      <h1>🔒 개인정보처리방침</h1>
      <p>코딧(Codit) | 충주고등학교 정보교사 박지훈 | 시행일: 2026년 3월 1일</p>
    </div>

    <div class="privacy-intro">
      <strong>코딧(Codit)</strong>은 정보주체의 자유와 권리 보호를 위해 「개인정보 보호법」 및 관계 법령이 정한 바를 준수하여, 적법하게 개인정보를 처리하고 안전하게 관리하고 있습니다.
      이에 「개인정보 보호법」 제30조에 따라 정보주체에게 개인정보의 처리와 보호에 관한 절차 및 기준을 안내하고, 이와 관련한 고충을 신속하고 원활하게 처리할 수 있도록 하기 위하여 다음과 같이 개인정보 처리방침을 수립·공개합니다.
    </div>

    <div class="privacy-card">

      <!-- 제1조 -->
      <div class="privacy-section open">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제1조</span>
          개인정보의 처리 목적
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 다음의 목적을 위하여 개인정보를 처리합니다. 처리하고 있는 개인정보는 다음의 목적 외의 용도로는 이용되지 않으며, 이용 목적이 변경되는 경우에는 「개인정보 보호법」 제18조에 따라 별도의 동의를 받는 등 필요한 조치를 이행할 예정입니다.</p>
          <ul>
            <li><strong>1. 회원 가입 및 교수학습 관리</strong><br>회원의 가입의사 확인, 서비스 이용에 따른 본인 확인, 개인 식별 등의 목적으로 개인정보를 처리합니다.</li>
            <li><strong>2. 교수학습 자료 개선 및 학습 활동 분석</strong><br>교수학습 활동 내역을 누적하여 학습 활동을 분석하고 자료 개선의 목적으로 개인정보를 처리합니다.</li>
          </ul>
        </div>
      </div>

      <!-- 제2조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제2조</span>
          처리하는 개인정보의 항목
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit) 서비스는 학습활동 운영, 평가, 지도 등 교육 목적 달성 및 서비스 제공을 위해 필요한 최소한의 개인정보를 수집·이용하며, 「개인정보 보호법」 제15조 제1항 제1호에 따라 정보주체의 동의를 받아 개인정보를 처리합니다. 수집 항목은 다음과 같습니다.</p>
          <table class="privacy-table">
            <thead>
              <tr><th>구분</th><th>목적 (정보 구분)</th><th>수집 항목</th><th>수집 필요성</th><th>법적 근거</th></tr>
            </thead>
            <tbody>
              <tr>
                <td rowspan="4"><strong>서비스 운영</strong></td>
                <td>본인 식별·인증 (<strong>필수</strong>)</td>
                <td>일괄 생성한 사용자 ID</td>
                <td>회원 식별, 로그인 ID, 의사소통 경로 확보를 위해 필수</td>
                <td>정보주체의 동의 (개인정보보호법 제15조 제1항 제1호)</td>
              </tr>
              <tr>
                <td>본인 식별·인증 (<strong>필수</strong>)</td>
                <td>사용자가 입력한 비밀번호</td>
                <td>계정 보안 및 본인 인증을 위해 필수</td>
                <td>정보주체의 동의 및 안전성 확보조치 기준 제7조</td>
              </tr>
              <tr>
                <td>본인 식별·인증 (선택)</td>
                <td>소속/학교정보</td>
                <td>회원 식별 및 의사소통 경로 확인을 위해 필요</td>
                <td>정보주체의 동의</td>
              </tr>
              <tr>
                <td>본인 식별·인증 (선택)</td>
                <td>이메일 주소</td>
                <td>회원 식별, 로그인 ID, 의사소통 경로 확인을 위해 필요</td>
                <td>정보주체의 동의</td>
              </tr>
              <tr>
                <td rowspan="2"><strong>서비스 접근통제</strong></td>
                <td>접속기록 확인 (<strong>필수</strong>)</td>
                <td>사용자 ID별 접속 IP주소</td>
                <td>부정 이용 방지 및 보안 강화를 위해 필수</td>
                <td>개인정보의 안전성 확보조치 기준 제8조</td>
              </tr>
              <tr>
                <td>접속기록 확인 (<strong>필수</strong>)</td>
                <td>사용자 ID별 접속 시간</td>
                <td>서비스 이용 기록 관리 및 보안을 위해 필수</td>
                <td>개인정보의 안전성 확보조치 기준 제8조</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 제3조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제3조</span>
          14세 미만 아동의 개인정보 처리에 관한 사항
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 14세 미만 아동의 회원가입을 승인하지 않음으로써, 원칙적으로 <strong>14세 미만 아동의 개인정보를 수집·처리하지 않습니다.</strong></p>
        </div>
      </div>

      <!-- 제4조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제4조</span>
          개인정보의 처리 및 보유 기간
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 법령에 따른 개인정보 보유·이용 기간 또는 정보주체로부터 개인정보를 수집 시에 동의받은 개인정보 보유·이용 기간 내에서 개인정보를 처리·보유합니다.</p>
          <p>각각의 개인정보 처리 및 보유 기간은 다음과 같습니다.</p>
          <p><strong>1. 서비스 운영 관리 및 서비스 접근통제 수집 항목 일체: 1년 이내</strong></p>
          <p>(당해 연도 교육과정 운영일정 이내이며, 학년도에 생성된 데이터는 차년도 2월 28일 이내 파기합니다.)</p>
          <p>다만, 다음의 사유에 해당하는 경우에는 해당 사유 종료 시까지 보유합니다.</p>
          <ul>
            <li>관계 법령 위반에 따른 수사·조사 등이 진행 중인 경우에는 해당 수사·조사 종료 시까지</li>
            <li>교수학습 활동 자료가 학생의 상급학교 선발에 제공될 사유가 존재하는 경우에는 해당 선발 절차 종료 시까지</li>
          </ul>
        </div>
      </div>

      <!-- 제5조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제5조</span>
          개인정보의 제3자 제공
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 이용자의 개인정보를 <strong>원칙적으로 외부 제3자에게 제공하지 않습니다.</strong> 다만, 다음의 경우에는 예외로 합니다.</p>
          <ul>
            <li>이용자가 사전에 동의한 경우</li>
            <li>법령의 규정에 의거하거나, 수사 목적으로 법령에 정해진 절차와 방법에 따라 수사기관의 요구가 있는 경우</li>
          </ul>
        </div>
      </div>

      <!-- 제6조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제6조</span>
          개인정보의 파기 절차 및 방법
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 개인정보 보유기간의 경과, 처리목적 달성 등 개인정보가 불필요하게 되었을 때에는 지체 없이 해당 개인정보를 파기합니다.</p>
          <p>정보주체로부터 동의받은 개인정보 보유기간이 경과하거나 처리목적이 달성되었음에도 불구하고 다른 법령에 따라 개인정보를 계속 보존하여야 하는 경우에는, 해당 개인정보를 별도의 데이터베이스(DB)로 옮기거나 보관장소를 달리하여 보존합니다.</p>
          <p>개인정보 파기의 절차 및 방법은 다음과 같습니다.</p>
          <ul>
            <li><strong>1. 파기절차</strong> — 코딧(Codit) 서비스 관리자는 파기 사유가 발생한 개인정보를 선정하고 파기합니다.</li>
            <li><strong>2. 파기방법</strong> — 코딧(Codit) 서비스 관리자는 전자적 파일 형태로 기록·저장된 개인정보를 기록을 재생할 수 없도록 파기합니다.</li>
          </ul>
        </div>
      </div>

      <!-- 제7조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제7조</span>
          개인정보 처리업무의 위탁에 관한 사항
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit)은 원활한 업무처리를 위하여 다음과 같이 개인정보 처리 업무를 위탁하고 있습니다.</p>
          <table class="privacy-table">
            <thead>
              <tr><th>항목</th><th>내용</th></tr>
            </thead>
            <tbody>
              <tr><td><strong>위탁을 받는 자 (수탁자)</strong></td><td>Amazon Web Services Inc. (아시아 태평양 - 서울 리전)</td></tr>
              <tr><td><strong>위탁하는 업무의 내용</strong></td><td>클라우드 서비스를 이용한 코딧(Codit) 시스템 인프라 제공, 정보 적재 및 처리</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 제8조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제8조</span>
          개인정보의 국외 수집 및 이전에 관한 사항
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit) 서비스는 <strong>AWS(서울 리전)</strong>을 이용하여 개인정보를 국내에서 보관·처리하며, 개인정보를 국외로 이전하지 않습니다.</p>
        </div>
      </div>

      <!-- 제9조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제9조</span>
          개인정보의 안전성 확보조치에 관한 사항
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit) 서비스 관리자는 개인정보의 안전성 확보를 위해 다음과 같은 조치를 취하고 있습니다.</p>
          <ul>
            <li>1. 회원 가입한 학생을 대상으로 정기적 사용자 교육</li>
            <li>2. 서비스에 접근하는 사용자를 식별하여 필수 인원만 접근 허용</li>
            <li>3. 공개된 웹 경로에 접근주소(IP)를 게시하지 않음</li>
            <li>4. 접근권한을 최소한의 범위로 차등 부여</li>
            <li>5. 비밀번호 암호화</li>
            <li>6. 접속기록의 보관 및 점검</li>
            <li>7. 보안 서비스 설치 및 갱신, 서비스 취약점 점검 및 보완</li>
            <li>8. 서비스 제공 서버의 접근통제</li>
            <li>9. 재해·재난에 대한 안전조치</li>
          </ul>
        </div>
      </div>

      <!-- 제10조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제10조</span>
          개인정보 자동 수집 장치의 설치·운영 및 그 거부에 관한 사항
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p><strong>&lt;설치·운영하는 개인정보 자동 수집 장치&gt;</strong></p>
          <p>코딧(Codit) 서비스 관리자는 정보주체에게 개별적인 서비스와 편의를 제공하기 위해 이용정보를 저장하고 수시로 불러오는 '쿠키(cookie)'를 사용합니다. 쿠키는 웹사이트 운영에 이용되는 서버(HTTP)가 정보주체의 브라우저에 보내는 소량의 정보로서 컴퓨터 또는 모바일에 저장되며, 웹사이트 접속 시 자동으로 전송됩니다.</p>
          <p>정보주체는 브라우저 옵션 설정을 통해 쿠키 허용, 차단 등의 설정을 할 수 있습니다.</p>
          <div class="cookie-box">
            <strong>웹 브라우저에서 쿠키 허용/차단</strong><br>
            · <strong>Chrome</strong> — 우측 상단 ⋮ > 설정 > 개인정보 및 보안 > 서드 파티 쿠키<br>
            · <strong>Edge</strong> — 우측 상단 … > 설정 > 쿠키 및 사이트 권한<br><br>
            <strong>모바일 브라우저에서 쿠키 허용/차단</strong><br>
            · <strong>Chrome</strong> — 우측 상단 ⋮ > 설정 > 사이트 설정 > 쿠키<br>
            · <strong>Safari</strong> — 기기 설정 > Safari > 고급 > 모든 쿠키 차단
          </div>
          <p style="margin-top:12px"><strong>&lt;행태정보의 수집·이용·제공 및 거부 등에 관한 사항&gt;</strong></p>
          <p>코딧(Codit) 서비스 관리자는 개인의 권리·이익이나 사생활을 침해할 우려가 있는 민감한 행태정보를 수집하지 않습니다.</p>
        </div>
      </div>

      <!-- 제11조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제11조</span>
          정보주체와 법정대리인의 권리·의무 및 행사방법
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>정보주체는 코딧(Codit) 서비스 관리자에 대해 언제든지 개인정보 열람·전송·정정·삭제·처리정지 및 동의 철회 등을 요구(이하 "권리 행사"라 함)할 수 있습니다.</p>
          <p>※ 14세 미만 아동의 권리 행사는 법정대리인이 직접 해야 하며, 14세 이상의 미성년자인 정보주체는 미성년자 본인이 권리를 행사하거나 법정대리인을 통하여 행사할 수 있습니다.</p>
          <p>권리 행사는 코딧(Codit) 서비스 관리자에 대해 「개인정보 보호법 시행령」 제41조 제1항에 따라 서면, 전자우편 등을 통하여 하실 수 있으며, 코딧(Codit) 서비스 관리자는 이에 대해 <strong>지체 없이 조치</strong>하겠습니다.</p>
          <ul>
            <li>정보주체는 언제든지 서비스 내 설정에서 개인정보를 직접 조회·수정·삭제하거나 관리자를 통해 열람을 요청할 수 있습니다.</li>
          </ul>
          <p>권리 행사는 정보주체의 법정대리인이나 위임을 받은 자 등 대리인을 통하여 하실 수도 있습니다. 이 경우 "개인정보 처리 방법에 관한 고시" [별지 11] 서식에 따른 위임장을 제출해야 합니다.</p>
          <p>정보주체가 개인정보 열람 및 처리 정지를 요구할 권리는 「개인정보 보호법」 제35조 제4항 및 제37조 제2항에 의하여 제한될 수 있습니다. 또한, 다른 법령에서 그 개인정보가 수집 대상으로 명시되어 있는 경우에는 해당 개인정보의 삭제를 요구할 수 없습니다.</p>
          <p>코딧(Codit) 서비스 관리자는 권리 행사를 한 자가 본인이거나 정당한 대리인인지를 확인하며, 청구받은 날로부터 <strong>10일 이내</strong>에 회신하겠습니다.</p>
        </div>
      </div>

      <!-- 제12조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제12조</span>
          권익침해 구제방법
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>정보주체는 개인정보침해로 인한 구제를 받기 위하여 개인정보 분쟁조정위원회, 한국인터넷진흥원 개인정보 침해 신고센터 등에 분쟁해결이나 상담 등을 신청할 수 있습니다.</p>
          <table class="privacy-table">
            <thead>
              <tr><th>기관명</th><th>연락처</th><th>홈페이지</th></tr>
            </thead>
            <tbody>
              <tr><td>개인정보 분쟁조정위원회</td><td>(국번없이) 1833-6972</td><td>www.kopico.go.kr</td></tr>
              <tr><td>개인정보침해 신고센터</td><td>(국번없이) 118</td><td>privacy.kisa.or.kr</td></tr>
              <tr><td>경찰청</td><td>(국번없이) 182</td><td>ecrm.police.go.kr</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 제13조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제13조</span>
          추가적인 개인정보 보호 노력
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit) 서비스 관리자는 정보주체의 개인정보를 안전하게 관리하기 위하여 최선을 다하며, 개인정보 보호법에서 요구하는 안전성 확보 조치 외에도 추가적인 개인정보 보호 노력을 기울이고 있습니다.</p>
          <p>서비스를 사용하는 사용자들에게 개인정보 및 민감정보를 코딧(Codit) 서비스에 불필요하게 등록하거나 유지하지 않도록 정기적·비정기적 교육 및 확인을 수행하고 있습니다. 또한, 사용한 서비스 시스템 내역을 매 학년도 말에 삭제함으로써 안전한 개인정보 처리를 위해 노력하고 있습니다.</p>
        </div>
      </div>

      <!-- 제14조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제14조</span>
          개인정보 보호책임자
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>코딧(Codit) 서비스의 개인정보 처리에 관한 업무를 총괄해서 책임지고 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.</p>
          <table class="privacy-table">
            <tbody>
              <tr><td style="width:140px"><strong>성명/소속</strong></td><td><strong>박지훈</strong> (충주고등학교 정보교사)</td></tr>
              <tr><td><strong>이메일</strong></td><td><a href="mailto:wlgnsdl122@naver.com">wlgnsdl122@naver.com</a></td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 제15조 -->
      <div class="privacy-section">
        <div class="privacy-section-head" onclick="this.parentElement.classList.toggle('open')">
          <span class="ps-num">제15조</span>
          개인정보 처리방침의 변경
          <span class="ps-arrow">▾</span>
        </div>
        <div class="privacy-section-body">
          <p>이 개인정보 처리방침은 <strong>2026. 03. 01.</strong>부터 적용됩니다.</p>
          <p>방침이 변경되는 경우 코딧(Codit) 공지사항을 통해 변경 사항을 안내합니다.</p>
        </div>
      </div>

    </div><!-- /privacy-card -->

    <div class="privacy-contact">
      <p style="margin:0 0 4px;font-weight:700">📧 개인정보 관련 문의</p>
      <p style="margin:0">충주고등학교 정보교사 박지훈 — <a href="mailto:wlgnsdl122@naver.com">wlgnsdl122@naver.com</a></p>
    </div>

  </div><!-- /privacy-wrap -->
</div><!-- /container -->

<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
