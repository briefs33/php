<?php
include('hlavicka.php');
$nadpis='Zoznam na obed a večeru';
include('nadpis.php');

echo '<h1 class="noprint">'.$nadpis.'</h1>';

$db='default';
include('databaza.php');

$datum=$_GET['datum'];
$den_v_tyzdni=array('1'=>'Pondelok','2'=>'Utorok','3'=>'Streda','4'=>'Štvrtok','5'=>'Piatok','6'=>'Sobota','7'=>'Nedeľa');
$cislo=date_create($datum);
$datumU=date_format($cislo,"U");
$cislo=date_format($cislo,"N");
$triobed=$styobed=$devobed=$trnobed=$bzlobed=$hviobed=$desobed=0;
$trivecera=$styvecera=$devvecera=$trnvecera=$bzlvecera=$hvivecera=$desvecera=0;

$oddelenie=$vecere=array();

$obedy_amo=$obedy_odlm=$obedy_azo=$obedy_odlz=$obedy_fro=array();
$obedy_amb=$obedy_gpo=$obedy_gpo2=$obedy_pracovna=$obedy_uz/*Ustajnenie zv.*/=array();
$obedy_hts=$obedy_lps/*Strav. prev.*/=$obedy_okb=$obedy_opldz=$obedy_kurici=array();
$obedy_udrzba=$obedy_vratnica=$obedy_ine=array();

$sql="SELECT oddelenie_id, oddelenie_skratka FROM oddelenia ORDER BY oddelenie_id";
$run=mysqli_query($dbcon, $sql);
if($run->num_rows>0){
  while($row=mysqli_fetch_array($run)){
    $oddelenie+=array($row['oddelenie_id']=>$row['oddelenie_skratka']);
  }
}

$sql="SELECT
stravnici.oddelenie_id,
stravnici.titul_pm,
SUBSTRING(stravnici.meno, 1, 1),
stravnici.priezvisko,
objednavky.obed_1,
objednavky.obed_1_pocet,
objednavky.obed_2,
objednavky.obed_2_pocet,
objednavky.vecera_1,
objednavky.vecera_1_pocet
FROM stravnici
INNER JOIN objednavky ON stravnici.stravnik_id=objednavky.stravnik_id
WHERE objednavky.datum='$datum'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
  OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
