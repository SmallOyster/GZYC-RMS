<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 工程师-所有送修单
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-27
* @modify 最后修改时间：2017-12-06
* ----------------------------------------
*/
$isEngineer=getSess(Prefix."isEngineer");
if($isEngineer!="1"){
  toAlertDie("当前用户非工程师角色！");
}

$list=PDOQuery($dbcon,"SELECT a.*,b.equipment,b.title FROM send_repairs a,repairs b WHERE a.repair_id=b.id ORDER BY status",[],[]);
$total=count($list[0]);

// 分页代码[Begin]
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
$TotalPage=ceil($total/$PageSize);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
  die("<script>window.location.href='$nowURL';</script>");
}

if($Limit>$total){$Limit=$total;}
// 分页代码[End]

// Ajax-Token[Begin]
$timestamp=time();
$JSToken=sha1(session_id().md5($timestamp));

setSess(Prefix."AJAX_timestamp",$timestamp);
// Ajax-Token[End]

?>

<center>
  <h1>所有送修单</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 张送修单</h3>";
  ?>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>设备名</th>  
  <th>故障简述</th>
  <th>状态</th>
  <th>送修时间</th>
  <th>操作</th>
</tr>

<?php
for($i=$Begin;$i<$Limit;$i++){ 
  $id=$list[0][$i]['id'];
  $repairID=$list[0][$i]['repair_id'];
  $title=$list[0][$i]['title'];
  $equipment=$list[0][$i]['equipment'];
  $status=$list[0][$i]['status'];
  $sendTime=$list[0][$i]['send_time'];
  
  $showSendTime=substr($sendTime,0,10);
  $showTitle=strlen($title)<=30?$title:substr($title,0,30)."...";
  if($status=="0") $showStatus="已归还";
  elseif($status=="1") $showStatus="<font color='blue'>送修中</font>";
?>

<tr>
  <td><?php echo $equipment; ?></td>
  <td><a onclick='alert("<?php echo $title; ?>")'><?php echo $showTitle; ?></a></td>
  <td><?php echo $showStatus; ?></td>
  <td><?php echo $showSendTime; ?></td>
  <td>
    <button class="btn btn-info" onclick='showSendOrderDetail("<?php echo $id; ?>")'>详细</button>
    <?php if($status==1){ ?>
    <button class="btn btn-success" onclick='sendRepairFinish("<?php echo $id; ?>","<?php echo $repairID; ?>")'>修完</button>   
    <?php } ?>
  </td>
</tr>
<?php } ?>
</table>

<?php include("Functions/PageNav.php"); ?>

<script>
var token = "<?php echo $JSToken; ?>";

function sendRepairFinish(id,repairID){
  lockScreen();
  $.ajax({
    url:"AJAX/Engineer_SendRepairFinish.php",
    type:"post",
    data:{"token":token,"id":id,"repairID":repairID},
    error:function(e){
      console.log(JSON.stringify(e));
      unlockScreen();
      $("#tips").html("系统错误！！！");
      $("#tipsModal").modal('show');
      return false;
    },
    success:function(got){
      unlockScreen();
      if(got=="1"){
        $("#tips").html("送修完成并领回！<br><br>请及时归还客户并关闭维修单！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="NoPurview"){
        $("#tips").html("当前用户无权限进行此次操作！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="UpdateStatusFailed"){
        $("#tips").html("更新维修单状态失败！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="SendOrderFinishFailed"){
        $("#tips").html("更新送修单状态失败！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="InvaildToken"){
        $("#tips").html("Token无效！");
        $("#tipsModal").modal('show');
        return false;      
      }else{
        $("#tips").html("服务器错误！<hr>"+got);
        $("#tipsModal").modal('show');
        return false;      
      }
    }
  });
}

function showSendOrderDetail(id){
  lockScreen();
  $.ajax({
    url:"AJAX/getSendOrderDetail.php",
    type:"post",
    data:{"id":id},
    dataType:"json",
    error:function(e){
      console.log(JSON.stringify(e));
      unlockScreen();
      $("#tips").html("系统错误！！！");
      $("#tipsModal").modal('show');
      return false;
    },
    success:function(got){
      unlockScreen();
      if(got.code=="404"){
        $("#tips").html("服务器错误或无此单数据！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got.code=="200"){
        $("#id").html(got.id);
        $("#place").html(got.place);
        $("#equipment").html(got.equipment);
        $("#title").html(got.title);
        $("#content").html(got.content);
        $("#sendTime").html(got.send_time);
        $("#factoryName").html(got.factory_name);
        $("#estReturnTime").html(got.est_return_time);
        $("#returnTime").html(got.return_time);

        $("#orderDetailModal").modal('show');
      }
    }
  });
}
</script>


<div class="modal fade" id="tipsModal">
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
        <button type="button" class="btn btn-primary" onclick="location.reload()">返回 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderDetailModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">送修单详情</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>ID</th>
            <td><p id="id"></p></td>
          </tr>
          <tr>
            <th>设备所在场室</th>
            <td><p id="place"></p></td>
          </tr>
          <tr>
            <th>设备名称</th>
            <td><p id="equipment"></p></td>
          </tr>
          <tr>
            <th>故障简述</th>
            <td><p id="title"></p></td>
          </tr>
          <tr>
            <th>故障内容</th>
            <td><p id="content"></p></td>
          </tr>
          <tr>
            <th>送修厂商</th>
            <td><p id="factoryName"></p></td>
          </tr>
          <tr>
            <th>送修时间</th>
            <td><p id="sendTime"></p></td>
          </tr>
          <tr>
            <th>预计归还时间</th>
            <td><p id="estReturnTime"></p></td>
          </tr>
          <tr>
            <th>归还时间</th>
            <td><p id="returnTime"></p></td>
          </tr>          
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>
