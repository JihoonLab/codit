# Codit Online Judge - 개발 가이드

## 프로젝트 개요
- **Codit** (codit.co.kr) - 충주고등학교 온라인 저지 시스템
- HustOJ 기반, AWS EC2 (52.79.47.61)
- PHP 8.3 + MySQL (MariaDB) + Apache
- 학생들이 C/Python 문제를 풀고 자동 채점받는 플랫폼

## 핵심 경로
- 템플릿: `/home/judge/src/web/template/bs3/`
- 백엔드: `/home/judge/src/web/`
- 관리자: `/home/judge/src/web/admin/`
- Ace 에디터: `/home/judge/src/web/ace/` (v1.3.3 정품)
- 언어파일: `/home/judge/src/web/lang/ko.php`
- 채점 데이터: `/home/judge/data/`
- Git 루트: `/home/judge/` (.git 위치)

## DB 접속
```bash
mysql -u hustoj -p'eqAHtdiYFUvCWbABBMqurEIKezlZF0' jol
```
- users, solution, source_code, source_code_user, problem, runtimeinfo, compileinfo 테이블

## 캐시 초기화
```bash
sudo systemctl restart php8.3-fpm
```
APCu 캐시 때문에 PHP 파일 수정 후 반드시 실행해야 반영됨

## 용어 규칙
- **"제출" → "도전"**, **"통과" → "해결"** (문제목록, 랭킹, 프로필 전체 통일)
- 학생 성취감을 위한 용어 선택

## 정답률/제출수 주의사항
- `users.submit`은 DISTINCT problem_id 기준 (시도한 문제 수)이지 총 제출 횟수가 아님
- 랭킹 페이지는 `solution` 테이블에서 `COUNT(*)` 실제 제출 횟수를 LEFT JOIN으로 가져옴
- `users.submit`은 채점 데몬/userinfo.php가 덮어쓰므로 직접 UPDATE해도 원복됨
- 정답률 바 색상: 70%↑ 초록, 40%↑ 노랑, 0%↑ 빨강, 0% 보라 (문제목록과 동일)

## 주요 파일과 역할
| 파일 | 설명 |
|------|------|
| `template/bs3/problem.php` | 문제 상세 페이지 (코드제출/내제출 버튼) |
| `template/bs3/submitpage.php` | 코드 제출 페이지 (Ace 에디터, D2Coding 폰트) |
| `template/bs3/reinfo.php` | 채점 결과 상세 (WA/PE 비교, TLE/RE 등 분석, AC 축하) |
| `template/bs3/ceinfo.php` | 컴파일 에러 상세 (한국어 번역 50+패턴, 줄 하이라이트) |
| `template/bs3/ranklist.php` | 랭킹 (개인별/반별 탭, 시상대, 반 클릭→필터) |
| `template/bs3/problemset.php` | 문제 목록 (도전/해결 색상 구분) |
| `template/bs3/userinfo.php` | 프로필 (도전/해결 색상, 활동 차트) |
| `template/bs3/registerpage.php` | 회원가입 (학년/반 선택, 번호 1~40) |
| `admin/user_list.php` | 사용자 목록 (이름/학년반/번호만 표시) |
| `admin/user_df_change.php` | 사용자 삭제 (하드 삭제 + 확인 팝업) |
| `login.php` | 로그인 (admin은 메인페이지로 리다이렉트) |
| `ceinfo.php` | CE 백엔드 ($solution_row 설정) |

## Ace 에디터 주의사항
- 버전 1.3.3 — `ace.config.set()` 없음, `ace.require('ace/config')` 사용
- `session.highlightLines()` 없음 → `session.addMarker()` + `Range` 사용
- 역방향 타이핑 버그 → `syncTextareaToCursor()` 함수로 textarea DOM 직접 이동
- D2Coding 폰트: `@font-face`로 직접 선언 (CDN은 'D2 coding'이라 불일치)

## 변수/설정
- `$OJ_NAME` = "Codit"
- `$OJ_CDN_URL` = "" (빈 문자열)
- `$OJ_TEMPLATE` = "bs3"
- 관리자 계정: admin

## 개발 규칙
- 한국어 UI (학생 대상 — 쉽고 직관적으로)
- 수정 후 `sudo systemctl restart php8.3-fpm` 필수
- 파일 권한 문제 시 `sudo` 사용
- git 작업도 `sudo git` 으로 (data 폴더 권한)
- 원격: `origin` → `https://github.com/JihoonLab/codit.git`

