<?php
include('hlavicka.php');
$nadpis='Pohyby pacientov';
include('nadpis.php');

echo '<h1>'.$nadpis.' Δ</h1>';
?>

<table>
<?php
$db='default';
include('databaza.php');

$pacienti_zostatok=$prijem=$vydaj=$zostatok=$pociatocny_stav=0.0;
$sql="SELECT pacienti_".$_SESSION['obdobie'].".pacient_cislo, karta_id, doklad_cislo, meno, priezvisko, pociatocny_stav, datum_uctovania_skr, datum_uctovania, kod, prijem, vydaj, zostatok
FROM karty_".$_SESSION['obdobie']."
INNER JOIN pacienti_".$_SESSION['obdobie']." ON pacienti_".$_SESSION['obdobie'].".pacient_cislo = karty_".$_SESSION['obdobie'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = datum_uctovania_skr
ORDER BY pacient_cislo, datum_uctovania, karta_id
";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
$dat=$riadok=0;

while($row=mysqli_fetch_array($run)){
  $doklad_cislo=$row['doklad_cislo'];

  if($dat!=$row['pacient_cislo']){
    $dat=$row['pacient_cislo'];

    if($riadok>0){
?>
  <tr class="hlavicka">
    <td colspan="3"></td>
    <td class="right"><b><?php echo number_format($prijem,2,',','');?></b></td>
    <td class="right"><b><?php echo number_format($vydaj,2,',','');?></b></td>
    <td class="right"><b><?php echo number_format($zostatok,2,',','');?></b></td>
  </tr>
<?php
      if((round($zostatok*100)/100)!=$pacienti_zostatok){
?>
  <tr class="hlavicka">
    <td colspan="4"></td>
    <td class="upozorneniestr">&Delta;</td>
    <th class="upozorneniestr"><?php echo number_format($pacienti_zostatok,2,',','');?></th>
  </tr>
<?php
      }
      $prijem=$vydaj=0.0;
?>
  <tr><td colspan="6"><br /></td></tr>
<?php
    }
?>
  <tr>
    <td class="left" colspan="2">Číslo pacienta: <b><?php echo $row['pacient_cislo'];?></b></th>
    <td class="left">Meno: <b><?php echo $row['priezvisko'].' '.$row['meno'];?></b></th>
    <td class="right" colspan="2">Počiatočný stav:</td>
    <th class="right"><?php echo number_format($row['pociatocny_stav'],2,',','');?></th>
  </tr>

  <tr class="hlavicka">
    <td>Dátum<br />účtovania</td>
    <td>Číslo<br />dokladu</td>
    <td>Kód</td>
    <td>Príjem<br />(€)</td>
    <td>Výdaj<br />(€)</td>
    <td>Zostatok<br />(€)</td>
  </tr>
<?php
    $zostatok=$pociatocny_stav=$row['pociatocny_stav'];
  }
  $prijem+=$row['prijem'];
  $vydaj+=$row['vydaj'];
  $pacienti_zostatok=$row['zostatok'];
?>
  <tr class="farba">
    <td class="right"><?php echo date('j.n.Y', strtotime($row['datum_uctovania']));?></td>
    <td class="right"><?php echo $row['doklad_cislo'];?></td>
    <td class="left"><?php echo $row['kod'].' - '.$_SESSION['kody'.$row['kod']];?></td><!-- kód -->
    <td class="right"><?php echo number_format($row['prijem'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['vydaj'],2,',','');?></td>
    <?php $zostatok+=$row['prijem']-$row['vydaj'];?>
    <td class="right"><?php echo number_format($zostatok,2,',','');?></td>
  </tr>
<?php
  $riadok++;
}
?>
  <tr class="hlavicka">
    <td colspan="3"></td>
    <td class="right"><b><?php echo number_format($prijem,2,',','');?></b></td>
    <td class="right"><b><?php echo number_format($vydaj,2,',','');?></b></td>
    <td class="right"><b><?php echo number_format($zostatok,2,',','');?></b></td>
  </tr>
<?php
if((round($zostatok*100)/100)!=$pacienti_zostatok){
?>
  <tr class="hlavicka">
    <td colspan="4"></td>
    <td class="upozorneniestr">&Delta;</td>
    <th class="upozorneniestr"><?php echo number_format($pacienti_zostatok,2,',','');?></th>
  </tr>
<?php
}
?>
</table>

<?php
//print_r($pacient);
include('pata.php');
?>