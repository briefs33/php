<?php

for($i;$i<count($zaradenia);$i++){
  switch($i){
    case $row['zaradenie']: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$zaradenia[$i].'</option>';
}
?>