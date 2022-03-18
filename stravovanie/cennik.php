<?php
include('hlavicka.php');
$nadpis='Cenník';
include('nadpis.php');

$db='default';
include('databaza.php');

if(isset($_POST['odoslat'])){
  $nahrad=array(','=>'.', ', '=>'.');
  if($_POST['cenid']==''){
    $sql="INSERT INTO ceny (
cena_id,
cena_obed_3,
cena_obed_4,
cena_obed_9,
cena_obed_13,
cena_obed_bzl,
cena_vecera_3,
cena_vecera_4,
cena_vecera_9,
cena_vecera_13,
cena_vecera_bzl) VALUES ('',
'".strtr($_POST['cena_obed_3'], $nahrad)."',
'".strtr($_POST['cena_obed_4'], $nahrad)."',
'".strtr($_POST['cena_obed_9'], $nahrad)."',
'".strtr($_POST['cena_obed_13'], $nahrad)."',
'".strtr($_POST['cena_obed_bzl'], $nahrad)."',
'".strtr($_POST['cena_vecera_3'], $nahrad)."',
'".strtr($_POST['cena_vecera_4'], $nahrad)."',
'".strtr($_POST['cena_vecera_9'], $nahrad)."',
'".strtr($_POST['cena_vecera_13'], $nahrad)."',
'".strtr($_POST['cena_vecera_bzl'], $nahrad)."'
)";
  }
  else{
    $sql="UPDATE ceny SET
cena_obed_3='".strtr($_POST['cena_obed_3'], $nahrad)."',
cena_obed_4='".strtr($_POST['cena_obed_4'], $nahrad)."',
cena_obed_9='".strtr($_POST['cena_obed_9'], $nahrad)."',
cena_obed_13='".strtr($_POST['cena_obed_13'], $nahrad)."',
cena_obed_bzl='".strtr($_POST['cena_obed_bzl'], $nahrad)."',
cena_vecera_3='".strtr($_POST['cena_vecera_3'], $nahrad)."',
cena_vecera_4='".strtr($_POST['cena_vecera_4'], $nahrad)."',
cena_vecera_9='".strtr($_POST['cena_vecera_9'], $nahrad)."',
cena_vecera_13='".strtr($_POST['cena_vecera_13'], $nahrad)."',
cena_vecera_bzl='".strtr($_POST['cena_vecera_bzl'], $nahrad)."'
WHERE cena_id=".$_POST['cenid'];
  }
  if($dbcon->query($sql)===true){header('Location: cennik.php');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else if(isset($_GET['heslo']) & isset($_GET['cenid'])){
  $sql="DELETE FROM ceny WHERE cena_id=".$_GET['cenid'];
  if($dbcon->query($sql)===true){header('Location: cennik.php?heslo=Záznam_bol_odstránený');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
?>
<h1>Cenník</h1>
<table>
  <tr class="hlavicka"><td></td><td>ID</td><td>Diéta č. 3</td><td>Diéta č. 9</td><td>Diéta č. 4</td><td>Diéta č. 13</td><td>Diéta BZL</td><td>Odstránenie<br />cenníka</td></tr>
<?php
$i=0;

$sql="SELECT cena_id, cena_obed_3, cena_obed_4, cena_obed_9, cena_obed_13, cena_obed_bzl, cena_vecera_3, cena_vecera_4, cena_vecera_9, cena_vecera_13, cena_vecera_bzl
FROM ceny";
$run=mysqli_query($dbcon, $sql);
while($row=mysqli_fetch_array($run)){
  $i++;
?>
  <tr class="farba">
    <th>Obedy:<br />Večere:</th>
    <td><?php echo $row['cena_id'];?></td>
    <td><?php echo $row['cena_obed_3'].'<br />'.$row['cena_vecera_3'];?></td>
    <td><?php echo $row['cena_obed_9'].'<br />'.$row['cena_vecera_9'];?></td>
    <td><?php echo $row['cena_obed_4'].'<br />'.$row['cena_vecera_4'];?></td>
    <td><?php echo $row['cena_obed_13'].'<br />'.$row['cena_vecera_13'];?></td>
    <td><?php echo $row['cena_obed_bzl'].'<br />'.$row['cena_vecera_bzl'];?></td>
    <td>
<?php
  if(isset($_GET['heslo'])){?>
      <a href="cennik.php?heslo=odstranit&cenid=<?php echo $row['cena_id'];?>"><button class="upozornenie">Odsrániť</button></a>
<?php
  }
?>
    </td>
  </tr>
<?php
}
?>
</table>

<fieldset class="uzky">
  <legend><b>Vložiť alebo nahradiť riadok</b></legend>
  <form action="cennik.php" method="POST">
    <table>
      <tr><td colspan="6"><br /></td></tr>
      <tr><th>ID *</th><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th><th>Diéta BZL</th></tr>

      <tr>
        <td rowspan="2"><input placeholder="ID" name="cenid" type="text" maxlength="3" size="1"></td>
        <td><input placeholder="Obed" name="cena_obed_3" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Obed" name="cena_obed_9" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Obed" name="cena_obed_4" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Obed" name="cena_obed_13" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Obed" name="cena_obed_bzl" type="text" maxlength="6" size="4"></td>
      </tr>

      <tr>
        <td><input placeholder="Večera" name="cena_vecera_3" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Večera" name="cena_vecera_9" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Večera" name="cena_vecera_4" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Večera" name="cena_vecera_13" type="text" maxlength="6" size="4"></td>
        <td><input placeholder="Večera" name="cena_vecera_bzl" type="text" maxlength="6" size="4"></td>
      </tr>
    </table>
    <p class="upozorneniestr">* Pri vložení nového riadku ID nevypĺňajte.</p>
    <input class="center" type="submit" value="Odoslať" name="odoslat">
  </form>
</fieldset>

<?php include('pata.php');?>