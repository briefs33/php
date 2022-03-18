<?php include('hlavicka.php');?>
    <title>Zrážková listina (Finančná učtáreň)</title>
  </head>

  <body>
    <section class="bez_obsahu">
<h1>Zrážková listina (Finančná učtáreň)</h1>

<?php
$db='default';
include('databaza.php');

if(isset($_GET['mesiac'])){$mesiac=$_GET['mesiac'];}else{$mesiac=idate('m');}
if(isset($_GET['rok'])){$rok=$_GET['rok'];}else{$rok=idate('Y');}

str_pad($mesiac,2,0,STR_PAD_LEFT);
$datum_z=$rok.'-'.$mesiac.'-01';
$den_k=date('t',strtotime($datum_z));
$datum_k=$rok.'-'.$mesiac.'-'.$den_k;

$cena=1.50;
$celkom=0;
?>

<table>
  <tr>
    <th class="noprint"><button onclick="window.close()">Zatvoriť</button></th>
    <th class="noprint"> pre obdobie od: <b><?php echo $datum_z;?></b> do: <b><?php echo $datum_k;?></b></th>
    <th class="skrite"><b>Zrážková listina</b> pre obdobie od: <b><?php echo $datum_z;?></b> do: <b><?php echo $datum_k;?></b></th>
    <th class="noprint">
      <!--a href="zrazkove_listiny_FU.php?mesiac=<?php echo $mesiac.'&rok='.$rok;?>&xlsx=create">
        <img src="php_excel.png" width="88" height="20" alt="php2xlsx">
      </a-->
    </th>
  </tr>
</table>

<table>
<?php
$sql="SELECT
-- stravnici.titul_pm,
-- stravnici.meno,
-- stravnici.priezvisko,
-- stravnici.titul_zm,
stravnici.osobne_cislo,
-- oddelenia.oddelenie_skratka,
-- oddelenia.oddelenie,
nakladove_strediska.stredisko_cislo,
nakladove_strediska.stredisko_nazov,
zaradenia_zamestnancov.zaradenie_id,
zaradenia_zamestnancov.zaradenie_popis,
objednavky.obed_1,
objednavky.obed_1_pocet,
objednavky.obed_2,
objednavky.obed_2_pocet,
objednavky.vecera_1,
objednavky.vecera_1_pocet
FROM stravnici

INNER JOIN zaradenia_zamestnancov ON zaradenia_zamestnancov.zaradenie_id = stravnici.zaradenie
INNER JOIN nakladove_strediska ON nakladove_strediska.stredisko_id = stravnici.nakladove_stredisko
INNER JOIN oddelenia ON oddelenia.oddelenie_id = stravnici.oddelenie_id
-- INNER JOIN objednavky ON objednavky.datum = jedalne_listky.datum
INNER JOIN objednavky ON objednavky.stravnik_id = stravnici.stravnik_id

WHERE
objednavky.datum BETWEEN '$datum_z' AND '$datum_k'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
   OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
--   AND zaradenia_zamestnancov.zaradenie_id BETWEEN 1 AND 3
ORDER BY zaradenia_zamestnancov.zaradenie_id, nakladove_strediska.stredisko_id, stravnici.osobne_cislo";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
$osobne_cislo=$zaradenie=$stredisko=$oddelenie=$osobne_cislo=$riadok=$trojka=$ostatne=$spolut=$spoluo=$spolu=$spolu_zaradenie=$spolu_stredisko=0;

