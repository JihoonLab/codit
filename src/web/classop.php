<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
if (!isset($_SESSION[$OJ_NAME . '_user_id'])) {
    echo "<script>location.replace('loginpage.php')</script>"; exit(0);
}
$is_admin = isset($_SESSION[$OJ_NAME . '_administrator']);
$action = $_GET['action'] ?? '';

// 수업 삭제

if ($is_admin && $action === 'toggle_defunct') {
    $cid = intval($_GET['id'] ?? 0);
    if ($cid > 0) {
        $cls = pdo_query("SELECT defunct FROM class WHERE class_id=?", $cid);
        if ($cls) {
            $new_defunct = $cls[0]['defunct'] == 'Y' ? 'N' : 'Y';
            pdo_query("UPDATE class SET defunct=? WHERE class_id=?", $new_defunct, $cid);
        }
    }
    echo "<script>location.replace('classop.php')</script>"; exit(0);
}

if ($is_admin && $action === 'copy') {
    $cid = intval($_GET['id'] ?? 0);
    if ($cid > 0) {
        $cls = pdo_query("SELECT * FROM class WHERE class_id=?", $cid);
        if ($cls) {
            $c = $cls[0];
            pdo_query("INSERT INTO class (title, description, tag, content, user_id, time, defunct) VALUES (?,?,?,?,?,NOW(),'N')",
                $c['title'].' (복사)', $c['description'], $c['tag'] ?? '', $c['content'], $_SESSION[$OJ_NAME.'_user_id']);
            $new_rows = pdo_query("SELECT LAST_INSERT_ID() as id");
            $new_id = $new_rows[0]['id'];
            $probs = pdo_query("SELECT * FROM class_problem WHERE class_id=?", $cid);
            foreach ($probs as $p) {
                pdo_query("INSERT INTO class_problem (class_id, problem_id, sort_order) VALUES (?,?,?)",
                    $new_id, $p['problem_id'], $p['sort_order']);
            }
        }
    }
    echo "<script>location.replace('classop.php')</script>"; exit(0);
}

if ($is_admin && $action === 'delete') {
    $cid = intval($_GET['id'] ?? 0);
    if ($cid > 0) {
        pdo_query("DELETE FROM class_problem WHERE class_id=?", $cid);
        pdo_query("DELETE FROM class WHERE class_id=?", $cid);
    }
    echo "<script>location.replace('classop.php')</script>"; exit(0);
}

// 수업 상세
if ($action === 'view') {
    $cid = intval($_GET['id'] ?? 0);
    if ($cid <= 0) { echo "<script>location.replace('classop.php')</script>"; exit(0); }
    $class = pdo_query("SELECT * FROM class WHERE class_id=? AND defunct='N'", $cid);
    if (empty($class)) { echo "<script>alert('존재하지 않는 수업입니다.'); location.replace('classop.php')</script>"; exit(0); }
    $class = $class[0];

    $problems = pdo_query("SELECT cp.problem_id, cp.sort_order, p.title FROM class_problem cp LEFT JOIN problem p ON cp.problem_id=p.problem_id WHERE cp.class_id=? ORDER BY cp.sort_order ASC, cp.id ASC", $cid);

    $students = [];
    $ac_map = [];
    $first_map = [];

    if (!empty($problems)) {
        $pids = implode(',', array_column($problems, 'problem_id'));

        $class_time = $class['time'] ?? '2000-01-01 00:00:00';
        // 제출한 학생 목록
        $students = pdo_query("SELECT DISTINCT s.user_id, u.nick FROM solution s LEFT JOIN users u ON s.user_id=u.user_id WHERE s.problem_id IN ($pids) AND s.class_id=? ORDER BY s.user_id ASC", $cid);

        // AC 여부
        $solved = pdo_query("SELECT DISTINCT user_id, problem_id FROM solution WHERE problem_id IN ($pids) AND result=4 AND class_id=?", $cid);
        foreach ($solved as $s) {
            $ac_map[$s['user_id']][$s['problem_id']] = true;
        }

        // 문제별 최초 AC
        $firsts = pdo_query("SELECT problem_id, user_id, MIN(in_date) as first_time FROM solution WHERE problem_id IN ($pids) AND result=4 AND class_id=? GROUP BY problem_id", $cid);
        foreach ($firsts as $f) {
            $first_map[$f['problem_id']] = ['user_id' => $f['user_id'], 'time' => $f['first_time']];
        }
    }

    require("template/" . $OJ_TEMPLATE . "/classview.php");
    exit(0);
}

