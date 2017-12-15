<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){

// type-1:所有角色
// type-null:指定某种角色
if($_POST['type']==1){
  $rs=PDOQuery($dbcon,"SELECT * FROM roles",[],[]);
  $allRole=$rs[0];
  $str=urldecode(json_encode($allRole));
  die($str);
}else{
  $ID=$_POST['rID'];
  $rs=PDOQuery($dbcon,"SELECT * FROM roles WHERE RoleID=?",[$ID],[PDO::PARAM_INT]);
  $role=$rs[0][0];
  $str=urldecode(json_encode($role));
  die($str);
}
}
?>