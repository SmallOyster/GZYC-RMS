<?php

/**
* ----------------------------------------
* @name PHP公用函数库 1 内容显示函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-09-16
* @modify 最后修改时间：2017-12-12
* ----------------------------------------
*/


/**
* ------------------------------
* parseOrderStatus 显示报修单状态文字
* ------------------------------
* @param String 报修单状态码
* ------------------------------
* @return String 报修单状态文字
* ------------------------------
**/
function parseOrderStatus($status)
{
  switch($status){
    case "0":
      $rtn="已结束";
      break;
    case "1":
      $rtn="<font color='red'>待接单</font>";
      break;
    case "2":
      $rtn="<font color='green'>待维修</font>";
      break;
    case "3":
      $rtn="<font color='blue'>送修中</font>";
      break;
    default:
      $rtn="/";
      break;
  }
  
  return $rtn;
}


/**
* ------------------------------
* parseGrade 显示数字对应的年级
* ------------------------------
* @param String/INT 年级数字码
* ------------------------------
* @return String 年级名称
* ------------------------------
**/
function parseGrade($gradeNum)
{
  switch($gradeNum){
    case "7":
      $rtn="初一";
      break;
    case "8":
      $rtn="初二";
      break;
    case "9":
      $rtn="初三";
      break;
    case "10":
      $rtn="高一";
      break;
    case "11":
      $rtn="高二";
      break;
    case "12":
      $rtn="高三";
      break;
    default:
      $rtn="未知年级";
      break;
  }
  
  return $rtn;
}


/**
* ------------------------------
* makeOprBtn 显示带参数的按钮
* ------------------------------
* @param Str 显示内容
* @param Str 按钮颜色类(Bootstrap)
* @param Str 所在文件夹
* @param Str 文件名
* @param Arr 参数
* ------------------------------
**/
function makeOprBtn($name,$color,$file,$action,$param=array())
{
  $url_param="index.php?file=$file&action=$action";
  
  foreach($param as $i=>$value){
    $param_name=$param[$i][0];
    $param_value=$param[$i][1];
    $url_param.="&$param_name=$param_value";
  }
  
  $url='<a class="btn btn-'.$color.'" href="'.$url_param.'">'.$name.'</a>';
  return $url;
}


/**
* ------------------------------
* getLetter 根据字母表顺序获取字母
* ------------------------------
* @param INT 字母所在字母表的顺序
* ------------------------------
* @return String 对应字母
* ------------------------------
**/
function getLetter($LetterID){
  // 首位符号是为了占位(第0个)，方便按顺序取字母
  $AllLetters="|ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  if($LetterID>26){
    return "";
  }else{
    return $AllLetters[$LetterID];
  }
}


/**
* ------------------------------
* showCNNum 显示数字对应的汉字
* ------------------------------
* @param INT 一位数字
* ------------------------------
* @return String 数字对应的汉字
* ------------------------------
**/
function showCNNum($Num){
  switch($Num){
    case 1:
      $rtn="一";
      break;
    case 2:
      $rtn="二";
      break;
    case 3:
      $rtn="三";
      break;
    case 4:
      $rtn="四";
      break;
    case 5:
      $rtn="五";
      break;
    case 6:
      $rtn="六";
      break;
    case 7:
      $rtn="七";
      break;
    case 8:
      $rtn="八";
      break;
    case 9:
      $rtn="九";
      break;
    case 0:
      $rtn="零";
      break;
  }

  return $rtn;
}
