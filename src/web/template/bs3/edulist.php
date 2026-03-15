<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>교안 - <?php echo $OJ_NAME?></title>
  <?php include("template/$OJ_TEMPLATE/css.php");?>
  <style>
.edu-wrap{max-width:960px;margin:36px auto;padding:0 16px}
.edu-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.edu-top h2{font-size:22px;font-weight:700;color:#7c3aed;margin:0}
.edu-table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.edu-table thead tr{background:#7c3aed;color:#fff}
.edu-table th{padding:13px 16px;font-size:13px;font-weight:600;text-align:left}
.edu-table td{padding:13px 16px;font-size:14px;border-bottom:1px solid #f0f0f0;color:#333}
.edu-table tbody tr:hover{background:#f5f8ff}
.edu-table tbody tr:last-child td{border-bottom:none}
.edu-table td a{color:#7c3aed;text-decoration:none;font-weight:500}
.edu-table td a:hover{text-decoration:underline}
.edu-num{color:#aaa;font-size:13px}
.edu-date{color:#aaa;font-size:12px;white-space:nowrap}
.edu-empty{text-align:center;padding:48px;color:#aaa;font-size:15px}
.btn-write{background:#7c3aed;color:#fff;border:none;padding:9px 20px;border-radius:7px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none}
.btn-write:hover{background:#6d28d9;color:#fff}
.edu-page{display:flex;justify-content:center;gap:6px;margin-top:24px}
.edu-page a,.edu-page span{padding:6px 13px;border-radius:6px;font-size:13px;border:1px solid #e0e0e0;color:#555;text-decoration:none}
.edu-page a:hover{background:#7c3aed;color:#fff;border-color:#7c3aed}
.edu-page .active{background:#7c3aed;color:#fff;border-color:#7c3aed}
  </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="edu-wrap">
  <div class="edu-top">
    <h2>📖 교안</h2>
    <?php if($is_admin): ?><a href="eduwrite.php" class="btn-write">+ 교안 작성</a><?php endif; ?>
  </div>
  <table class="edu-table">
    <thead><tr><th style="width:60px">번호</th><th>제목</th><th style="width:100px">작성자</th><th style="width:130px">날짜</th></tr></thead>
    <tbody>
    <?php if(empty($rows)): ?>
      <tr><td colspan="4" class="edu-empty">등록된 교안이 없습니다.</td></tr>
    <?php else: ?>
      <?php foreach($rows as $i => $row): ?>
      <tr>
        <td class="edu-num"><?php echo $total - ($page-1)*$per_page - $i; ?></td>
        <td><a href="eduview.php?id=<?php echo $row['edu_id']?>"><?php echo htmlspecialchars($row['title'])?></a></td>
        <td><?php echo htmlspecialchars($row['user_id'])?></td>
        <td class="edu-date"><?php echo substr($row['time'],0,10)?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
  <?php if($total_pages > 1): ?>
  <div class="edu-page">
    <?php for($p=1;$p<=$total_pages;$p++): ?>
      <?php if($p==$page): ?><span class="active"><?php echo $p?></span>
      <?php else: ?><a href="edulist.php?page=<?php echo $p?>"><?php echo $p?></a><?php endif; ?>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
</body>
</html>
