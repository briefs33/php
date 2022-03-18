<?php
include('hlavicka.php');
$nadpis='Depozit';
include('nadpis.php');

$db='default';
include('databaza.php');

$sql="SELECT kod_id, popis FROM kody";
$run=mysqli_query($dbcon, $sql);
while($row=mysqli_fetch_array($run)){
  $_SESSION['kody'.$row['kod_id']]=$row['popis'];
}

if(isset($_GET['chyba'])){echo '<br /><br /><br /><br /><h1><p class="upozorneniestr">Duplicitné hodnoty!</p></h1>';}
else{
  echo '<h1>'.$nadpis.'</h1>';

  if(isset($_POST['obdobie'])){$case=$_SESSION['obdobie']=$_POST['obdobie'];}
  else if(isset($_SESSION['obdobie'])){$case=$_SESSION['obdobie'];}
  else{$case=$_SESSION['obdobie']=idate('Y');}

  $sql_obdobie="SELECT obdobie_stav FROM obdobia WHERE rok=".$_SESSION['obdobie'];
  $run=mysqli_query($dbcon, $sql_obdobie);
  while($row=mysqli_fetch_array($run)){
    $_SESSION['obdobie_stav']=$row['obdobie_stav'];
  }
?>

<fieldset class="mini">
  <form role="form" method="post" action="index.php" name="obdobie" onsubmit="return validateForm()">
    <table>
      <tr><td colspan="3"><br /></td></tr>

      <tr>
        <th>Obdobie:</th>

        <td>
          <select name="obdobie">
<?php include("obdobie.php");?>
          </select>
        </td>

        <td><input type="submit" value="Zmenit" name="register"/></td>
      </tr>
    </table>
  </form>
</fieldset>
<?php
}
include('pata.php');
?>