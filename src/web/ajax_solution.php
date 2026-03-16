<?php
ini_set("display_errors", "Off");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require_once('./include/db_info.inc.php');
header('Content-Type: application/json; charset=utf-8');

// 로그인 확인
if (!isset($_SESSION[$OJ_NAME . '_user_id'])) {
    echo json_encode(['error' => '로그인이 필요합니다.']);
    exit;
}

$user_id = $_SESSION[$OJ_NAME . '_user_id'];
$is_admin = isset($_SESSION[$OJ_NAME . '_administrator']);
$sol_id = intval($_GET['id'] ?? 0);

if ($sol_id <= 0) {
    echo json_encode(['error' => '잘못된 요청입니다.']);
    exit;
}

// 본인의 AC 풀이인지 확인 (관리자는 모든 풀이 열람 가능)
if ($is_admin) {
    $sol = pdo_query("SELECT solution_id, problem_id, user_id, language, in_date, time, memory, result FROM solution WHERE solution_id=? AND result=4", $sol_id);
} else {
    $sol = pdo_query("SELECT solution_id, problem_id, user_id, language, in_date, time, memory, result FROM solution WHERE solution_id=? AND user_id=? AND result=4", $sol_id, $user_id);
}

if (empty($sol)) {
    echo json_encode(['error' => '열람할 수 없는 풀이입니다.']);
    exit;
}

$sol = $sol[0];

// 소스코드 가져오기 (user 버전 우선)
$src = pdo_query("SELECT source FROM source_code_user WHERE solution_id=?", $sol_id);
if (empty($src)) {
    $src = pdo_query("SELECT source FROM source_code WHERE solution_id=?", $sol_id);
}

if (empty($src)) {
    echo json_encode(['error' => '소스코드를 찾을 수 없습니다.']);
    exit;
}

$source = $src[0]['source'] ?? '';

$lang_names = array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Scheme","Clang","Clang++","Lua","JavaScript","Go","SQL","Fortran","Matlab","Cobol","R","Scratch3","Cangjie");
$lang_idx = intval($sol['language']);
$lang = isset($lang_names[$lang_idx]) ? $lang_names[$lang_idx] : 'Unknown';

echo json_encode([
    'source' => $source,
    'language' => $lang,
    'date' => $sol['in_date'],
    'time' => intval($sol['time']),
    'memory' => intval($sol['memory']),
    'solution_id' => intval($sol['solution_id'])
], JSON_UNESCAPED_UNICODE);
