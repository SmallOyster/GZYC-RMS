<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 AJAX-工程师-改单状态
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-23
* @modify 最后修改时间：2017-11-26
* ----------------------------------------
*/

require_once("../Functions/PublicFunc.php");
require_once("../Functions/PDOConn.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$nowUserID=getSess(Prefix."UserID");
$isEngineer=getSess(Prefix."isEngineer");
$nowTime=date("Y-m-d H:i:s");

if(isset($_POST) && $_POST){

  // 检查是否有权限
  if($isEngineer!="1"){
    die("NoPurview");
  }
  
  // 获取用户输入的数据
  $token=$_POST['token'];
  $id=$_POST['id'];
  $status=$_POST['status'];

  // 检查TOKEN的有效性
  $checkToken=checkAJAXToken($token);
  if($checkToken!="1"){
    die("InvaildToken");
  }
    
  $sql="UPDATE repairs SET status=?";
  
  if($status=="2"){
    $sql.=",engineer_id='{$nowUserID}',receive_time='{$nowTime}'";
  }elseif($status=="0"){
    $sql.=",repair_time='{$nowTime}'";
  }
  
  $sql.=" WHERE id=?";
  $rs=PDOQuery($dbcon,$sql,[$status,$id],[PDO::PARAM_INT,PDO::PARAM_INT]);
  
  if($rs[1]==1){
    die("1");
  }else{
    die();
  }
}else{
  die();
}
?>