<?php
include('hlavicka.php');
$nadpis='Objednanie stravy - Kalendár';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$tyzden=date('W/Y', date(time())); //aktualny tyzden

$db='default';
include('databaza.php');
$stravnik_id=$_SESSION['stravnik_id'];
$sql="SELECT datum FROM objednavky WHERE stravnik_id='$stravnik_id'";
$run=mysqli_query($dbcon, $sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
$pole=array();

if($run->num_rows>0){
  while($row=$run->fetch_assoc()){
    $date=date_create($row['datum']);
    $date_week=date_format($date,'W/Y');
    if(in_array($date_week,$pole)){}else{$pole[]=$date_week;}
  }
}
?>
<table>
<?php
$startdate=strtotime('monday');
$startdate=strtotime('-1 week',$startdate);
?>
  <tr><th colspan="4">Týždne:</th></tr>
<?php
for($x=0;$x<=2;$x++){
  echo '<tr>';
  for($y=0;$y<=3;$y++){
    $tyzdeni=date('W/Y',$startdate);
	if(date('Y',$startdate)>=2018){
      if(($tyzden==$tyzdeni) and in_array($tyzdeni,$pole)){$trieda='class="objednanednes"';}
      else if(in_array($tyzdeni,$pole)){$trieda='class="objednane"';}
      else if($tyzden==$tyzdeni){$trieda='class="dnes"';}
      else{$trieda='';}
?>
    <td>
      <a href="objednanie_stravy.php?datum=<?php echo $startdate;?>">
        <button <?php echo $trieda;?>><?php echo date('W/Y',$startdate);?></button>
      </a>
    </td>
<?php
/**/}
/**/else{echo '<td></td>';}
    $startdate=strtotime('+1 week',$startdate);
  }
  echo '</tr>';
}
?>
</table>

<?php include('pata.php');?>