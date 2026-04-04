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

## 주요 파일과 역할
| 파일 | 설명 |
|------|------|
| `template/bs3/problem.php` | 문제 상세 페이지 (코드제출/내제출 버튼) |
| `template/bs3/submitpage.php` | 코드 제출 페이지 (Ace 에디터, D2Coding 폰트) |
| `template/bs3/reinfo.php` | 채점 결과 상세 (WA/PE 비교, TLE/RE 등 분석) |
| `template/bs3/ceinfo.php` | 컴파일 에러 상세 (한국어 번역, 줄 하이라이트) |
| `template/bs3/ranklist.php` | 랭킹 페이지 (정답률별 색상 프로그레스바) |
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
- Ace 에디터 역방향 타이핑 버그 수정
- Ace 1.3.3 정품 파일 교체 (구문 강조 복구)
- D2Coding 폰트 적용
- 채점 결과 페이지 전면 리디자인 (WA/PE 비교, 에러 분석 체크리스트, AC 축하 카드)
- 컴파일 에러 한국어 번역 + 줄 하이라이트
- 관리자 사용자 목록 정리 (IP/이메일/그룹/만료일 제거, 별명→이름, 소속→학년/반)
- 사용자 하드 삭제로 변경
- 랭킹 정답률별 프로그레스바 색상 (5단계)
- 회원가입 번호 정수 제한 (1~40)
- 문제 4, 6번 참고사항 수정
- admin 로그인 → 메인 리다이렉트
