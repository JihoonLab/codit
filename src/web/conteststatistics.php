<?php
$OJ_CACHE_SHARE = true;
$cache_time = 3;
require_once("./include/db_info.inc.php");
require_once("./include/cache_start.php");
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid = intval($_GET['cid']);
if (isset($OJ_NO_CONTEST_WATCHER) && $OJ_NO_CONTEST_WATCHER) require_once("contest-check.php");

$sql = "SELECT title,end_time,start_time,contest_type FROM `contest` WHERE `contest_id`=? AND `start_time`<NOW()";
$result = mysql_query_cache($sql, $cid);
$num = count($result);
if ($num == 0) {
    $view_errors = "$MSG_CONTEST $MSG_Contest_Pending!";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}
$row = $result[0];
$title = $row[0];
$contest_type = $row['contest_type'];
$end_time = strtotime($row[1]);
// [2026-04-21 B 강화] 종료된 대회의 통계는 학생 차단 (순위표로만 유도)
require_contest_not_ended_for_students($cid, $end_time);
$start_time = strtotime($row[2]);
$noip = (time() < $end_time) && ((stripos($title, $OJ_NOIP_KEYWORD) !== false) || (($contest_type & 20) > 0));
if (isset($_SESSION[$OJ_NAME . '_' . "administrator"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "m$cid"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "source_browser"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "contest_creator"])
) $noip = false;
if ($noip) {
    $view_errors = "<h2>$MSG_NOIP_WARNING</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

$view_title = "Contest Statistics";

$sql = "SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);
$row = $result[0];
$pid_cnt = intval($row[0]);

// ═══════════════════════════════════════════════════════════
// [수행평가 채점] 과목/만점 결정 (우선: 저장값 → 자동감지)
// ═══════════════════════════════════════════════════════════
$_exam_row = mysql_query_cache("SELECT exam_max_score FROM contest WHERE contest_id=?", $cid);
$_exam_stored = intval($_exam_row[0]['exam_max_score'] ?? 0);

if ($_exam_stored == 20) {
    $exam_max_score   = 20;
    $exam_subject_lbl = '2학년 정보 (C)';
} elseif ($_exam_stored == 40) {
    $exam_max_score   = 40;
    $exam_subject_lbl = '3학년 인공지능기초 (Python)';
} else {
    $_subj_counts = mysql_query_cache(
        "SELECT
            SUM(CASE WHEN problem_id < 1000 THEN 1 ELSE 0 END) AS c_cnt,
            SUM(CASE WHEN problem_id >= 1000 THEN 1 ELSE 0 END) AS py_cnt
         FROM contest_problem WHERE contest_id=?", $cid);
    $c_cnt_tmp  = intval($_subj_counts[0]['c_cnt']  ?? 0);
    $py_cnt_tmp = intval($_subj_counts[0]['py_cnt'] ?? 0);
    if ($c_cnt_tmp >= $py_cnt_tmp) {
        $exam_max_score   = 20;
        $exam_subject_lbl = '2학년 정보 (C)';
    } else {
        $exam_max_score   = 40;
        $exam_subject_lbl = '3학년 인공지능기초 (Python)';
    }
}

if (!function_exists('calc_exam_score')) {
function calc_exam_score($solved, $total, $max) {
    if ($total <= 0) return 0;
    $rate = $solved / $total;
    if ($max == 20) {
        if ($rate >= 0.9) return 20;
        if ($rate >= 0.7) return 18;
        if ($rate >= 0.6) return 16;
        if ($rate >= 0.5) return 14;
        if ($rate >= 0.4) return 12;
        if ($rate >= 0.3) return 10;
        if ($rate >= 0.2) return 8;
        return 6;
    } else {
        if ($rate >= 0.9) return 40;
        if ($rate >= 0.7) return 36;
        if ($rate >= 0.6) return 32;
        if ($rate >= 0.5) return 28;
        if ($rate >= 0.4) return 24;
        if ($rate >= 0.3) return 20;
        if ($rate >= 0.2) return 16;
        return 12;
    }
}
}

// 각 사용자별 AC 개수 집계 후 점수 분포 계산
$exam_scores = array();       // [user_id => score]
$exam_user_info = array();    // [user_id => ['nick'=>..., 'solved'=>N, 'score'=>X]]
$exam_distribution = array(); // [score => count]

$_is_grading_admin = isset($_SESSION[$OJ_NAME.'_administrator']) || isset($_SESSION[$OJ_NAME.'_contest_creator']);
if ($_is_grading_admin) {
    $user_ac = mysql_query_cache(
        "SELECT s.user_id, u.nick, COUNT(DISTINCT s.num) AS ac_cnt
         FROM solution s LEFT JOIN users u ON s.user_id=u.user_id
         WHERE s.contest_id=? AND s.result=4 AND s.num>=0
         GROUP BY s.user_id", $cid);
    foreach ($user_ac as $r) {
        $sc = calc_exam_score(intval($r['ac_cnt']), $pid_cnt, $exam_max_score);
        $exam_scores[$r['user_id']] = $sc;
        $exam_user_info[$r['user_id']] = [
            'nick'   => $r['nick'],
            'solved' => intval($r['ac_cnt']),
            'score'  => $sc,
        ];
        if (!isset($exam_distribution[$sc])) $exam_distribution[$sc] = 0;
        $exam_distribution[$sc]++;
    }
    // 등록은 했지만 제출 안 한 학생 포함 (기본 점수)
    $absent = mysql_query_cache(
        "SELECT p.user_id, u.nick FROM privilege p LEFT JOIN users u ON p.user_id=u.user_id
         WHERE p.rightstr=? AND p.user_id NOT IN (SELECT DISTINCT user_id FROM solution WHERE contest_id=?)",
        "c$cid", $cid);
    foreach ($absent as $r) {
        $sc = calc_exam_score(0, $pid_cnt, $exam_max_score);
        $exam_scores[$r['user_id']] = $sc;
        $exam_user_info[$r['user_id']] = [
            'nick'   => $r['nick'],
            'solved' => 0,
            'score'  => $sc,
        ];
        if (!isset($exam_distribution[$sc])) $exam_distribution[$sc] = 0;
        $exam_distribution[$sc]++;
    }
    krsort($exam_distribution); // 높은 점수부터
}


$sql = "SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`=? and num>=0";
$result = mysql_query_cache($sql, $cid);
$R = array();
foreach ($result as $row) {
    $res = intval($row['result']) - 4;
    if ($res < 0) $res = 8;
    $num = intval($row['num']);
    $lag = intval($row['language']);
    if (!isset($R[$num][$res]))
        $R[$num][$res] = 1;
    else
        $R[$num][$res]++;
    if (!isset($R[$num][$lag + 11]))
        $R[$num][$lag + 11] = 1;
    else
        $R[$num][$lag + 11]++;
    if (!isset($R[$pid_cnt][$res]))
        $R[$pid_cnt][$res] = 1;
    else
        $R[$pid_cnt][$res]++;
    if (!isset($R[$pid_cnt][$lag + 11]))
        $R[$pid_cnt][$lag + 11] = 1;
    else
        $R[$pid_cnt][$lag + 11]++;
    if (!isset($R[$num][10]))
        $R[$num][10] = 1;
    else
        $R[$num][10]++;
    if (!isset($R[$pid_cnt][10]))
        $R[$pid_cnt][10] = 1;
    else
        $R[$pid_cnt][10]++;
}


$sql = "SELECT date(in_date) md,count(1) c FROM (select * from solution where `contest_id`=? and result<13 and problem_id>0 and  result>=4 ) solution group by md order by md desc limit 1000";
$result = mysql_query_cache($sql, $cid);
$chart_data_all = array();
//echo $sql;
if (!empty($result))
    foreach ($result as $row) {
        array_push($chart_data_all, array($row['md'], $row['c']));
    }

$sql = "SELECT date(in_date) md,count(1) c FROM  (select * from solution where `contest_id`=?  and result=4 and problem_id>0) solution group by md order by md desc limit 1000";
$result2 = mysql_query_cache($sql, $cid);
$ac = array();
foreach ($result2 as $row) {
    $ac[$row['md']] = $row['c'];
}
$chart_data_ac = array();
//echo $sql;
if (!empty($result)){
    foreach ($result as $row) {
        if (isset($ac[$row['md']]))
            array_push($chart_data_ac, array($row['md'], $ac[$row['md']]));
        else
            array_push($chart_data_ac, array($row['md'], 0));
    }
}


if (!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT = 0;
$lock = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;

//echo $lock.'-'.date("Y-m-d H:i:s",$lock);
$view_lock_time = $start_time + ($end_time - $start_time) * (1 - $OJ_RANK_LOCK_PERCENT);
$locked_msg = "";
if (time() > $view_lock_time && time() < $end_time + $OJ_RANK_LOCK_DELAY) {
    $locked_msg = "The board has been locked.";
}

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/conteststatistics.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
