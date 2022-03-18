<?php
switch ($_SESSION['email']){
  case 'lehotska@pnh.sk':
  case 'gyetvenova@pnh.sk':
  case 'attila.csontos@pnh.sk':
  case 'szabolcs.holop@pnh.sk':
  case 'vera.zorenikova@pnh.sk':
  case 'alena.chladkova@pnh.sk': $diety=array(
0=>array('value'=>0,'dieta'=>''),
1=>array('value'=>3,'dieta'=>'Diéta č. 3'),
2=>array('value'=>9,'dieta'=>'Diéta č. 9'),
3=>array('value'=>4,'dieta'=>'Diéta č. 4'),
4=>array('value'=>13,'dieta'=>'Diéta č. 13'),
5=>array('value'=>'BZL','dieta'=>'Bezlepková'),
6=>array('value'=>'*','dieta'=>'*'),
7=>array('value'=>'9-S','dieta'=>'Diéta č. 9-S'),
); break;

  default: $diety=array(
0=>array('value'=>0,'dieta'=>''),
1=>array('value'=>3,'dieta'=>'Diéta č. 3'),
2=>array('value'=>9,'dieta'=>'Diéta č. 9'),
3=>array('value'=>4,'dieta'=>'Diéta č. 4'),
4=>array('value'=>13,'dieta'=>'Diéta č. 13'),
5=>array('value'=>'BZL','dieta'=>'Bezlepková'),
);
}
?>