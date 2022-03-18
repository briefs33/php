<?php
include('hlavicka.php');
$nadpis='Registrácia';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
include('pole_pracovisk.php');
?>

<fieldset class="mini">
  <form role="form" method="post" action="registracia.php" name="registracny_formular" onsubmit="return validateForm()">
    <table>
      <tr>
        <td class="right">Titul pred menom:</td>
        <td><input placeholder="titul pred menom" name="titul_pm" type="text" autofocus="autofocus"></td>
      </tr>

      <tr>
        <td class="right">Meno:</td>
        <td><input placeholder="meno" name="meno" type="text" required></td>
      </tr>

      <tr>
        <td class="right">Priezvisko:</td>
        <td><input placeholder="priezvisko" name="priezvisko" type="text" required></td>
      </tr>

      <tr>
        <td class="right">Titul za menom:</td>
        <td><input placeholder="titul za menom" name="titul_zm" type="text"></td>
      </tr>

      <tr>
        <td class="right">Osobné číslo:</td>
        <td><input placeholder="osobné číslo" name="osobne_cislo" type="text"></td>
      </tr>

      <tr>
        <td class="right">Pracovisko:</td>
        <td><select name="oddelenie_id">
<?php
$i=1;
include('pracoviska.php');
?>
         </select></td>
      </tr>

      <tr>
        <td class="right">E-mail:</td>
        <td><input placeholder="e-mail" name="email" type="email" required></td>
      </tr>

      <tr>
        <td class="right">Heslo:</td>
        <td><input placeholder="heslo" name="heslo" type="text" required></td>
      </tr>
    </table><br />
    <input type="submit" value="Registrovať" name="register"/>
  </form>
</fieldset>
<?php
if(isset($_POST['register'])){
  $db='default';
  include('databaza.php');
  $email=$_POST['email'];

  $check_email_query="SELECT stravnik_id FROM prihlasovacie_udaje WHERE email='$email'";
  $run_query=mysqli_query($dbcon,$check_email_query);
  if(mysqli_num_rows($run_query)>0){echo '<script>alert("Zadali ste existujúci e-mail $email, Prosím zadajte iný!")</script>'; exit();}

  $stravnici="INSERT INTO stravnici
  (stravnik_id,titul_pm,meno,priezvisko,titul_zm,osobne_cislo,oddelenie_id,cena_id)
VALUES(
'',
'".$_POST['titul_pm']."',
'".$_POST['meno']."',
'".$_POST['priezvisko']."',
'".$_POST['titul_zm']."',
'".$_POST['osobne_cislo']."',
'".$_POST['oddelenie_id']."',
1
)";

  if(mysqli_query($dbcon,$stravnici)){
    $stravnik_id=$dbcon->insert_id;
    $prihlasovacie_udaje="INSERT INTO prihlasovacie_udaje
(stravnik_id,email,heslo,rola,posledne_prihlasenie)
VALUES(
'$stravnik_id',
'$email',
'".$_POST['heslo']."',
1,
''
)";

    if(mysqli_query($dbcon,$prihlasovacie_udaje)){
?>
<script>
  alert("Registrácia stravníka prebehla úspešne.");
  location.href = "prehlad_stravnikov.php";
</script>
<?php
    }
  }
  else{echo '<script>alert("Chyba pri registrácii stravníka.")</script>';}
}

include('pata.php');
?>