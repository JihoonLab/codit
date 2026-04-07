<?php
$OJ_CACHE_SHARE = false;
$cache_time = 30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("./include/my_func.inc.php");
require_once('./include/setlang.php');
require_once('./include/memcache.php');
$now = date('Y-m-d H:i', time());
$sql = "select count(contest_id) from contest where start_time<'$now' and end_time>'$now' and ( title like '%$OJ_NOIP_KEYWORD%' or (contest_type & 20)>0 )  ";
$rows = pdo_query($sql);
$row = $rows[0];
if ($row[0] > 0) {
    $view_errors = "<h2> $MSG_NOIP_WARNING </h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

$view_title = $MSG_RANKLIST;
if (!isset($OJ_RANK_HIDDEN)) $OJ_RANK_HIDDEN = "'admin','zhblue'";

$scope = "";
if (isset($_GET['scope']))
    $scope = $_GET['scope'];
if ($scope != "" && $scope != 'd' && $scope != 'w' && $scope != 'm')
    $scope = 'y';
$where = "";
$param = array();
if (isset($_GET['prefix'])) {
    $prefix = $_GET['prefix'];
    $where = "where u.user_id like ? and u.user_id not in (" . $OJ_RANK_HIDDEN . ") and u.defunct='N' ";
    array_push($param, $prefix . "%");
} else {
    $where = "where u.user_id not in (" . $OJ_RANK_HIDDEN . ") and u.defunct='N' ";
}
if (isset($_GET['group_name']) && !empty($_GET['group_name'])) {
    $group_name = $_GET['group_name'];
    $where .= "and u.group_name like ? ";
    array_push($param, $group_name . '%');
}
if (isset($_GET['school']) && !empty($_GET['school'])) {
    $view_school_filter = $_GET['school'];
    $where .= "and u.school = ? ";
    array_push($param, $view_school_filter);
}
// 학년 필터
$view_grade = '';
if (isset($_GET['grade']) && in_array($_GET['grade'], ['2','3'])) {
    $view_grade = $_GET['grade'];
    $where .= "and u.school LIKE ? ";
    array_push($param, $view_grade . '-%');
} else if (isset($_GET['grade']) && $_GET['grade'] === 'all') {
    // 명시적 "전체" 선택: 자동감지 안함
    $view_grade = '';
} else {
    // 자동 감지: 로그인한 학생의 학년
    if (isset($_SESSION[$OJ_NAME.'_user_id'])) {
        $my_sch_row = pdo_query("SELECT school FROM users WHERE user_id=?", $_SESSION[$OJ_NAME.'_user_id']);
        if (!empty($my_sch_row)) {
            $my_sch = $my_sch_row[0]['school'] ?? '';
            $my_sch_p = explode('-', $my_sch);
            if (count($my_sch_p) === 2 && in_array($my_sch_p[0], ['2','3'])) {
                $view_grade = $my_sch_p[0];
                $where .= "and u.school LIKE ? ";
                array_push($param, $view_grade . '-%');
            }
        }
    }
}
$rank = 0;

// total count (학년 필터 반영)
if ($view_grade !== '') {
    $cnt_result = pdo_query("SELECT count(1) as mycount FROM users WHERE defunct='N' AND school LIKE ?", $view_grade . '-%');
    $view_total = $cnt_result[0]['mycount'] ?? 0;
} else {
    $cnt_result = mysql_query_cache("SELECT count(1) as mycount FROM users WHERE defunct='N'");
    $view_total = $cnt_result[0]['mycount'] ?? 0;
}

if (isset($_GET ['start']))
    $rank = intval($_GET ['start']);

if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}
$page_size = 50;
//$rank = intval ( $_GET ['start'] );
if ($rank < 0)
    $rank = 0;

$sql = "SELECT u.`user_id`,u.`nick`,u.`solved`, IFNULL(sc.cnt,0) as `submit`, u.group_name, u.school, u.starred FROM `users` u LEFT JOIN (SELECT user_id, COUNT(*) as cnt FROM solution GROUP BY user_id) sc ON u.user_id=sc.user_id $where ORDER BY u.`solved` DESC, u.submit, u.reg_time LIMIT " . strval($rank) . ",$page_size";

