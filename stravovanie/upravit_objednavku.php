<?php
include('hlavicka.php');
$nadpis='Úprava objednanej stravy';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');
include('pole_diet.php');

$stravnik_id=$_GET['strid'];
$objednavka_id=$_GET['objid'];
$datum=$_GET['datum'];

if(!isset($_POST['upravit'])){
  $obed_1=$_GET['obed_1'];
  $obed_1_pocet=$_GET['obed_1_pocet'];
  $obed_2=$_GET['obed_2'];
  $obed_2_pocet=$_GET['obed_2_pocet'];
  $vecera_1=$_GET['vecera_1'];
  $vecera_1_pocet=$_GET['vecera_1_pocet'];

  $sql="SELECT jl_obed_3, jl_obed_4, jl_obed_9, jl_obed_13, jl_vecera_3, jl_vecera_4, jl_vecera_9, jl_vecera_13
FROM jedalne_listky
WHERE datum='$datum'";
  $run=mysqli_query($dbcon,$sql);
  if($dbcon->multi_query($sql)===TRUE){
    while($row=mysqli_fetch_array($run)){
?>
<fieldset class="uzky">
  <form action="upravit_objednavku.php?strid=<?php echo $stravnik_id;?>&objid=<?php echo $objednavka_id?>&datum=<?php echo $datum;?>" method="POST">
    <table>
      <tr><td></td><th>Dátum:</th><td><?php echo date('j.n.Y',strtotime($datum));?></td><td></td><td></td></tr>
      <tr><td></td><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th></tr>

      <tr>
        <th class="rotate_objed">Obed</th>
        <td width="120"><?php echo $row['jl_obed_3'];?></td>
        <td width="120"><?php echo $row['jl_obed_9'];?></td>
        <td width="120"><?php echo $row['jl_obed_4'];?></td>
        <td width="120"><?php echo $row['jl_obed_13'];?></td>
      </tr>

      <tr><td colspan="5"><br /></td></tr>

      <tr>
        <th class="rotate_objed">Večera</th>
        <td width="120"><?php echo $row['jl_vecera_3'];?></td>
        <td width="120"><?php echo $row['jl_vecera_9'];?></td>
        <td width="120"><?php echo $row['jl_vecera_4'];?></td>
        <td width="120"><?php echo $row['jl_vecera_13'];?></td>
      </tr>

      <tr>
        <th></th>
        <th>Obed 1:</th>
         <td><select name="obed_1">
<?php
$i=0;
$case=$obed_1;
include('dieta.php');
?>
        </select></td>

        <th>Počet porcií:</th>

        <td><select name="obed_1_pocet">
<?php
$case=$obed_1_pocet;
include('pocet.php');
?>
        </select></td>
      </tr>

      <tr>
        <th></th>
        <th>Obed 2:</th>
        <td><select name="obed_2">
<?php
$i=0;
$case=$obed_2;
include('dieta.php');
?>
        </select></td>

        <th>Počet porcií:</th>

        <td><select name="obed_2_pocet">
<?php
$case=$obed_2_pocet;
include('pocet.php');
?>
        </select></td>
      </tr>

      <tr>
        <th></th>
        <th>Večera:</th>
        <td><select name="vecera_1">
<?php
$i=0;
$case=$vecera_1;
include('dieta.php');
?>
        </select></td>

        <th>Počet porcií:</th>

        <td><select name="vecera_1_pocet">
<?php
$case=$vecera_1_pocet;
include('pocet.php');
?>
        </select></td>
      </tr>

      <tr><td colspan="5"><hr /></td></tr>
    </table>

    <input type="submit" value="Upraviť" name="upravit">
  </form>
</fieldset>
<?php
    }
  }
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

if(isset($_POST['upravit'])){
  $sql ="UPDATE objednavky SET
obed_1='".$_POST['obed_1']."',
obed_1_pocet='".$_POST['obed_1_pocet']."',
obed_2='".$_POST['obed_2']."',
obed_2_pocet='".$_POST['obed_2_pocet']."',
vecera_1='".$_POST['vecera_1']."',
vecera_1_pocet='".$_POST['vecera_1_pocet']."',
objednavka_uprava=NOW(),
objednavka_uprava_ssid='".$_SESSION['stravnik_id']."' WHERE
objednavka_id='$objednavka_id'";
  $run=mysqli_query($dbcon,$sql);

  if($dbcon->query($sql)===TRUE){header('Location: prehlad_objednavok.php?strid='.$stravnik_id.'&updated=Objednavka_bola_uspesne_zmenena');}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}

include('pata.php');
?>