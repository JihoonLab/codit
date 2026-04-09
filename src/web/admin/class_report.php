<?php
/**
 * 포트폴리오 대시보드 (관리자 전용)
 * - 반별 종합 진척도 대시보드
 * - 반별 모든 수업 문제 합산 → 학생별 진척도 → 10점 만점 환산
 * - 수업 밖 제출도 반영 (class_id 무관, 해당 문제 AC면 인정)
 * - 엑셀(CSV) 내보내기
 */
chdir(dirname(__FILE__) . '/..');
require_once('include/db_info.inc.php');
require_once('include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_administrator'])) {
    echo "<script>alert('관리자만 접근할 수 있습니다.');history.back();</script>";
    exit;
}

// 포트폴리오 점수 기준 (정보: 10점, AI: 20점)
$score_table_10 = [
    ['min' => 100, 'score' => 10, 'label' => '100%'],
    ['min' =>  90, 'score' =>  9, 'label' => '90%↑'],
    ['min' =>  80, 'score' =>  8, 'label' => '80%↑'],
    ['min' =>  70, 'score' =>  7, 'label' => '70%↑'],
    ['min' =>  60, 'score' =>  6, 'label' => '60%↑'],
    ['min' =>  50, 'score' =>  5, 'label' => '50%↑'],
    ['min' =>  40, 'score' =>  4, 'label' => '40%↑'],
    ['min' =>   1, 'score' =>  3, 'label' => '1%↑'],
    ['min' =>   0, 'score' =>  2, 'label' => '0%↑'],
];
$score_table_20 = [
    ['min' => 100, 'score' => 20, 'label' => '100%'],
    ['min' =>  90, 'score' => 18, 'label' => '90%↑'],
    ['min' =>  80, 'score' => 16, 'label' => '80%↑'],
    ['min' =>  70, 'score' => 14, 'label' => '70%↑'],
    ['min' =>  60, 'score' => 12, 'label' => '60%↑'],
    ['min' =>  50, 'score' => 10, 'label' => '50%↑'],
    ['min' =>  40, 'score' =>  8, 'label' => '40%↑'],
    ['min' =>   0, 'score' =>  6, 'label' => '기본'],
];
function get_score_table($tag) {
    global $score_table_10, $score_table_20;
    return preg_match('/^AI-/', $tag) ? $score_table_20 : $score_table_10;
}
function get_max_score($tag) {
    return preg_match('/^AI-/', $tag) ? 20 : 10;
}

function calc_score($pct, $score_table) {
    foreach ($score_table as $s) {
        if ($pct >= $s['min']) return $s;
    }
    return end($score_table);
}

$tag = trim($_GET['tag'] ?? '');
$export = $_GET['export'] ?? '';

