<?php
include('hlavicka.php');
$nadpis='Objednanie stravy na mesiac';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';
if(isset($_GET['meno'])){
  echo '<h3>'.$_GET['meno'].'</h3>';
}

$db='default';
include('databaza.php');
include('pole_diet.php');

$mesiac=$_GET['mesiac'];

$rok=date('Y',$_GET['datum']);
$datum_z=$rok.'-'.$mesiac.'-01';
$den_k=date('t',strtotime($datum_z));
$datum_k=$rok.'-'.$mesiac.'-'.$den_k;

if(isset($_GET['strid'])){$stravnik_id=$_GET['strid'];}
else{$stravnik_id=$_SESSION['stravnik_id'];}

if(isset($_POST['objednaj'])){
  $den_k=$_GET['den_k'];

  $dni_post=array();

  for($x=1;$x<=$den_k;$x++){
    $x_den=str_pad($x,2,0,STR_PAD_LEFT);
    $dni_post[$x_den]=date('Y-m-d', strtotime($_POST['datum_'.$x_den]));
//    echo $x_den.' = '.$dni_post[$x_den].'<br />';
  }

/***
  $diety=array(
0=>array('value'=>0,'dieta'=>''),
1=>array('value'=>3,'dieta'=>'Diéta č. 3'),
2=>array('value'=>9,'dieta'=>'Diéta č. 9'),
3=>array('value'=>4,'dieta'=>'Diéta č. 4'),
4=>array('value'=>13,'dieta'=>'Diéta č. 13'),
5=>array('value'=>'BZL','dieta'=>'Bezlepková'),
6=>array('value'=>'*','dieta'=>'*'),
7=>array('value'=>'9-S','dieta'=>'Diéta č. 9-S'),
);
***/

  $skontroluj_den="SELECT datum
FROM objednavky
WHERE stravnik_id='$stravnik_id' AND (datum BETWEEN '".$datum_z."' AND '".$datum_k."')";

//echo "<br><br>".$skontroluj_den;

  $run_query=mysqli_query($dbcon,$skontroluj_den);
  if(mysqli_num_rows($run_query)>0){
    header('Location: index.php?chyba=neuspesna_objednavka');
    exit();
  }
  else{
    $sql="INSERT INTO objednavky
(stravnik_id, datum, obed_1, obed_1_pocet, obed_2, obed_2_pocet, vecera_1, vecera_1_pocet, objednavka_registracia_ssid)
VALUES";
    for($x=1;$x<=$den_k;$x++){
      $x_den=str_pad($x,2,0,STR_PAD_LEFT);
      $dni_post[$x_den]=date('Y-m-d', strtotime($_POST['datum_'.$x_den]));
      if($x==$den_k){
        $sql.="
('$stravnik_id', '".$dni_post[$x_den]."', '".$_POST['obed1_'.$x_den]."', '".$_POST['obed1porcia_'.$x_den]."', '".$_POST['obed2_'.$x_den]."', '".$_POST['obed2porcia_'.$x_den]."', '".$_POST['vecera1_'.$x_den]."', '".$_POST['vecera1porcia_'.$x_den]."', '".$_SESSION['stravnik_id']."')
";
      }
      else{
        $sql.="
('$stravnik_id', '".$dni_post[$x_den]."', '".$_POST['obed1_'.$x_den]."', '".$_POST['obed1porcia_'.$x_den]."', '".$_POST['obed2_'.$x_den]."', '".$_POST['obed2porcia_'.$x_den]."', '".$_POST['vecera1_'.$x_den]."', '".$_POST['vecera1porcia_'.$x_den]."', '".$_SESSION['stravnik_id']."'),
";
      }
    }

//    echo "<br><br>".$sql;

    if($dbcon->query($sql)===true){header('Location: prehlad_objednavok.php?strid='.$stravnik_id.'&insert=Objednavka_bola_pridana');}
    else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
//    header('Location: prehlad_objednavok.php?strid='.$stravnik_id.'&insert=Objednavka_bola_pridana');
  }
}
else{
?>
    <table class="noprint">
      <tr>
        <th>Zmeniť mesiac</th>
        <td>
          <a href="objednanie_stravy_na_mesiac.php?mesiac=<?php $datum=strtotime('-1 month', $_GET['datum']); if(isset($_GET['meno'])){echo date('m',$datum).'&datumAZ='.date('d-m-Y',$datum).'&datum='.date($datum).'&strid='.$stravnik_id.'&meno='.$_GET['meno'];} else{echo date('m',$datum).'&datum='.date($datum).'&strid='.$stravnik_id;}?>">
            <button>Predchádzajúci</button>
          </a>
        </td>

        <td>
          <a href="objednanie_stravy_na_mesiac.php?mesiac=<?php $datum=strtotime('+1 month', $_GET['datum']); if(isset($_GET['meno'])){echo date('m',$datum).'&datumAZ='.date('d-m-Y',$datum).'&datum='.date($datum).'&strid='.$stravnik_id.'&meno='.$_GET['meno'];} else{echo date('m',$datum).'&datum='.date($datum).'&strid='.$stravnik_id;}?>">
            <button>Nasledovný</button>
          </a>
        </td>
      </tr>
    </table>

<?php
  include('sviatky.php');

  $startdate=$_GET['datum'];
  include('obmedzenia.php');

  $dni=array('-1-', 'datum', '', $obmedz_ned, 'obed1', 'obed2', 'vecera1', 'nedeľu');
  $dni_v_tyzdni=array('Nedeľa','Pondelok', 'Utorok', 'Streda', 'Štvrtok', 'Piatok', 'Sobota');

  $sql="SELECT datum, jl_obed_3, jl_obed_9, jl_obed_4, jl_obed_13, jl_vecera_3, jl_vecera_9, jl_vecera_4, jl_vecera_13
FROM jedalne_listky
WHERE datum
BETWEEN '$datum_z' AND '$datum_k'
ORDER BY datum";

  $run=mysqli_query($dbcon, $sql);
  $row=array();

  if($run->num_rows>0){
?>
  <fieldset class="uzky">
    <form action="objednanie_stravy_na_mesiac.php?strid=<?php echo $stravnik_id.'&mesiac='.$_GET['mesiac'].'&datum='.$_GET['datum'].'&den_k='.$den_k;?>" method="POST">
      <table>
        <tr>
          <th>Dátum</th>
          <th>Deň</th>
          <th>Obed 1</th>
          <th>Počet<br />porcií</th>
          <th>Obed 2</th>
          <th>Počet<br />porcií</th>
          <th>Večera</th>
          <th>Počet<br />porcií</th>
        </tr>

<?php
    for($x=1;$x<=$den_k;$x++){
      $rok=date('Y',$_GET['datum']); //musí tu byť
      $x_den=str_pad($x,2,0,STR_PAD_LEFT);
      $x_den_w=date('w',strtotime($rok.'-'.$mesiac.'-'.$x_den));
?>
        <tr <?php if(in_array($rok.'-'.$mesiac.'-'.$x_den, $sviatok)){echo 'class="sviatok"';} else if($x_den_w==0 or $x_den_w==6){echo 'class="vykend"';}?>>
          <td><textarea readonly name="<?php echo $dni[1].'_'.$x_den;?>" cols="10" rows="1"><?php echo $x_den.'-'.$mesiac.'-'.$rok;?></textarea></td>
          
          <td><?php echo $dni_v_tyzdni[$x_den_w];?></td>
<?php
      $na=array(4=>'Obed 1:',5=>'Obed 2:',6=>'Večera:',7=>'Počet porcií:');
      for($y=4;$y<=6;$y++){
?>
          <td>
            <select name="<?php echo $dni[$y].'_'.$x_den;?>">
<?php
        $i=$case=0;
        include("dieta.php");
?>
            </select>
          </td>

          <td>
            <select name="<?php echo $dni[$y].'porcia_'.$x_den;?>">
<?php
        $case=1;
        include("pocet.php");
?>
            </select>
          </td>
<?php
      }
//      echo '<tr><td colspan="8"><br /></td></tr>';
    }
?>
      </table>
      <input type="submit" value="Odoslať" name="objednaj">
    </form>
  </fieldset>
<?php
  }
  else{
    echo 'Žiadny jedálny lístok';
  }
}
include("pata.php");
?>