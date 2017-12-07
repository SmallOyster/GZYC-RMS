<?php
if(isset($_POST) && $_POST){
$OprType=!isset($_POST['OprType'])?"0":$_POST['OprType'];
switch($OprType){
  // 编辑
  case 1:
    $id=$_POST['id'];
    $name=$_POST['Name'];
    $file=$_POST['File'];
    $DOS=$_POST['DOS'];
    $icon=$_POST['Icon'];
    $sql="UPDATE menus SET Menuname=?,PageFile=?,PageDOS=?,MenuIcon=? WHERE MenuID=?";
    $rs=PDOQuery($dbcon,$sql,[$name,$file,$DOS,$icon,$id],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
    if($rs[1]>0){
  	  echo "<script>alert('修改节点信息成功！');</script>";
    }else{
  	  echo "<script>alert('修改节点信息失败！！！');</script>";
    }
    break;

  // 新增
  case 2:
    $fid=$_POST['FID'];
    $name=$_POST['Name'];
    $file=$_POST['File'];
    $DOS=$_POST['DOS'];
    $icon=$_POST['Icon'];
    $sql="INSERT INTO menus(FatherID,Menuname,MenuIcon,PageFile,PageDOS) VALUES (?,?,?,?,?)";
    $rs=PDOQuery($dbcon,$sql,[$fid,$name,$icon,$file,$DOS],[PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
    if($rs[1]>0){
  	  echo "<script>alert('新增节点成功！');</script>";
    }else{
  	  echo "<script>alert('新增节点失败！！！');</script>";
    }
    break;

  // 删除
  case 3:
    $id=$_POST['id'];
    $sql="DELETE FROM menus WHERE MenuID=?";
    $sql2="DELETE FROM role_purview WHERE PurvID=?";
    $rs=PDOQuery($dbcon,$sql,[$id],[PDO::PARAM_INT]);
    $rs2=PDOQuery($dbcon,$sql2,[$id],[PDO::PARAM_INT]);
    if($rs[1]>0){
  	  echo "<script>alert('删除节点成功！');</script>";
    }else{
  	  echo "<script>alert('删除节点失败！！！');</script>";
    }
    break;
  // 空
  case 0:
    break;
  default:
    break;
  }
}
?>

<script>
 var setting = {
  view: {
   addHoverDom: addHoverDom,
   removeHoverDom: removeHoverDom,
   selectedMulti: false
  },
  edit: {
   enable: true,
   editNameSelectAll: false
  },
  data: {
   simpleData: {
    enable: true
   }
  },
  callback: {
   beforeEditName: beforeEditName,
   beforeRemove: beforeRemove
  }
 };
 
  var zNodes = <?php include("Functions/Api/AllMenuData.php"); ?>;
  $(document).ready(function(){
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);
  });
	
// 点击编辑按钮后
function beforeEditName(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  zTree.selectNode(treeNode);
  setTimeout(function(){
    setModalMsg(1,treeNode.id,treeNode.name);
    $('#myModal').modal('show');
  }, 0);
  return false;
}
		
// 点击删除按钮后
function beforeRemove(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  zTree.selectNode(treeNode);
  setModalMsg(3,treeNode.id,treeNode.name);
  $('#myModal').modal('show');
  return false; 
}
 
// 点击新增按钮后	
function addHoverDom(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  var sObj = $("#" + treeNode.tId + "_span");
  if(treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
  var addStr = "<span class='button add' id='addBtn_" +treeNode.tId+ "' title='add node' onfocus='this.blur();'></span>";
  sObj.after(addStr);
  var btn = $("#addBtn_"+treeNode.tId);
  if(btn) btn.bind("click", function(){   
    // 新增节点
    setModalMsg(2,treeNode.id,treeNode.name);
    $('#myModal').modal('show');
    return false;
  });		
}
 
// 当节点失去焦点，移除节点按钮组
function removeHoverDom(treeId,treeNode){
  $("#addBtn_"+treeNode.tId).unbind().remove();
}


/**
* ----------------
*   Form表单参数
* ----------------
* id 当前节点ID
* FID 父节点ID
* Name 节点名称
* File 节点指向目录
* DOS 节点指向文件
* Icon 节点图标名称，详见font-awesome类
*/
function setModalMsg(type,id,name){
  switch(type){
    /*********** 修改 Edit ***********/
    case 1:
      var Detail=getMenuData(id);
      $("#OprType").val(1);
      $('#ModalTitle').html("修改节点信息");
      msg="<br>"
      +"<input type='hidden' name='id' value='"+id+"'>"
      +"节点名称：<input name='Name' value='"+name+"'><br>"
      +"对应目录：<input name='File' value='"+Detail.File+"'><br>"
      +"对应文件：<input name='DOS' value='"+Detail.DOS+"'><br>"
      +"图标名称：<input name='Icon' value='"+Detail.Icon+"'>";
      $('#msg').html(msg);
      $("#okbtn").attr("onclick","submitOpr();");
      break;

    /*********** 新增 Add ***********/
    case 2:
      $("#OprType").val(2);
      $('#ModalTitle').html("新增节点");
      msg=''
      +"<input type='hidden' name='FID' value='"+id+"'>"
      +"父节点名称："+id+". "+name+"<hr>"
      +"节点名称：<input name='Name'><br>"
      +"对应目录：<input name='File'><br>"
      +"对应文件：<input name='DOS'><br>"
      +"图标名称：<input name='Icon'>";
      $('#msg').html(msg);
      $("#okbtn").attr("onclick","submitOpr();");
      break;

    /*********** 删除 Delete ***********/
    case 3:
      $("#OprType").val(3);
      $('#ModalTitle').html("删除节点");
      msg=''
      +"<input type='hidden' name='id' value='"+id+"'>"
      +"<center><h1>"
      +"<font color='blue'>确定要删除此节点吗？</font><br>"
      +"<font color='green'>【"+name+"】</font>"
      +"</h1></center>";
      $('#msg').html(msg);
      $("#okbtn").attr("onclick","submitOpr();");
      break;

    /*********** 参数错误 Error ***********/
    default:
      $("#OprType").val(0);
      msg="<br><br><center><h1><font color='red'>参数错误，请重试！</font><br><font color='blue'>状态码：</font><font color='green'>"+type+"</font></h1></center><br><br>";
      $('#msg').html(msg);
      $("#okbtn").attr("onclick","$('#myModal').modal('hide');");
      break;
  }
}
 
function submitOpr(){
  $("form").submit();
}
 
function getMenuData(id){
  var rtn=new Object();
  $.ajax({
    url:"Functions/Api/getMenuData.php",
    type:"POST",
    dataType:"json",
    async:false,
    data:{MID:id},
    error:function(e){alert();},
    success:function(g){
      for(i in g[0]){
        rtn.id=id;
        if(i==="Fatherid"){
          rtn.Fid=g[0][i];
        }else if(i==="Menuname"){
          rtn.Name=g[0][i];
        }else if(i==="MenuIcon"){
          rtn.Icon=g[0][i];
        }else if(i==="PageFile"){
          rtn.File=g[0][i];
        }else if(i==="PageDOS"){
          rtn.DOS=g[0][i];
        }
      }    
    }
  });
  return rtn;
}
</script>

<style>
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
</style>

<h1>菜单管理</h1>

<hr>

<center>
<button class="btn btn-info" style="width:97%" onclick='setModalMsg(2,0,"系统主菜单");$("#myModal").modal("show");return false;'>新 增 主 菜 单</button>
</center>

<hr>

<div class="content_wrap">
 <div class="zTreeDemoBackground left" style="margin-left:10px">
  <ul id="treeDemo" class="ztree"></ul>
 </div>
 <div class="right highlight_red">
  <br>因本操作会导致全系统运作的改动，
  <br>请在专业人员辅导下使用！
 </div>
</div>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hidden;">
        </div>
        <form method="post" name="OprNode">
        <input type="hidden" id="OprType" name="OprType">
        <p id="msg"></p>       
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick="submitOpr()">确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->