<?php session_start();?>
<!doctype html />
<html lang="sk">
  <head>
    <meta charset="utf8" />
    <meta name="author" content="Bc. Attila Csontos" />
    <link rel="icon" href="pnh.png" type="image/png" />
    <link rel="stylesheet" type="text/css" href="styl.css" media="all" /><!-- media: all, screen, print -->
    <title>Prihlásenie</title>
  </head>

  <body>
<h1>Elektronický zber objednávok stravy</h1><br /><h2>Prihlásenie</h2>

<fieldset class="mikro">
  <form role="form" method="post" action="prihlasenie.php">
    <table>
      <tr><td nowrap="nowrap">E-mail:</td><td><input placeholder="email" name="email" type="email" autofocus="autofocus"></td></tr>
      <tr><td>Heslo:</td><td><input placeholder="heslo" name="heslo" type="password"></td></tr>
    </table>
    <input type="submit" value="Prihlásiť sa" name="login">
  </form>
</fieldset>
<div><img src="pnh.png" alt="PNH" border="0" width="45" height="45">Psychiatrická nemocnica Hronovce</div>
  </body>
</html>

<?php
if(isset($_POST['login'])){
  $db='default';
  include('databaza.php');

  $check_user="SELECT stravnik_id, email, rola FROM prihlasovacie_udaje WHERE email='".$_POST['email']."' AND heslo='".$_POST['heslo']."'";
  $run=mysqli_query($dbcon,$check_user);

  if(mysqli_num_rows($run)){
    while($row=mysqli_fetch_array($run)){
      $_SESSION['stravnik_id']=$row['stravnik_id'];
      $_SESSION['email']=$row['email'];
      $_SESSION['rola']=$row['rola'];
    }
    $sql_update="UPDATE prihlasovacie_udaje SET posledne_prihlasenie=NOW() WHERE email='".$_POST['email']."' AND heslo='".$_POST['heslo']."'";
    $run_update=mysqli_query($dbcon,$sql_update);

    $check_oddelenie="
SELECT oddelenia.oddelenie_id, oddelenia.oddelenie_skratka, oddelenia.oddelenie
FROM oddelenia
INNER JOIN stravnici
ON stravnici.oddelenie_id=oddelenia.oddelenie_id
WHERE stravnici.stravnik_id='".$_SESSION['stravnik_id']."'";
    $run_oddelenie=mysqli_query($dbcon,$check_oddelenie);

    if(mysqli_num_rows($run_oddelenie)){
      while($row=mysqli_fetch_array($run_oddelenie)){
        $_SESSION['oddelenie_id']=$row['oddelenie_id'];
        /*$session['pracovisko']=*/$_SESSION['oddelenie_skratka']=$row['oddelenie_skratka'];
        $_SESSION['oddelenie_nazov']=$row['oddelenie'];
      }
    }
    header('Location: index.php');
  }
  else{
    echo "<script>alert('E-mail alebo heslo sú neplatné!')</script>";
    echo "<script>window.open('prihlasenie.php','_self')</script>";
  }
}
?>