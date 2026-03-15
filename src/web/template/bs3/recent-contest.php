<?php if(stripos($_SERVER['REQUEST_URI'],"template"))exit(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>최근 대회 - <?php echo $OJ_NAME?></title>
<?php include("template/$OJ_TEMPLATE/css.php");?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');
* { box-sizing: border-box; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6f9; margin: 0; color: #333; }
.rc-wrap { max-width: 1000px; margin: 32px auto; padding: 0 20px 60px; }
.rc-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.rc-header h2 { margin: 0; font-size: 21px; font-weight: 700; color: #7c3aed; }
.rc-card { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow: hidden; }
.rc-table { width: 100%; border-collapse: collapse; }
.rc-table thead tr { background: #7c3aed; color: #fff; }
.rc-table th { padding: 12px 16px; font-size: 13px; font-weight: 600; text-align: left; }
.rc-table td { padding: 11px 16px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.rc-table tbody tr:hover { background: #f5f8ff; }
.rc-table tbody tr:last-child td { border-bottom: none; }
.rc-table td a { color: #7c3aed; text-decoration: none; }
.rc-table td a:hover { text-decoration: underline; }
.rc-footer { padding: 12px 20px; font-size: 12px; color: #aaa; border-top: 1px solid #f0f0f0; text-align: right; }
</style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="rc-wrap">
  <div class="rc-header">
    <span style="font-size:24px">🏆</span>
    <h2>외부 대회 일정</h2>
  </div>
  <div class="rc-card">
    <table class="rc-table">
      <thead>
        <tr>
          <th>OJ</th><th>대회명</th><th>시작시간</th><th>요일</th><th>접근</th>
        </tr>
      </thead>
      <tbody id="contest-list">
        <tr><td colspan="5" style="text-align:center;color:#aaa;padding:24px">불러오는 중...</td></tr>
      </tbody>
    </table>
    <div class="rc-footer">
      데이터: algcontest.rainng.com &nbsp;|&nbsp; 크롤러 작성:
      <a href="https://github.com/Azure99/AlgContestInfo" target="_blank">Azure99</a>
    </div>
  </div>
</div>
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
$.get("https://algcontest.rainng.com/contests.json", function(data){
  var tbody = $("#contest-list");
  tbody.empty();
  if(!data || data.length===0){
    tbody.append('<tr><td colspan="5" style="text-align:center;color:#aaa;padding:24px">데이터 없음</td></tr>');
    return;
  }
  data.forEach(function(val){
    tbody.append(
      '<tr>' +
      '<td>'+val.oj+'</td>' +
      '<td><a target="_blank" href="'+val.link+'">'+val.name+'</a></td>' +
      '<td>'+val.start_time+'</td>' +
      '<td>'+val.week+'</td>' +
      '<td>'+val.access+'</td>' +
      '</tr>'
    );
  });
}).fail(function(){
  $("#contest-list").html('<tr><td colspan="5" style="text-align:center;color:#aaa;padding:24px">데이터를 불러올 수 없습니다.</td></tr>');
});
</script>
</body>
</html>
