<?php
for($i=2019;$i<=idate('Y');$i++){
  switch($i){
    case $case: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$i.'</option>';
}
?>