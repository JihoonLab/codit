<?php
$OJ_CACHE_SHARE = true;
$cache_time = 10;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
require_once("./include/memcache.php");
$view_title = $MSG_CONTEST . $MSG_RANKLIST;
$title = "";

/**
 * TM类 - 用于存储和计算用户在比赛中的成绩信息
 * 包含解题数量、总时间、错误提交次数、通过时间等信息
 */
class TM
{
    var $solved = 0;      // 解题数量
    var $time = 0;        // 总时间（包含罚时）
    var $p_wa_num;        // 每道题的错误提交次数数组
    var $p_ac_sec;        // 每道题的通过时间数组
    var $user_id;         // 用户ID
    var $nick;            // 用户昵称

    /**
     * 构造函数 - 初始化TM对象的属性
     */
    function __construct()
    {
        $this->solved = 0;
        $this->time = 0;
        $this->p_wa_num = array();
        $this->p_ac_sec = array();
    }

    /**
     * 添加提交记录到用户成绩中
     * @param int $pid 题目编号
     * @param int $sec 通过时间（相对于比赛开始的时间）
     * @param int $res 提交结果代码
     */
    function Add($pid, $sec, $res)
    {
        global $OJ_CE_PENALTY;
        //echo "Add $pid $sec $res<br>";
        if ($sec < 0) return;  // restarted contest ignore previous submission
        if (isset($this->p_ac_sec[$pid]))  // already solved  ignore later submission
            return;
        if ($res != 4) {
            //$this->p_ac_sec[$pid]=0;
            if (isset($OJ_CE_PENALTY) && !$OJ_CE_PENALTY && $res == 11)
                return;  // ACM WF punish no ce
            if (isset($this->p_wa_num[$pid])) {
                $this->p_wa_num[$pid]++;
            } else {
                $this->p_wa_num[$pid] = 1;
            }
        } else {
            $this->p_ac_sec[$pid] = $sec;
            $this->solved++;

            if (!isset($this->p_wa_num[$pid]))
                $this->p_wa_num[$pid] = 0;

            $this->time += $sec + $this->p_wa_num[$pid] * 1200;   // 每次错误提交罚时20分钟
            //echo "Time:".$this->time."<br>";
            //echo "Solved:".$this->solved."<br>";
        }
    }
}

/**
 * 排序比较函数 - 用于对用户成绩进行排序
 * 先按解题数量降序，再按总时间升序
 * @param object $A 第一个TM对象
 * @param object $B 第二个TM对象
 * @return int 比较结果（用于usort）
 */
function s_cmp($A, $B)
{
    //echo "Cmp....<br>";
    if ($A->solved != $B->solved)
        return $A->solved < $B->solved;
    else
        return $A->time > $B->time;
}

// contest start time
if (!isset($_GET['cid']))
    die("No Such Contest!");

$cid = intval($_GET['cid']);

// 查询比赛信息
$sql = "select `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`=? ";
$result = mysql_query_cache($sql, $cid);
if ($result)
    $rows_cnt = count($result);
else
    $rows_cnt = 0;


$start_time = 0;
$end_time = 0;

if ($rows_cnt > 0) {
    //$row=$result[0];

    $row = $result[0];

    $start_time = strtotime($row['start_time']);
    $end_time = strtotime($row['end_time']);
    $title = $row['title'];
    $view_title = $title;
    if (isset($_GET['down'])) {
        header("Content-type:   application/excel");
        $ftitle = rawurlencode(preg_replace('/\.|\\\|\\/|\:|\*|\?|\"|\<|\>|\|/', '', $title));
        header("content-disposition:   attachment;   filename=contest" . $cid . "_" . $ftitle . ".xls");
    }

}

if (!$OJ_MEMCACHE)
    if ($start_time == 0) {
        $view_errors = "Wrong $MSG_CONTEST id";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }

