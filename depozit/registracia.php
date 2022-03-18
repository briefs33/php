<?php
include('hlavicka.php');
$nadpis='Registrácia';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
?>

<fieldset class="mini">
  <form role="form" method="post" action="registracia.php" name="registracny_formular" onsubmit="return validateForm()">
    <table>
      <tr><td colspan="2"><br /></td></tr>    

      <tr>
        <td class="right">Číslo pacienta:</td>
        <td><input placeholder="číslo pacienta" name="pacient_cislo" type="text" maxlength="4" required autofocus="autofocus"></td>
      </tr>

      <tr>
        <td class="right">Priezvisko:</td>
        <td><input placeholder="priezvisko" name="priezvisko" type="text" maxlength="25" required></td>
      </tr>

      <tr>
        <td class="right">Meno, titul:</td>
        <td><input placeholder="meno" name="meno" type="text" maxlength="20" required></td>
      </tr>

      <tr>
        <td class="right">Rodné číslo:</td>
        <td><input placeholder="rodné číslo" name="rodne_cislo" type="text" maxlength="11"></td>
      </tr>

      <tr>
        <td class="right">Počiatočný stav:</td>
        <td><input placeholder="počiatočný stav" name="pociatocny_stav" type="text" maxlength="8" required></td>
      </tr>

      <tr>
        <td class="right">Poznámka:</td>
        <td><textarea placeholder="poznámka" name="poznamka" type="text" maxlength="62" cols="21" rows="3"></textarea></td>
      </tr>
    </table>
    <input type="submit" value="Registrovať" name="register"/>
  </form>
</fieldset>
<?php
if(isset($_POST['register'])){
  $db='default';
  include('databaza.php');
  $pacient_cislo=$_POST['pacient_cislo'];

  $check_pacient_cislo_query="SELECT pacient_cislo FROM pacienti_".$_SESSION['obdobie']." WHERE pacient_cislo='$pacient_cislo'";
  $run_query=mysqli_query($dbcon,$check_pacient_cislo_query);
  if(mysqli_num_rows($run_query)>0){echo '<script>alert("Zadali ste existujúce osobné číslo $pacient_cislo, Prosím zadajte iné!")</script>'; exit();}

  $nahrad=array(','=>'.', ', '=>'.');

  $sql_pacienti="INSERT INTO pacienti_".$_SESSION['obdobie']."
  (pacient_cislo,meno,priezvisko,rodne_cislo,pociatocny_stav, zostatok,poznamka,stav)
VALUES(
'".$_POST['pacient_cislo']."',
'".$_POST['meno']."',
'".$_POST['priezvisko']."',
'".$_POST['rodne_cislo']."',
'".strtr($_POST['pociatocny_stav'], $nahrad)."',
'".strtr($_POST['pociatocny_stav'], $nahrad)."',
'".$_POST['poznamka']."',
1
)";

  if(mysqli_query($dbcon,$sql_pacienti)){
$sql="SELECT pacient_cislo, meno, priezvisko, zostatok, stav
FROM pacienti_".$_SESSION['obdobie']."
ORDER BY pacient_cislo";
// WHERE stav='$stav'

$run=mysqli_query($dbcon,$sql);


if(isset($_SESSION['pacienti'])){
  array_push($_SESSION['pacienti'],array('pacient_cislo'=>$_POST['pacient_cislo'], 'priezvisko'=>$_POST['priezvisko'], 'meno'=>$_POST['meno'], 'zostatok'=>strtr($_POST['pociatocny_stav'], $nahrad)));

/** /
print_r($pacienti);
echo '<br /><br />';
/**/
}
else{
  $pacienti=array();
  sleep(1/4);
  while($row=mysqli_fetch_array($run)){
    array_push($pacienti,array('pacient_cislo'=>$row['pacient_cislo'], 'priezvisko'=>$row['priezvisko'], 'meno'=>$row['meno'], 'zostatok'=>$row['zostatok']));
  }
  $_SESSION['pacienti']=$pacienti;
}
?>
<script>
  alert("Registrácia pacienta prebehla úspešne.");
//  location.href = "prehlad_pacientov.php?stav=1";
</script>
<?php
  }
  else{echo '<script>alert("Chyba pri registrácii pacienta!")</script>';}
}

include('pata.php');
?>