// 수업 작성/수정
if ($is_admin && $action === 'write') {
    $cid = intval($_GET['id'] ?? 0);
    $class = ['title' => '', 'description' => '', 'tag' => '', 'content' => '', 'problem_ids' => ''];

    if ($cid > 0) {
        $result = pdo_query("SELECT * FROM class WHERE class_id=?", $cid);
        if (!empty($result)) {
            $class = $result[0];
            $cp = pdo_query("SELECT problem_id FROM class_problem WHERE class_id=? ORDER BY sort_order ASC", $cid);
            $class['problem_ids'] = implode(', ', array_column($cp, 'problem_id'));
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title   = trim($_POST['title'] ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $tag     = trim($_POST['tag'] ?? '');
        $pid_str = trim($_POST['problem_ids'] ?? '');
        $uid     = $_SESSION[$OJ_NAME . '_user_id'];

        if ($title === '') { $error = "수업 제목을 입력해주세요."; }
        else {
            if ($cid > 0) {
                pdo_query("UPDATE class SET title=?, description=?, tag=?, content=?, time=NOW() WHERE class_id=?", $title, $desc, $tag, $content, $cid);
            } else {
                pdo_query("INSERT INTO class (title, description, tag, content, user_id, time, defunct) VALUES (?,?,?,?,?,NOW(),'N')", $title, $desc, $tag, $content, $uid);
                $cid = pdo_query("SELECT LAST_INSERT_ID() as id")[0]['id'];
            }
            pdo_query("DELETE FROM class_problem WHERE class_id=?", $cid);
            $pids = array_values(array_filter(array_map('intval', explode(',', $pid_str))));
            foreach ($pids as $order => $pid) {
                if ($pid > 0) pdo_query("INSERT INTO class_problem (class_id, problem_id, sort_order) VALUES (?,?,?)", $cid, $pid, $order);
            }
            echo "<script>location.replace('classop.php?action=view&id=$cid')</script>"; exit(0);
        }
    }
    require("template/" . $OJ_TEMPLATE . "/classwrite.php");
    exit(0);
}

// 학생 소속 정보
$my_uid = $_SESSION[$OJ_NAME . '_user_id'];
$my_school = '';
$school_row = pdo_query("SELECT school FROM users WHERE user_id=?", $my_uid);
if(!empty($school_row)) $my_school = trim($school_row[0]['school'] ?? '');

// 수업 목록
if($is_admin) {
    // 관리자: 전체 수업 (비공개 포함)
    $classes = pdo_query("SELECT c.*, COUNT(cp.id) as problem_count FROM class c LEFT JOIN class_problem cp ON c.class_id=cp.class_id GROUP BY c.class_id ORDER BY c.class_id DESC");
} else if($my_school !== '') {
    // 학생: 본인 반 태그 수업 + 태그 없는(전체공개) 수업만
    $classes = pdo_query("SELECT c.*, COUNT(cp.id) as problem_count FROM class c LEFT JOIN class_problem cp ON c.class_id=cp.class_id WHERE c.defunct='N' AND (c.tag=? OR c.tag='') GROUP BY c.class_id ORDER BY c.class_id DESC", $my_school);
} else {
    // 학년/반 미입력 학생: 태그 없는(전체공개) 수업만
    $classes = pdo_query("SELECT c.*, COUNT(cp.id) as problem_count FROM class c LEFT JOIN class_problem cp ON c.class_id=cp.class_id WHERE c.defunct='N' AND c.tag='' GROUP BY c.class_id ORDER BY c.class_id DESC");
}

// 내 풀이 현황
$my_solved_map = [];
if (!empty($classes)) {
    foreach ($classes as $c) {
        if ($c['problem_count'] > 0) {
            $pids_rows = pdo_query("SELECT problem_id FROM class_problem WHERE class_id=?", $c['class_id']);
            $pids = array_column($pids_rows, 'problem_id');
            if (!empty($pids)) {
                $pids_str = implode(',', $pids);
                $class_time = $c['time'] ?? '2000-01-01 00:00:00';
                $solved = pdo_query("SELECT COUNT(DISTINCT problem_id) cnt FROM solution WHERE user_id=? AND problem_id IN ($pids_str) AND result=4 AND class_id=?", $my_uid, $c['class_id']);
                $my_solved_map[$c['class_id']] = $solved[0]['cnt'] ?? 0;
            }
        }
    }
}
require("template/" . $OJ_TEMPLATE . "/classlist.php");
