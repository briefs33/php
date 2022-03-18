<?php
include('hlavicka.php');
$nadpis='Zadanie pohybov';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$dnes=date('Y-m-d', date(time())); //aktualny den
$rok=$_SESSION['obdobie'];

if($_SESSION['obdobie']<idate('Y')){$mesiac=12;}
else{$mesiac=idate('n');}

$mesiac=str_pad($mesiac,2,0,STR_PAD_LEFT);
$den='01';

include('sviatky.php');

if(isset($_GET['datum_uctovania'])){
  if(isset($_GET['U'])){
    $date=date_create(date('Ym',$_GET['U']).'01');
    $startdate=date_format($date,'U');
    $mesiac=date('m',$_GET['U']);
  }
  else{
    $startdate=$_GET['datum_uctovania'];
    $mesiac=date('m',$_GET['datum_uctovania']);
  }
}
else{
  $date=date_create($rok.$mesiac.$den);
  $startdate=date_format($date,'U');
}

$nazov_mesiaca=array('01'=>'Január','02'=>'Február','03'=>'Marec','04'=>'Apríl','05'=>'Máj','06'=>'Jún','07'=>'Júl','08'=>'August','09'=>'September','10'=>'Október','11'=>'November','12'=>'December');

?>
<table>
  <tr>
    <td>
<?php
if(date('Y',strtotime('-1 month', $startdate))==$_SESSION['obdobie']){
?>
      <a href="kalendar_zadanie_pohybov.php?datum_uctovania=<?php $datum_uctovania=strtotime('-1 month', $startdate); echo date($datum_uctovania);?>">
        <button><<</button>
      </a>
<?php
}
?>
    </td>
    <th colspan="3"><?php echo $nazov_mesiaca[$mesiac];?></th>
    <th colspan="2"><?php echo date('Y',$startdate);?></th>
    <td>
<?php
if(date('Y',strtotime('+1 month', $startdate))==$_SESSION['obdobie']){
?>
      <a href="kalendar_zadanie_pohybov.php?datum_uctovania=<?php $datum_uctovania=strtotime('+1 month', $startdate); echo date($datum_uctovania);?>">
        <button>>></button>
      </a>
<?php
}
?>
    </td>
  </tr>

  <tr><td>Po</td><td>Ut</td><td>St</td><td>Št</td><td>Pi</td><td>So</td><td>Ne</td></tr>

<?php
switch(date('N',$startdate)){
  case 1: break;
  case 2: $startdate=strtotime('-1 day', $startdate); break;
  case 3: $startdate=strtotime('-2 days', $startdate); break;
  case 4: $startdate=strtotime('-3 days', $startdate); break;
  case 5: $startdate=strtotime('-4 days', $startdate); break;
  case 6: $startdate=strtotime('-5 days', $startdate); break;
  case 7: $startdate=strtotime('-6 days', $startdate); break;
}
?>
  <tr>
<?php
$db='default';
include('databaza.php');
$sql="SELECT datum_uctovania_skr, datum_uctovania
FROM karty_".$_SESSION['obdobie']."
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = datum_uctovania_skr
WHERE datum_uctovania_skr = '".$_SESSION['obdobie']."-".$mesiac."'";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

$pole=array();

if($run->num_rows>0){
  while($row=$run->fetch_assoc()){
    if(in_array($row['datum_uctovania'],$pole)){}else{$pole[]=$row['datum_uctovania'];}
  }
}

for($riadok=0;$riadok<6;$riadok++){
  for($stlpec=0;$stlpec<7;$stlpec++){
?>
    <td>
      <a href="zadanie_pohybov.php?<?php echo 'datum_uctovania='.date('Y-m-d',$startdate);?>">
<?php
    if(date('m', $startdate)==$mesiac){
      $deni=date('Y-m-d',$startdate);
      if(($dnes==$deni) and in_array($deni,$pole)){$trieda=' class="objednanednes"';}
      else if(in_array($deni,$pole)){$trieda=' class="objednane"';}
      else if($dnes==$deni){$trieda=' class="dnes"';}
      else if(in_array($deni,$sviatok)){$trieda=' class="sviatok"';}
      else if(date('N',$startdate)>=6){$trieda=' class="vykend"';}
      else{$trieda='';}
      echo '<button'.$trieda.'>'.date('d', $startdate).'</button>';
    }
    $startdate=strtotime('+1 day', $startdate);
?>
      </a>
    </td>
<?php
  }
?>
  </tr><tr>
<?php
}
?>
  </tr>
</table>

<?php include('pata.php');?>