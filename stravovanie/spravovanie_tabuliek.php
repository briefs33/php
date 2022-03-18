<?php
include('hlavicka.php');
$nadpis='Spravovanie tabuliek';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
?>

<div class="container-siroky">
  <form role="form" method="post" action="spravovanie_tabuliek.php">
    <input required type="password" placeholder="heslo" name="heslo" type="text">
    <input type="submit" value="Vytvoriť databázu a tabuľky" name="wisdom">
  </form>

  <br />

  <form role="form" method="post" action="spravovanie_tabuliek.php">
    <textarea name="prikaz_sql" cols="72" placeholder="prikaz_sql" rows="18"></textarea>
    <br /><br />
    <input type="submit" value="Odoslat prikaz" name="odoslat_sql">
  </form>
</div>
<?php
/*** Vytvoriť databázu a tabuľky ***/
if(isset($_POST['wisdom'])){
  $db='root';
  $heslo=$_POST['heslo'];
  include('databaza.php');

  $sql='';
  include('stvorenie.php');
  $run=mysqli_query($dbcon,$sql);

  if($dbcon->multi_query($sql)===TRUE){echo 'Databáza s tabuľkami bola vytvorená!';}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

/*** Odoslat prikaz ***/
if(isset($_POST['prikaz_sql'])){
  $db='root';
  $heslo=$_POST['heslo'];
  include('databaza.php');
  $sql=$_POST['prikaz_sql'];
  $run=mysqli_query($dbcon,$sql);

  if($dbcon->multi_query($sql)===TRUE){
    echo 'Odoslat prikaz bola vytvorená!';
    // pridat spracovanie prikazu/prikazov
  }
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

include('pata.php');
?>