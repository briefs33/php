<?php
include('hlavicka.php');
$nadpis='Prehľad účtu';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$obdobie=$_SESSION['obdobie'];
$pacient_cislo=$_GET['pacient_cislo'];
$meno=$_GET['meno'];

$pokladna_konecny_stav=$banka_konecny_stav='';
?>

<table>
  <tr>
    <!--td class="center" colspan="2">
      <a href="prehlad_pacientov.php?stav=1">
        <button>Prehľad pacientov</button>
      </a>
    </td-->

    <td colspan="3">
      <h3><?php echo $pacient_cislo.' - '.$meno;?></h3>
    </td>

    <td class="right" colspan="2">Počiatočný stav:<br /><br /><!--p id="pociatocny_stav"></p--></td>

    <td>
      <h3 id="pociatocny_stav"></h3>
    </td>

    <th colspan="2" class="noprint">
      <a href="pdf_prehlad_uctu.php?pacient_cislo=<?php echo $pacient_cislo.'&meno='.$meno.'&obdobie='.$_SESSION['obdobie'];
for($x=1;$x<10;$x++){
  echo '&kod_'.$x.'='.$_SESSION['kody'.$x];
}
?>" target="_blank">
        <img src="tcpdf.png" width="88" height="29" border="1" alt="php2pdf">
      </a>
    </th>
  </tr>

  <tr>
    <td colspan="8"></td>
  </tr>

  <tr class="hlavicka">
    <td>Dátum<br />účtovania</td>
    <td>Číslo<br />dokladu</td>
    <td>Kód</td>
    <td>Príjem<br />(€)</td>
    <td>Výdaj<br />(€)</td>
    <td>Zostatok<br />(€)</td>
    <td>Opravenie<br />zápisu</td>
    <!--td>Odstránenie<br />zápisu</td-->
    <td>Poznámka</td>
  </tr>
<?php
$db='default';
include('databaza.php');

//$pacient='';
//$pocet='149';
$pc=$riadok=0;
$pacienti_zostatok=$prijem=$vydaj=$zostatok=$pociatocny_stav=0.0;

$sql="SELECT karta_id, karta_poznamka, pacienti_".$_SESSION['obdobie'].".pacient_cislo, pokladna_konecny_stav, banka_konecny_stav, meno, priezvisko, datum_uctovania_skr, datum_uctovania, doklad_cislo, kod, pociatocny_stav, prijem, vydaj, zostatok
FROM karty_".$_SESSION['obdobie']."
INNER JOIN pacienti_".$_SESSION['obdobie']." ON pacienti_".$_SESSION['obdobie'].".pacient_cislo = karty_".$_SESSION['obdobie'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = karty_".$_SESSION['obdobie'].".datum_uctovania_skr
WHERE pacienti_".$_SESSION['obdobie'].".pacient_cislo='$pacient_cislo'";
//ORDER BY datum_uctovania";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

