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
