<?php
/**
* ----------------------------------------
* @name 育才报修管理系统 AJAX-用户-新建报修单
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-11-21
* @modify 最后修改时间：2017-11-30
* ----------------------------------------
*/

require_once("../Functions/PublicFunc.php");
require_once("../Functions/PDOConn.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$nowUserID=getSess(Prefix."UserID");

if(isset($_POST) && $_POST){
  $equipment=$_POST['equipment'];
  $place=$_POST['place'];
  $title=$_POST['title'];
  $content=$_POST['content'];
  $token=$_POST['token'];
  
  // 去除所有的引号(其他符号需保留)
  $equipment=str_replace("'","",$equipment);
  $equipment=str_replace('"','',$equipment);
  $place=str_replace("'","",$place);
  $place=str_replace('"','',$place);
  $title=str_replace("'","",$title);
  $title=str_replace('"','',$title);
  $content=str_replace("'","",$content);
  $content=str_replace('"','',$content);
  
  $checkToken=checkAJAXToken($token);
  if($checkToken!="1"){
    die("InvaildToken");
  }
  
  $sql="INSERT INTO repairs(create_user_id,place,equipment,title,content) VALUES (?,?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$nowUserID,$place,$equipment,$title,$content],[PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    die("1");
  }else{
    die();
  }
}else{
  die();
}
?>