while($row=mysqli_fetch_array($run)){
  $pc++;
  $riadok++;
  $karta_id=$row['karta_id'];
  $karta_poznamka=$row['karta_poznamka'];
  $date=date_create($row['datum_uctovania']);
  date_sub($date,date_interval_create_from_date_string('0 days'));
  $den=$date;
  if($riadok==1){$zostatok=$pociatocny_stav=$row['pociatocny_stav'];}
  $prijem+=$row['prijem'];
  $vydaj+=$row['vydaj'];
  $pacienti_zostatok=$row['zostatok'];

  $zostatok+=$row['prijem']-$row['vydaj'];
?>

  <tr class="farba">
    <td class="right"><?php echo date_format($date, 'j.n.Y');?></td>
    <td class="right"><?php echo $row['doklad_cislo'];?></td>
    <td class="left"><?php echo $row['kod'].' - '.$_SESSION['kody'.$row['kod']];?></td><!-- kód -->
    <td class="right"><?php echo number_format($row['prijem'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['vydaj'],2,',','');?></td>
    <td class="right"><?php echo number_format($zostatok,2,',','');
    //str_replace(".",",",(round($zostatok*100)/100));
    ?></td>

    <td>
<?php
  if($_SESSION['obdobie_stav']==0){
?>
<a href="opravenie_zapisu.php?pacient_cislo=<?php echo $row['pacient_cislo'].'&meno='.$row['priezvisko'].' '.$row['meno'].'&datum_uctovania_skr='.date('Y-m', strtotime($row['datum_uctovania_skr'])).'&datum_uctovania='.date('Y-m-d', strtotime($row['datum_uctovania'])).'&karta_id='.$row['karta_id'].'&karta_poznamka='.$row['karta_poznamka'].'&doklad_cislo='.$row['doklad_cislo'].'&kod='.$row['kod'].'&prijem='.$row['prijem'].'&vydaj='.$row['vydaj'].'&zostatok='.$row['zostatok'].'&pokladna_konecny_stav='.$row['pokladna_konecny_stav'].'&banka_konecny_stav='.$row['banka_konecny_stav'];?>">
  <button>Opraviť</button>
</a>
<?php
  }
?>
    </td>

    <!--td>
<a href="zmazanie_zapisu.php?odstranit=.$stravnik_id.'&pacient_cislo=< ?php echo $row['pacient_cislo'].'&meno='.$row['priezvisko'].' '.$row['meno'].'&datum_uctovania='.date('Y-m-d', strtotime($row['datum_uctovania'])).'&karta_id='.$row['karta_id'].'&doklad_cislo='.$row['doklad_cislo'].'&doklad='.$row['kod'].'&prijem='.str_replace(".",",",$row['prijem']).'&vydaj='.str_replace(".",",",$row['vydaj']);?>">
  <button class="upozornenie">Odstrániť</button>
</a>
    </td-->

    <td class="right"><?php echo $row['karta_poznamka'];?></td>
  </tr>

<?php
}

  if($pc==0){
$sql="SELECT pacient_cislo, meno, priezvisko, pociatocny_stav, zostatok FROM pacienti_".$_SESSION['obdobie']." WHERE pacient_cislo='$pacient_cislo';";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

while($row=mysqli_fetch_array($run)){
//  $pc++;
//  $riadok++;
//  $karta_id=$row['karta_id'];
//  $date=date_create($row['datum_uctovania']);
//  date_sub($date,date_interval_create_from_date_string('0 days'));
//  $den=$date;
  $zostatok=$pociatocny_stav=$row['pociatocny_stav'];
//  $prijem+=$row['prijem'];
//  $vydaj+=$row['vydaj'];
  $pacienti_zostatok=$row['zostatok'];
?>

  <tr class="farba">
    <td>-</td>
    <td>-</td>
    <td>-</td><!-- kód -->
    <td class="right">0</td>
    <td class="right">0</td>
    <td class="right"><?php echo number_format($zostatok,2,',','');?></td>
    <td td colspan="2"></td>
    <!--td>
<a href="zmazanie_zapisu.php?odstranit=.$stravnik_id.'&pacient_cislo=< ?php echo $row['pacient_cislo'].'&meno='.$row['priezvisko'].' '.$row['meno'].'&datum_uctovania='.date('Y-m-d', strtotime($row['datum_uctovania'])).'&karta_id='.$row['karta_id'].'&doklad_cislo='.$row['doklad_cislo'].'&doklad='.$row['kod'].'&prijem='.str_replace(".",",",$row['prijem']).'&vydaj='.str_replace(".",",",$row['vydaj']);?>">
  <button class="upozornenie">Odstrániť</button>
</a>
    </td-->
  </tr>

<?php
}
  }
?>
  <tr class="hlavicka">
    <td colspan="3"></td>
    <td class="right"><b><?php echo number_format($prijem,2,',','');?></b></td>
    <td class="right"><b><?php echo number_format($vydaj,2,',','');?></b></td>
    <td class="right"><b <?php if((round($zostatok*100)/100)!=$pacienti_zostatok){echo 'class="upozorneniestr"';}?> ><?php echo number_format($pacienti_zostatok,2,',','');?></b></td>
    <td td colspan="2"></td>
  </tr>
</table>

<script>document.getElementById("pociatocny_stav").innerHTML="<?php echo number_format($pociatocny_stav,2,',','').'€';?>";</script>

<?php
include('pata.php');
?>