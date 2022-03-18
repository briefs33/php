<?php
include('hlavicka.php');
$nadpis='Pohyby pokladne a banky';
/*...* /include('nadpis.php');/*...*/

echo '<h1>'.$nadpis.' Δ</h1>';
?>

<table>
<?php
$db='default';
include('databaza.php');

$prijem=$vydaj=$zostatok=$pociatocny_stav=0.0;
$sql="SELECT pacienti_".$_GET['rok'].".pacient_cislo, karta_id, doklad_cislo, meno, priezvisko, datum_uctovania_skr, datum_uctovania, kod, prijem, vydaj, pokladna_pociatocny_stav, pokladna_konecny_stav, banka_pociatocny_stav, banka_konecny_stav
FROM karty_".$_GET['rok']."
INNER JOIN pacienti_".$_GET['rok']." ON pacienti_".$_GET['rok'].".pacient_cislo = karty_".$_GET['rok'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = datum_uctovania_skr
WHERE datum_uctovania_skr = '".$_GET['rok']."-".$_GET['mesiac']."'
ORDER BY datum_uctovania, karta_id";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
$dat=$riadok=0;
$pokladna=$banka=$pokladna_konecny_stav=$banka_konecny_stav=$rozdiel_pokladna=$rozdiel_banka=$spolu_prijem_banka=$spolu_prijem_pokladna=$spolu_vydaj_banka=$spolu_vydaj_pokladna=0.0;

while($row=mysqli_fetch_array($run)){
  $doklad_cislo=$row['doklad_cislo'];
  $prijem=$row['prijem'];
  $vydaj=$row['vydaj'];

  if($riadok==0){
    $pokladna=$pokladna_pociatocny_stav=$row['pokladna_pociatocny_stav'];
    $banka=$banka_pociatocny_stav=$row['banka_pociatocny_stav'];
  }

  $pokladna_konecny_stav=$row['pokladna_konecny_stav'];
  $banka_konecny_stav=$row['banka_konecny_stav'];

  if($dat!=$row['datum_uctovania']){
    $dat=$row['datum_uctovania'];

    if($riadok>0){
?>
  <tr class="hlavicka">
    <td colspan="4" class="right">Pokladňa:</td>
    <th class="right"><?php echo number_format($spolu_prijem_pokladna,2,',','');?></th>
    <th class="right"><?php echo number_format($spolu_vydaj_pokladna,2,',','');?></th>
    <th class="right"><?php echo number_format($pokladna,2,',','');?></th>
    <th class="right"></th>
  </tr>

  <tr class="hlavicka">
    <td colspan="4" class="right">Banka:</td>
    <th class="right"><?php echo number_format($spolu_prijem_banka,2,',','');?></th>
    <th class="right"><?php echo number_format($spolu_vydaj_banka,2,',','');?></th>
    <th class="right"></th>
    <th class="right"><?php echo number_format($banka,2,',','');?></th>
  </tr>

  <tr><td colspan="8"><br /></td></tr>
<?php
      $spolu_prijem_banka=$spolu_prijem_pokladna=$spolu_vydaj_banka=$spolu_vydaj_pokladna=0.0;
    }
?>
  <tr>
    <td colspan="2" class="left">Dátum účtovania: <b><?php echo date('j.n.Y', strtotime($row['datum_uctovania']));?></b></td>
    <!--td class="left" colspan="2">Dátum účtovania:</td>
    <th class="left">< ?php echo date('j.n.Y', strtotime($row['datum_uctovania']));?></th-->
    <td colspan="2"></td>
    <td class="right">Pokladňa:</td>
    <th><?php echo number_format($pokladna,2,',','');?></th>
    <td class="right">Banka:</td>
    <th><?php echo number_format($banka,2,',','');?></th>
  </tr>

  <tr class="hlavicka">
    <td>Číslo<br />pacienta</td>
    <td>Meno</td>
    <td>Číslo<br />dokladu</td>
    <td>Kód</td>
    <td>Príjem<br />(€)</td>
    <td>Výdaj<br />(€)</td>
    <td>Pokladňa<br />(€)</td>
    <td>Banka<br />(€)</td>
  </tr>
<?php
//    $pokladna_konecny_stav=$row['pokladna_konecny_stav'];
//    $banka_konecny_stav=$row['banka_konecny_stav'];
  }

  switch(strcspn($doklad_cislo,'B')){
    case 0:
      $banka+=round(($prijem-$vydaj)*100)/100;
      $spolu_prijem_banka+=$prijem;
      $spolu_vydaj_banka+=$vydaj;
      break;
    default:
      $pokladna+=round(($prijem-$vydaj)*100)/100;
      $spolu_prijem_pokladna+=$prijem;
      $spolu_vydaj_pokladna+=$vydaj;
  }

?>
  <tr class="farba">
    <td><?php echo $row['pacient_cislo'];?></td>
    <td class="left"><?php echo $row['priezvisko'].' '.$row['meno'];?></td>
    <td class="right"><?php echo $doklad_cislo;?></td>
    <td class="left"><?php echo $row['kod'].' - '.$_SESSION['kody'.$row['kod']];?></td><!-- kód -->
    <td class="right"><?php echo number_format($prijem,2,',','');?></td>
    <td class="right"><?php echo number_format($vydaj,2,',','');?></td>
    <td class="right"><?php echo number_format($pokladna,2,',','');?></td>
    <td class="right"><?php echo number_format($banka,2,',','');?></td>
  </tr>
<?php
  $riadok++;
}
?>
  <tr class="hlavicka">
    <td colspan="4" class="right">Pokladňa:</td>
    <th class="right"><?php echo number_format($spolu_prijem_pokladna,2,',','');?></th>
    <th class="right"><?php echo number_format($spolu_vydaj_pokladna,2,',','');?></th>
    <th class="right"><?php echo number_format($pokladna,2,',','');?></th>
    <th class="right"></th>
  </tr>

  <tr class="hlavicka">
    <td colspan="4" class="right">Banka:</td>
    <th class="right"><?php echo number_format($spolu_prijem_banka,2,',','');?></th>
    <th class="right"><?php echo number_format($spolu_vydaj_banka,2,',','');?></th>
    <th class="right"></th>
    <th class="right"><?php echo number_format($banka,2,',','');?></th>
  </tr>
<?php
$rozdiel_pokladna=round(($pokladna_konecny_stav-$pokladna)*100)/100;
$rozdiel_banka=round(($banka_konecny_stav-$banka)*100)/100;

if($rozdiel_pokladna!=0 OR $rozdiel_banka!=0){
?>
  <tr class="hlavicka">
    <td colspan="5"></td>
    <td class="upozorneniestr">&Delta;</td>
    <th class="upozorneniestr"><?php echo number_format($rozdiel_pokladna,2,',','');?></th>
    <th class="upozorneniestr"><?php echo number_format($rozdiel_banka,2,',','');?></th>
  </tr>
<?php
}
?>
</table>

<?php
//print_r($pacient);
/*...* /include('pata.php');/*...*/

if(isset($dbcon)){
  $dbcon->close();
}
?>