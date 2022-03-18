<?php
include('hlavicka.php');
$nadpis='Uzávierka';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');
$riadok='1';

if(isset($_POST['vytvorit'])){
  $obdobie=$_POST['obdobie']-1;
  $obdobie_nove=$_POST['obdobie'];
  $sql_obdobie="INSERT INTO obdobia (rok,vytvoril) VALUES (".$obdobie_nove.",'".$_SESSION['email']."');";

  if($dbcon->query($sql_obdobie)===true){/*header('Location: uzavierka.php?insert=Obdobie_bolo_vytvorené.');*/}
  else{echo 'Chyba: '.$sql_obdobie.'<br />'.$dbcon->error;}

  $sql_select="SELECT `pacient_cislo`, `meno`, `priezvisko`, `rodne_cislo`, `zostatok`, `poznamka`, `stav`
FROM pacienti_".$obdobie." WHERE `stav`=1;";
  $run=mysqli_query($dbcon,$sql_select) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql_select');

  $n=1;
  $ck=1;
  $cz=0;
  $sql_insert="INSERT INTO pacienti_".$obdobie_nove." (`pacient_cislo`, `meno`, `priezvisko`, `rodne_cislo`, `pociatocny_stav`, `zostatok`, `poznamka`, `stav`) VALUES ";
  while($row=mysqli_fetch_array($run)){
    if($n==1){$sql_insert.='(';}else{$sql_insert.=',(';}
    $n++;
    if($cz!=substr($row['pacient_cislo'],0,2)){$ck=1;}else{$ck++;}
    $cz=substr($row['pacient_cislo'],0,2);
    if($cz<88){$pacient_cislo="'".str_pad($cz,2,0,STR_PAD_LEFT).str_pad($ck,2,0,STR_PAD_LEFT)."'";}
    else{$pacient_cislo="'".$cz.$cz."'";}
    $sql_insert.=$pacient_cislo.",'".$row['meno']."','".$row['priezvisko']."','".$row['rodne_cislo']."',".$row['zostatok'].",".$row['zostatok'].",'".$row['poznamka']."',".$row['stav'].")";
  }
  if($dbcon->query($sql_insert)===true){header('Location: uzavierka.php?insert=Obdobie_bolo_vytvorené.');}
  else{echo 'Chyba: '.$sql_insert.'<br />'.$dbcon->error;}
//  echo $sql_insert;
}
else{
  $sql="SELECT rok, cas_vytvorenia FROM obdobia";
  $run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
?>

<table>
  <tr class="hlavicka">
    <td>Obdobie</td>
    <td>Čas vytvorenia</td>
    <td>Odstrániť</td>
  </tr>

<?php
  while($row=mysqli_fetch_array($run)){
?>
  <tr class="farba">
    <td><?php echo $row['rok'];?></td>
    <td><?php echo $row['cas_vytvorenia'];?></td>
    <td>?</td>
    </td>
  </tr>
<?php
  }
?>
</table>

<br />

<fieldset class="mikro">
  <legend><b>Vytvoreniť nové obdobie</b></legend>
  <form role="form" method="post" action="uzavierka.php" name="formular_obdobia" onsubmit="return validateForm()">
    <table>
      <tr><td colspan="3"><br /></td></tr>

      <tr>
        <td class="right">Obdobie:</td>
        <td>
          <select name="obdobie">
<?php include("obdobie.php");?>
          </select>
        </td>
        <td>
          <input type="submit" value="Vytvoriť" name="vytvorit"/>
        </td>
      </tr>
    </table>
  </form>
</fieldset>

<?php
}

include('pata.php');
?>