if ($scope) {
    $s = "";
    switch ($scope) {
        case 'd':
            $s = date('Y') . '-' . date('m') . '-' . date('d');
            break;
        case 'w':
            $monday = mktime(0, 0, 0, date("m"), date("d") - (date("w") + 6) % 7, date("Y"));
            $s = date('Y-m-d', $monday);
            break;
        case 'm':
            $s = date('Y') . '-' . date('m') . '-01';
            break;
        default :
            $s = date('Y') . '-01-01';
    }
    $last_id = mysql_query_cache("select solution_id from solution where  in_date<str_to_date('$s','%Y-%m-%d') order by solution_id desc limit 1;");
    if (!empty($last_id) && is_array($last_id)) $last_id = $last_id[0][0]; else $last_id = 0;
    $grade_scope_filter = ($view_grade !== '') ? "AND users.school LIKE '{$view_grade}-%'" : "";
    if ($view_grade !== '') {
        $view_total = mysql_query_cache("select count(distinct(s.user_id)) from solution s inner join users u on s.user_id=u.user_id where s.solution_id>$last_id and u.school LIKE '{$view_grade}-%'")[0][0];
    } else {
        $view_total = mysql_query_cache("select count(distinct(user_id)) from solution where solution_id>$last_id")[0][0];
    }
    $sql = "SELECT users.`user_id`,`nick`,s.`solved`,t.`submit`,group_name,school,starred FROM `users`
                                        inner join
                                        (select count(distinct (problem_id)) solved ,user_id from solution
                                               where solution_id>$last_id and user_id not in (" . $OJ_RANK_HIDDEN . ") and problem_id>0 and result=4 and first_time=1
					       group by user_id order by solved desc limit " . strval($rank) . ",$page_size) s
                                        on users.user_id=s.user_id
                                        inner join
                                        (select count( problem_id) submit ,user_id from solution
                                                where solution_id > $last_id
                                                group by user_id order by submit desc ) t
                                        on users.user_id=t.user_id
                                        and users.user_id not in (" . $OJ_RANK_HIDDEN . ") and defunct='N'
                                        $grade_scope_filter
                                ORDER BY s.`solved` DESC,t.submit,reg_time  LIMIT  0,50
                         ";
//                      echo $sql;
}


if (!empty($param)) {
    $result = pdo_query($sql, $param);
} else {
    $result = mysql_query_cache($sql);
}
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;
$view_rank = array();
$i = 0;
for ($i = 0; $i < $rows_cnt; $i++) {

    $row = $result[$i];

    $rank++;

    $view_rank[$i][0] = $rank;
    $view_rank[$i][1] = "<a href='userinfo.php?user=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "'>" . $row['user_id'] . "</a>";
    // starred display removed
    $view_rank[$i][2] = "<div class=center>" . htmlentities($row['nick'], ENT_QUOTES, "UTF-8") . "</div>";
    $sch = $row['school'] ?? '';
    $sch_parts = explode('-', $sch);
    $sch_label = (count($sch_parts)===2 && is_numeric($sch_parts[0]) && is_numeric($sch_parts[1]))
        ? $sch_parts[0].'학년 '.$sch_parts[1].'반' : htmlentities($sch, ENT_QUOTES, "UTF-8");
    $view_rank[$i][3] = "<div class=center>" . $sch_label . "</div>";
    $view_rank[$i][4] = "<div class=center><a href='status.php?user_id=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "&jresult=4'>" . $row['solved'] . "</a>" . "</div>";
    $view_rank[$i][5] = "<div class=center><a href='status.php?user_id=" . htmlentities($row['user_id'], ENT_QUOTES, "UTF-8") . "'>" . $row['submit'] . "</a>" . "</div>";

    if ($row['submit'] == 0)
        $view_rank[$i][6] = "0.00%";
    else
        $view_rank[$i][6] = sprintf("%.02lf%%", 100 * $row['solved'] / $row['submit']);

//                      $i++;
}


// 반별 랭킹 집계
$view_class_rank = array();
$class_sql = "SELECT u.school,
  COUNT(*) as member_count,
  SUM(u.solved) as total_solved,
  SUM(IFNULL(sc.cnt,0)) as total_submit,
  ROUND(AVG(u.solved),1) as avg_solved,
  ROUND(IF(SUM(IFNULL(sc.cnt,0))>0, 100*SUM(u.solved)/SUM(IFNULL(sc.cnt,0)), 0), 2) as avg_rate
  FROM users u
  LEFT JOIN (SELECT user_id, COUNT(*) as cnt FROM solution GROUP BY user_id) sc ON u.user_id=sc.user_id
  WHERE u.defunct='N' AND u.school IS NOT NULL AND u.school != '' AND u.school LIKE '%-%'
  AND u.user_id NOT IN (" . $OJ_RANK_HIDDEN . ")
  " . ($view_grade !== '' ? "AND u.school LIKE '{$view_grade}-%'" : "") . "
  GROUP BY u.school
  ORDER BY total_solved DESC, total_submit ASC";
$class_result = pdo_query($class_sql);
if($class_result) {
  $ci = 0;
  foreach($class_result as $crow) {
    $sch_parts = explode('-', $crow['school']);
    if(count($sch_parts) === 2 && is_numeric($sch_parts[0]) && is_numeric($sch_parts[1])) {
      $view_class_rank[$ci] = array(
        'label' => $sch_parts[0].'학년 '.$sch_parts[1].'반',
        'school' => $crow['school'],
        'members' => intval($crow['member_count']),
        'solved' => intval($crow['total_solved']),
        'submit' => intval($crow['total_submit']),
        'avg_solved' => floatval($crow['avg_solved']),
        'rate' => floatval($crow['avg_rate'])
      );
      $ci++;
    }
  }
}

/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/ranklist.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>