// 检查比赛是否已经开始
if ($start_time > time()) {
    $view_errors = "$MSG_CONTEST $MSG_Contest_Pending!";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

// 检查是否为NOIP比赛并进行权限验证
$noip = (time() < $end_time) && (stripos($title, $OJ_NOIP_KEYWORD) !== false);
if (isset($_SESSION[$OJ_NAME . '_' . "administrator"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "m$cid"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "source_browser"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "contest_creator"])
) {
    $noip = false;
} else if ($noip || contest_locked($cid, 20)) {   // 20 = 2^2 + 2^4
    $view_errors = "<h2>$MSG_NOIP_WARNING</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

if (!isset($OJ_RANK_LOCK_PERCENT))
    $OJ_RANK_LOCK_PERCENT = 0;

$lock = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;

//echo $lock.'-'.date("Y-m-d H:i:s",$lock);
$view_lock_time = $start_time + ($end_time - $start_time) * (1 - $OJ_RANK_LOCK_PERCENT);
$locked_msg = "";

if (time() > $view_lock_time && time() < $end_time + $OJ_RANK_LOCK_DELAY) {
    $locked_msg = "The board has been locked.";
}

// 获取比赛题目数量
$sql = "SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);

if ($result)
    $rows_cnt = count($result);
else
    $rows_cnt = 0;

$row = $result[0];

//$row=$result[0];
$pid_cnt = intval($row['pbc']);

// ═══════════════════════════════════════════════════════════
// [수행평가 채점] 과목/만점 결정
// 우선순위: 1) 관리자가 수동 설정한 exam_max_score (20 또는 40)
//           2) 자동 감지 (problem_id 다수결)
// ═══════════════════════════════════════════════════════════
$_exam_row = mysql_query_cache("SELECT exam_max_score FROM contest WHERE contest_id=?", $cid);
$_exam_stored = intval($_exam_row[0]['exam_max_score'] ?? 0);
$_exam_auto_detect = ($_exam_stored == 0);

if ($_exam_stored == 20) {
    $exam_max_score   = 20;
    $exam_subject     = 'C';
    $exam_subject_lbl = '2학년 정보 (C)';
} elseif ($_exam_stored == 40) {
    $exam_max_score   = 40;
    $exam_subject     = 'PY';
    $exam_subject_lbl = '3학년 인공지능기초 (Python)';
} else {
    // 자동 감지
    $exam_subject_counts = mysql_query_cache(
        "SELECT
            SUM(CASE WHEN problem_id < 1000 THEN 1 ELSE 0 END) AS c_cnt,
            SUM(CASE WHEN problem_id >= 1000 THEN 1 ELSE 0 END) AS py_cnt
         FROM contest_problem WHERE contest_id=?", $cid);
    $c_cnt_tmp  = intval($exam_subject_counts[0]['c_cnt']  ?? 0);
    $py_cnt_tmp = intval($exam_subject_counts[0]['py_cnt'] ?? 0);

    if ($c_cnt_tmp >= $py_cnt_tmp) {
        $exam_max_score   = 20;
        $exam_subject     = 'C';
        $exam_subject_lbl = '2학년 정보 (C)';
    } else {
        $exam_max_score   = 40;
        $exam_subject     = 'PY';
        $exam_subject_lbl = '3학년 인공지능기초 (Python)';
    }
}

/**
 * 수행평가 점수 계산
 * @param int $solved 해결한 문제 수
 * @param int $total  전체 문제 수
 * @param int $max    만점 (20 또는 40)
 * @return int 점수
 */
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
        return 6; // 기본점수
    } else { // 40
        if ($rate >= 0.9) return 40;
        if ($rate >= 0.7) return 36;
        if ($rate >= 0.6) return 32;
        if ($rate >= 0.5) return 28;
        if ($rate >= 0.4) return 24;
        if ($rate >= 0.3) return 20;
        if ($rate >= 0.2) return 16;
        return 12; // 기본점수
    }
}
}

require("./include/contest_solutions.php");

$user_cnt = 0;
$user_name = '';
$U = array();

//$U[$user_cnt]=new TM();
for ($i = 0; $i < $rows_cnt; $i++) {
    $row = $result[$i];
    $n_user = $row['user_id'];

    if (strcmp($user_name, $n_user)) {
        $user_cnt++;
        $U[$user_cnt] = new TM();

        $U[$user_cnt]->user_id = $row['user_id'];
        $U[$user_cnt]->nick = $row['nick'];

        $user_name = $n_user;
    }

    if (time() < $end_time + $OJ_RANK_LOCK_DELAY && $lock < strtotime($row['in_date']))
        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 0);
    else
        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, intval($row['result']));
}

usort($U, "s_cmp");

////firstblood
$first_blood = array();
for ($i = 0; $i < $pid_cnt; $i++) {
    $first_blood[$i] = "";
}

// 查询每道题的首杀信息
$sql = "select s.num,s.user_id from solution s ,
(select num,min(solution_id) minId from solution where contest_id=? and result=4 GROUP BY num ) c where s.solution_id = c.minId";
$fb = mysql_query_cache($sql, $cid);

if ($fb)
    $rows_cnt = count($fb);
else
    $rows_cnt = 0;


for ($i = 0; $i < $rows_cnt; $i++) {
    $row = $fb[$i];
    $first_blood[$row['num']] = $row['user_id'];
}

// 获取只注册但未提交的参赛用户
$absent = mysql_query_cache("select user_id from privilege where rightstr='c$cid' and user_id not in (select distinct user_id from solution where contest_id=?)", $cid);
$absentList = mysql_query_cache("select user_id,nick from users where user_id in (select user_id from privilege where rightstr='c$cid' and user_id not in (select distinct user_id from solution where contest_id=?))", $cid);
foreach ($absentList as $row) {
    $U[$user_cnt] = new TM();
    $U[$user_cnt]->user_id = $row['user_id'];
    $U[$user_cnt]->nick = $row['nick'];
    $user_cnt++;
}

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/contestrank.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');

