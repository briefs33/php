<?php
include('hlavicka.php');
$nadpis='Kódy';
include('nadpis.php');

$db='default';
include('databaza.php');

if(isset($_POST['vlozit'])){
  $sql="INSERT INTO kody (kod_id, popis) VALUES ('".$_POST['kod_id']."', '".$_POST['popis']."')";
  if($dbcon->query($sql)===true){header('Location: kody.php');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else if(isset($_POST['upravit'])){  
  $sql="UPDATE kody SET popis='".$_POST['popis']."' WHERE kod_id=".$_POST['kod_id'];
  if($dbcon->query($sql)===true){header('Location: kody.php');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else if(isset($_GET['heslo']) & isset($_GET['kod_id'])){
  $sql="DELETE FROM kody WHERE kod_id=".$_GET['kod_id'];
  if($dbcon->query($sql)===true){header('Location: kody.php?heslo=Záznam_bol_odstránený');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

echo '<h1>'.$nadpis.'</h1>';
?>
<table>
  <tr class="hlavicka">
    <td>Kód</td>
    <td>Popis</td>
    <?php if(isset($_GET['heslo'])){echo '<td>Odstránenie<!--br /--> kódu</td>';}?>
  </tr>

<?php
$sql="SELECT kod_id, popis FROM kody";
$run=mysqli_query($dbcon, $sql);
while($row=mysqli_fetch_array($run)){
?>
  <tr class="farba">
    <td><?php echo $row['kod_id'];?></td>
    <td class="left"><?php echo $row['popis'];?></td>
    
<?php
  if(isset($_GET['heslo'])){?>
    <td>
      <a href="kody.php?heslo=odstranit&kod_id=<?php echo $row['kod_id'];?>">
        <button class="upozornenie">Odsrániť</button>
      </a>
    </td>
<?php
  }
?>
    
  </tr>
<?php
}
?>
</table>

<br />

<fieldset class="mini">
  <legend><b>Vložiť alebo nahradiť riadok</b></legend>
  <form action="kody.php" method="POST">
    <table>
      <tr><td colspan="2"><br /></td></tr>
      <tr><th>Kód</th><th>Popis</th></tr>

      <tr>
        <td rowspan="2"><input placeholder="Kód" name="kod_id" type="text" maxlength="1" size="1" autofocus="autofocus"></td>
        <td><input placeholder="Popis" name="popis" type="text" maxlength="25" size="25"></td>
      </tr>
    </table>
    <input class="center" type="submit" value="Vložiť nový" name="vlozit">
    <input class="center" type="submit" value="Upraviť" name="upravit">
  </form>
</fieldset>

<?php include('pata.php');?>