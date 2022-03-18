<?php
switch($db){
  case 'default': $dbcon=mysqli_connect('localhost','wisdom','resu'); break;
  case 'root': $dbcon=mysqli_connect('localhost','root',$heslo); break;
  default: $dbcon=mysqli_connect('192.168.1.162','wisdom','resu');
}

mysqli_select_db($dbcon,'depozit');
if(!$dbcon){
  echo 'Chyba spojenia: %s\n', mysqli_connect_error();
  exit();
}
?>