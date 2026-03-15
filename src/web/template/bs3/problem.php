<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>
                <?php echo $row['title']." - ".$MSG_PROBLEM." - ".$OJ_NAME; ?>
        </title>

        <?php include("template/$OJ_TEMPLATE/css.php");?>

        <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php if (isset($OJ_MATHJAX)&&$OJ_MATHJAX){?>
<script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
  };
</script>
<script id="MathJax-script" async src="template/<?php echo $OJ_TEMPLATE?>/tex-chtml.js"></script>
<style>.jumbotron1{ font-size: 18px; }</style>
<?php } ?>

<style>
/* ── 문제 페이지 전용 스타일 ── */
.prob-header {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 12px;
  padding: 28px 32px 20px;
  margin-bottom: 16px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  text-align: center;
}

.prob-title {
  font-size: 32px;
  font-weight: 900;
  color: #1a1a2e;
  margin: 0 0 10px;
  letter-spacing: -0.5px;
  line-height: 1.3;
}

.prob-credits {
  font-size: 13px;
  color: #aaa;
  margin-bottom: 14px;
}

.prob-meta {
  display: inline-flex;
  gap: 24px;
  background: #f5f8ff;
  border-radius: 8px;
  padding: 12px 28px;
  margin-bottom: 20px;
  font-size: 15px;
  color: #555;
}

.prob-meta span { display: flex; align-items: center; gap: 5px; }
.prob-meta strong { color: #7c3aed; font-weight: 700; }

/* CodeUp 스타일 액션 버튼 */
.prob-actions {
  display: flex;
  gap: 8px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 4px;
}

.prob-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 24px;
  border-radius: 7px;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none !important;
  border: none;
  cursor: pointer;
  transition: all 0.15s;
  white-space: nowrap;
}

