<?php
include('hlavicka.php');
$nadpis='Profil';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');
include('pole_pracovisk.php');
$email=$_SESSION['email'];
$rola=$_SESSION['rola'];

if(isset($_GET['strid'])){$stravnik_id=$_GET['strid'];}
else{$stravnik_id=$_SESSION['stravnik_id'];}

if(isset($_POST['upravit'])){
  $sql="UPDATE prihlasovacie_udaje SET
email='".$_POST['email']."',
heslo='".$_POST['heslo']."',
rola='".$_POST['rola']."'
WHERE prihlasovacie_udaje.stravnik_id='$stravnik_id';";
  $sql.="UPDATE stravnici SET
titul_pm='".$_POST['titul_pm']."',
meno='".$_POST['meno']."',
priezvisko='".$_POST['priezvisko']."',
titul_zm='".$_POST['titul_zm']."',
osobne_cislo='".$_POST['osobne_cislo']."',
oddelenie_id='".$_POST['oddelenie_id']."',
cena_id='".$_POST['cena_id']."'
WHERE stravnici.stravnik_id='$stravnik_id'";

  $run=mysqli_query($dbcon,$sql);
  if($dbcon->multi_query($sql)===true){header('Location: profil.php?strid='.$stravnik_id);}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else{
  $sql="SELECT
prihlasovacie_udaje.email,
prihlasovacie_udaje.heslo,
prihlasovacie_udaje.rola,
stravnici.titul_pm,
stravnici.meno,
stravnici.priezvisko,
stravnici.titul_zm,
stravnici.osobne_cislo,
stravnici.oddelenie_id,
stravnici.cena_id,
oddelenia.oddelenie_skratka
FROM stravnici
INNER JOIN prihlasovacie_udaje ON stravnici.stravnik_id=prihlasovacie_udaje.stravnik_id
INNER JOIN oddelenia ON stravnici.oddelenie_id=oddelenia.oddelenie_id
WHERE stravnici.stravnik_id='$stravnik_id'";

  $run=mysqli_query($dbcon,$sql);
  $row=mysqli_fetch_array($run);
?>
<fieldset class="mini">
  <form role="form" method="post" action="profil.php?strid=<?php echo $stravnik_id;?>">
    <table>
      <tr>
        <td class="right">Titul pred menom:</td>
        <td><input placeholder="titul pred menom" name="titul_pm" type="text" autofocus="autofocus" value="<?php echo $row['titul_pm'];?>"></td>
      </tr>

      <tr>
        <td class="right">Meno:</td>
        <td><input required placeholder="meno" name="meno" type="text" value="<?php echo $row['meno'];?>"></td>
      </tr>

      <tr>
        <td class="right">Priezvisko:</td>
        <td><input required placeholder="priezvisko" name="priezvisko" type="text" value="<?php echo $row['priezvisko'];?>"></td>
      </tr>

      <tr>
        <td class="right">Titul za menom:</td>
        <td><input placeholder="titul za menom" name="titul_zm" type="text" value="<?php echo $row['titul_zm'];?>"></td>
      </tr>

      <tr>
        <td class="right">Osobn?? ????slo:</td>
        <td><input required placeholder="osobn?? ????slo" name="osobne_cislo" type="text" value="<?php echo $row['osobne_cislo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Pracovisko:<br /></td>
        <td><select name="oddelenie_id">
<?php
$i=1;
include('pracoviska.php');
?>
        </select></td>
      </tr>

      <tr>
        <td class="right">E-mail:</td>
        <td><input required <?php if($rola<8){echo 'readonly';}?> placeholder="email" name="email" type="email" value="<?php echo $row['email'];?>"></td>
      </tr>

      <tr>
        <td class="right">Heslo:</td>
        <td><input required <?php if($_SESSION['email']===$row['email']){} else if($rola<8){echo 'readonly type="password"';}?> placeholder="heslo" name="heslo" value="<?php echo $row['heslo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Kateg??ria<?php if($rola>8){?><span class='tooltip'>
<em>?</em><div><strong>Rola:</strong><p>
1 - be??n?? u????vate??<br />
2 - ved??ce sestry<br />
3 - skupina (AMB, ??dr??ba)<br />
4 - skupina (Pr????ov??a)<br />
5 - <br />
6 - <br />
7 - stravovacia prev??dzka<br />
8 - administr??tor (IT)<br />
9 - ??pln?? pr??stup (IT)
</p></div></span><?php }?>:</td>

        <td><input required <?php if($rola<9){echo 'readonly';}?> placeholder="kategoria" name="rola" type="text" value="<?php echo $row['rola'];?>"></td>
      </tr>

      <tr>
        <td class="right">Cenn??k<?php if($rola==7){?><span class='tooltip'>
<em>?</em><div><p>
Identifika??n?? ????slo cenn??ka (vi??. cenn??k)
</p></div></span><?php }?>:</td>

        <td><input required <?php if($rola!=7){echo 'readonly';}?> placeholder="cennik_id" name="cena_id" type="text" value="<?php echo $row['cena_id'];?>"></td>
      </tr>
    </table>
      <input type="submit" value="Upravi??" name="upravit">
  </form>
</fieldset>
<?php
  if(isset($_GET['strid'])){
    if(isset($_SESSION['oddelenie_id'])){echo '<p class="center"><a href="prehlad_stravnikov.php"><button>Preh??ad stravn??kov</button></a></p>';}
  }
}
include('pata.php');
?>