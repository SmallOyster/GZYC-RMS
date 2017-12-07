<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 AJAX-获取报修单详情
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-22
* @modify 最后修改时间：2017-11-30
* ----------------------------------------
*/

require_once("../Functions/PublicFunc.php");
require_once("../Functions/PDOConn.php");

if(isset($_POST) && $_POST){
  $id=$_POST['id'];
  
  $sql="SELECT a.*,b.RealName FROM repairs a,users b WHERE a.id=? AND a.create_user_id=b.UserID";
  $rs=PDOQuery($dbcon,$sql,[$id],[PDO::PARAM_INT]);

  if($rs[1]==1){
    $rtn=$rs[0][0];
    $rtn['code']="200";
    $status=$rtn['status'];

    // 获取维修信息(如无返回'/')
    if($status==0 || $status>=2){
      $engineerID=$rtn['engineer_id'];
      $engineerInfo=PDOQuery($dbcon,"SELECT RealName FROM users WHERE UserID=?",[$engineerID],[PDO::PARAM_INT]);
      if($engineerInfo[1]!=1){
        $rtn['engineer_name']="工程师";
      }else{
        $engineerName=$engineerInfo[0][0]['RealName'];
        $rtn['engineer_name']=$engineerName;
      }
    }else{
      $rtn['engineer_name']="/";
      $rtn['receive_time']="/";
      $rtn['repair_time']="/";
    }

    $rtn['status']=parseOrderStatus($status);

    // 返回JSON格式的字符串
    $rtn=json_encode($rtn);
    die($rtn);
  }else{
    $rtn['code']="404";
    $rtn=json_encode($rtn);
    die($rtn);
  }
}else{
  die();
}
?>