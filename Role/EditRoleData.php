<?php
$RoleID=isset($_GET['RID'])?$_GET['RID']:"";
$RoleName=isset($_GET['n'])?$_GET['n']:"";

if($RoleID=="" || $RoleName=="") ErrCodedie("500");

$rtnURL="index.php?file=Role&action=toList.php";

if(isset($_POST) && $_POST){
  $RoleName=$_POST['RoleName'];
  $Brief=$_POST['Brief'];
  $isEngineer=$_POST['isEngineer'];

  $sql="UPDATE roles SET RoleName=?,Brief=?,isEngineer=? WHERE RoleID=?";
  $rs=PDOQuery($dbcon,$sql,[$RoleName,$Brief,$isEngineer,$RoleID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_INT]);

  if($rs[1]==1){
    echo "<script>alert('修改成功！');window.location.href='$rtnURL';</script>";
  }else{    
    echo "<script>alert('修改失败！！！');window.location.href='$rtnURL';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>编辑角色资料</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">角色名称</span>
      <input type="text" class="form-control" name="RoleName" value="<?php echo $RoleName; ?>">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <br>
    <div class="input-group">
      <span class="input-group-addon">是否为工程师</span>
      <select class="form-control" name="isEngineer">
        <option selected disabled>--- 请选择 ---</option>
        <option value="1">是 √</option>
        <option value="0">否 ×</option>
      </select>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>
    <h4>角色介绍</h4><br>
    <textarea class="form-control" name="Brief"></textarea>
    <hr>
    <input type="submit" class="btn btn-success" style="width:100%" value="确 认 修 改">
  </div>
</div>
</form>