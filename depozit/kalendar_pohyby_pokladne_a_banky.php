<?php
include('hlavicka.php');
$nadpis='Pohyby pokladne a banky';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

if(isset($_GET['mesiac'])){$mesiac=$_GET['mesiac']; $rok=$_GET['rok'];}
else{$mesiac=idate('m'); $rok=$_SESSION['obdobie'];}

str_pad($mesiac,2,0,STR_PAD_LEFT);
$datum_z=$rok.'-'.$mesiac.'-01';
$den_k=date('t',strtotime($datum_z));
$datum_k=$rok.'-'.$mesiac.'-'.$den_k;

?>
<table>
  <form role="form" method="get" action="kalendar_pohyby_pokladne_a_banky.php">
    <tr>
      <th>Rok:</th>
      <td>
        <select id="rok" name="rok">
<?php
$case=$rok;

include("obdobie.php");
?>
        </select>
      </td>
      <th>Mesiac:</th>
      <td>
        <select id="mesiac" name="mesiac">
<?php include('mesiac.php');?>
        </select>
      </td>

      <td>
        <img onclick="window.open(href='pdf_pohyby_pokladne_a_banky.php?mesiac='+(document.getElementById('mesiac').value)+'&rok='+(document.getElementById('rok').value)+'<?php for($x=1;$x<10;$x++){echo '&kod_'.$x.'='.$_SESSION['kody'.$x];} ?>');" src="tcpdf.png" width="88" height="29" border="1" alt="php2pdf">
      </td>

      <td>
        <button onclick="window.open(href='pohyby_pokladne_a_banky.php?mesiac='+(document.getElementById('mesiac').value)+'&rok='+(document.getElementById('rok').value));">Zobraziť</button>
      </td>
    </tr>
  </form>
</table>

<?php include('pata.php');?>