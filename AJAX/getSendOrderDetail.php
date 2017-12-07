<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 AJAX-获取送修单详情
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-26
* @modify 最后修改时间：2017-12-02
* ----------------------------------------
*/

require_once("../Functions/PublicFunc.php");
require_once("../Functions/PDOConn.php");

if(isset($_POST) && $_POST){
  $id=$_POST['id'];
  
  $sql="SELECT a.*,b.place,b.equipment,b.title,b.content,c.RealName FROM send_repairs a,repairs b,users c WHERE a.id=? AND a.repair_id=b.id AND a.create_user_id=c.UserID";
  $rs=PDOQuery($dbcon,$sql,[$id],[PDO::PARAM_INT]);

  if($rs[1]==1){
    $rtn=$rs[0][0];
    $rtn['code']="200";

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