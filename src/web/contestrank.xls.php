<?php
ini_set("display_errors", "Off");
// [2026-04-21] Excel 2003 SpreadsheetML — 경고 없이 서식 포함 렌더링
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("X-Content-Type-Options: nosniff");
require_once("./include/db_info.inc.php");
global $mark_base, $mark_per_problem, $mark_per_punish;
$mark_start = 60;
$mark_end = 100;
$mark_sigma = 5;
if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");

/**
 * 竞赛选手类，用于记录和计算选手的解题情况、时间和分数
 */
class TM
{
    var $solved = 0;      // 解决的问题数量
    var $time = 0;        // 总用时
    var $p_wa_num;        // 每个问题的错误提交次数
    var $p_ac_sec;        // 每个问题的通过时间
    var $user_id;         // 用户ID
    var $nick;            // 用户昵称
    var $school = '';     // 학년-반
    var $student_no = ''; // 학생 번호
    var $mark = 0;        // 得分

    function TM()
    {
        $this->solved = 0;
        $this->time = 0;
        $this->p_wa_num = array();
        $this->p_ac_sec = array();
    }

    /**
     * 添加一次提交记录
     * @param int $pid 问题ID
     * @param int $sec 提交时间（秒）
     * @param int $res 提交结果
     * @param int $mark_base 基础分数
     * @param int $mark_per_problem 每题分数
     * @param int $mark_per_punish 惩罚分数
     */
    function Add($pid, $sec, $res, $mark_base, $mark_per_problem, $mark_per_punish)
    {
        global $OJ_CE_PENALTY;
//		echo "Add $pid $sec $res<br>";

        if (isset($this->p_ac_sec[$pid]))
            return;
        if ($res != 4) {
            //$this->p_ac_sec[$pid]=0;
            if (isset($OJ_CE_PENALTY) && !$OJ_CE_PENALTY && $res == 11) return;  // ACM WF punish no ce
            if (isset($this->p_wa_num[$pid])) {
                $this->p_wa_num[$pid]++;
            } else {
                $this->p_wa_num[$pid] = 1;
            }
        } else {
            $this->p_ac_sec[$pid] = $sec;
            $this->solved++;
            $this->time += $sec + $this->p_wa_num[$pid] * 1200;
            if ($this->mark == 0) {
                $this->mark = $mark_base;
            } else {
                $this->mark += $mark_per_problem;
            }
            if ($this->p_wa_num[$pid] == "") $this->p_wa_num[$pid] = 0;
            $punish = intval($this->p_wa_num[$pid] * $mark_per_punish);
            if ($punish < intval($mark_per_problem * .8))
                $this->mark -= $punish;
            else
                $this->mark -= intval($mark_per_problem * .8);
        }
    }
}

/**
 * 比较函数，用于排序选手
 * @param object $A 选手A
 * @param object $B 选手B
 * @return bool 排序结果
 */
function s_cmp($A, $B)
{
//	echo "Cmp....<br>";
    if ($A->solved != $B->solved) return $A->solved < $B->solved;
    else return $A->time > $B->time;
}

/**
 * 计算正态分布值
 * @param float $x 输入值
 * @param float $u 均值
 * @param float $s 标准差
 * @return float 正态分布概率密度值
 */
function normalDistribution($x, $u, $s)
{

    $ret = 1 / ($s * sqrt(2 * M_PI))
        * pow(M_E, -pow($x - $u, 2) / (2 * $s * $s));

    return $ret;

}

/**
 * 根据正态分布为用户分配分数
 * @param array $users 用户数组
 * @param int $start 分数起始值
 * @param int $end 分数结束值
 * @param int $s 分布参数
 * @return int 返回用户数量
 */