// 모든 수업 가져오기
$all_classes = pdo_query("SELECT c.class_id, c.title, c.tag, COUNT(cp.id) as pcnt
    FROM class c LEFT JOIN class_problem cp ON c.class_id=cp.class_id
    WHERE c.defunct='N' GROUP BY c.class_id ORDER BY c.class_id ASC");

// ============================
// 대시보드: 반별 종합 현황
// ============================
if ($tag === '' && $export === '') {
    // 반(tag)별로 그룹핑
    $tag_groups = [];
    foreach ($all_classes as $cls) {
        $t = $cls['tag'];
        if ($t === '') continue;
        if (!isset($tag_groups[$t])) {
            $tag_groups[$t] = ['tag' => $t, 'classes' => [], 'problem_ids' => []];
        }
        $tag_groups[$t]['classes'][] = $cls;
        if ($cls['pcnt'] > 0) {
            $pids_rows = pdo_query("SELECT problem_id FROM class_problem WHERE class_id=?", $cls['class_id']);
            foreach ($pids_rows as $pr) $tag_groups[$t]['problem_ids'][$pr['problem_id']] = true;
        }
    }

    $dashboard = [];
    foreach ($tag_groups as $t => $data) {
        $total = count($data['problem_ids']);
        if (preg_match('/^AI-(\d+)$/', $t, $ai_m)) {
            $students = pdo_query("SELECT user_id FROM users WHERE ai_group=? AND defunct='N'", intval($ai_m[1]));
        } else {
            $students = pdo_query("SELECT user_id FROM users WHERE school=? AND defunct='N'", $t);
        }
        $sc = count($students);
        $ts = 0;
        if ($total > 0 && $sc > 0) {
            $pids_str = implode(',', array_keys($data['problem_ids']));
            foreach ($students as $s) {
                $r = pdo_query("SELECT COUNT(DISTINCT problem_id) cnt FROM solution WHERE user_id=? AND problem_id IN ($pids_str) AND result=4", $s['user_id']);
                $ts += ($r[0]['cnt'] ?? 0);
            }
        }
        $avg = ($sc > 0 && $total > 0) ? round($ts / ($sc * $total) * 100, 1) : 0;
        $dashboard[] = [
            'tag' => $t,
            'class_count' => count($data['classes']),
            'problem_count' => $total,
            'student_count' => $sc,
            'avg_pct' => $avg,
            'max_score' => get_max_score($t),
        ];
    }
    usort($dashboard, function($a, $b) { return strcmp($a['tag'], $b['tag']); });

    require("template/$OJ_TEMPLATE/class_report_dashboard.php");
    exit;
}

// ============================
// 반별 종합 리포트
// ============================
if ($tag === '') {
    echo "<script>alert('반을 선택해주세요.');history.back();</script>";
    exit;
}

// 해당 반의 모든 수업
$classes = pdo_query("SELECT c.class_id, c.title FROM class c WHERE c.tag=? AND c.defunct='N' ORDER BY c.class_id ASC", $tag);
if (empty($classes)) {
    echo "<script>alert('해당 반에 등록된 수업이 없습니다.');history.back();</script>";
    exit;
}

// 수업별 문제 목록 수집
$all_problem_ids = [];
$class_problems = [];
foreach ($classes as $c) {
    $probs = pdo_query("SELECT cp.problem_id, p.title
        FROM class_problem cp LEFT JOIN problem p ON cp.problem_id=p.problem_id
        WHERE cp.class_id=? ORDER BY cp.sort_order ASC, cp.id ASC", $c['class_id']);
    $class_problems[$c['class_id']] = $probs;
    foreach ($probs as $p) $all_problem_ids[$p['problem_id']] = true;
}
$total = count($all_problem_ids);
$pids_str = $total > 0 ? implode(',', array_keys($all_problem_ids)) : '0';

// 학생 목록
if (preg_match('/^AI-(\d+)$/', $tag, $ai_m)) {
    $students = pdo_query("SELECT user_id, nick, student_no FROM users WHERE ai_group=? AND defunct='N' ORDER BY CAST(student_no AS UNSIGNED) ASC, user_id ASC", intval($ai_m[1]));
} else {
    $students = pdo_query("SELECT user_id, nick, student_no FROM users WHERE school=? AND defunct='N' ORDER BY CAST(student_no AS UNSIGNED) ASC, user_id ASC", $tag);
}

// 리포트 생성
$report = [];
foreach ($students as $st) {
    $uid = $st['user_id'];
    $solved_set = [];
    if ($total > 0) {
        $ac = pdo_query("SELECT DISTINCT problem_id FROM solution WHERE user_id=? AND problem_id IN ($pids_str) AND result=4", $uid);
        foreach ($ac as $r) $solved_set[$r['problem_id']] = true;
    }

    // 수업별 해결 현황
    $per_class = [];
    foreach ($classes as $c) {
        $cp = $class_problems[$c['class_id']];
        $class_total = count($cp);
        $class_solved = 0;
        foreach ($cp as $p) {
            if (isset($solved_set[$p['problem_id']])) $class_solved++;
        }
        $per_class[$c['class_id']] = ['solved' => $class_solved, 'total' => $class_total];
    }

    $sc = count($solved_set);
    $pct = $total > 0 ? round($sc / $total * 100, 1) : 0;
    $score_info = calc_score($pct, get_score_table($tag));

    $report[] = [
        'user_id' => $uid,
        'nick' => $st['nick'] ?? '',
        'student_no' => $st['student_no'] ?? '',
        'solved_count' => $sc,
        'per_class' => $per_class,
        'pct' => $pct,
        'score' => $score_info['score'],
        'score_label' => $score_info['label'],
    ];
}

// CSV 내보내기
if ($export === 'csv') {
    $tag_parts = explode('-', $tag);
    $tag_label = (count($tag_parts) === 2) ? $tag_parts[0].'학년 '.$tag_parts[1].'반' : $tag;
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="포트폴리오_' . $tag_label . '_' . date('Ymd') . '.csv"');
    echo "\xEF\xBB\xBF";
    $fp = fopen('php://output', 'w');

    // 헤더
    $h = ['번호', '학생ID', '이름'];
    foreach ($classes as $c) $h[] = $c['title'];
    $max = get_max_score($tag);
    $h = array_merge($h, ['해결', '전체', '진척도(%)', "점수($max)"]);
    fputcsv($fp, $h);

    foreach ($report as $r) {
        $row = [$r['student_no'] ?: '-', $r['user_id'], $r['nick']];
        foreach ($classes as $c) {
            $pc = $r['per_class'][$c['class_id']];
            $row[] = $pc['solved'] . '/' . $pc['total'];
        }
        $row = array_merge($row, [$r['solved_count'], $total, $r['pct'], $r['score']]);
        fputcsv($fp, $row);
    }

    fputcsv($fp, []);
    fputcsv($fp, ['반: ' . $tag_label]);
    fputcsv($fp, ['수업 수: ' . count($classes), '총 문제: ' . $total, '학생 수: ' . count($students)]);
    $avg = count($report) > 0 ? round(array_sum(array_column($report, 'pct')) / count($report), 1) : 0;
    fputcsv($fp, ['평균 진척도: ' . $avg . '%', '내보내기: ' . date('Y-m-d H:i:s')]);
    fclose($fp);
    exit;
}

require("template/$OJ_TEMPLATE/class_report_detail.php");
