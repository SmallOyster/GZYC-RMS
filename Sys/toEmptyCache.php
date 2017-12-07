<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

if(isset($_POST) && $_POST){
	$Password=$_POST['Password'];
	$Sign=$_POST['Sign'];
  $CacheType=$_POST['CacheType'];
  
	if(getSess(Prefix."Ajax_Sign") != $Sign){
		die("InvaildSign");
	}

	$NowUserID=GetSess(Prefix."UserID");
  
  $sql1="SELECT Password,salt FROM users WHERE UserID=?";
  $Verify_rs=PDOQuery($dbcon,$sql1,[$NowUserID],[PDO::PARAM_INT]);
  $iptPW_indb=$Verify_rs[0][0]['Password'];
  $salt=$Verify_rs[0][0]['salt'];

  $iptPW=encryptPW($Password,$salt);
  
  if($iptPW != $iptPW_indb){
    die('PasswordErr');
  }
  
  $Cache=new Cache($dbcon,$CacheType);
  // 删除导出缓存
  $Cache->E();

  die("1");
}
?>