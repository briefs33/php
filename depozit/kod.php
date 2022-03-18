<?php
for($i=1;$i<=9;$i++){
  switch($i){
    case $case: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$i.'</option>';
}
?>