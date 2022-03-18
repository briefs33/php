<?php
include('hlavicka.php');
$nadpis='Elektronický zber objednávok stravy';
include('nadpis.php');

if(isset($_GET['chyba'])){echo '<br /><br /><br /><br /><h1><p class="upozorneniestr">Vaša objednávka nebola úspešná,<br />prosím skontrolujte prehľad objednávok!</p></h1>';}
else{
  echo '<h1>Elektronický zber objednávok stravy</h1>';
}

include('pata.php');
?>