<?php
include('hlavicka.php');
$nadpis='Stav pokladne a banky &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp '.$_SESSION['obdobie'];
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$nazov_mesiaca=array('01'=>'Január','02'=>'Február','03'=>'Marec','04'=>'Apríl','05'=>'Máj','06'=>'Jún','07'=>'Júl','08'=>'August','09'=>'September','10'=>'Október','11'=>'November','12'=>'December');
?>

<table>
  <tr class="hlavicka">
    <td>Mesiac</td>
    <td></td>
    <td>Počiatočný<br />stav (€)</td>
    <td>Konečný<br />stav (€)</td>
    <td>Pohyb<br />(€)</td>
  </tr>
<?php
$db='default';
include('databaza.php');

$sql="SELECT pokladna_datum_uctovania_skr, pokladna_pociatocny_stav, pokladna_konecny_stav, banka_pociatocny_stav, banka_konecny_stav
FROM pokladna WHERE pokladna_datum_uctovania_skr LIKE '".$_SESSION['obdobie']."-%'";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
//$pokladna=$banka=$pokladna_konecny_stav=$banka_konecny_stav=$rozdiel_pokladna=$rozdiel_banka=$spolu_prijem_banka=$spolu_prijem_pokladna=$spolu_vydaj_banka=$spolu_vydaj_pokladna=0.0;

while($row=mysqli_fetch_array($run)){
  $pokladna_pohyb=round(($row['pokladna_konecny_stav']-$row['pokladna_pociatocny_stav'])*100)/100;
  $banka_pohyb=round(($row['banka_konecny_stav']-$row['banka_pociatocny_stav'])*100)/100;
?>
  <tr class="farba">
    <th class="left"><?php echo $nazov_mesiaca[substr($row['pokladna_datum_uctovania_skr'],5,2)] /*.substr($row['pokladna_datum_uctovania_skr'],0,4) / *date('M Y', strtotime($row['pokladna_datum_uctovania_skr']))*/;?></th>
    <td class="right">Pokladňa:<br />Banka:</td>
    <td class="right"><?php echo number_format($row['pokladna_pociatocny_stav'],2,',','').'<br />'.number_format($row['banka_pociatocny_stav'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['pokladna_konecny_stav'],2,',','').'<br />'.number_format($row['banka_konecny_stav'],2,',','');?></td>
    <th class="right"><?php echo number_format($pokladna_pohyb,2,',','').'<br />'.number_format($banka_pohyb,2,',','');?></th>
  </tr>
<?php
}
?>
</table>

<?php
include('pata.php');
?>