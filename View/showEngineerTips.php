<?php
/**
* ---------------------------------------
* @name 育才报修管理系统 工程师-首页显示单提醒
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 系统创建时间：2017-12-12
* @modify 最后修改时间：2017-12-14
* ---------------------------------------
*/

$unreceiveTotal_SQL="SELECT count(*) FROM repairs WHERE status=1";
$unreceiveTotal_rs=PDOQuery($dbcon,$unreceiveTotal_SQL,[],[]);
$unreceiveTotal=$unreceiveTotal_rs[0][0]['count(*)'];

$repairingTotal_SQL="SELECT count(*) FROM repairs WHERE status=2";
$repairingTotal_rs=PDOQuery($dbcon,$repairingTotal_SQL,[],[]);
$repairingTotal=$repairingTotal_rs[0][0]['count(*)'];

$sendTotal_SQL="SELECT count(*) FROM repairs WHERE status=3";
$sendTotal_rs=PDOQuery($dbcon,$sendTotal_SQL,[],[]);
$sendTotal=$sendTotal_rs[0][0]['count(*)'];
?>

<?php if($unreceiveTotal>0){ ?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <font style="font-size:16px">
  目前有<?php echo $unreceiveTotal; ?>张报修单未有工程师接单，请尽快<a href="index.php?file=Repair&action=EngineerOrders.php">点此接单</a>！
  </font>
</div>
<?php } ?>

<?php if($repairingTotal>0){ ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <font style="font-size:16px">
  目前有<?php echo $repairingTotal; ?>张报修单仍在处理，请尽快维修！
  </font>
</div>
<?php } ?>

<?php if($sendTotal>0){ ?>
<div class="alert alert-info alert-dismissible" role="alert">
  <font style="font-size:16px">
  目前有<?php echo $sendTotal; ?>张报修单已送修，请密切留意并及时归还给用户！
  </font>
</div>
<?php } ?>
