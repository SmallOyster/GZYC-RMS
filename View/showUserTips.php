<?php
/**
* ---------------------------------------
* @name 育才报修管理系统 用户-首页显示单提醒
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 系统创建时间：2017-12-13
* @modify 最后修改时间：2017-12-14
* ---------------------------------------
*/
$nowUserID=getSess(Prefix."UserID");
$nowTime=date("Y-m-d H:i:s");

$repairingTotal_SQL="SELECT count(*) FROM repairs WHERE create_user_id=? AND status=2 OR status=3";
$repairingTotal_rs=PDOQuery($dbcon,$repairingTotal_SQL,[$nowUserID],[PDO::PARAM_INT]);
$repairingTotal=$repairingTotal_rs[0][0]['count(*)'];

$finishTotal_SQL="SELECT count(*) FROM repairs WHERE create_user_id=? AND status=0 AND repair_time>?";
$finishTotal_time=date('Y-m-d H:i:s',strtotime("$nowTime - 1 day"));
$finishTotal_rs=PDOQuery($dbcon,$finishTotal_SQL,[$nowUserID,$finishTotal_time],[PDO::PARAM_INT,PDO::PARAM_STR]);
$finishTotal=$finishTotal_rs[0][0]['count(*)'];
?>

<?php if($finishTotal>0){ ?>
<div class="alert alert-info alert-dismissible" role="alert">
  <font style="font-size:16px">
  最近1天内已有<?php echo $finishTotal; ?>张维修单完成维修啦！
  </font>
</div>
<?php } ?>

<?php if($repairingTotal>0){ ?>
<div class="alert alert-info alert-dismissible" role="alert">
  <font style="font-size:16px">
  目前有<?php echo $repairingTotal; ?>张报修单正在处理，请密切留意！
  </font>
</div>
<?php } ?>