function getMark($users, $start, $end, $s)
{
    $accum = 0;
    $p = 0;
    $ret = 0;
    $cn = count($users);


    for ($i = $end; $i > $start; $i--) {

        $prob = $cn
            * normalDistribution($i, ($start + $end) / 2 + 10, ($end - $start)
                / $s);
        $accum += $prob;


    }

    $p = $accum / $cn;
    $accum = 0;
    $i = 0;

    for ($i = $end; $i > $start; $i--) {
        $prob = $cn
            * normalDistribution($i, ($start + $end) / 2 + 10, ($end - $start)
                / $s);
        $accum += $prob;
        while ($accum > $p / 2) {
            if ($ret < $cn)
                $users[$ret]->mark = $i;
            $accum -= $p;
            $ret++;
        }
    }
    while ($ret < $cn) {
        $users[$ret]->mark = $users[$ret - 1]->mark;
        $ret++;
    }
    return $ret;

}


// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid = intval($_GET['cid']);
if (isset($OJ_NO_CONTEST_WATCHER) && $OJ_NO_CONTEST_WATCHER) require_once("contest-check.php");

// 获取竞赛信息
$sql = "SELECT `start_time`,`title`,`end_time`,`exam_max_score` FROM `contest` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);
$rows_cnt = count($result);
$start_time = 0;
$end_time = 0;
$exam_max_score = 0;
if ($rows_cnt > 0) {
    $row = $result[0];
    $start_time = strtotime($row[0]);
    $title = $row[1];
    $end_time = strtotime($row[2]);
    $exam_max_score = intval($row[3] ?? 0);
    // [2026-04-21] SpreadsheetML XML → .xls 확장자 + 타임스탬프
    $ftitle_raw = mb_substr($title, 0, 32);
    $fbase = preg_replace('/\.|\\\\|\\/|\:|\*|\?|\"|\<|\>|\|/', '', str_replace(' ', '', "C$cid-" . $ftitle_raw));
    $ftimestamp = date('YmdHis');
    $ftitle = rawurlencode($fbase . "_" . $ftimestamp . ".xls");
    $fallback = "contest_" . $cid . "_" . $ftimestamp . ".xls";
    header('Content-Disposition: attachment; filename="' . $fallback . '"; filename*=utf-8\'\'' . $ftitle);
}

if ($start_time == 0) {
    echo "No Such Contest";
    //require_once("oj-footer.php");
    exit(0);
}

if ($start_time > time()) {
    echo "Contest Not Started!";
    //require_once("oj-footer.php");
    exit(0);
}
$noip = (time() < $end_time) && (stripos($title, $OJ_NOIP_KEYWORD) !== false);
if (isset($_SESSION[$OJ_NAME . '_' . "administrator"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "m$cid"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "source_browser"]) ||
    isset($_SESSION[$OJ_NAME . '_' . "contest_creator"])
) {
    $noip = false;
} else if ($noip || contest_locked($cid, 20)) {
    $view_errors = "<h2>$MSG_NOIP_WARNING</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}
if (!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT = 0;
$lock = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;

// 获取竞赛题目数量并设置分数参数
$sql = "SELECT count(1) FROM `contest_problem` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);
$row = $result[0];
$pid_cnt = intval($row[0]);
if ($pid_cnt == 1) {
    $mark_base = 100;
    $mark_per_problem = 0;
} else {
    $mark_per_problem = (100 - $mark_base) / ($pid_cnt - 1);
}
$mark_per_punish = $mark_per_problem / 5;

// 获取竞赛提交记录 (users JOIN으로 school, student_no 추가)
$sql = "select
        solution.user_id, users.nick, users.school, users.student_no,
        solution.result, solution.num, solution.in_date
        from solution
        INNER JOIN users ON users.user_id = solution.user_id
        where solution.contest_id=? and solution.num>=0 and solution.problem_id>0
        ORDER BY solution.user_id, solution.solution_id";

//echo $sql;
$result = mysql_query_cache($sql, $cid);
$user_cnt = 0;
$user_name = '';
$U = array();
foreach ($result as $row) {
    $n_user = $row['user_id'];
    if (strcmp($user_name, $n_user)) {
        $user_cnt++;
        $U[$user_cnt] = new TM();
        $U[$user_cnt]->user_id = $row['user_id'];
        $U[$user_cnt]->nick = $row['nick'];
        $U[$user_cnt]->school = $row['school'] ?? '';
        $U[$user_cnt]->student_no = $row['student_no'] ?? '';

        $user_name = $n_user;
    }

    if (time() < $end_time + $OJ_RANK_LOCK_DELAY && $lock < strtotime($row['in_date']) && !isset($_SESSION[$OJ_NAME . '_' . 'administrator']))
        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 0, $mark_base, $mark_per_problem, $mark_per_punish);
    else
        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, intval($row['result']), $mark_base, $mark_per_problem, $mark_per_punish);
}

