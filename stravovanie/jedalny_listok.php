<?php
include('hlavicka.php');
$nadpis='Predpokladaný jedálny lístok';
include('nadpis.php');
?>

<h1 class="noprint"><?php echo $nadpis;?></h1>

<?php
$den0=date('Y-m-d', $startdate=$_GET['datum']);
$den1=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den2=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den3=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den4=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den5=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den6=date('Y-m-d', strtotime('+1 day', $startdate));

$dni=array(array('Pondelok', 'pondelok', $den0),array('Utorok', 'utorok', $den1),array('Streda', 'streda', $den2),array('Štvrtok', 'stvrtok', $den3),array('Piatok', 'piatok', $den4),array('Sobota', 'sobota', $den5),array('Nedeľa', 'nedela', $den6),);
?>

<div class="container-siroky">
  <table>
    <tr>
      <th class="noprint"><a href="jedalne_listky.php"><button>Späť</button></a></th>
      <th class="noprint"> pre: <?php echo date('W', $_GET['datum']).'. týždeň';?></th>
      <th class="skrite"><b>Predpokladaný jedálny lístok</b> od: <?php echo $den0;?>, do: <?php echo $den6;?></th>
      <th class="noprint">
        <a href="jedalne_listky.php?datum=<?php echo $_GET['datum'];?>&xlsx=create">
          <img src="php_excel.png" width="88" height="20" border="1" alt="php2xlsx">
        </a>
      </th>
      <th class="noprint">
        <a href="jedalny_listok_pdf.php?datum=<?php echo $_GET['datum'];?>" target="_blank">
          <img src="tcpdf.png" width="88" height="20" border="1" alt="php2pdf">
        </a>
      </th>
    </tr>
  </table>

  <form role="form" method="post" action="jedalny_listok.php?datum=<?php echo $_GET['datum'];?>">
    <table class="podfarbene">
      <tr class="noprint"><td colspan="5"><br /></td></tr>
      <tr class="skrite"><td></td><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th></tr>
<?php
if($rola<7){$read='readonly';}
else{$read='';}

$db='default';
include('databaza.php');

$sql="SELECT datum, jl_obed_3, jl_obed_9, jl_obed_4, jl_obed_13, jl_vecera_3, jl_vecera_9, jl_vecera_4, jl_vecera_13
FROM jedalne_listky
WHERE datum
BETWEEN '$den0' AND '$den6'
ORDER BY datum";
$run=mysqli_query($dbcon, $sql);

if(!isset($_POST['upravit'])){
  $row=array();

  for($x=0; $x<=6; $x++){
    for($z=0; $z<=8; $z++){$row[$x][$z]='';}
  }

  if($run->num_rows>0){
    $x=0;
    while($riadok=mysqli_fetch_array($run)){
      $row[$x][0]=$riadok['datum'];
      $row[$x][1]=$riadok['jl_obed_3'];
      $row[$x][2]=$riadok['jl_obed_9'];
      $row[$x][3]=$riadok['jl_obed_4'];
      $row[$x][4]=$riadok['jl_obed_13'];
      $row[$x][5]=$riadok['jl_vecera_3'];
      $row[$x][6]=$riadok['jl_vecera_9'];
      $row[$x][7]=$riadok['jl_vecera_4'];
      $row[$x][8]=$riadok['jl_vecera_13'];
      $x++;
    }
  }

  for($x=0; $x<=6; $x++){
?>
      <tr>
        <td></td>
        <th><?php echo $dni[$x][0];?></th>
        <td><textarea readonly name="<?php echo $dni[$x][1]; ?>" cols="10" rows="1"><?php echo $dni[$x][2];?></textarea></td>
        <td></td>
        <td></td>
      </tr>
      
      <tr class="noprint"><td></td><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th></tr>

      <tr>
        <th class="rotate">Obed:</th>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="obed_3_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][1]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="obed_9_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][2]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="obed_4_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][3]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="obed_13_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][4]);?></textarea></td>
      </tr>

      <tr>
        <th class="rotate">Večera:</th>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="vecera_3_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][5]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="vecera_9_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][6]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="vecera_4_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][7]);?></textarea></td>
        <td class="jl"><textarea class="jl" <?php echo $read;?> name="vecera_13_jl_<?php echo $x;?>" cols="24" rows="4"><?php echo ($row[$x][8]);?></textarea></td>
      </tr>

      <tr class="noprint"><td colspan="5"><hr/></td></tr>
<?php
  }
}
?>
      <tr class="noprint"><td colspan="5"><input type="submit" value="Upraviť" name="upravit"></td></tr>
      <tr class="noprint"><td colspan="5"><br /></td></tr>
    </table>
  </form>
</div>
<br />

<?php
if(isset($_POST['upravit'])){
$nahrad=array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',','=>', ','.'=>'. ');
$sql="";
  if($run->num_rows>0){
    for($x=0; $x<=6; $x++){
$sql.="UPDATE jedalne_listky SET
 jl_obed_3='".str_replace('  ',' ',strtr($_POST['obed_3_jl_'.$x], $nahrad))."',
 jl_obed_4='".str_replace('  ',' ',strtr($_POST['obed_4_jl_'.$x], $nahrad))."',
 jl_obed_9='".str_replace('  ',' ',strtr($_POST['obed_9_jl_'.$x], $nahrad))."',
 jl_obed_13='".str_replace('  ',' ',strtr($_POST['obed_13_jl_'.$x], $nahrad))."',
 jl_vecera_3='".str_replace('  ',' ',strtr($_POST['vecera_3_jl_'.$x], $nahrad))."',
 jl_vecera_4='".str_replace('  ',' ',strtr($_POST['vecera_4_jl_'.$x], $nahrad))."',
 jl_vecera_9='".str_replace('  ',' ',strtr($_POST['vecera_9_jl_'.$x], $nahrad))."',
 jl_vecera_13='".str_replace('  ',' ',strtr($_POST['vecera_13_jl_'.$x], $nahrad))."',
 jl_cas_upravy=NOW()
 WHERE datum='".$_POST[$dni[$x][1]]."';";
    }
  }
  else{
    for($x=0; $x<=6; $x++){
      $sql.="INSERT INTO jedalne_listky
(datum, jl_obed_3, jl_obed_4, jl_obed_9, jl_obed_13, jl_vecera_3, jl_vecera_4, jl_vecera_9, jl_vecera_13)
VALUES(
'".$_POST[$dni[$x][1]]."',
'".str_replace('  ',' ',strtr($_POST['obed_3_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['obed_4_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['obed_9_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['obed_13_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['vecera_3_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['vecera_4_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['vecera_9_jl_'.$x], $nahrad))."',
'".str_replace('  ',' ',strtr($_POST['vecera_13_jl_'.$x], $nahrad))."'
);";
    }
  }

  $run=mysqli_query($dbcon,$sql);
  if($dbcon->multi_query($sql)===true){header('Location: jedalne_listky.php?datum='.$_GET['datum']);}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

include('pata.php');
?>