.prob-btn-submit  { background: #e74c3c; color: #fff !important; }
.prob-btn-submit:hover  { background: #c0392b; color: #fff !important; }

.prob-btn-rank    { background: #7c3aed; color: #fff !important; }
.prob-btn-rank:hover    { background: #6d28d9; color: #fff !important; }

.prob-btn-status  { background: #27ae60; color: #fff !important; }
.prob-btn-status:hover  { background: #1e9450; color: #fff !important; }

.prob-btn-stat    { background: #f39c12; color: #fff !important; }
.prob-btn-stat:hover    { background: #d68910; color: #fff !important; }

.prob-btn-off     { background: #8e44ad; color: #fff !important; }
.prob-btn-off:hover     { background: #7d3c98; color: #fff !important; }

.prob-btn-edit    { background: #16a085; color: #fff !important; }
.prob-btn-edit:hover    { background: #138d75; color: #fff !important; }

.prob-btn-list    { background: #555; color: #fff !important; }
.prob-btn-list:hover    { background: #333; color: #fff !important; }

.prob-btn-bbs     { background: #e67e00; color: #fff !important; }
.prob-btn-bbs:hover     { background: #cc6d00; color: #fff !important; }

/* 섹션 패널 */
.prob-section {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 10px;
  margin-bottom: 14px;
  overflow: hidden;
  box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.prob-section-head {
  background: #f8fafc;
  border-bottom: 1px solid #e5e9f0;
  padding: 14px 24px;
  font-size: 16px;
  font-weight: 700;
  color: #7c3aed;
  display: flex;
  align-items: center;
  gap: 8px;
}

.prob-section-body {
  padding: 22px 26px;
  font-size: 16px;
  line-height: 1.9;
  color: #333;
}

.prob-section-body pre {
  background: #f8fafc;
  border: 1px solid #e5e9f0;
  border-radius: 8px;
  padding: 16px 18px;
  font-size: 15px;
  line-height: 1.8;
}

/* IO 예시 2단 레이아웃 */
.io-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
@media(max-width:600px){ .io-grid { grid-template-columns: 1fr; } }

/* 하단 액션 */
.prob-footer {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 10px;
  padding: 16px;
  text-align: center;
  margin-top: 8px;
  display: flex;
  gap: 8px;
  justify-content: center;
  flex-wrap: wrap;
}
</style>
</head>

<body>
<div id="main" class="container">
  <?php include("template/$OJ_TEMPLATE/nav.php");?>

  <!-- ── 문제 헤더 ── -->
  <div class="prob-header">
    <?php
    if($pr_flag) {
      echo "<div class='prob-title'>$id: ".htmlspecialchars($row['title'])."</div>";
    } else {
      $id = $row['problem_id'];
      echo "<div class='prob-title'>$MSG_PROBLEM ".$PID[$pid].": ".htmlspecialchars($row['title'])."</div>";
    }
    ?>
    <?php if(!empty($row["credits"])): ?><div class="prob-credits"><?php echo $MSG_Creator.": ".$row["credits"]; ?></div><?php endif; ?>

    <div class="prob-meta">
      <span>⏱ <strong><?php echo $row['time_limit']?> sec</strong> 시간제한<span fd='time_limit' pid='<?php echo $row['problem_id']?>' style='display:none'><?php echo $row['time_limit']?></span></span>
      <span>💾 <strong><?php echo $row['memory_limit']?> MiB</strong> 메모리제한</span>
      <span>⚙️ <strong><?php echo array('일반 채점','스페셜 저지','실시간 채점')[$row['spj']]?></strong></span>
    </div>

    <div class="prob-actions">
      <?php
      /* 코드 제출 */
      if($pr_flag) {
        echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?id=$id'>✏️ 코드 제출</a>";
      } else {
        if($contest_is_over)
          echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?id=$id'>✏️ 코드 제출</a>";
        else
          echo "<a id='submit' class='prob-btn prob-btn-submit' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>✏️ 코드 제출</a>";
        echo "<a class='prob-btn prob-btn-list' href='contest.php?cid=$cid'>📋 문제 목록</a>";
      }

      if(!(isset($OJ_OI_MODE)&&$OJ_OI_MODE)) {
        echo "<a class='prob-btn prob-btn-rank' href='status.php?problem_id=".$row['problem_id']."&jresult=4'>🏆 순위 (".$row['accepted'].")</a>";
        echo "<a class='prob-btn prob-btn-status' href='status.php?problem_id=".$row['problem_id']."'>▶ 채점상황 (".$row['submit'].")</a>";

      }

      if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'."p".$row['problem_id']])) {
        require_once("include/set_get_key.php");
        echo "<a class='prob-btn prob-btn-edit' href='admin/problem_edit.php?id=$id&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."'>✏️ EDIT</a>";
        echo "<a class='prob-btn prob-btn-edit' href='javascript:phpfm(".$row['problem_id'].")'>📁 TESTDATA</a>";
        if(isset($used_in_contests) && count($used_in_contests)>0 && cid==0) {
          echo "<br><small style='color:#aaa;margin-top:8px;display:block'>$MSG_PROBLEM_USED_IN: ";
          foreach($used_in_contests as $contests) {
            echo "<a class='label label-warning' href='contest.php?cid=".$contests[0]."'>".$contests[1]."</a> ";
          }
          echo "</small>";
        }
      }
      ?>
    </div>
  </div>

  <?php echo "<!--StartMarkForVirtualJudge-->"; ?>

  <!-- ── 문제 본문 섹션들 ── -->
  <div class="prob-section">
    <div class="prob-section-head">📄 <?php echo $MSG_Description?></div>
    <div id="description" class="prob-section-body content"><?php echo bbcode_to_html($row['description'])?></div>
  </div>

  <?php if($row['input']): ?>
  <div class="prob-section">
    <div class="prob-section-head">⌨️ <?php echo $MSG_Input?></div>
    <div id="input" class="prob-section-body content"><?php echo bbcode_to_html($row['input'])?></div>
  </div>
  <?php endif; ?>

  <?php if($row['output']): ?>
  <div class="prob-section">
    <div class="prob-section-head">🖨️ <?php echo $MSG_Output?></div>
    <div id="output" class="prob-section-body content"><?php echo bbcode_to_html($row['output'])?></div>
  </div>
  <?php endif; ?>

  <?php
  $sinput=str_replace("<","&lt;",$row['sample_input']);
  $sinput=str_replace(">","&gt;",$sinput);
  $soutput=str_replace("<","&lt;",$row['sample_output']);
  $soutput=str_replace(">","&gt;",$soutput);
  if(strlen($sinput) || strlen($soutput)):
  ?>
  <div class="prob-section">
    <div class="prob-section-head">🔁 예제</div>
    <div class="prob-section-body">
      <div class="io-grid">
        <?php if(strlen($sinput)): ?>
        <div>
          <div style="font-size:13px;font-weight:700;color:#888;margin-bottom:8px;">입력</div>
          <pre><span id="sampleinput" class="sampledata"><?php echo $sinput?></span></pre>
          <a href="javascript:CopyToClipboard($('#sampleinput').text())" style="font-size:12px;color:#7c3aed;">복사</a>
        </div>
        <?php endif; ?>
        <?php if(strlen($soutput)): ?>
        <div>
          <div style="font-size:13px;font-weight:700;color:#888;margin-bottom:8px;">출력</div>
          <pre><span id="sampleoutput" class="sampledata"><?php echo $soutput?></span></pre>
          <a href="javascript:CopyToClipboard($('#sampleoutput').text())" style="font-size:12px;color:#7c3aed;">복사</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if($row['hint']): ?>
  <div class="prob-section">
    <div class="prob-section-head">💡 <?php echo $MSG_HINT?></div>
    <div id="hint" class="prob-section-body content hint"><?php echo bbcode_to_html($row['hint'])?></div>
  </div>
  <?php endif; ?>

  <?php if($pr_flag): ?>
  <div class="prob-section">
    <div class="prob-section-head">🏷️ <?php echo $MSG_SOURCE?></div>
    <div fd="source" style="word-wrap:break-word;" pid="<?php echo $row['problem_id']?>" class="prob-section-body content">
      <?php
      $cats=explode(" ",$row['source']);
      foreach($cats as $cat){
        $hash_num=hexdec(substr(md5($cat),0,7));
        $label_theme=$color_theme[$hash_num%count($color_theme)];
        if($label_theme=="") $label_theme="default";
        echo "<a class='label label-$label_theme' style='display:inline-block;margin:2px' href='problemset.php?search=".urlencode(htmlentities($cat,ENT_QUOTES,'utf-8'))."'>".htmlentities($cat,ENT_QUOTES,'utf-8')."</a>&nbsp;";
      }
      ?>
    </div>
  </div>
  <?php endif; ?>

  <?php echo "<!--EndMarkForVirtualJudge-->"; ?>

  <!-- 하단 버튼 -->
  <div class="prob-footer">
    <?php
    if($pr_flag)
      echo "<a class='prob-btn prob-btn-submit' href='submitpage.php?id=$id'>✏️ 코드 제출</a>";
    else
      echo "<a class='prob-btn prob-btn-submit' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>✏️ 코드 제출</a>";
    if($OJ_BBS)
      echo "<a class='prob-btn prob-btn-bbs' href='bbs.php?pid=".$row['problem_id']."$ucid'>💬 $MSG_BBS</a>";
    ?>
  </div>

</div><!-- /container -->

<?php include("template/$OJ_TEMPLATE/js.php");?>
<script>
function phpfm(pid){
  $.post("admin/phpfm.php",{'frame':3,'pid':pid,'pass':''},function(data,status){
    if(status=="success") document.location.href="admin/phpfm.php?frame=3&pid="+pid;
  });
}
function selectOne(num,answer){
  let editor=$("iframe")[0].contentWindow.$("#source");
  let rep=editor.text().replace(new RegExp(num+".*"),num+" "+answer);
  editor.text(rep);
}
function selectMulti(num,answer){
  let editor=$("iframe")[0].contentWindow.$("#source");
  let rep=editor.text().replace(new RegExp(num+".*"),num+" "+answer);
  editor.text(rep);
}
$(document).ready(function(){
  $("#creator").load("problem-ajax.php?pid=<?php echo $id?>");
  <?php if(isset($OJ_MARKDOWN)&&$OJ_MARKDOWN){ ?>
  $(".md").each(function(){ $(this).html(marked.parse($(this).html())); });
  <?php } ?>
  $('span[class=auto_select]').each(function(){
    let i=1,start=0,raw=$(this).html(),options=['A','B','C','D'];
    while(start>=0){
      start=raw.indexOf(i+".",start);
      if(start<0) break;
      let end=start,type="radio";
      for(let j=0;j<4;j++){
        let option=options[j];
        end=raw.indexOf(option+".",start);
        if(j==0&&raw.substring(start,end).indexOf("多选")>0) type="checkbox";
        if(end<0) break;
        let disp="<input type=\""+type+"\" name=\""+i+"\" value=\""+option+"\" />"+option+".";
        raw=raw.substring(0,end-1)+disp+raw.substring(end+2);
        start+=disp.length;
      }
      start=end+1; i++;
    }
    $(this).html(raw);
  });
  $('input[type="radio"]').click(function(){
    if($(this).is(':checked')) selectOne($(this).attr("name"),$(this).val());
  });
  $('input[type="checkbox"]').click(function(){
    let num=$(this).attr("name"),answer="";
    $("input[type=checkbox][name="+num+"]").each(function(){
      if($(this).is(':checked')) answer+=$(this).val();
    });
    selectMulti(num,answer);
  });
  <?php if($row['spj']>1 || isset($_GET['sid']) || (isset($OJ_AUTO_SHOW_OFF)&&$OJ_AUTO_SHOW_OFF)){ ?>
  transform();
  <?php }?>
});
function CopyToClipboard(input){
  if(window.clipboardData){ window.clipboardData.setData("Text",input); }
  else {
    var el=document.createElement("pre");
    el.style.cssText="position:absolute;left:-10000px;top:-10000px";
    el.textContent=input; el.contentEditable=true;
    document.body.appendChild(el);
    var r=document.createRange(); r.selectNodeContents(el);
    var s=window.getSelection(); s.removeAllRanges(); s.addRange(r);
    try { document.execCommand("copy"); alert("채점 데이터를 복사했습니다."); }
    catch(e){ alert("복사할 수 없습니다."); }
    document.body.removeChild(el);
  }
}
function transform(){
  let width=parseInt(document.body.clientWidth*0.6);
  let width2=parseInt(document.body.clientWidth*0.4);
  let submitURL=$("#submit")[0].href;
  <?php if(isset($_GET['sid'])) echo "submitURL+='&sid=".intval($_GET['sid'])."';"; ?>
  let main=$("#main");
  if(window.screen.width<500){
    main.parent().append("<div id='submitPage' class='container' style='opacity:0.8;z-index:88;top:49px;'></div>");
    $("#submitPage").html("<iframe id='ansFrame' src='"+submitURL+"&spa' width='100%' height='"+window.innerHeight+"px'></iframe>");
    window.setTimeout('$("#ansFrame")[0].scrollIntoView()',1000);
  } else {
    main.css({"width":width2,"margin-left":"0px"});
    main.parent().append("<div id='submitPage' class='container' style='opacity:0.8;position:fixed;z-index:1000;top:49px;right:-"+width2+"px'></div>");
    $("#submitPage").html("<iframe src='"+submitURL+"&spa' width='"+width*0.96+"px' height='"+window.innerHeight*0.9+"px'></iframe>");
  }
  $("#submit").remove();
  <?php if($row['spj']>1){ ?>
  window.setTimeout('$("iframe")[0].contentWindow.$("#TestRun").remove();',1000);
  <?php }?>
}
</script>
</body>
</html>