usort($U, "s_cmp");
// 获取未提交的用户列表
$absentList = mysql_query_cache("select user_id,nick,school,student_no from users where user_id in (select user_id from privilege where rightstr='c$cid' and user_id not in (select distinct user_id from solution where contest_id=?))", $cid);
foreach ($absentList as $row) {
    $U[$user_cnt] = new TM();
    $U[$user_cnt]->user_id = $row['user_id'];
    $U[$user_cnt]->nick = $row['nick'];
    $U[$user_cnt]->school = $row['school'] ?? '';
    $U[$user_cnt]->student_no = $row['student_no'] ?? '';
    $user_cnt++;
}

// ═══ [2026-04-21 고급 SpreadsheetML XML — 디자인 강화판] ═══

getMark($U, $mark_start, $mark_end, $mark_sigma);

// rank 부여
$rank = 1;
for ($i = 0; $i < $user_cnt; $i++) {
    if (!isset($U[$i])) continue;
    if (isset($U[$i]->nick[0]) && $U[$i]->nick[0] === '*') {
        $U[$i]->rank_display = '*';
    } else {
        $U[$i]->rank_display = strval($rank);
        $rank++;
    }
}

// 학번 생성 + 정렬
function _build_hakbun($school, $student_no) {
    if (empty($school) || empty($student_no)) return '';
    $sch = str_replace('-', '', trim($school));
    $num = str_pad(trim((string)$student_no), 2, '0', STR_PAD_LEFT);
    return $sch . $num;
}
usort($U, function($a, $b) {
    $ka = _build_hakbun($a->school, $a->student_no);
    $kb = _build_hakbun($b->school, $b->student_no);
    if ($ka === '') $ka = 'ZZZZ';
    if ($kb === '') $kb = 'ZZZZ';
    return strcmp($ka, $kb);
});

