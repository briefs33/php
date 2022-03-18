<?php
include('hlavicka.php');
$nadpis='Prehľad pacientov';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

if(isset($_GET['stav'])){
  $_SESSION['stav']=$stav=$_GET['stav'];
}
else{
  $stav=1;
}

$i=0;
?>
<table>
  <tr>
    <th class="stav">
      Stav:
      <select name="stav" onchange="window.location.href='prehlad_pacientov.php?stav='+this.value;">
<?php include('stav.php');?>
      </select>
    </th>
    <!--th><p id="stav"></p></th-->

    <th colspan="2" class="noprint">
      <a href="pdf_prehlad_pacientov.php?stav=<?php echo $_SESSION['stav'].'&obdobie='.$_SESSION['obdobie'];?>" target="_blank">
        <img src="tcpdf.png" width="88" height="29" border="1" alt="php2pdf">
      </a>
    </th>
  </tr>
</table>

<table>
  <tr class="hlavicka">
    <td>Pacient<br />číslo</td>
    <td>Meno</td>
    <td>Rodné<br />číslo</td>
    <td>Počiatočný<br />stav (€)</td>
    <td>Zostatok<br />(€)</td>
    <td>Poznámka</td>
    <td>Prehľad<br />účtu</td>
    <td>Profil</td>
  </tr>

<?php
$db='default';
$vyber=array('WHERE stav=0', 'WHERE stav=1', '');
include('databaza.php');
$sql="SELECT pacient_cislo, meno, priezvisko, rodne_cislo, pociatocny_stav, zostatok, poznamka, stav
FROM pacienti_".$_SESSION['obdobie']."
".$vyber[$stav]."
ORDER BY pacient_cislo";

$pociatocny_stav=$zostatok=0.0;
$prvy=0;

$run=mysqli_query($dbcon,$sql);
while($row=mysqli_fetch_array($run)){

  switch($row['pacient_cislo']){
    case '9999':
    case '8888':

      if($prvy==0){
?>
  <tr class="hlavicka">
    <td colspan="3"></td>
    <td><?php echo '<b>'.number_format($pociatocny_stav,2,',','').'</b>';?></td>
    <td><?php echo '<b>'.number_format($zostatok,2,',','').'</b>';?></td>
    <td colspan="3"></td>
  </tr>

  <tr>
    <td colspan="8"><br /></td>
  </tr>

  <tr class="hlavicka">
    <td>Pacient<br />číslo</td>
    <td>Meno</td>
    <td>Rodné<br />číslo</td>
    <td>Počiatočný<br />stav (€)</td>
    <td>Zostatok<br />(€)</td>
    <td>Poznámka</td>
    <td>Prehľad<br />účtu</td>
    <td>Profil</td>
  </tr>
<?php
        $prvy++;
      }
?>
  <tr class="farba">
    <td><?php echo $row['pacient_cislo'];?></td>
    <td class="left"><?php echo $row['priezvisko'].' '.$row['meno'];?></td>
    <td class="left"><?php echo $row['rodne_cislo'];?></td>
    <td class="right"><?php echo number_format($row['pociatocny_stav'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['zostatok'],2,',','');?></td>
    <td class="left"><?php echo $row['poznamka'];?></td>

    <td>
      <a href="prehlad_uctu.php?pacient_cislo=<?php echo $row['pacient_cislo'];?>&meno=<?php echo $row['priezvisko'].' '.$row['meno'];?>">
        <button>Zobraziť</button>
      </a>
    </td>

    <td>
      <a href="profil.php?pacient_cislo=<?php echo $row['pacient_cislo'];?>">
        <button>Upraviť</button>
      </a>
    </td>
  </tr>
<?php
      break;
    default:
$pociatocny_stav+=$row['pociatocny_stav'];
$zostatok+=$row['zostatok'];
?>
  <tr class="farba">
    <td><?php echo $row['pacient_cislo'];?></td>
    <td class="left"><?php echo $row['priezvisko'].' '.$row['meno'];?></td>
    <td class="left"><?php echo $row['rodne_cislo'];?></td>
    <td class="right"><?php echo number_format($row['pociatocny_stav'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['zostatok'],2,',','');?></td>
    <td class="left"><?php echo $row['poznamka'];?></td>

    <td>
      <a href="prehlad_uctu.php?pacient_cislo=<?php echo $row['pacient_cislo'];?>&meno=<?php echo $row['priezvisko'].' '.$row['meno'];?>">
        <button>Zobraziť</button>
      </a>
    </td>

    <td>
      <a href="profil.php?pacient_cislo=<?php echo $row['pacient_cislo'];?>">
        <button>Upraviť</button>
      </a>
    </td>
  </tr>
<?php
  }
}

if($prvy==0){
?>
  <tr class="hlavicka">
    <td colspan="3"></td>
    <td><?php echo '<b>'.number_format($pociatocny_stav,2,',','').'</b>';?></td>
    <td><?php echo '<b>'.number_format($zostatok,2,',','').'</b>';?></td>
    <td colspan="3"></td>
  </tr>
<?php
}
else{
?>
  <tr>
    <td colspan="8" class="hlavicka"><br /></td>
  </tr>
<?php
}
?>
</table><br />

<!-- ?php
if(isset($_GET['stav'])){
  if($_GET['stav']==0){$stav='Neaktívny';}
  else if($_GET['stav']==1){$stav='Aktívny';}
  else{$stav='Všetci';}
}
?>
<script>document.getElementById("stav").innerHTML="< ?php echo $stav;?>";</script-->

<?php include("pata.php");?>