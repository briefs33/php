<?php
for($i;$i<count($diety);$i++){
  switch('"'.$diety[$i]['value'].'"'){
    case '"'.$case.'"': $s=' selected '; break;
    default: $s=' ';
  }
  echo '
<option'.$s.'value="'.$diety[$i]['value'].'">'.$diety[$i]['dieta'].'</option>';
}
?>