while($row=mysqli_fetch_array($run)){
  if($riadok==0){
    $riadok++;

    $zaradenie_id=$row['zaradenie_id'];
    $zaradenie_popis=$row['zaradenie_popis'];
    hlavicka_zaradenie($zaradenie_popis);

    $stredisko_cislo=$row['stredisko_cislo'];
    $stredisko_nazov=$row['stredisko_nazov'];
    hlavicka_stredisko($stredisko_cislo,$stredisko_nazov);

    $spolut=$spoluo=$spolu=$spolu_zaradenie=$spolu_stredisko=0;
  }
  else if($zaradenie_id!=$row['zaradenie_id']){
    $spolu=$spolut+$spoluo;
    $spolu_zaradenie+=$spolu;
    $spolu_stredisko+=$spolu_zaradenie;

    pata_stredisko($spolut,$spoluo,$spolu);
    pata_zaradenie($spolu_stredisko);
    $celkom+=$spolu_stredisko;
    $zaradenie_id=$row['zaradenie_id'];
    $zaradenie_popis=$row['zaradenie_popis'];
    hlavicka_zaradenie($zaradenie_popis);

    $stredisko_cislo=$row['stredisko_cislo'];
    $stredisko_nazov=$row['stredisko_nazov'];
    hlavicka_stredisko($stredisko_cislo,$stredisko_nazov);

    $spolut=$spoluo=$spolu=$spolu_zaradenie=$spolu_stredisko=0;
  }
  else if($stredisko_cislo!=$row['stredisko_cislo']){
    $spolu=$spolut+$spoluo;
    $spolu_zaradenie+=$spolu;
    $spolu_stredisko+=$spolu_zaradenie;

    pata_stredisko($spolut,$spoluo,$spolu);

    $stredisko_cislo=$row['stredisko_cislo'];
    $stredisko_nazov=$row['stredisko_nazov'];
    hlavicka_stredisko($stredisko_cislo,$stredisko_nazov);

    $spolut=$spoluo=$spolu=$spolu_zaradenie=0;
  }
  $trojka=$ostatne=0;

  switch($row['obed_1']){
    case 3: case '*': $trojka=$trojka+$row['obed_1_pocet']; break;
    case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$row['obed_1_pocet'];
  }

  switch($row['obed_2']){
    case 3: case '*': $trojka=$trojka+$row['obed_2_pocet']; break;
    case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$row['obed_2_pocet'];
  }

  switch($row['vecera_1']){
    case 3: case '*': $trojka=$trojka+$row['vecera_1_pocet']; break;
    case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$row['vecera_1_pocet'];
  }

  $spolut+=$trojka;
  $spoluo+=$ostatne;
}
$spolu=$spolut+$spoluo;
$spolu_zaradenie+=$spolu;
$spolu_stredisko+=$spolu_zaradenie;
$dbcon->close();

function hlavicka_stredisko($stredisko_cislo,$stredisko_nazov){
?>
  <tr class="farba">
    <th><?php echo $stredisko_cislo;?></th>
    <td class="left"><?php echo $stredisko_nazov;?></td>
<?php
}
function hlavicka_zaradenie($zaradenie_popis){
?>
  <tr class="hlavicka">
    <th class="left" colspan="2"><?php echo $zaradenie_popis;?></th>
    <td>∑ ( ks )</td>
    <td>Spolu ( ks )</td>
	<td>Cena* ( € )</td>
  </tr>
<?php
}
function pata_stredisko($spolut,$spoluo,$spolu){
  global $cena;
?>
    <td class="right"><?php echo $spolut.' + '.$spoluo;?></td>
    <td class="right"><?php echo $spolu;?></td>
	<th class="right"><?php echo number_format($spolu*$cena,2,',','');?></th>
  </tr>
<?php
}
function pata_zaradenie($spolu_stredisko){
  global $cena;
?>
  <tr class="hlavicka">
    <td class="right" colspan="2"></td>
    <th class="right">Spolu</th>
    <td class="right"><?php echo $spolu_stredisko.' ks';?></td>
	<th class="right"><?php echo number_format($spolu_stredisko*$cena,2,',','');?></th>
  </tr>

  <tr><td colspan="5"><br /></td></tr>
<?php
}
?>
    <td class="right"><?php echo $spolut.' + '.$spoluo;?></td>
    <td class="right"><?php echo $spolu;?></td>
	<th class="right"><?php echo number_format($spolu*$cena,2,',','');?></th>
  </tr>

  <tr class="hlavicka">
    <td class="right" colspan="2"></td>
    <th class="right">Spolu</th>
    <td class="right"><?php echo $spolu_stredisko.' ks';?></td>
	<th class="right"><?php echo '<b>'.number_format($spolu_stredisko*$cena,2,',','');?></th>
  </tr>

  <tr><td colspan="5"><br /></td></tr>

  <tr class="hlavicka">
    <td class="right" colspan="2"></td>
    <th class="right">Celkom</th>
    <td class="right"><?php echo $celkom+$spolu_stredisko.' ks';?></td>
	<th class="right"><?php echo '<b>'.number_format(($celkom+$spolu_stredisko)*$cena,2,',','');?></th>
  </tr>
  <tr><td class="right" colspan="5">* jednotková cena je 1,50€</td></tr>
</table>
    </section>
  </body>
</html>