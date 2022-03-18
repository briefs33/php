<?php
session_start();
if(!$_SESSION['email']){header('Location: prihlasenie.php');}
?>
<!doctype html />
<html lang="sk">
  <head>
    <meta charset="utf8" />
    <meta name="author" content="Bc. Attila Csontos" />
    <link rel="icon" href="pnh.png" type="image/png" />
    <link rel="stylesheet" type="text/css" href="styl.css" media="all" />
    <!--p id="demo"><link rel="stylesheet" type="text/css" href="styl_zaklad.css" media="all" /></p-->
<?php
switch ($_SESSION['email']){
//  case 'attila.csontos@pnh.sk':
  case 'seres@pnh.sk': echo '<link rel="stylesheet" type="text/css" href="fialova.css" media="all" />'; break;
  default: echo '<link rel="stylesheet" type="text/css" href="zelena.css" media="all" />';
}
?>