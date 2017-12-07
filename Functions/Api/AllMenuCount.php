<?php
$rs=PDOQuery($dbcon,"SELECT * FROM menus",[],[]);
echo $rs[1]+1;
?>