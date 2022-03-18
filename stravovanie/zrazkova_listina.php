<?php include('hlavicka.php');?>
    <title>Zrážková listina</title>
  </head>

  <body>
    <section class="bez_obsahu">
<h1>Zrážková listina</h1>
<?php
$db='default';
include('databaza.php');

if(isset($_GET['mesiac'])){$mesiac=$_GET['mesiac'];}else{$mesiac=idate('m');}
if(isset($_GET['rok'])){$rok=$_GET['rok'];}else{$rok=idate('Y');}

str_pad($mesiac,2,0,STR_PAD_LEFT);
$datum_z=$rok.'-'.$mesiac.'-01';
$den_k=date('t',strtotime($datum_z));
$datum_k=$rok.'-'.$mesiac.'-'.$den_k;
?>

<table>
  <tr>
    <th class="noprint"><button onclick="window.close()">Zatvoriť</button></th>
    <th class="noprint"> pre obdobie od: <b><?php echo $datum_z;?></b> do: <b><?php echo $datum_k;?></b></th>
    <th class="skrite"><b>Zrážková listina</b> pre obdobie od: <b><?php echo $datum_z;?></b> do: <b><?php echo $datum_k;?></b></th>
    <th class="noprint">
      <a href="zrazkove_listiny.php?mesiac=<?php echo $mesiac.'&rok='.$rok;?>&xlsx=create">
        <img src="php_excel.png" width="88" height="20" alt="php2xlsx">
      </a>
    </th>
  </tr>
</table>

<table border='2'>
  <tr>
    <th class="zrazkova_listina">P. č.</th>
    <th class="zrazkova_listina">Osobné<br />číslo</th>
    <th class="zrazkova_listina">Meno</th>
    <th class="zrazkova_listina">Suma<br />( € )</th>
<?php
for($i=1;$i<=$den_k;$i++){echo '<th class="zrazkova_listina">'.$i.'</th>';}//dni v mesiaci
?>
    <th class="zrazkova_listina">∑<br />( ks )</th>
    <th class="zrazkova_listina">Spolu<br />( € )</th>
  </tr>
<?php
$ceny=array();
$pc=0;

$sql_ceny="SELECT cena_id,cena_obed_3,cena_obed_9 FROM ceny";
$run_ceny=mysqli_query($dbcon, $sql_ceny);
while($row_ceny=mysqli_fetch_array($run_ceny)){
  $ceny+=array($row_ceny['cena_id']=>array(
    'cena_id'=>$row_ceny['cena_id'],
    'cena_trojka'=>$row_ceny['cena_obed_3'],
    'cena_ostatne'=>$row_ceny['cena_obed_9']
  ));
}

$sql_stravnici="SELECT
DISTINCT stravnici.stravnik_id,
stravnici.titul_pm,
stravnici.meno,
stravnici.priezvisko,
stravnici.titul_zm,
stravnici.osobne_cislo,
stravnici.oddelenie_id,
stravnici.cena_id
FROM stravnici
INNER JOIN objednavky
ON stravnici.stravnik_id=objednavky.stravnik_id
WHERE objednavky.datum BETWEEN '$datum_z' AND '$datum_k'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
  OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
  ORDER BY stravnici.priezvisko, stravnici.meno, objednavky.datum ASC";
// ORDER BY stravnici.osobne_cislo, objednavky.datum ASC";

$run_stravnici=mysqli_query($dbcon, $sql_stravnici);
while($row_stravnici=mysqli_fetch_array($run_stravnici)){
  $pc++;
?>
  <tr>
    <th class="zrazkova_listina"><?php echo $pc;?></th>
    <td><?php echo $row_stravnici['osobne_cislo'];?></td>
    <td><?php echo $row_stravnici['titul_pm'].' '.$row_stravnici['meno'].' '.$row_stravnici['priezvisko'].' '.$row_stravnici['titul_zm'];?></td>
<?php
  $cena_id=$row_stravnici['cena_id'];

  if(in_array($cena_id, $ceny[$cena_id])){
    $cena_trojka=$ceny[$cena_id]['cena_trojka'];
    $cena_ostatne=$ceny[$cena_id]['cena_ostatne'];
  }
  else{$cena_trojka=$cena_ostatne=0.0;}
?>
    <td><?php echo str_replace(".",",",$cena_trojka.'<br />'.$cena_ostatne);?></td>
<?php
  $stravnik_id=$row_stravnici['stravnik_id'];
  $spolut=$spoluo=$spolu=0.0;
  $objednavky=array();

  $sql_objednavky="SELECT
objednavky.stravnik_id,
DAY(objednavky.datum) AS den,
objednavky.obed_1,
objednavky.obed_1_pocet,
objednavky.obed_2,
objednavky.obed_2_pocet,
objednavky.vecera_1,
objednavky.vecera_1_pocet
FROM objednavky
INNER JOIN stravnici
ON objednavky.stravnik_id=stravnici.stravnik_id
WHERE objednavky.stravnik_id='$stravnik_id'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
  OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
AND datum BETWEEN '$datum_z' AND '$datum_k'
  ORDER BY stravnici.priezvisko, stravnici.meno, objednavky.datum ASC";
// ORDER BY stravnici.osobne_cislo, objednavky.datum ASC";

  $run_objednavky=mysqli_query($dbcon, $sql_objednavky);
  while($row_objednavky=mysqli_fetch_array($run_objednavky)){
    $objednavky+=array($row_objednavky['den']=>array(
      'den'=>$row_objednavky['den'],
      'obed_1'=>$row_objednavky['obed_1'],
      'obed_1_pocet'=>$row_objednavky['obed_1_pocet'],
      'obed_2'=>$row_objednavky['obed_2'],
      'obed_2_pocet'=>$row_objednavky['obed_2_pocet'],
      'vecera_1'=>$row_objednavky['vecera_1'],
      'vecera_1_pocet'=>$row_objednavky['vecera_1_pocet']
    ));
  }
  for($i=1;$i<=$den_k;$i++){
    if(!empty($objednavky[$i]['den'])){
      $trojka=$ostatne=0;

      switch($objednavky[$i]['obed_1']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['obed_1_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['obed_1_pocet'];
      }

      switch($objednavky[$i]['obed_2']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['obed_2_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['obed_2_pocet'];
      }

      switch($objednavky[$i]['vecera_1']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['vecera_1_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['vecera_1_pocet'];
      }

	  if($trojka==0 AND $ostatne==0){echo '<td class="zrazkova_listina"><br /></td>';}
      else{echo '<td class="zrazkova_listina">'.$trojka.'<br />'.$ostatne.'</td>';}

      $spolut+=$trojka;
      $spoluo+=$ostatne;
    }
    else{echo '<td class="zrazkova_listina"></td>';}
  }
  echo '<td>'.$spolut.'<br />'.$spoluo.'</td>';
  $spolu=$spolut*$cena_trojka;
  $spolu+=$spoluo*$cena_ostatne;
  echo '<td><b>'.str_replace('.',',',$spolu).'</b></td>';
?>
  </tr>
<?php
}
$dbcon->close();
?>
</table>
    </section>
  </body>
</html>