## 최근 완료된 작업 (2026-04-04)
- Ace 에디터 역방향 타이핑 버그 수정 + 1.3.3 정품 파일 교체
- D2Coding 폰트 적용
- 채점 결과 페이지 전면 리디자인 (WA/PE 비교, 에러 분석, AC 축하 카드)
- WA 비교: "내 프로그램의 출력 → 정답" 순서 (학생 관점)
- 컴파일 에러 한국어 번역 + 줄 하이라이트 (50+ 패턴, 스마트따옴표 정규화)
- 관리자 사용자 목록 정리 (IP/이메일/그룹/만료일 제거)
- 사용자 하드 삭제로 변경
- 랭킹: 개인별/반별 탭, 시상대 UI, 반 클릭→해당 반 학생 필터
- 랭킹 정답률 실제 제출수 기준으로 수정 (solution 테이블 JOIN)
- 정답률 바 색상 4단계 (문제목록/랭킹/반별 통일)
- 제출→도전, 통과→해결 용어 통일 (문제목록, 랭킹, 프로필)
- 도전/해결 폰트 색상 구분 (해결=#16a34a, 도전=#64748b)
- 회원가입 번호 정수 제한 (1~40)
- admin 로그인 → 메인 리다이렉트

## 완료된 작업 (2026-04-04 야간)

### 보안 패치 (10항목)
- 쿠키 보안 플래그 (Secure, HttpOnly, SameSite=Strict): login.php, logout.php, init.php
- 세션 쿠키 secure 플래그 활성화 (init.php)
- SQL 인젝션 방지: submit.php prepared statement 3건
- IP 스푸핑 방지: register.php filter_var 검증
- status.php IP 노출 제거, 리다이렉트 HOST 검증
- 파일 권한 수정: admin/phpfm.php 666→644
- 비밀번호 해싱 체계 정리: pwGen() md5ed 파라미터, register.php/modify.php 수정

### 비밀번호 초기화 기능
- admin/ajax.php: reset_password 핸들러 (123456 초기화, admin 보호)
- admin/user_list.php: 초기화 버튼 UI

### 정보 수정 페이지 (modify) 전면 리디자인
- 프로필 수정과 비밀번호 변경 분리 (AJAX JSON)
- 프로필: 비밀번호 없이 이름/학년/반/번호 변경
- 비밀번호: 현재 비밀번호 확인 후 변경 (강도 바, 일치 체크)
- 다른 메뉴 페이지와 동일한 레이아웃 (.modify-wrap + .modify-header 스타일)

### UI 통일
- 로그인/회원가입 "Online Judge" 텍스트 대소문자+폰트 메인과 통일
- 페이지 타이틀 전체 통일: 26px / 800 / #1a1a2e + em #7c3aed
  - 문제 목록, 랭킹, 제출현황, 교안, 수업 목록 모두 동일 스타일
- 랭킹 개인별 시상대에 "INDIVIDUAL RANKING" 타이틀 추가

### 모바일 반응형 패치 (전체)
- ceinfo.php: @media 600px 신규 (버튼 풀폭, 패딩/폰트 축소)
- index.php: @media 600px 신규 (히어로/카드/리스트/명예의전당)
- problemset.php: @media 768px 강화 + 480px 신규 (검색바 세로, 카드 축소)
- problem.php: @media 600px 확장 (제목/버튼/섹션)
- ranklist.php: @media 600px 강화 (시상대/테이블 가로스크롤)
- userinfo.php: @media 480px 신규 (프로필 세로배치, 아바타 축소)
- submitpage.php: @media 600px 강화 (에디터/버튼/메타정보)
- status.php: @media 480px 신규 (필터/테이블 가로스크롤)
- reinfo.php: @media 600px 확장 (버튼 풀폭, 체크리스트 축소)

### 문제 페이지
- 관리자 전용 "전체 제출" 버튼 추가 (problem.php)
- 런타임 에러 힌트 1번에 scanf & 주소연산자 추가, 빨간 강조

## 완료된 작업 (2026-04-05)

### 도전현황 (status.php) 전면 리디자인
- 테이블 기반 모던 레이아웃 (보라 그라디언트 헤더, 결과 배지 pill 스타일)
- AC 행 초록 하이라이트, 문제번호 배지, 성능 데이터 monospace
- 결과 배지 색상: ac=green, wa=red, ce=yellow, tle=blue, mle=indigo, re=purple, pe=orange
- 대기 중(pending) 결과에 pulse 애니메이션
- nav.php "제출현황" → "도전현황" 변경

### WA/PE 비교 3열 확장
- reinfo.php 백엔드: 실패 테스트케이스의 .in 파일 읽기 (`/home/judge/data/{pid}/{tc}.in`)
- reinfo.php 템플릿: 3열 비교 (📥 입력 | 내 프로그램의 출력 | 정답 출력)
- 입력 없으면 2열로 자동 폴백
- "정답" → "정답 출력" 컬럼 헤더 변경

### PE(출력 형식 오류) 공백 시각화
- 공백을 `·`로, 줄 끝 공백은 빨간 배경, 줄바꿈은 `↵`로 표시
- 차이나는 줄 노란 하이라이트 (ws-diff-line)
- PE 전용 안내 배너 + 범례 (공백/줄끝공백/줄바꿈/차이 아이콘)

### 기타
- SIM(유사도) 필터 도전현황에서 제거

### Python 100제 정리 시작
- 불필요한 문제 하드삭제: 1014, 1015, 1045, 1046, 1058~1061, 1098 (9문제)
- 1011 ← 1014(split 버전) 내용 흡수, 제목 정리
- 1012 ← 1015(split 버전) 내용 흡수, 제목 정리
- 1000번 참고에 작은따옴표도 사용 가능하다는 설명 추가
- 현재 남은 문제: 90개 (100제까지 10문제 추가 필요)
- 다음 작업: 출력 파트(1000~1007) 설명/output 필드 정리, 이후 전체 카테고리 분석 및 신규 문제 추가

## 완료된 작업 (2026-04-07)

### Python 100제 입출력 파트 최종 정리 (1006-1015)
- 1006~1015 문제 설명/예시/참고/모범답안/채점데이터 전수 점검 통일
- 난이도 순 정렬 + 제목에 문법 표기 (예: `[입출력] 2개 입력받아 각각 출력하기 (split)`)
- 문제 번호 재배치: 1009↔1010 교환, 1014/1015 신규 이동
- 힌트 최소화, `<strong>` 강조, 참고1/참고2 형식 통일
- 모범답안 리스트 인덱싱 제거 (언패킹 사용)

### 형변환 파트 추가 (1016-1018)
- 1016: `int()` 기본 형변환 (선행 0 테스트데이터로 `print(input())` 방지)
- 1017: `int()` 두 수 합 (기존 1024에서 이동)
- 1018: `float()` 실수 합 (기존 1025에서 이동)
- 1000~1018 전체 공개(defunct='N') 처리

### AI분반 시스템 구현
- DB: `users.ai_group` TINYINT 컬럼 추가 (0=미배정, 1=AI-1 박지훈, 2=AI-2 박지훈, 3=AI-3 안예찬)
- 관리자 UI: `admin/user_list.php`에 AI분반 드롭다운 (색상 코딩, AJAX 즉시 변경)
- `admin/ajax.php`에 `user_update_ai_group` 핸들러 추가

### 수업 생성 AI분반 지원
- `classop.php` batch: 3학년 → AI-1, AI-2, AI-3 분반으로 생성 (기존 반 대신)
- `classbatch.php`: 3학년 옵션 "AI 3분반", 미리보기에 교사명 표시
- `classwrite.php`: 개별생성에도 AI분반 드롭다운 (AI-1 박지훈T 등), 제목에 "3-" 접두사 제거
- 수업 수정 시 AI 태그 파싱 지원 (정규식 업데이트)

### 수업 목록 AI분반 필터링
- `classop.php`: 학생별 `ai_group` 조회 → AI-N 태그 수업 자동 포함
- 학교반(school) + AI분반(ai_group) 동시 지원 (OR 조건)
- 미배정/미입력 학생: 전체공개 수업만 표시
- `classlist.php`: 반+AI분반 둘 다 있는 학생은 전체보기 기본

### 수업 상세(classview) AI분반 학생 전체 표시
- AI-N 태그 수업: `users.ai_group=N`인 전체 학생 표시 (미제출 학생 포함)
- 일반 반(2-3 등) 태그 수업: `users.school=태그`인 전체 학생 표시
- 태그 없는 수업: 기존처럼 제출한 학생만 표시
- 학생 수 카운트 표시, 빈 상태 메시지 분반별 차별화
- 관리자 비공개 수업 상세 접근 허용

## 완료된 작업 (2026-04-21)

### 대회 상세 페이지 전면 리디자인 (`template/bs3/contest.php`)
- 보라 테마 통일 (예상 점수 노랑→보라, ct-ended 회고형 UI, 슬레이트 톤)
- 메타 정보 바 추가 (참가자 수, 허용 언어, 출제자, 서버시계 chip)
- 다음 문제 CTA 에 문제 제목 표시 (pid 배지 + 제목, ellipsis)
- 🏁 종료된 대회 워터마크 + "대회 결과"/"N시간 전 종료" 동적 표시
- 모바일 600px/420px 브레이크포인트 강화
- 데드코드 정리 (`.label` 스타일 제거)
- 트렌디 애니메이션 (shimmer, 플로팅 오브, bounce)

### 대회 비밀번호 PRG 패턴 (`contest-check.php`)
- 비밀번호 POST → 303 See Other 리다이렉트 → GET
- 뒤로가기 시 "양식 재제출" 팝업 제거
- `session_write_close()` + `ob_end_clean()` 포함

### 대회 입장하기 버튼 스타일 (`template/bs3/error.php`)
- Bootstrap 3 `form-control` height:34px 고정 덮어쓰기
- `height: auto !important; line-height: 1.2; display: inline-flex` 로 input과 높이 매칭

### B 옵션: 종료 대회 학생 접근 제한
- 종료 대회 상세 진입 시 순위표 배너만 노출, 문제 목록 숨김
- `problem.php?cid=X&pid=Y`: 가드 추가 (학생 종료 대회 문제 차단)
- `submitpage.php`: 가드 추가 (종료 대회 제출 차단)
- `status.php?cid=X`, `conteststatistics.php`: 가드 추가 (순위만 열람 가능)
- `require_contest_not_ended_for_students()` 헬퍼 함수 (`my_func.inc.php`)

### 대회 제출 코드 보안 강화 (`reinfo.php`, `ceinfo.php`)
- 연습 제출(contest_id=0)이 현재 활성 대회에 잠긴 문제면 본인이어도 차단
- `problem_locked($pid, 28)` 사용
- 대회 제출(contest_id>0)은 본인의 정당한 활동이므로 항상 허용

### 중국어 메시지 한국어 번역
- `contest-check.php:50`: "比赛已经关闭!" → "대회를 찾을 수 없어요"
- `contest-check.php:45`: "Not in subnet" → "허용된 네트워크에서만 접속할 수 있어요"
- `suspect_list.php`: 동일 메시지 번역
- `reinfo.php`: 런타임 에러 힌트 한글화
- `login.php`: 쿠키 에러 alert 한글화
- `lostpassword.php`: 이메일 불일치 한글화

### 🚨 대회 삭제 안전장치 (매우 중요)
- **`contest_df_change.php` 버그 발견**: 활성 대회 defunct 토글 버튼이 실제로는 **완전 삭제** 수행
- **수정**: 순수 defunct 플래그 토글만 (데이터 보존)
- **신규 `contest_hard_delete.php`**: 이중 확인 + 명시적 문구 타이핑 필요 (`DELETE-{cid}`)
- **`contest_list.php` UI**: "👁 공개/🙈 숨김" 토글 + "🗑️ 삭제" 별도 빨간 버튼
- 영구 삭제 페이지: 영향 범위 표시 + confirm 입력 + JavaScript 팝업 3중 안전장치

### MyISAM 삭제 데이터 복구 (긴급 대응)
- 수행평가 28명 × 863 제출 실수 삭제 → **완전 복구 성공**
- MyISAM 고정 길이 레코드(766 bytes) 직접 Python 파싱
- flag byte 0x00 (deleted) 블록 863개 → contest_id=1 필터
- DB 재삽입: contest, contest_problem, solution, privilege 전부 복원
- 이전 분석 결과와 100% 일치 확인
- 백업 보관: `/root/mysql_emergency_backup_20260421/`

### 엑셀 순위표 다운로드 개선 (`contestrank.xls.php`)
- **Nick 버그 수정**: `$row['nick']` → `$U[$i]->nick` (전원 같은 이름 나오던 버그)
- **수행평가 컬럼 추가**: exam_max 기준 비율별 티어 환산 (20점/40점 만점)
- **학번 컬럼 추가**: User ↔ Nick 사이 ("3608" 형식 = school 3-6 + student_no 08)
- **Penalty 컬럼 제거**
- **학번순 정렬**
- **SpreadsheetML XML 포맷**으로 전환 (Excel 경고 최소화)
- **고급 디자인**:
  - 🏆 타이틀 행 (병합, 보라 배경, 16pt)
  - 📅 부제 (대회 기간·참가자수·문제수)
  - 🟣 헤더 (보라 배경 + 흰색 굵은)
  - 🦓 Zebra striping (짝수행 연회색)
  - 🥇🥈🥉 메달 이모지 (1-3등)
  - 🎨 수행평가 레벨별 색상 (초록/노랑/빨강)
  - 🔢 학번 monospace (D2Coding)
  - 🟢🔴 AC/WA 셀 색상 구분
  - 🔒 첫 4행 스크롤 고정
- **파일명 타임스탬프 추가** (중복 다운로드 권한 충돌 방지)

### 수행평가 구성 확정 (3학년 AI-1,2,3)
- 10문제 × 3유형 (A/B/C), 35분
- 공통 4문제: 1014(f-string), 1044(and/or/%), 1054(반복누적), 1061(반복+조건)
- 차별 6문제: 분반별 비슷한 난이도 다른 문제
- 실제 AI-3(C형) 시행: 28명 참가, 평균 52점, 만점 2명, 변별력 확보
