<?php
if(empty($row['oddelenie_id'])){$case=$_SESSION['oddelenie_id'];}
else{$case=$row['oddelenie_id'];}

for($i;$i<count($pracoviska);$i++){
  switch($i){
    case $case: $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$i.'">'.$pracoviska[$i].'</option>';
}
?>