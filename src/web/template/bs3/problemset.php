<?php if(!isset($_GET['ajax'])): ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>문제 목록 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.ps-wrap{max-width:1100px;margin:36px auto;padding:0 16px}
.ps-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.ps-top h2{font-size:22px;font-weight:700;color:#7c3aed;margin:0}
.ps-search-bar{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap}
.ps-search-bar input{flex:1;min-width:140px;padding:10px 16px;border:1.5px solid #e0e4ea;border-radius:8px;font-size:14px;outline:none}
.ps-search-bar input:focus{border-color:#7c3aed}
.ps-search-bar button{padding:10px 20px;background:#7c3aed;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer}
.ps-search-bar button:hover{background:#6d28d9}
.ps-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.ps-table thead tr{background:#7c3aed;color:#fff}
.ps-table th{padding:13px 16px;font-size:13px;font-weight:600;text-align:center}
.ps-table th.th-title{text-align:left}
.ps-table td{padding:12px 16px;font-size:14px;border-bottom:1px solid #f0f0f0;text-align:center;vertical-align:middle}
.ps-table td.td-title{text-align:left}
.ps-table tbody tr:hover{background:#f5f8ff}
.ps-table tbody tr:last-child td{border-bottom:none}
.ps-table td a{color:#7c3aed;text-decoration:none;font-weight:500}
.ps-table td a:hover{text-decoration:underline}
.ps-page{display:flex;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap}
.ps-page a,.ps-page span{padding:6px 13px;border-radius:6px;font-size:13px;border:1px solid #e0e0e0;color:#555;text-decoration:none}
.ps-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
.ps-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}
.badge-src{background:#fff3e0;color:#e67e22;border-radius:12px;padding:2px 10px;font-size:12px;font-weight:600;white-space:nowrap}
.ac-mark{color:#27ae60;font-weight:700;font-size:13px}
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ps-wrap">
  <div class="ps-top">
    <h2>📋 문제 목록</h2>
  </div>
  <div class="ps-search-bar">
    <form class="d-flex" action="problemset.php" style="display:flex;gap:10px;flex:2">
      <input type="text" name="search" id="ps-search-input" placeholder="제목, 출처/분류로 검색..." value="<?php echo htmlspecialchars($_GET['search'] ?? '')?>">
      <button type="submit">검색</button>
    </form>
  </div>
  <table class="ps-table" id="problemset">
    <thead>
      <tr>
        <th style="width:30px"></th>
        <th style="width:80px">문제ID</th>
        <th class="th-title">제목</th>
        <th style="width:160px">출처/분류</th>
        <th style="width:70px">통과</th>
        <th style="width:70px">제출</th>
      </tr>
    </thead>
    <tbody>
<?php endif; ?>
      <?php
      $cnt = 0;
      foreach($view_problemset as $row) {
        $cells = array_values((array)$row);
        echo "<tr>";
        echo "<td>" . $cells[0] . "</td>"; // AC 마크
        echo "<td class='hidden-xs'>" . $cells[1] . "</td>"; // 문제ID
        echo "<td class='td-title'>" . $cells[2] . "</td>"; // 제목
        echo "<td class='hidden-xs'>" . $cells[3] . "</td>"; // 출처
        echo "<td>" . $cells[4] . "</td>"; // 통과
        echo "<td>" . $cells[5] . "</td>"; // 제출
        echo "</tr>";
        $cnt++;
      }
      ?>
<?php if(!isset($_GET['ajax'])): ?>
    </tbody>
  </table>

  <!-- 페이지네이션 -->
  <div class="ps-page">
    <a href="problemset.php?page=1">&laquo;</a>
    <?php
    if(!isset($page)) $page = 1;
    $page = intval($page);
    $section = 8;
    $start = $page > $section ? $page - $section : 1;
    $end = min($page + $section, $view_total_page);
    for($i = $start; $i <= $end; $i++):
    ?>
    <?php if($page == $i): ?>
      <span class="active"><?php echo $i?></span>
    <?php else: ?>
      <a href="problemset.php?page=<?php echo $i . htmlentities($postfix, ENT_QUOTES, 'UTF-8')?>"><?php echo $i?></a>
    <?php endif; ?>
    <?php endfor; ?>
    <a href="problemset.php?page=<?php echo $view_total_page?>">&raquo;</a>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="include/jquery.tablesorter.js"></script>
<script>
$(document).ready(function() {
  $("#problemset").tablesorter();

  // 실시간 필터
  $("#ps-search-input").on("input", function() {
    var q = $(this).val().toLowerCase();
    if (q === "") {
      $("#problemset tbody tr").show();
      return;
    }
    $("#problemset tbody tr").each(function() {
      var title = $(this).find("td:eq(2)").text().toLowerCase();
      var src   = $(this).find("td:eq(3)").text().toLowerCase();
      var pid   = $(this).find("td:eq(1)").text().toLowerCase();
      $(this).toggle(title.includes(q) || src.includes(q) || pid.includes(q));
    });
  });
});
</script>
</body>
</html>
<?php endif; ?>
