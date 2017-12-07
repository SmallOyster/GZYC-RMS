<?php
include("Functions/OEA.php");
$OEA=new OEA();

if(isset($_POST) && $_POST){
  $UserName=$_POST['UserName'];
  $RealName=$_POST['RealName'];
  $RoleID=$_POST['RoleID'];
  $schoolGrade=$_POST['SchoolGrade'];
  $schoolClass=$_POST['SchoolClass'];
  $Status=1;
  $Pw_arr=getRanPW();
  $originPassword=$Pw_arr[0];
  $salt=$Pw_arr[1];
  $Password=$Pw_arr[2];

  $sql="INSERT INTO users(UserName,RealName,Password,salt,RoleID,Status,originPassword,SchoolGrade,SchoolClass) VALUES (?,?,?,?,?,?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$UserName,$RealName,$Password,$salt,$RoleID,$Status,$originPassword,$schoolGrade,$schoolClass],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_INT]);

  if($rs[1]==1){   
    $ShowPwd=$OEA->Encrypt($originPassword);
    $url="index.php?file=User&action=ShowOriginPW.php&u=$UserName&r=$RealName&p={$ShowPwd[0]}&k={$ShowPwd[1]}&re_file=User&re_action=AddUser.php";
    echo "<script>alert('新增用户成功！');window.location.href='$url';</script>";
  }else{
    echo "<script>alert('新增用户失败！！！');window.location.href='index.php';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>新增用户</h3><br>
  <div class="input-group">
    <span class="input-group-addon">用户名</span>
    <input type="text" class="form-control" name="UserName">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <div class="input-group">
    <span class="input-group-addon">真实姓名</span>
    <input type="text" class="form-control" name="RealName">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <div class="input-group">
    <span class="input-group-addon">角色</span>
    <p ID="msg"></p>
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <hr>

  <div class="input-group">
    <span class="input-group-addon">所在年级</span>
    <select class="form-control" name="SchoolGrade">
      <option value="" selected disabled>--- 请选择年级 ---</option>
      <option value="7">7. 初一</option>
      <option value="8">8. 初二</option>
      <option value="9">9. 初三</option>
      <option value="10">10. 高一</option>
      <option value="11">11. 高二</option>
      <option value="12">12. 高三</option>
    </select>
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <div class="input-group">
    <span class="input-group-addon">所在班级</span>
    <select class="form-control" name="SchoolClass">
      <option value="" selected disabled>--- 请选择班级 ---</option>
      <?php
      for($i=1;$i<=12;$i++){
        echo '<option value="'.$i.'">'.$i.' 班</option>';
      }
      ?>
    </select>
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>

  <hr>

  <input type="submit" class="btn btn-success" style="wIDth:100%" value="确 认 增 加">
</div>
</form>

<script>
function getRole(){
 msg=''
 +'<select name="RoleID" class="form-control">'
 +'<option selected="selected" disabled>---请选择角色---</option>';
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:1},
  type:"post",
  dataType:"json",
  error:function(e){alert()},
  success:function(got){
   for(i in got){
    msg+='<option ';
    for(j in got[i]){
     if(j==="ID"){
      msg+='value="'+got[i][j]+'">';
     }
     else if(j==="name"){
      msg+=got[i][j]+"</option>";
     }
    }
   }
   $('#msg').html(msg);
  }
 });
}

$(document).ready(function(){
	getRole();
});
</script>