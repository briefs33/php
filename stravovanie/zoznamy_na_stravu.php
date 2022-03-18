<?php
include('hlavicka.php');
$nadpis='Zoznamy na obed a večeru';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

if(isset($_GET['xlsx'])){include('zoznam_na_stravu_xlsx.php');}

$dnes=date('Y-m-d', date(time())); //aktualny den
$rok=idate('Y');
$mesiac=idate('m');
$mesiac=str_pad($mesiac,2,0,STR_PAD_LEFT);
$den='01';

include('sviatky.php');

if(isset($_GET['datum'])){
  if(isset($_GET['U'])){
    $date=date_create(date('Ym',$_GET['U']).'01');
    $startdate=date_format($date,'U');
    $mesiac=date('m',$_GET['U']);
  }
  else{
    $startdate=$_GET['datum'];
    $mesiac=date('m',$_GET['datum']);
  }
}
else{
  $date=date_create($rok.$mesiac.$den);
  $startdate=date_format($date,'U');
}

switch($mesiac){
  case 1: $nazov_mesiaca='Január'; break;
  case 2: $nazov_mesiaca='Február'; break;
  case 3: $nazov_mesiaca='Marec'; break;
  case 4: $nazov_mesiaca='Apríl'; break;
  case 5: $nazov_mesiaca='Máj'; break;
  case 6: $nazov_mesiaca='Jún'; break;
  case 7: $nazov_mesiaca='Júl'; break;
  case 8: $nazov_mesiaca='August'; break;
  case 9: $nazov_mesiaca='September'; break;
  case 10: $nazov_mesiaca='Október'; break;
  case 11: $nazov_mesiaca='November'; break;
  case 12: $nazov_mesiaca='December'; break;
}
?>
<table>
  <tr>
    <td>
      <a href="zoznamy_na_stravu.php?datum=<?php $datum=strtotime('-1 month', $startdate); echo date($datum);?>">
        <button><<</button>
      </a>
    </td><th colspan="5"><?php echo $nazov_mesiaca?></th></td><td>
      <a href="zoznamy_na_stravu.php?datum=<?php $datum=strtotime('+1 month', $startdate); echo date($datum);?>">
        <button>>></button>
      </a>
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
for($riadok=0;$riadok<6;$riadok++){
  for($stlpec=0;$stlpec<7;$stlpec++){
?>
    <td>
      <a href="zoznam_na_stravu.php?<?php echo 'datum='.date('Y-m-d',$startdate);?>">
<?php
    if(date('m', $startdate)==$mesiac){
      $deni=date('Y-m-d',$startdate);

      if($dnes==$deni){$trieda=' class="dnes"';}
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

  <tr>
    <td colspan="7">
<?php
if(isset($_GET['subor'])){
  $subor='zoznamy_na_stravu/'.$_GET['subor'];
  if(!unlink($subor)){echo '<b>Nastala chyba pri odstraňovaní súboru "'.$_GET['subor'].'".</b>';}
  else{echo '<br /><b>Súbor "'.$_GET['subor'].'" bol odstránený.</b>';}
}
else{echo '<br /><br />';}
?>
    </td>
  </tr>
</table>

<table>
  <tr class="hlavicka">
    <th>Stiahnuť súbor</th>
    <th class="upozornenie">Odstrániť súbor</th>
  </tr>
<?php
$files=scandir('zoznamy_na_stravu/');
$hideName=array('.','..','.DS_Store');

foreach($files as $filename){
  if(!in_array($filename, $hideName)){
?>
  <tr class="farba">
    <td><a href="zoznamy_na_stravu/<?php echo $filename;?>"><?php echo $filename;?></a></td>
    <td><a href="zoznamy_na_stravu.php?subor=<?php echo $filename;?>"><button>Odstrániť</button></a></td>
  </tr>
<?php
  }
}
?>
</table>

<?php include('pata.php');?>