function _xml_esc($v) {
    return htmlspecialchars((string)$v, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

// ═══ 풍부한 스타일 정의 ═══
echo '<Styles>
  <Style ss:ID="Default" ss:Name="Normal">
    <Alignment ss:Vertical="Center"/>
    <Font ss:FontName="맑은 고딕" x:Family="Swiss" ss:Size="11" ss:Color="#1F2937"/>
  </Style>

  <!-- 메인 타이틀 (1행) -->
  <Style ss:ID="sTitle">
    <Font ss:FontName="맑은 고딕" ss:Size="16" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#6D28D9" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>

  <!-- 부제 (2행) -->
  <Style ss:ID="sSubtitle">
    <Font ss:FontName="맑은 고딕" ss:Size="10" ss:Color="#7C3AED"/>
    <Interior ss:Color="#F5F3FF" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>

  <!-- 헤더 행 -->
  <Style ss:ID="sHeader">
    <Font ss:FontName="맑은 고딕" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>
    <Interior ss:Color="#7C3AED" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#4C1D95"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#4C1D95"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#5B21B6"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#5B21B6"/>
    </Borders>
  </Style>

  <!-- 일반 데이터 (홀수 행) -->
  <Style ss:ID="sData">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>

  <!-- 짝수 행 (zebra striping) -->
  <Style ss:ID="sDataAlt">
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Interior ss:Color="#FAFAFA" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>

  <!-- 왼쪽 정렬 (User, Nick) -->
  <Style ss:ID="sDataLeft">
    <Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:Indent="1"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
  <Style ss:ID="sDataLeftAlt">
    <Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:Indent="1"/>
    <Interior ss:Color="#FAFAFA" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>

  <!-- 학번 (monospace 느낌 + 진한 보라) -->
  <Style ss:ID="sHakbun">
    <Font ss:FontName="D2Coding,Consolas,맑은 고딕" ss:Size="11" ss:Bold="1" ss:Color="#6D28D9"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>
  <Style ss:ID="sHakbunAlt">
    <Font ss:FontName="D2Coding,Consolas,맑은 고딕" ss:Size="11" ss:Bold="1" ss:Color="#6D28D9"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Interior ss:Color="#FAFAFA" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>
    </Borders>
  </Style>

  <!-- 수행평가 상위 (20/18/16, 40/36/32) -->
  <Style ss:ID="sExamHi">
    <Font ss:FontName="맑은 고딕" ss:Size="12" ss:Bold="1" ss:Color="#15803D"/>
    <Interior ss:Color="#DCFCE7" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#86EFAC"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#86EFAC"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#86EFAC"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#86EFAC"/>
    </Borders>
  </Style>
  <!-- 수행평가 중간 (14/12, 28/24) -->
  <Style ss:ID="sExamMid">
    <Font ss:FontName="맑은 고딕" ss:Size="12" ss:Bold="1" ss:Color="#B45309"/>
    <Interior ss:Color="#FEF3C7" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCD34D"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCD34D"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCD34D"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCD34D"/>
    </Borders>
  </Style>
  <!-- 수행평가 하위 (10/8/6, 20/16/12) -->
  <Style ss:ID="sExamLow">
    <Font ss:FontName="맑은 고딕" ss:Size="12" ss:Bold="1" ss:Color="#B91C1C"/>
    <Interior ss:Color="#FEE2E2" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCA5A5"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCA5A5"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCA5A5"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FCA5A5"/>
    </Borders>
  </Style>

  <!-- AC 셀 (해결) -->
  <Style ss:ID="sAc">
    <Font ss:FontName="맑은 고딕" ss:Size="10.5" ss:Bold="1" ss:Color="#166534"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Interior ss:Color="#F0FDF4" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#BBF7D0"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#BBF7D0"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#BBF7D0"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#BBF7D0"/>
    </Borders>
  </Style>

  <!-- WA만 있는 셀 -->
  <Style ss:ID="sWa">
    <Font ss:FontName="맑은 고딕" ss:Size="10.5" ss:Color="#B91C1C"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Interior ss:Color="#FEF2F2" ss:Pattern="Solid"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FECACA"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FECACA"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FECACA"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#FECACA"/>
    </Borders>
  </Style>

  <!-- Rank 1등 -->
  <Style ss:ID="sRank1">
    <Font ss:FontName="맑은 고딕" ss:Size="12" ss:Bold="1" ss:Color="#92400E"/>
    <Interior ss:Color="#FEF3C7" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
    <Borders>
      <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#F59E0B"/>
      <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#F59E0B"/>
      <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#F59E0B"/>
      <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#F59E0B"/>
    </Borders>
  </Style>

</Styles>' . "\n";

$sheet_name = mb_substr(preg_replace('/[\\\\\/*?\[\]:]/u', '', $title ?? 'Sheet1'), 0, 30);
if (!$sheet_name) $sheet_name = 'Ranking';

$header_cols = ["Rank", "User", "학번", "Nick", "Solved", "수행평가", "Mark"];
for ($i = 0; $i < $pid_cnt; $i++) $header_cols[] = $PID[$i];
$total_cols = count($header_cols);

echo '<Worksheet ss:Name="' . _xml_esc($sheet_name) . '">' . "\n";
echo '<Table ss:DefaultRowHeight="22">' . "\n";

// 컬럼 너비
$col_widths = [55, 110, 70, 85, 60, 72, 60];
for ($i = 0; $i < $pid_cnt; $i++) $col_widths[] = 95;
foreach ($col_widths as $w) echo '  <Column ss:Width="' . $w . '"/>';
echo "\n";

// 타이틀 행 (전체 병합, 큰 글씨)
echo '  <Row ss:Height="36">';
echo '<Cell ss:MergeAcross="' . ($total_cols - 1) . '" ss:StyleID="sTitle"><Data ss:Type="String">🏆 ' . _xml_esc($title ?? 'Contest RankList') . '</Data></Cell>';
echo '</Row>' . "\n";

// 부제 행 (대회 기간)
$date_str = date('Y년 n월 j일', $start_time) . ' · ' . date('H:i', $start_time) . ' ~ ' . date('H:i', $end_time);
echo '  <Row ss:Height="22">';
echo '<Cell ss:MergeAcross="' . ($total_cols - 1) . '" ss:StyleID="sSubtitle"><Data ss:Type="String">' . _xml_esc($date_str) . ' · 총 ' . count($U) . '명 참가 · ' . $pid_cnt . '문제</Data></Cell>';
echo '</Row>' . "\n";

// 빈 행
echo '  <Row ss:Height="6"/>' . "\n";

// 헤더 행
echo '  <Row ss:Height="30">';
foreach ($header_cols as $h) {
    echo '<Cell ss:StyleID="sHeader"><Data ss:Type="String">' . _xml_esc($h) . '</Data></Cell>';
}
echo '</Row>' . "\n";

// 데이터 행
$row_idx = 0;
foreach ($U as $u) {
    if (!isset($u)) continue;
    $row_idx++;
    $alt = ($row_idx % 2 === 0); // zebra striping
    $usolved = $u->solved;
    $hakbun = _build_hakbun($u->school, $u->student_no);
    $nick = $u->nick;
    if (preg_match('/^[=+\-@]/', $nick)) $nick = "'" . $nick;

    // 수행평가 점수 계산
    $_exam_mx = $exam_max_score > 0 ? $exam_max_score : 20;
    $_rate = $pid_cnt > 0 ? ($usolved / $pid_cnt) : 0;
    if ($_exam_mx == 20) {
        if      ($_rate >= 0.9) $_exam_pt = 20;
        elseif  ($_rate >= 0.7) $_exam_pt = 18;
        elseif  ($_rate >= 0.6) $_exam_pt = 16;
        elseif  ($_rate >= 0.5) $_exam_pt = 14;
        elseif  ($_rate >= 0.4) $_exam_pt = 12;
        elseif  ($_rate >= 0.3) $_exam_pt = 10;
        elseif  ($_rate >= 0.2) $_exam_pt = 8;
        else                    $_exam_pt = 6;
    } else {
        if      ($_rate >= 0.9) $_exam_pt = 40;
        elseif  ($_rate >= 0.7) $_exam_pt = 36;
        elseif  ($_rate >= 0.6) $_exam_pt = 32;
        elseif  ($_rate >= 0.5) $_exam_pt = 28;
        elseif  ($_rate >= 0.4) $_exam_pt = 24;
        elseif  ($_rate >= 0.3) $_exam_pt = 20;
        elseif  ($_rate >= 0.2) $_exam_pt = 16;
        else                    $_exam_pt = 12;
    }
    // 수행평가 레벨 → 스타일
    if ($_exam_mx == 20) {
        if ($_exam_pt >= 16) $examStyle = 'sExamHi';
        elseif ($_exam_pt >= 12) $examStyle = 'sExamMid';
        else $examStyle = 'sExamLow';
    } else {
        if ($_exam_pt >= 32) $examStyle = 'sExamHi';
        elseif ($_exam_pt >= 24) $examStyle = 'sExamMid';
        else $examStyle = 'sExamLow';
    }

    if ($usolved == 0) $u->mark = 0;
    $mark_val = $u->mark > 0 ? intval($u->mark) : 0;

    $dataStyle = $alt ? 'sDataAlt' : 'sData';
    $leftStyle = $alt ? 'sDataLeftAlt' : 'sDataLeft';
    $hakbunStyle = $alt ? 'sHakbunAlt' : 'sHakbun';

    echo '  <Row ss:Height="24">';
    // Rank
    if ($u->rank_display === '*') {
        echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="String">*</Data></Cell>';
    } elseif (intval($u->rank_display) === 1) {
        echo '<Cell ss:StyleID="sRank1"><Data ss:Type="String">🥇 1</Data></Cell>';
    } elseif (intval($u->rank_display) === 2) {
        echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="String">🥈 2</Data></Cell>';
    } elseif (intval($u->rank_display) === 3) {
        echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="String">🥉 3</Data></Cell>';
    } else {
        echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="Number">' . intval($u->rank_display) . '</Data></Cell>';
    }
    // User
    echo '<Cell ss:StyleID="' . $leftStyle . '"><Data ss:Type="String">' . _xml_esc($u->user_id) . '</Data></Cell>';
    // 학번 (강조)
    echo '<Cell ss:StyleID="' . $hakbunStyle . '"><Data ss:Type="String">' . _xml_esc($hakbun) . '</Data></Cell>';
    // Nick
    echo '<Cell ss:StyleID="' . $leftStyle . '"><Data ss:Type="String">' . _xml_esc($nick) . '</Data></Cell>';
    // Solved
    echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="Number">' . intval($usolved) . '</Data></Cell>';
    // 수행평가 (레벨별 색상)
    echo '<Cell ss:StyleID="' . $examStyle . '"><Data ss:Type="Number">' . intval($_exam_pt) . '</Data></Cell>';
    // Mark
    echo '<Cell ss:StyleID="' . $dataStyle . '"><Data ss:Type="Number">' . intval($mark_val) . '</Data></Cell>';

    // 문제별
    for ($j = 0; $j < $pid_cnt; $j++) {
        $ac_time = isset($u->p_ac_sec[$j]) && $u->p_ac_sec[$j] > 0 ? sec2str($u->p_ac_sec[$j]) : '';
        $wa_cnt  = isset($u->p_wa_num[$j]) && $u->p_wa_num[$j] > 0 ? $u->p_wa_num[$j] : 0;

        $txt = '';
        if ($ac_time) $txt .= $ac_time;
        if ($wa_cnt > 0) $txt .= "(-" . $wa_cnt . ")";

        if ($ac_time)         $cStyle = 'sAc';
        elseif ($wa_cnt > 0)  $cStyle = 'sWa';
        else                  $cStyle = $dataStyle;

        if ($txt === '') {
            echo '<Cell ss:StyleID="' . $cStyle . '"/>';
        } else {
            echo '<Cell ss:StyleID="' . $cStyle . '"><Data ss:Type="String">' . _xml_esc($txt) . '</Data></Cell>';
        }
    }
    echo '</Row>' . "\n";
}

echo '</Table>' . "\n";

// 시트 옵션 (첫 4행 고정, 격자 숨김)
echo '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
  <PageSetup>
    <Layout x:Orientation="Landscape"/>
    <PageMargins x:Bottom="0.5" x:Left="0.5" x:Right="0.5" x:Top="0.5"/>
  </PageSetup>
  <Selected/>
  <DoNotDisplayGridlines/>
  <FreezePanes/>
  <FrozenNoSplit/>
  <SplitHorizontal>4</SplitHorizontal>
  <TopRowBottomPane>4</TopRowBottomPane>
  <ActivePane>2</ActivePane>
  <Panes>
    <Pane><Number>3</Number></Pane>
    <Pane><Number>2</Number><ActiveRow>4</ActiveRow><ActiveCol>0</ActiveCol></Pane>
  </Panes>
</WorksheetOptions>' . "\n";
echo '</Worksheet>' . "\n";
echo '</Workbook>' . "\n";
exit(0);





