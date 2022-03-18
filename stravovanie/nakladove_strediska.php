<?php

for($i;$i<count($nakladove_strediska);$i++){
  switch($i){
    case $row['stredisko_id']: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$nakladove_strediska[$i]['stredisko_id'].' - '.$nakladove_strediska[$i]['stredisko_nazov'].'</option>';
}
?>