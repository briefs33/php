<?php
include('hlavicka.php');
$nadpis='Prehľad objednávok';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
?>

<table>
  <tr>
    <td class="center" colspan="3">
<?php
if(isset($_GET['strid'])){
  $stravnik_id=$_GET['strid'];
  echo '<a href="prehlad_stravnikov.php"><button>Prehľad stravníkov</button></a>';
}
else {$stravnik_id=$_SESSION['stravnik_id'];}
?>
    </td>

    <td colspan="8">
<?php if($_SESSION['rola']>=7){echo '<h3 id="stravnik"></h3>';}?>
    </td>
  </tr>

  <tr class="hlavicka">
    <td>Týždeň</td><td>Dátum</td><td>Deň</td><td>Obed 1</td><td>Počet<br />porcií</td><td>Obed 2</td><td>Počet<br />porcií</td><td>Večera</td><td>Počet<br />porcií</td><td>Zobrazenie<br />objednávky</td><td>Zmena<br />objednávky</td>
  </tr>
<?php
$db='default';
include('databaza.php');

$startdate=strtotime('monday');
//include('obmedzenia_prehlad_objednavok.php');
include('obmedzenia.php');

function prepinac($dieta, $pocet){ //upraviť na pole (array();)
  switch($dieta){
    case 'BZL': echo 'Bezlepková</td><td>'.$pocet; break;
    case '*': echo '*</td><td>'.$pocet; break;
    case 0: echo '</td><td>'; break;
    default: echo $dieta.'</td><td>'.$pocet;
  }
}

$stravnik='';
if($_SESSION['rola']>=7){
  $pocet='63';
}
else{
  $pocet='49';
}
//$pocet='49';
//
$riadok='1';
$sql="SELECT stravnici.titul_pm, stravnici.meno, stravnici.priezvisko, stravnici.titul_zm, objednavka_id, datum, obed_1, obed_1_pocet, obed_2, obed_2_pocet, vecera_1, vecera_1_pocet
FROM objednavky
INNER JOIN stravnici ON stravnici.stravnik_id = objednavky.stravnik_id
WHERE objednavky.stravnik_id='$stravnik_id'
ORDER BY datum DESC
LIMIT $pocet";
$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

while($row=mysqli_fetch_array($run)){
  $riadok++;
  $objednavka_id=$row['objednavka_id'];
  $datum=$row['datum'];
  $obed_1=$row['obed_1'];
  $obed_1_pocet=$row['obed_1_pocet'];
  $obed_2=$row['obed_2'];
  $obed_2_pocet=$row['obed_2_pocet'];
  $vecera_1=$row['vecera_1'];
  $vecera_1_pocet=$row['vecera_1_pocet'];
  $stravnik=$row['titul_pm'].' '.$row['meno'].' '.$row['priezvisko'].' '.$row['titul_zm'];

  $date=date_create($datum);
  date_sub($date,date_interval_create_from_date_string('0 days'));
  $den=$date;
?>

  <tr class="farba">
    <td <?php if(date('W', date(time()))==date_format($date, 'W')){echo ' class="aktualny"';}?>><?php echo date_format($date, 'W');?></td>
    <td><?php echo date_format($date, 'j.n.Y'); ?></td>
    <td>
<?php
  switch(date_format($date, 'N')){ //upraviť na pole (array();)
    case 1: echo 'Pondelok'; break;
    case 2: echo 'Utorok'; break;
    case 3: echo 'Streda'; break;
    case 4: echo 'Štvrtok'; break;
    case 5: echo 'Piatok'; break;
    case 6: echo 'Sobota'; break;
    case 7: echo 'Nedeľa'; break;
  }
?>
    </td>

    <td>
<?php
prepinac($obed_1, $obed_1_pocet);
echo '</td><td>';
prepinac($obed_2, $obed_2_pocet);
echo '</td><td>';
prepinac($vecera_1, $vecera_1_pocet);
?>
    </td>

    <td>
<a href="zobrazit_objednavku.php?objid=<?php echo $objednavka_id.'&datum='.$datum.'&obed_1='.$obed_1.'&obed_1_pocet='.$obed_1_pocet.'&obed_2='.$obed_2.'&obed_2_pocet='.$obed_2_pocet.'&vecera_1='.$vecera_1.'&vecera_1_pocet='.$vecera_1_pocet;?>"
  onclick="window.open(this.href,'','width=700,height=540,top=60,left=100'); return false">
  <button>Zobraziť</button>
</a>
    </td>

    <td>
<a href="upravit_objednavku.php?objid=<?php echo $objednavka_id.'&strid='.$stravnik_id.'&datum='.$datum.'&obed_1='.$obed_1.'&obed_1_pocet='.$obed_1_pocet.'&obed_2='.$obed_2.'&obed_2_pocet='.$obed_2_pocet.'&vecera_1='.$vecera_1.'&vecera_1_pocet='.$vecera_1_pocet;?>">
<?php
  if(((date('U')>=$obmedz_pon) and (date_format($den, "U")>$koniecdatumobm)) or ((date("U")<=($obmedz_pon)) and (date_format($den, 'U')>$datumobm))){echo '<button>Upraviť</button>';}
  else if($_SESSION['rola']>=7){echo '<button class="upozornenie">Upraviť</button>';}
?>
</a>
    </td>
  </tr>
<?php
}
?>
</table>
<?php
if($_SESSION['rola']>=7){echo '<script>document.getElementById("stravnik").innerHTML="'.$stravnik.'";</script>';}

include('pata.php');
?>