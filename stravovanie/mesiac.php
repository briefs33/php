<?php
for($i=1;$i<=12;$i++){
  $a=str_pad($i,2,0,STR_PAD_LEFT);
  switch($a){
    case $mesiac: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$a.'">'.$i.'</option>';
}
?>