<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 AJAX-工程师-送修
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-25
* @modify 最后修改时间：2017-12-06
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
  $factoryName=$_POST['factoryName'];
  $estReturnTime=$_POST['estReturnTime'];

  // 检查TOKEN的有效性
  $checkToken=checkAJAXToken($token);
  if($checkToken!="1"){
    die("InvaildToken");
  }
    
  $sql1="UPDATE repairs SET status='3',repair_time=? WHERE id=?";
  $rs1=PDOQuery($dbcon,$sql1,[$nowTime,$id],[PDO::PARAM_STR,PDO::PARAM_INT]);
  
  $sql2="INSERT INTO send_repairs(repair_id,factory_name,est_return_time,create_user_id) VALUES (?,?,?,?)";
  $rs2=PDOQuery($dbcon,$sql2,[$id,$factoryName,$estReturnTime,$nowUserID],[PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs1[1]==1 && $rs2[1]==1){
    die("1");
  }elseif($rs1[1]!=1){
    die("UpdateStatusFailed");
  }elseif($rs2[1]!=1){
    die("InsertFailed");
  }else{
    die();
  }
}else{
  die();
}
?>