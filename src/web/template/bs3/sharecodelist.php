<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>코드 공유 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.sc-wrap { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }
.sc-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.sc-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; flex: 1; }
.sc-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; }
.sc-table { width: 100%; border-collapse: collapse; }
.sc-table thead tr { background: #7c3aed; color: #fff; }
.sc-table th { padding: 12px 16px; font-size: 13px; font-weight: 600; text-align: center; }
.sc-table td { padding: 11px 16px; font-size: 13px; border-bottom: 1px solid #f0f0f0; text-align: center; }
.sc-table tbody tr:hover { background: #f5f8ff; }
.sc-table tbody tr:last-child td { border-bottom: none; }
.sc-table td a { color: #7c3aed; text-decoration: none; }
.sc-table td a:hover { text-decoration: underline; }
.sc-empty { text-align: center; padding: 40px; color: #aaa; }
.btn-act { padding: 5px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; margin: 0 2px; }
.btn-view { background: #e8f0fe; color: #7c3aed; }
.btn-del  { background: #fee2e2; color: #dc2626; }
.sc-page { display: flex; justify-content: center; gap: 6px; padding: 16px; }
.sc-page a { padding: 6px 13px; border-radius: 6px; font-size: 13px; border: 1px solid #e0e0e0; color: #555; text-decoration: none; }
.sc-page a:hover { background: #7c3aed; color: #fff; border-color: #7c3aed; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="sc-wrap">
  <div class="sc-header">
    <span style="font-size:24px">📤</span>
    <h2>코드 공유 목록</h2>
    <a href="sharecodepage.php" style="background:#7c3aed;color:#fff;padding:9px 18px;border-radius:7px;font-size:13px;font-weight:600;text-decoration:none">+ 코드 공유</a>
  </div>
  <div class="sc-card">
    <table class="sc-table">
      <thead><tr><th>번호</th><th>제목</th><th>언어</th><th>공유시간</th><th>작업</th></tr></thead>
      <tbody>
      <?php if($pageNum == 0): ?>
        <tr><td colspan="5" class="sc-empty">공유된 코드가 없습니다. <a href="sharecodepage.php">지금 공유하기</a></td></tr>
      <?php else: foreach($share_list as $s): ?>
      <tr>
        <td><?php echo $s['share_id']?></td>
        <td><a href="sharecodepage.php?sid=<?php echo $s['share_id']?>"><?php echo htmlspecialchars($s['title'])?></a></td>
        <td><?php echo htmlspecialchars($s['language'])?></td>
        <td><?php echo $s['share_time']?></td>
        <td>
          <button class="btn-act btn-view" onclick="seeCode(<?php echo $s['share_id']?>)">보기</button>
          <button class="btn-act btn-del"  onclick="deleteCode(<?php echo $s['share_id']?>)">삭제</button>
        </td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
    <?php if($pageNum > 1): ?>
    <div class="sc-page">
      <a href="sharecodelist.php?page=1">« 처음</a>
      <?php for($i=1;$i<=$pageNum;$i++): ?>
      <a href="sharecodelist.php?page=<?php echo $i?>"><?php echo $i?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function deleteCode(sid){
  if(!confirm("삭제하시겠습니까?")) return;
  $.ajax({ type:"POST", url:"./sharecodepage.php", data:{delete:sid},
    success:function(data){ alert(data.msg); if(data.status=="success") location.reload(); }
  });
}
function seeCode(sid){ location.href="./sharecodepage.php?sid="+sid; }
</script>
</body>
</html>
