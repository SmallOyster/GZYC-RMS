<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 用户-我的报修单
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-19
* @modify 最后修改时间：2017-12-02
* ----------------------------------------
*/

$nowUserID=getSess(Prefix."UserID");
$list=PDOQuery($dbcon,"SELECT * FROM repairs WHERE create_user_id=? ORDER BY status DESC,create_time",[$nowUserID],[PDO::PARAM_INT]);
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

?>

<center>
  <h1>我的报修单</h1><hr>
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
  <td><button class="btn btn-info" onclick='showOrderDetail("<?php echo $id; ?>")'>详细</button></td>
</tr>
<?php } ?>
</table>

<?php include("Functions/PageNav.php"); ?>

<script>
function showOrderDetail(id){
  lockScreen();
  $.ajax({
    url:"AJAX/getOrderDetail.php",
    type:"post",
    data:{"id":id},
    dataType:"json",
    error:function(e){
      console.log(e);
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">返回 &gt;</button>
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