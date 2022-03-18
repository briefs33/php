<?php
session_start();
if(!$_SESSION['email']){header('Location: prihlasenie.php');}
else{
	switch ($_SESSION['email']){
	  case 'attila.csontos@pnh.sk':
	  case 'monika.maczkoova@pnh.sk':
	  case 'gyetvenova@pnh.sk':
	  case 'nikoleta.oraveczova@pnh.sk': break;
	  default: header('Location: ../stravovanie/prihlasenie.php');
	}
}
?>
<!doctype html />
<html lang="sk">
  <head>
    <meta charset="utf8" />
    <meta name="author" content="Bc. Attila Csontos" />
    <link rel="icon" href="pnh.png" type="image/png" />
    <link rel="stylesheet" type="text/css" href="styl.css" media="all" />
    <link rel="stylesheet" type="text/css" href="belasa.css" media="all" />