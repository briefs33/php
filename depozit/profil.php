<?php
include('hlavicka.php');
$nadpis='Profil';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');

$pacient_cislo=$_GET['pacient_cislo'];

if(isset($_POST['upravit'])){
  $nahrad=array(','=>'.', ', '=>'.');

  $check_pacient_cislo_query="SELECT pacient_cislo FROM pacienti_".$_SESSION['obdobie']." WHERE pacient_cislo='".$_POST['pacient_cislo']."'";
  $run_query=mysqli_query($dbcon,$check_pacient_cislo_query);
  if(mysqli_num_rows($run_query)>1){echo '<script>alert("Zadali ste existujúce osobné číslo $pacient_cislo, Prosím zadajte iné!")</script>'; exit();}

  $sql.="UPDATE pacienti_".$_SESSION['obdobie']." SET
pacient_cislo='".$_POST['pacient_cislo']."',
meno='".$_POST['meno']."',
priezvisko='".$_POST['priezvisko']."',
rodne_cislo='".$_POST['rodne_cislo']."',
pociatocny_stav='".strtr($_POST['pociatocny_stav'], $nahrad)."',
poznamka='".$_POST['poznamka']."',
stav='".$_POST['stav']."'
WHERE pacient_cislo='$pacient_cislo'";

  $run=mysqli_query($dbcon,$sql);
  if($dbcon->multi_query($sql)===true){header('Location: profil.php?pacient_cislo='.$pacient_cislo);}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else{
  $sql="SELECT pacient_cislo, meno, priezvisko, rodne_cislo, pociatocny_stav, poznamka, stav FROM pacienti_".$_SESSION['obdobie']."
WHERE pacient_cislo='$pacient_cislo'";

  $run=mysqli_query($dbcon,$sql);
  $row=mysqli_fetch_array($run);
?>
<fieldset class="mini">
  <form role="form" method="post" action="profil.php?pacient_cislo=<?php echo $pacient_cislo;?>">
    <table>
      <tr><td colspan="2"><br /></td></tr>

      <tr>
        <td class="right">Číslo pacienta:</td>
        <td><input placeholder="číslo pacienta" name="pacient_cislo" type="text" autofocus="autofocus" maxlength="4" required value="<?php echo $row['pacient_cislo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Priezvisko:</td>
        <td><input placeholder="priezvisko" name="priezvisko" type="text" maxlength="25" required value="<?php echo $row['priezvisko'];?>"></td>
      </tr>

      <tr>
        <td class="right">Meno, titul:</td>
        <td><input placeholder="meno" name="meno" type="text" maxlength="20" required value="<?php echo $row['meno'];?>"></td>
      </tr>

      <tr>
        <td class="right">Rodné číslo:</td>
        <td><input placeholder="rodné číslo" name="rodne_cislo" type="text" maxlength="11" value="<?php echo $row['rodne_cislo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Počiatočný stav:</td>
        <td><input placeholder="počiatočný stav" name="pociatocny_stav" type="text" maxlength="8" required value="<?php echo str_replace(".",",",$row['pociatocny_stav']);?>"></td>
      </tr>

      <tr>
        <td class="right">Poznámka:</td>
        <td><textarea placeholder="poznámka" name="poznamka" type="text" maxlength="62" cols="21" rows="3"><?php echo $row['poznamka'];?></textarea></td>
      </tr>

      <tr>
        <td class="right">Stav<span class='tooltip'>
<em>?</em><div><strong>Stav:</strong><p>
0 - neaktívny<br />
1 - aktívny
</p></div></span></td>

        <td><select name="stav">
<?php
$i=0;
$stav=$row['stav'];
include('stav.php');
?>
        </select></td>
      </tr>
    </table>
<?php
  if($_SESSION['obdobie_stav']==0){
?>
      <input type="submit" value="Upraviť" name="upravit">
<?php
  }
?>
  </form>
</fieldset>

<?php
}
?>

<p class="center"><a href="prehlad_pacientov.php?stav=<?php if(empty($stav)){echo 1;}else{echo $stav;}?>"><button>Prehľad pacientov</button></a></p>

<?php
include('pata.php');
?>