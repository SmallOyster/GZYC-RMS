<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 工程师-所有报修单
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-22
* @modify 最后修改时间：2017-12-06
* ----------------------------------------
*/

$isEngineer=getSess(Prefix."isEngineer");
if($isEngineer!="1"){
  toAlertDie("当前用户非工程师角色！");
}

$list=PDOQuery($dbcon,"SELECT * FROM repairs ORDER BY status DESC,create_time",[],[]);
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

$timestamp=time();
$JSToken=sha1(session_id().md5($timestamp));
setSess(Prefix."AJAX_timestamp",$timestamp);
?>

<center>
  <h1>所有报修单</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 张报修单</h3>";
  ?>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>设备名</th>
  <th>简述</th>
  <th>状态</th>
  <th>开单时间</th>
  <th>操作</th>
</tr>

<?php
for($i=$Begin;$i<$Limit;$i++){ 
  $id=$list[0][$i]['id'];
  $equipment=$list[0][$i]['equipment'];
  $title=$list[0][$i]['title'];
  $status=$list[0][$i]['status'];
  $content=$list[0][$i]['content'];
  $createTime=$list[0][$i]['create_time'];
  $repairTime=$list[0][$i]['repair_time'];
  
  $showCreateTime=substr($createTime,0,10);
  $showStatus=parseOrderStatus($status);
  $showTitle=strlen($title)<=30?$title:substr($title,0,30)."...";
?>

<tr>
  <td><?php echo $equipment; ?></td>
  <td><a onclick='alert("<?php echo $title; ?>")'><?php echo $showTitle; ?></a></td>
  <td><?php echo $showStatus; ?></td>
  <td><?php echo $showCreateTime; ?></td>
  <td>
    <button class="btn btn-info" onclick='showOrderDetail("<?php echo $id; ?>")'>详细</button>
    <?php if($status==1){ ?>
    <button class="btn btn-warning" onclick='receiveOrder("<?php echo $id; ?>")'>接单</button>
    <?php }elseif($status==2){ ?>
    <button class="btn btn-success" onclick='repairFinish("<?php echo $id; ?>")'>修完</button>
    <button class="btn btn-primary" onclick='showSendRepair("<?php echo $id; ?>","<?php echo $equipment; ?>","<?php echo $content; ?>")'>送修</button>
    <?php }elseif($status==3){ ?>
    <button class="btn btn-primary" onclick='showSendOrderDetail("<?php echo $id; ?>")'>送修详情</button>
    <?php } ?>
  </td>
</tr>
<?php } ?>
</table>

<?php include("Functions/PageNav.php"); ?>

<script>
var token = "<?php echo $JSToken; ?>";