ORDER BY stravnici.oddelenie_id, stravnici.priezvisko ASC";
//AND stravnici.oddelenie_id='$oddelenie_id'
$run=mysqli_query($dbcon, $sql);
if($run->num_rows>0){
  while($row=mysqli_fetch_array($run)){
    $meno=$row['priezvisko'].' '.$row[2].'., '.$row['titul_pm'];
    $oddelenie_id=$row['oddelenie_id'];
    $plus='';

    if(!empty($row['obed_1'] or $row['obed_2'])){
      $obed='';
      if(!empty($row['obed_1'])){
        $obed=$row['obed_1'].' '.$row['obed_1_pocet'].'x';
        switch($row['obed_1']){
          case '9-S': $desobed+=$row['obed_1_pocet']; break;
          case 3: $triobed+=$row['obed_1_pocet']; break;
          case 9: $devobed+=$row['obed_1_pocet']; break;
          case 4: $styobed+=$row['obed_1_pocet']; break;
          case 13: $trnobed+=$row['obed_1_pocet']; break;
          case 'BZL': $bzlobed+=$row['obed_1_pocet']; break;
          case '*': $hviobed+=$row['obed_1_pocet'];
        }
        $plus='<br />'; //', ';
      }

      if(!empty($row['obed_2'])){
        $obed.=$plus.$row['obed_2'].' '.$row['obed_2_pocet'].'x';
        switch($row['obed_2']){
          case '9-S': $desobed+=$row['obed_2_pocet']; break;
          case 3: $triobed+=$row['obed_2_pocet']; break;
          case 9: $devobed+=$row['obed_2_pocet']; break;
          case 4: $styobed+=$row['obed_2_pocet']; break;
          case 13: $trnobed+=$row['obed_2_pocet']; break;
          case 'BZL': $bzlobed+=$row['obed_2_pocet']; break;
          case '*': $hviobed+=$row['obed_2_pocet'];
        }
      }

      switch($oddelenie_id){
        case 1: array_push($obedy_amo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 2: array_push($obedy_odlm,array('meno'=>$meno,'obed'=>$obed)); break;
        case 3: array_push($obedy_azo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 4: array_push($obedy_odlz,array('meno'=>$meno,'obed'=>$obed)); break;
        case 5: array_push($obedy_fro,array('meno'=>$meno,'obed'=>$obed)); break;
        case 6: array_push($obedy_amb,array('meno'=>$meno,'obed'=>$obed)); break;
        case 7: array_push($obedy_gpo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 8: array_push($obedy_gpo2,array('meno'=>$meno,'obed'=>$obed)); break;
        case 9: array_push($obedy_pracovna,array('meno'=>$meno,'obed'=>$obed)); break;
        case 10: array_push($obedy_uz,array('meno'=>$meno,'obed'=>$obed)); break;//Ustajnenie zv.
        case 11: array_push($obedy_hts,array('meno'=>$meno,'obed'=>$obed)); break;
        case 12: array_push($obedy_lps,array('meno'=>$meno,'obed'=>$obed)); break;//Strav. prev.
        case 13: array_push($obedy_okb,array('meno'=>$meno,'obed'=>$obed)); break;
        case 14: array_push($obedy_opldz,array('meno'=>$meno,'obed'=>$obed)); break;
        case 15: array_push($obedy_kurici,array('meno'=>$meno,'obed'=>$obed)); break;
        case 16: array_push($obedy_udrzba,array('meno'=>$meno,'obed'=>$obed)); break;
        case 17: array_push($obedy_vratnica,array('meno'=>$meno,'obed'=>$obed)); break;
        default: array_push($obedy_ine,array('meno'=>$meno,'obed'=>$obed));
      }
    }

    if(!empty($row['vecera_1'])){
      array_push($vecere,array('meno'=>$meno,'vecera'=>$row['vecera_1'].' '.$row['vecera_1_pocet'].'x'));
      switch($row['vecera_1']){
        case '9-S': $desvecera+=$row['vecera_1_pocet']; break;
        case 3: $trivecera+=$row['vecera_1_pocet']; break;
        case 9: $devvecera+=$row['vecera_1_pocet']; break;
        case 4: $styvecera+=$row['vecera_1_pocet']; break;
        case 13: $trnvecera+=$row['vecera_1_pocet']; break;
        case 'BZL': $bzlvecera+=$row['vecera_1_pocet']; break;
        case '*': $hvivecera+=$row['vecera_1_pocet'];
      }
    }
  }
}

/**
print_r($oddelenie);
echo '<br /><br />';
print_r($obedy_azo);
echo '<br /><br />';
print_r($vecere);
echo '<br /><br />';
**/

function zoznam_na_stravu($array_key, $foreach){
  global $oddelenie;
  echo '<th class="zoznam_na_stravu">';
  if(array_key_exists($array_key, $oddelenie)){echo $oddelenie[$array_key];} else{echo $array_key;}
  echo '<br /><table><tr><th class="zrazkova_listina">Meno</th><th class="zrazkova_listina">Diéta č.,<br />Počet</th>';
  echo '</tr><tr>';
  foreach($foreach as $pc=>$obed){echo '<tr><td class="oramovanie">'.current($obed).'</td><td class="oramovanie">'.next($obed).'</td></tr>';}
  echo '</tr></table></th>';
}
?>
<table border="2">
  <tr>
    <th class="noprint"><a href="zoznamy_na_stravu.php"><button>Späť</button></a></th>
    <th class="noprint" colspan="2">Deň: <?php echo $den_v_tyzdni[$cislo];?></th>
    <th class="skrite" colspan="4">Zoznam na obed a večeru na deň: <?php echo $den_v_tyzdni[$cislo];?></th>
    <th>Dátum: <?php echo $datum;?></th>
    <th class="noprint">
      <a href="zoznamy_na_stravu.php?datum=<?php echo $datum.'&U='.$datumU;?>&xlsx=create">
        <img src="php_excel.png" width="88" height="20" border="1" alt="php2xlsx">
      </a>
    </th>
  </tr>

  <tr>
<?php
zoznam_na_stravu(2, $obedy_odlm);
zoznam_na_stravu(4, $obedy_odlz);
zoznam_na_stravu(8, $obedy_gpo2);
zoznam_na_stravu(5, $obedy_fro);
zoznam_na_stravu(11, $obedy_hts);
echo '</tr><tr>';
zoznam_na_stravu(6, $obedy_amb);
zoznam_na_stravu(13, $obedy_okb);
zoznam_na_stravu(9, $obedy_pracovna);
zoznam_na_stravu(16, $obedy_udrzba);
zoznam_na_stravu('Večere:', $vecere);
echo '</tr><tr>';
zoznam_na_stravu(1, $obedy_amo);
zoznam_na_stravu(3, $obedy_azo);
zoznam_na_stravu(7, $obedy_gpo);
zoznam_na_stravu(14, $obedy_opldz);
zoznam_na_stravu(12, $obedy_lps);
echo '</tr><tr>';
zoznam_na_stravu(15, $obedy_kurici);
zoznam_na_stravu(10, $obedy_uz);
zoznam_na_stravu(17, $obedy_vratnica);
zoznam_na_stravu('Iné', $obedy_ine);
?>
    <th class="zoznam_na_stravu">Počty diét<br />
<table>
  <tr><th colspan="2">Obedy:</th><th></th><th colspan="2">Večere:</th></tr>
  <tr><td>3 =</td><td><?php echo $triobed;?></td><td></td><td>3 =</td><td><?php echo $trivecera;?></td></tr>
  <tr><td>9 =</td><td><?php echo $devobed;?></td><td></td><td>9 =</td><td><?php echo $devvecera;?></td></tr>
  <tr><td>4 =</td><td><?php echo $styobed;?></td><td></td><td>4 =</td><td><?php echo $styvecera;?></td></tr>
  <tr><td>13 =</td><td><?php echo $trnobed;?></td><td></td><td>13 =</td><td><?php echo $trnvecera;?></td></tr>
  <tr><td>bzl =</td><td><?php echo $bzlobed;?></td><td></td><td>bzl =</td><td><?php echo $bzlvecera;?></td></tr>
  <tr><td>* =</td><td><?php echo $hviobed;?></td><td></td><td>* =</td><td><?php echo $hvivecera;?></td></tr>
  <tr><td>9-S =</td><td><?php echo $desobed;?></td><td></td><td>9-S =</td><td><?php echo $desvecera;?></td></tr>
  <tr><th colspan="5"><br /><?php echo date('Y-m-d (H:i:s)', date(time()));?></th></tr>
</table>
    </th>
  </tr>
</table>

<?php include('pata.php');?>