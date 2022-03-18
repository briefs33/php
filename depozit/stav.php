<?php
$stav_id=array('neaktívny','aktívny','všetci');

for($i;$i<=2;$i++){
  switch($i){
    case $stav: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$stav_id[$i].'</option>';
}
?>