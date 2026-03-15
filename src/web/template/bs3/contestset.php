<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>대회 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.cs-wrap{max-width:1000px;margin:36px auto;padding:0 16px}
.cs-header{text-align:center;margin-bottom:24px}
.cs-header h2{font-size:26px;font-weight:700;color:#7c3aed;margin:0 0 10px}
.cs-header .cs-time{font-size:28px;font-weight:700;color:#e74c3c;letter-spacing:1px}
.cs-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.cs-table thead tr{background:#7c3aed;color:#fff}
.cs-table th{padding:13px 16px;font-size:13px;font-weight:600;text-align:center}
.cs-table th.th-title{text-align:left}
.cs-table td{padding:13px 16px;font-size:14px;border-bottom:1px solid #f0f0f0;text-align:center;vertical-align:middle}
.cs-table td.td-title{text-align:left}
.cs-table tbody tr:hover{background:#f5f8ff}
.cs-table tbody tr:last-child td{border-bottom:none}
.cs-table td a{color:#7c3aed;text-decoration:none;font-weight:500}
.cs-table td a:hover{text-decoration:underline}
.cs-page{display:flex;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap}
.cs-page a,.cs-page span{padding:6px 13px;border-radius:6px;font-size:13px;border:1px solid #e0e0e0;color:#555;text-decoration:none}
.cs-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
.cs-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}
.btn-admin{display:inline-block;padding:8px 18px;background:#7c3aed;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;margin-bottom:16px}
.btn-admin:hover{background:#6d28d9;color:#fff}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="cs-wrap">
  <div class="cs-header">
    <h2>🎮 대회</h2>
    <div style="display:inline-flex;align-items:center;gap:10px;background:#fff5f5;border:1.5px solid #fcc;border-radius:12px;padding:8px 20px;margin-top:8px">
      <span style="font-size:17px;color:#888;font-weight:600">현재 시간</span>
      <span style="font-size:28px;font-weight:700;color:#e74c3c;letter-spacing:2px;font-variant-numeric:tabular-nums" id="nowdate"></span>
    </div>
  </div>
  <table class="cs-table">
    <thead>
      <tr>
        <th style="width:80px">대회ID</th>
        <th class="th-title">대회 이름</th>
        <th style="width:220px">대회 상태</th>
        <th style="width:90px;white-space:nowrap">대회 구분</th>
        <th style="width:80px">등록자</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($view_contest as $row):
        $cells = array_values((array)$row);
      ?>
      <tr>
        <td><?php echo $cells[0]?></td>
        <td class="td-title"><?php echo $cells[1]?></td>
        <td><?php echo $cells[2]?></td>
        <td><?php echo $cells[3]?></td>
        <td><?php echo $cells[4]?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class="cs-page">
    <a href="contest.php?page=1">&laquo;</a>
    <?php
    if(!isset($page)) $page=1;
    $page=intval($page);
    $section=8;
    $start=$page>$section?$page-$section:1;
    $end=min($page+$section,$view_total_page);
    for($i=$start;$i<=$end;$i++):
    ?>
    <?php if($page==$i):?>
      <span class="active"><?php echo $i?></span>
    <?php else:?>
      <a href="contest.php?page=<?php echo $i.(isset($_GET['my'])?"&my":"")?>"><?php echo $i?></a>
    <?php endif;?>
    <?php endfor;?>
    <a href="contest.php?page=<?php echo $view_total_page?>">&raquo;</a>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
function clock(){
  var x=new Date(new Date().getTime()+diff);
  var y=x.getFullYear(),mon=x.getMonth()+1,d=x.getDate(),h=x.getHours(),m=x.getMinutes(),s=x.getSeconds();
  var pad=function(n){return n>=10?n:"0"+n};
  document.getElementById('nowdate').innerHTML=y+"-"+pad(mon)+"-"+pad(d)+" "+pad(h)+":"+pad(m)+":"+pad(s);
  setTimeout(clock,1000);
}
clock();
</script>
</body>
</html>
