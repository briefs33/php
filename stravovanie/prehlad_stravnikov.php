<?php
include('hlavicka.php');
$nadpis='Prehľad stravníkov';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

include('pole_pracovisk.php');

$pondelok_U=strtotime('monday');
$pondelok=date('Y-m-d', $pondelok_U);
$nedela_U=strtotime('+6 days', $pondelok_U);
$nedela=date('Y-m-d', $nedela_U);

if(isset($_GET['pracovisko'])){
  $_SESSION['oddelenie_id']=$oddelenie_id=$_GET['pracovisko'];
//  $_SESSION['oddelenie_skratka']=$oddelenie_skratka=$_GET['oddelenie'];
}
else{
  $oddelenie_id=$_SESSION['oddelenie_id'];
//  $oddelenie_skratka=$_SESSION['oddelenie_skratka'];
}
/**/$oddelenie_skratka=$_SESSION['oddelenie_skratka'];
$oddelenie_nazov=$_SESSION['oddelenie_nazov'];
$rola=$_SESSION['rola'];
$i=0;
?>
<table>
  <tr>
    <th class="pracovisko">
      Pracovisko:
      <select name="oddelenie_id" onchange="window.location.href='prehlad_stravnikov.php?pracovisko='+this.value+'&oddelenie='+options[this.value].text;">
<?php if($rola>=7){
  include('pracoviska.php');
}
?>
      </select>
    </th>
    <th><p id="oddelenie_nazov"></p></th>
  </tr>
</table>

<table>
  <tr class="hlavicka">
    <td>P. č.</td>
    <?php if($oddelenie_id==0){echo'<td>Oddelenie</td>';}?>
    <td>Meno</td>
    <td>Prehľad<br />objednávok</td>
<?php
if(($rola==7 or $rola==9) and $oddelenie_id==9){
  echo '<td>Obj. stravy<br />na mesiac</td>';
}
else{
  echo '<td>Objednanie<br />stravy</td>';
}
?>
    <td>Profil</td>
    <?php if($rola>8 and isset($_GET['heslo'])){echo '<td>Odstránenie<br />stravníka</td>';}?>
  </tr>

<?php
$db='default';
include('databaza.php');
switch ($oddelenie_id){
  case 0:
$sql="SELECT stravnici.stravnik_id, stravnici.titul_pm, stravnici.meno, stravnici.priezvisko, stravnici.titul_zm, stravnici.cena_id, oddelenia.oddelenie, oddelenia.oddelenie_skratka
FROM stravnici
INNER JOIN oddelenia ON stravnici.oddelenie_id = oddelenia.oddelenie_id
ORDER BY stravnici.priezvisko ASC, stravnici.meno ASC";
  break;
  default:
$sql="SELECT stravnici.stravnik_id, stravnici.titul_pm, stravnici.meno, stravnici.priezvisko, stravnici.titul_zm, stravnici.cena_id, oddelenia.oddelenie, oddelenia.oddelenie_skratka
FROM stravnici
INNER JOIN oddelenia ON stravnici.oddelenie_id = oddelenia.oddelenie_id
WHERE oddelenia.oddelenie_id='$oddelenie_id'
ORDER BY stravnici.priezvisko ASC, stravnici.meno ASC";
}

//$pondelok_U=strtotime('+1 week', $pondelok_U);
$i=0;
$run=mysqli_query($dbcon,$sql);
while($row=mysqli_fetch_array($run)){
  $i++;
  $stravnik_id=$row['stravnik_id'];
  $meno=$row['titul_pm'].' '.$row['meno'].' '.$row['priezvisko'].' '.$row['titul_zm'];
  $oddelenie_nazov=$row['oddelenie'];
  $pracovisko=$row['oddelenie_skratka'];
  $cena_id=$row['cena_id'];
?>
  <tr class="farba">
    <td><?php echo $i;?></td>
    <?php if($oddelenie_id==0){echo'<td>'.$pracovisko.'</td>';}?>
    <td <?php if($cena_id==1){echo ' class="aktualny"';}?>><?php echo $meno;?></td>
<?php
  if($rola>2 or $stravnik_id==$_SESSION['stravnik_id']){
    if($dbcon->connect_error){die('Connection failed:'.$dbcon->connect_error);}
    $skontroluj_den="SELECT datum, stravnik_id from objednavky WHERE stravnik_id='$stravnik_id' AND (datum BETWEEN '$pondelok' AND '$nedela')";
    $run_query=mysqli_query($dbcon,$skontroluj_den);
?>
    <td>
      <a href="prehlad_objednavok.php?strid=<?php echo $stravnik_id;?>">
        <button>Zobraziť</button>
      </a>
    </td>
<?php
    if($oddelenie_id!=9){
?>
    <td>
      <a href="objednanie_stravy.php?datum=<?php echo $pondelok_U.'&strid='.$stravnik_id.'&meno='.$meno;?>">
        <button <?php if(mysqli_num_rows($run_query)>0){echo 'class="objednane"';}?> >Objednať</button>
      </a>
    </td>
<?php
    }
  }
  else{echo '<td></td><td></td>';}

  if(($rola==7 or $rola==9) and $oddelenie_id==9){
    $datum=/*strtotime('+1 month', $pondelok_U)*/$pondelok_U;// echo date('m',$datum);
?>
    <td>
      <a href="objednanie_stravy_na_mesiac.php?mesiac=<?php echo date('m',$datum).'&datum='.$datum.'&strid='.$stravnik_id.'&meno='.$meno;?>">
        <button <?php if(mysqli_num_rows($run_query)>0){echo 'class="objednane"';}?> ><?php echo date('m / Y',$datum);?></button>
      </a>
    </td>
<?php
  }
?>
    <td>
      <a href="profil.php?strid=<?php echo $stravnik_id;?>">
        <button>Upraviť</button>
      </a>
    </td>
<?php
  if($rola>8 and isset($_GET['heslo'])){
    echo '<td><a href="zrusenie_stravnika.php?odstranit='.$stravnik_id.'"><button class="upozornenie">Odstrániť</button></a></td>';
  }
?>
  </tr>
<?php
}
?>
</table><br />

<?php if(isset($_GET['pracovisko'])){if($_GET['pracovisko']==0){$oddelenie_nazov='Všetky oddelenia';}}?>
<script>document.getElementById("oddelenie_nazov").innerHTML="<?php echo $oddelenie_nazov;?>";</script>

<?php include("pata.php");?>