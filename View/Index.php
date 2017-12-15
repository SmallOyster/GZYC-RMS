<?php
/**
* ---------------------------------------
* @name 育才报修管理系统 默认首页
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 系统创建时间：2017-11-18
* @modify 最后修改时间：2017-12-15
* ---------------------------------------
*/

$isEngineer=getSess(Prefix."isEngineer");
$isClassTch=getSess(Prefix."isClassTch");
?>

<h2 style="text-align:center">
  欢迎登录<br><?php echo $title; ?>！
</h2>
<hr>
<div class="alert alert-success alert-dismissible" role="alert">
  <font style="font-size:16px">
  请在每次使用完毕以后，点击导航栏的头像->安全退出系统，以便下次登录。谢谢配合！
  </font>
</div>
<?php
if($isEngineer=="1"){
  include("View/showEngineerTips.php");
}else{
  include("View/showUserTips.php");
}
?>
<hr>
<h3>快速菜单</h3>
<?php if($isEngineer=="1"){ ?>
  <a class="btn btn-primary" href="index.php?file=Repair&action=EngineerOrders.php" style="width:98%">报 修 单 列 表</a>
  <br><br>
  <a class="btn btn-warning" href="index.php?file=SendRepair&action=toList.php" style="width:98%">送 修 单 列 表</a>
<?php }else{ ?>
  <a class="btn btn-primary" href="index.php?file=Repair&action=UserOrders.php" style="width:98%">报 修 单 列 表</a>
  <?php if($isClassTch=="1"){ ?>
    <br><br>
    <a class="btn btn-success" href="index.php?file=Repair&action=ClassOrders.php" style="width:98%">班 级 报 修 单 列 表</a> 
  <?php } ?>
<?php } ?>


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