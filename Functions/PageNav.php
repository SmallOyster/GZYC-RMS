
<!-- 分页功能@选择页码[Begin] -->
<center>
<nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$Previous&PageSize=$PageSize"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='active'><span>$j</span></li>";
   }else{
    echo "<li><a href='$nowURL&Page=$j&PageSize=$PageSize'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$next&PageSize=$PageSize"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav>
<div class="btn-group dropup">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 每页条数 <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&PageSize=20'; ?>">20</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&PageSize=50'; ?>">50</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&PageSize=100'; ?>">100</a></li>
    <li><a href="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&PageSize=200'; ?>">200</a></li>
  </ul>
</div>
</center>
<!-- 分页功能@选择页码[End] -->
