<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 用户-新建报修单页面
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-21
* @modify 最后修改时间：2017-11-30
* ----------------------------------------
*/

$timestamp=time();
$JSToken=sha1(session_id().md5($timestamp));

setSess(Prefix."AJAX_timestamp",$timestamp);
?>

<div class="well col-md-12 text-center">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>新 开 报 修 单</h3><br>
  
  <div class="input-group">
    <span class="input-group-addon">报修场所</span>
    <input class="form-control" id="place">
  </div>
  <br> 
  <div class="input-group">
    <span class="input-group-addon">报修设备</span>
    <input class="form-control" id="equipment">
  </div>
  
  <hr>
  
  <div class="input-group">
    <span class="input-group-addon">故障简述</span>
    <input class="form-control" id="title">
  </div>
  <br>
  <textarea class="form-control" id="content" placeholder="请详细填写故障内容"></textarea>
  
  <hr>
  
  <button class="btn btn-success" onclick='toCreateOrder()' style="width:98%">确 认 开 单 &gt;</button>
</div>

<script>
var token = "<?php echo $JSToken; ?>";

function toCreateOrder(){
  lockScreen();

  place=$("#place").val();
  equipment=$("#equipment").val();
  title=$("#title").val();
  content=$("#content").val();
  content=content.replace(/\n/g,"<br>");
  if(place==""){
    unlockScreen();
    $("#tips").html("请填写故障设备所在的场室！");
    $("#myModal").modal('show');
    return false;
  }
  if(equipment==""){
    unlockScreen();
    $("#tips").html("请填写故障设备！");
    $("#myModal").modal('show');
    return false;
  }
  if(title=="" || title.length<5){
    unlockScreen();
    $("#tips").html("请简述故障内容！");
    $("#myModal").modal('show');
    return false;
  }
  if(content==""){
    unlockScreen();
    $("#tips").html("请详细填写故障内容！");
    $("#myModal").modal('show');
    return false;
  }
  
  $.ajax({
    url:"AJAX/User_CreateOrder.php",
    type:"post",
    data:{"token":token,"place":place,"equipment":equipment,"title":title,"content":content},
    error:function(e){
      console.log(e);
      unlockScreen();
      $("#tips").html("系统错误！！！");
      $("#myModal").modal('show');
      return false;
    },
    success:function(got){
      unlockScreen();
      if(got=="1"){
        alert("报修成功");
        history.go(-1);
      }else if(got=="InvaildToken"){
        $("#tips").html("Token无效！！！");
        $("#myModal").modal('show');
        return false;
      }else{
        $("#tips").html("服务器错误！！！<hr>"+got);
        $("#myModal").modal('show');
        return false;
      }
    }
  });
}
</script>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <font color="red" style="font-weight:bold;font-size:24;text-align:center;">
          <p id="tips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div>
  </div>
</div>