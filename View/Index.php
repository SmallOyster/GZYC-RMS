<h2 style="text-align:center">
  欢迎登录<br><?php echo $title; ?>！
</h2>
<hr>
<div class="alert alert-success alert-dismissible" role="alert">
  <font style="font-size:16px">
  请在每次使用完毕以后，点击导航栏的头像->安全退出系统，以便下次登录。谢谢配合！
  </font>
</div>
<hr>
<h3>快速菜单</h3>
<hr>

<!--
<center>
  <a class="btn btn-warning" href="index.php?file=View&action=ContactAdmin.php" style="width:98%;font-size:18;">联 系 管 理 员</a>
</center>
<hr>
-->

<script>
var GlobalNotice="";

window.onload=function(){
  getGlobalNotice();
};

function getGlobalNotice(){
  $.ajax({
    url:"Functions/Api/getGlobalNotice.php",
    type:"get",
    dataType:"json",
    success:function(got){
      if(got.Content!="" && got.PubTime!=""){
        Content=got.Content;
        PubTime=got.PubTime;
        msg="发布时间：<b>"+PubTime+"</b><hr>"+Content;
        dm_notification(msg,'green',7000);
      }
    }
	});
}
</script>