function receiveOrder(id){
  lockScreen();
  $.ajax({
    url:"AJAX/Engineer_UpdateOrderStatus.php",
    type:"post",
    data:{"token":token,"id":id,"status":"2"},
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
        $("#tips").html("接单成功~");
        $("#tipsModal").modal('show');
        return false;      
      }else if(got=="NoPurview"){
        $("#tips").html("当前用户无权限进行此次操作！");
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

function repairFinish(id){
  lockScreen();
  $.ajax({
    url:"AJAX/Engineer_UpdateOrderStatus.php",
    type:"post",
    data:{"token":token,"id":id,"status":"0"},
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
        $("#tips").html("维修完成~");
        $("#tipsModal").modal('show');
        return false;      
      }else if(got=="NoPurview"){
        $("#tips").html("当前用户无权限进行此次操作！");
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

function showSendRepair(id,equipment,content){
  $("#send_ID").val(id);
  $("#send_equipment").html(equipment);
  $("#send_content").html(content);
  $("#sendRepairModal").modal('show');
}

function toSendRepair(){
  lockScreen();

  send_ID=$("#send_ID").val();
  send_factoryName=$("#send_factoryName").val();
  send_estReturnTime=$("#send_estReturnTime").val();

  $.ajax({
    url:"AJAX/Engineer_SendRepair.php",
    type:"post",
    data:{"token":token,"id":send_ID,"factoryName":send_factoryName,"estReturnTime":send_estReturnTime},
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
        $("#sendRepairModal").modal('hide');
        $("#tips").html("送修成功~");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="NoPurview"){
        $("#sendRepairModal").modal('hide');
        $("#tips").html("当前用户无权限进行此次操作！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="InvaildToken"){
        $("#sendRepairModal").modal('hide');
        $("#tips").html("Token无效！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="UpdateStatusFailed"){
        $("#sendRepairModal").modal('hide');
        $("#tips").html("报修单状态更新失败！");
        $("#tipsModal").modal('show');
        return false;
      }else if(got=="InsertFailed"){
        $("#sendRepairModal").modal('hide');
        $("#tips").html("新增送修单失败！");
        $("#tipsModal").modal('show');
        return false;
      }else{
        $("#sendRepairModal").modal('hide');
        $("#tips").html("服务器错误！<hr>"+got);
        $("#tipsModal").modal('show');
        return false;
      }
    }
  });
}

function showOrderDetail(id){
  lockScreen();
  $.ajax({
    url:"AJAX/getOrderDetail.php",
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
        $("#RealName").html(got.RealName);
        $("#place").html(got.place);
        $("#equipment").html(got.equipment);
        $("#title").html(got.title);
        $("#content").html(got.content);
        $("#status").html(got.status);
        $("#createTime").html(got.create_time);
        $("#engineerName").html(got.engineer_name);
        $("#receiveTime").html(got.receive_time);
        $("#repairTime").html(got.repair_time);

        $("#orderDetailModal").modal('show');
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
        $("#sendShow_id").html(got.id);
        $("#sendShow_place").html(got.place);
        $("#sendShow_equipment").html(got.equipment);
        $("#sendShow_title").html(got.title);
        $("#sendShow_content").html(got.content);
        $("#sendShow_sendTime").html(got.send_time);
        $("#sendShow_factoryName").html(got.factory_name);
        $("#sendShow_estReturnTime").html(got.est_return_time);
        $("#sendShow_returnTime").html(got.return_time);

        $("#sendOrderDetailModal").modal('show');
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
        <font color="red" style="font-weight:bold;font-size:24;test-align:center;">
          <p id="tips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.reload()">返回 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="sendRepairModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">填写送修单</h3>
      </div>
      <div class="modal-body">
        <input type="hidden" id="send_ID">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>设备名称</th>
            <td><p id="send_equipment"></p></td>
          </tr>
          <tr>
            <th>故障内容</th>
            <td><p id="send_content"></p></td>
          </tr>
          <tr>
            <th>送修厂商名</th>
            <td><input class="form-control" id="send_factoryName"></td>
          </tr>
          <tr>
            <th>预计归还日期</th>
            <td><input class="form-control" id="send_estReturnTime"></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="toSendRepair()">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderDetailModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">报修单详情</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>ID</th>
            <td><p id="id"></p></td>
          </tr>
          <tr>
            <th>申请人</th>
            <td><p id="RealName"></p></td>
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
            <th>报修单状态</th>
            <td><p id="status"></p></td>
          </tr>
          <tr>
            <th>开单时间</th>
            <td><p id="createTime"></p></td>
          </tr>
          <tr>
            <th>接单工程师姓名</th>
            <td><p id="engineerName"></p></td>
          </tr>
          <tr>
            <th>接单时间</th>
            <td><p id="receiveTime"></p></td>
          </tr>
          <tr>
            <th>维修时间</th>
            <td><p id="repairTime"></p></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="sendOrderDetailModal">
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
            <td><p id="sendShow_id"></p></td>
          </tr>
          <tr>
            <th>设备所在场室</th>
            <td><p id="sendShow_place"></p></td>
          </tr>
          <tr>
            <th>设备名称</th>
            <td><p id="sendShow_equipment"></p></td>
          </tr>
          <tr>
            <th>故障简述</th>
            <td><p id="sendShow_title"></p></td>
          </tr>
          <tr>
            <th>故障内容</th>
            <td><p id="sendShow_content"></p></td>
          </tr>
          <tr>
            <th>送修厂商</th>
            <td><p id="sendShow_factoryName"></p></td>
          </tr>
          <tr>
            <th>送修时间</th>
            <td><p id="sendShow_sendTime"></p></td>
          </tr>
          <tr>
            <th>预计归还时间</th>
            <td><p id="sendShow_estReturnTime"></p></td>
          </tr>
          <tr>
            <th>归还时间</th>
            <td><p id="sendShow_returnTime"></p></td>
          </tr>          
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>
