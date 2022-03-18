<?php
include('hlavicka.php');
$nadpis='Objednanie stravy';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
if(isset($_GET['meno'])){
  echo '<h3>'.$_GET['meno'].'</h3>';
}

$db='default';
include('databaza.php');
include('pole_diet.php');

if(isset($_GET['strid'])){$stravnik_id=$_GET['strid'];}
else{$stravnik_id=$_SESSION['stravnik_id'];}

if(isset($_POST['objednaj'])){
  $pon_post=date('Y-m-d', strtotime($_POST['pondelok']));
  $uto_post=date('Y-m-d', strtotime($_POST['utorok']));
  $str_post=date('Y-m-d', strtotime($_POST['streda']));
  $stv_post=date('Y-m-d', strtotime($_POST['stvrtok']));
  $pia_post=date('Y-m-d', strtotime($_POST['piatok']));
  $sob_post=date('Y-m-d', strtotime($_POST['sobota']));
  $ned_post=date('Y-m-d', strtotime($_POST['nedela']));

  $skontroluj_den="SELECT datum
FROM objednavky
WHERE stravnik_id='$stravnik_id' AND (datum BETWEEN '".$pon_post."' AND '".$ned_post."')";

//echo "<br><br>".$skontroluj_den;

  $run_query=mysqli_query($dbcon,$skontroluj_den);
  if(mysqli_num_rows($run_query)>0){
    header('Location: index.php?chyba=neuspesna_objednavka');
    exit();
  }
  else{
    $sql="INSERT INTO objednavky
(stravnik_id, datum, obed_1, obed_1_pocet, obed_2, obed_2_pocet, vecera_1, vecera_1_pocet, objednavka_registracia_ssid)
VALUES
('$stravnik_id', '".$pon_post."', '".$_POST['opon1']."', '".$_POST['opon1porcia']."', '".$_POST['opon2']."', '".$_POST['opon2porcia']."', '".$_POST['vpon1']."', '".$_POST['vpon1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$uto_post."', '".$_POST['outo1']."', '".$_POST['outo1porcia']."', '".$_POST['outo2']."', '".$_POST['outo2porcia']."', '".$_POST['vuto1']."', '".$_POST['vuto1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$str_post."', '".$_POST['ostr1']."', '".$_POST['ostr1porcia']."', '".$_POST['ostr2']."', '".$_POST['ostr2porcia']."', '".$_POST['vstr1']."', '".$_POST['vstr1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$stv_post."', '".$_POST['ostv1']."', '".$_POST['ostv1porcia']."', '".$_POST['ostv2']."', '".$_POST['ostv2porcia']."', '".$_POST['vstv1']."', '".$_POST['vstv1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$pia_post."', '".$_POST['opia1']."', '".$_POST['opia1porcia']."', '".$_POST['opia2']."', '".$_POST['opia2porcia']."', '".$_POST['vpia1']."', '".$_POST['vpia1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$sob_post."', '".$_POST['osob1']."', '".$_POST['osob1porcia']."', '".$_POST['osob2']."', '".$_POST['osob2porcia']."', '".$_POST['vsob1']."', '".$_POST['vsob1porcia']."', '".$_SESSION['stravnik_id']."'),
('$stravnik_id', '".$ned_post."', '".$_POST['oned1']."', '".$_POST['oned1porcia']."', '".$_POST['oned2']."', '".$_POST['oned2porcia']."', '".$_POST['vned1']."', '".$_POST['vned1porcia']."', '".$_SESSION['stravnik_id']."')
";

    if($dbcon->query($sql)===true){header('Location: prehlad_objednavok.php?strid='.$stravnik_id.'&insert=Objednavka_bola_pridana');}
    else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
  }
}
else{
?>
    <table class="noprint">
      <tr>
        <th>Zmeniť týždeň</th>
        <td>
          <a href="objednanie_stravy.php?datum=<?php $datum=strtotime('-1 week', $_GET['datum']); if(isset($_GET['meno'])){echo date($datum).'&strid='.$stravnik_id.'&meno='.$_GET['meno'];} else{echo date($datum).'&strid='.$stravnik_id;}?>">
            <button>Predchádzajúci</button>
          </a>
        </td>

        <td>
          <a href="objednanie_stravy.php?datum=<?php $datum=strtotime('+1 week', $_GET['datum']); if(isset($_GET['meno'])){echo date($datum).'&strid='.$stravnik_id.'&meno='.$_GET['meno'];} else{echo date($datum).'&strid='.$stravnik_id;}?>">
            <button>Nasledovný</button>
          </a>
        </td>
      </tr>
    </table>

<?php
  include('sviatky.php');

  $startdate=$_GET['datum'];
  //include('obmedzenia_objednanie_stravy.php');
  include('obmedzenia.php');

  $den0=date('Y-m-d', $startdate);
  $den1=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
  $den2=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
  $den3=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
  $den4=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
  $den5=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
  $den6=date('Y-m-d', strtotime('+1 day', $startdate));

//echo $den0;

  $dni=array(
    array('Pondelok', 'pondelok', $den0, $obmedz_pon, 'opon1', 'opon2', 'vpon1', 'pondelok'),
    array('Utorok', 'utorok', $den1, $obmedz_uto, 'outo1', 'outo2', 'vuto1', 'utorok'),
    array('Streda', 'streda', $den2, $obmedz_str, 'ostr1', 'ostr2', 'vstr1', 'stredu'),
    array('Štvrtok', 'stvrtok', $den3, $obmedz_stv, 'ostv1', 'ostv2', 'vstv1', 'štvrtok'),
    array('Piatok', 'piatok', $den4, $obmedz_pia, 'opia1', 'opia2', 'vpia1', 'piatok'),
    array('Sobota', 'sobota', $den5, $obmedz_sob, 'osob1', 'osob2', 'vsob1', 'sobotu'),
    array('Nedeľa', 'nedela', $den6, $obmedz_ned, 'oned1', 'oned2', 'vned1', 'nedeľu'),
  );

  $sql="SELECT datum, jl_obed_3, jl_obed_9, jl_obed_4, jl_obed_13, jl_vecera_3, jl_vecera_9, jl_vecera_4, jl_vecera_13
FROM jedalne_listky
WHERE datum
BETWEEN '$den0' AND '$den6'
ORDER BY datum";

  $run=mysqli_query($dbcon, $sql);
  $row=array();

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
  else{
    $sql='';
    for($x=0;$x<=6;$x++){
      for($z=1;$z<=8;$z++){$row[$x][$z]='';}

      $sql.="INSERT INTO jedalne_listky (datum, jl_obed_3, jl_obed_4, jl_obed_9, jl_obed_13, jl_vecera_3, jl_vecera_4, jl_vecera_9, jl_vecera_13)
      VALUES ('".$dni[$x][2]."', '', '', '', '', '', '', '', '');";
    }
    $run=mysqli_query($dbcon,$sql);
    if($dbcon->multi_query($sql)===true){}
    else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
  }
?>
  <fieldset class="uzky">
    <form action="objednanie_stravy.php?strid=<?php echo $stravnik_id;?>" method="POST">
      <table>
<?php
  for($x=0;$x<=6;$x++){
?>
        <tr <?php if(in_array($dni[$x][2], $sviatok)){echo 'class="sviatok"';} else if($x>=5){echo 'class="vykend"';}?>>
          <td></td>
          <td><textarea readonly name="<?php echo $dni[$x][1];?>" cols="10" rows="1"><?php echo date('j.n.Y', strtotime($dni[$x][2]));?></textarea></td>
          <th><?php echo $dni[$x][0];?></th>
          <td></td>
          <td></td>
        </tr>

<?php
    if(idate("U")<=($dni[$x][3])){
      if($_SESSION['rola']==4 or $_SESSION['rola']==7){}
      else{
?>
        <tr><td></td><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th></tr>
        <tr><th class="rotate_objed">Obed</th><?php for($y=1;$y<=4;$y++){echo '<td width="120">'.$row[$x][$y].'</td>';}?></tr>
        <tr><td colspan="5"><br /></td></tr>
        <tr><th class="rotate_objed">Večera</th><?php for($y=5;$y<=8;$y++){echo '<td width="120">'.$row[$x][$y].'</td>';}?></tr>
<?php
      }
    }
    else{echo '<tr><th colspan="5"><p class="upozorneniestr">Objednávka na '.$dni[$x][7].' už nie je možná!</p></th></tr>';}

    $na=array(4=>'Obed 1:',5=>'Obed 2:',6=>'Večera:',7=>'Počet porcií:');
    for($y=4;$y<=6;$y++){
?>
        <tr>
          <th></th>
          <th><?php echo $na[$y];?></th>
          <td>
            <select name="<?php echo $dni[$x][$y];?>">
<?php
      if(idate("U")<=($dni[$x][3]) or ($rola>=7)){
        $i=$case=0;
        include("dieta.php");
      }
?>
            </select>
          </td>
          <th><?php echo $na[7];?></th>
          <td>
            <select name="<?php echo $dni[$x][$y].'porcia';?>">
<?php
      $case=1;
      include("pocet.php");
?>
            </select>
          </td>
        </tr>
<?php
    }
    echo '<tr><td colspan="5"><hr /></td></tr>';
  }
?>
      </table>
      <input type="submit" value="Odoslať" name="objednaj">
    </form>
  </fieldset>
<?php
}
include("pata.php");
?>