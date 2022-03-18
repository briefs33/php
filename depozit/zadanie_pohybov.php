<?php
include('hlavicka.php');
$mesiace=array(0,'januára','februára','marca','apríla','mája','júna','júla','augusta','septembra','októbra','novembra','decembra');

$cas=strtotime($_GET['datum_uctovania']);

$nadpis='Zadanie pohybov &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp';
$nadpis.='&nbsp &nbsp &nbsp &nbsp'.date('j.', $cas).' '.$mesiace[date('n', $cas)].' '.date('Y', $cas);

include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');

if(isset($_POST['realizuj'])){
  $cakanie=1;

  $nahrad=array(','=>'.', ', '=>'.');

  if(is_numeric(strpos($_POST['doklad_cislo'],'B')) OR is_numeric(strpos($_POST['doklad_cislo'],'b'))){
    $pokladna_konecny_stav=strtr($_POST['pokladna_konecny_stav'], $nahrad);
    $banka_konecny_stav=strtr($_POST['banka_konecny_stav'], $nahrad)+strtr($_POST['prijem'], $nahrad)-strtr($_POST['vydaj'], $nahrad);
  }
  else{
    $pokladna_konecny_stav=strtr($_POST['pokladna_konecny_stav'], $nahrad)+strtr($_POST['prijem'], $nahrad)-strtr($_POST['vydaj'], $nahrad);
    $banka_konecny_stav=strtr($_POST['banka_konecny_stav'], $nahrad);
  }

  $sql_vloz.="UPDATE pokladna SET
pokladna_konecny_stav='".(round($pokladna_konecny_stav*100)/100)."',
banka_konecny_stav='".(round($banka_konecny_stav*100)/100)."',
pokladna_uprava=NOW()
WHERE
pokladna_datum_uctovania_skr='".date('Y-m', $cas)."';";

  $sql_vloz.="UPDATE pacienti_".$_SESSION['obdobie']." SET
zostatok='".strtr($_POST['novy_zostatok'], $nahrad)."'
WHERE
pacient_cislo='".$_POST['pacient_cislo']."';";

  $sql_vloz.="INSERT INTO karty_".$_SESSION['obdobie']."
(pacient_cislo, datum_uctovania_skr, datum_uctovania, doklad_cislo, kod, prijem, vydaj, karta_poznamka)
VALUES
('".$_POST['pacient_cislo']."', '".date('Y-m', $cas)."', '".date('Y-m-d', $cas)."', '".$_POST['doklad_cislo']."', '".$_POST['kod']."', '".strtr($_POST['prijem'], $nahrad)."', '".strtr($_POST['vydaj'], $nahrad)."', '".$_POST['karta_poznamka']."');";

  $run=mysqli_query($dbcon,$sql_vloz);
  if($dbcon->multi_query($sql_vloz)===true){
    $arrlength=count($_SESSION['pacienti']);
      for($row=0;$row<$arrlength;$row++){
        if($_SESSION['pacienti'][$row]['pacient_cislo']==$_POST['pacient_cislo']){
          $_SESSION['pacienti'][$row]['pacient_cislo']=$_POST['pacient_cislo'];
          $_SESSION['pacienti'][$row]['zostatok']=strtr($_POST['novy_zostatok'], $nahrad);
      }
    }

    sleep($cakanie);
    header('Location: zadanie_pohybov.php?insert=Pohyb_bol_zrealizovaný&datum_uctovania='.$_GET['datum_uctovania'].'&doklad_cislo='.$_POST['doklad_cislo'].'&kod='.$_POST['kod']);
  }
  else{echo 'Chyba: '.$sql_vloz.'<br />'.$dbcon->error;}
}
else{
  $pokladna_konecny_stav=$banka_konecny_stav='';

  if(isset($_SESSION['pacienti'])){
    $pacienti=$_SESSION['pacienti'];

/** /
  print_r($pacienti);
  echo '<br /><br />';
/**/
  }
  else{
    $pacienti=array();
    $stav=1;

    $sql="SELECT pacient_cislo, meno, priezvisko, zostatok, stav
FROM pacienti_".$_SESSION['obdobie']."
WHERE stav='$stav'
ORDER BY pacient_cislo";

    $run=mysqli_query($dbcon,$sql);

    while($row=mysqli_fetch_array($run)){
      array_push($pacienti,array('pacient_cislo'=>$row['pacient_cislo'], 'priezvisko'=>$row['priezvisko'], 'meno'=>$row['meno'], 'zostatok'=>$row['zostatok']));
    }
    $_SESSION['pacienti']=$pacienti;
  }
?>
<script language="javascript" type="text/javascript">
<!--
  var js_pacienti=[];
//  var js_pacient_cislo=[];
//  var js_meno=[];
<?php
  foreach($pacienti as $zoznam_pacientov=>$pacient){
    echo 'js_pacienti.push({pacient_cislo:"'.current($pacient).'", meno:"'.next($pacient)." ".next($pacient).'", zostatok:"'.next($pacient).'"}); ';
  }
?>
/* vypise pole js_pacienti */
function showHint(str){
  var var_meno="";
  var var_zostatok=0;
  
  if(str.length==0){ 
    document.getElementById("js_meno").innerHTML="";
    document.getElementById("js_zostatok").innerHTML=0;
    return;
  }
  else{
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
      if (this.readyState ==4&&this.status==200){
        document.getElementById("js_meno").innerHTML=this.responseText;
      }
    };
    for(i=0; i<js_pacienti.length; i++){
      if(js_pacienti[i].pacient_cislo.indexOf(str)==0){
//        var_meno+=js_pacienti[i].pacient_cislo+" ";
        var_meno+=js_pacienti[i].meno;
        var_zostatok=js_pacienti[i].zostatok;
        break;
      }
    };
    document.getElementById("js_meno").innerHTML=var_meno;
    document.getElementById("js_zostatok").innerHTML=var_zostatok;
  }
}
/* vypise pole js_pacienti */


function vyp_zostatok(){
  var z = parseFloat((document.getElementById("js_zostatok").value).replace(/,/g , "."));
  var p = parseFloat((document.getElementById("prijem").value).replace(/,/g , "."));
  var v = parseFloat((document.getElementById("vydaj").value).replace(/,/g , "."));
  var nz = Math.round((z + p - v)*100)/100;

  document.getElementById("js_novy_zostatok").innerHTML=nz;//.replace(/\./g, ',');
}
//-->
</script>

<noscript>
  Pozor! JavaScript musí byť podporovaný!
</noscript>

<?php
  $p=0;

  $sql="SELECT karta_id, karta_poznamka, pacienti_".$_SESSION['obdobie'].".pacient_cislo, pokladna_konecny_stav, banka_konecny_stav, meno, priezvisko, datum_uctovania_skr, datum_uctovania, doklad_cislo, kod, prijem, vydaj, zostatok
FROM karty_".$_SESSION['obdobie']."
INNER JOIN pacienti_".$_SESSION['obdobie']." ON pacienti_".$_SESSION['obdobie'].".pacient_cislo = karty_".$_SESSION['obdobie'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = datum_uctovania_skr
WHERE datum_uctovania ='".date('Y-m-d', $cas)."'
ORDER BY karta_id DESC";

  $run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

  if(mysqli_num_rows($run)>0){
    while($row=mysqli_fetch_array($run)){
      $p++;
//      $date=date_create($row['datum_uctovania']);
      if($p==1){
        $pokladna_konecny_stav=$row['pokladna_konecny_stav'];
        $banka_konecny_stav=$row['banka_konecny_stav'];

        if($_SESSION['obdobie_stav']==0){
          formular();
        }
?>
<table>
  <tr class="hlavicka">
    <td>Číslo<br />pacienta</td>
    <td>Meno</td>
    <td>Číslo<br />dokladu</td>
    <td>Kód</td>
    <td>Príjem<br />(€)</td>
    <td>Výdaj<br />(€)</td>
    <td>Zostatok<br />(€)</td>
    <td>Opravenie<br />zápisu</td>
    <td>Poznámka</td>
  </tr>
<?php
      }
?>
  <tr class="farba">
    <td class="right"><?php echo $row['pacient_cislo'];?></td>

    <td class="left">
<a href="prehlad_uctu.php?pacient_cislo=<?php echo $row['pacient_cislo'].'&meno='.$row['priezvisko'].' '.$row['meno'];?>">
  <button><?php echo $row['priezvisko'].' '.$row['meno'];?></button>
</a>
    </td>

    <td class="right"><?php echo $row['doklad_cislo'];?></td>
    <td class="left"><?php echo $row['kod'].' - '.$_SESSION['kody'.$row['kod']];?></td><!-- kód -->
    <td class="right"><?php echo number_format($row['prijem'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['vydaj'],2,',','');?></td>
    <td class="right"><?php echo number_format($row['zostatok'],2,',','');?></td>

    <td>
<?php
      if($_SESSION['obdobie_stav']==0){
?>
      <a href="opravenie_zapisu.php?pacient_cislo=<?php echo $row['pacient_cislo'].'&meno='.$row['priezvisko'].' '.$row['meno'].'&datum_uctovania_skr='.date('Y-m', $cas).'&datum_uctovania='.date('Y-m-d', $cas).'&karta_id='.$row['karta_id'].'&karta_poznamka='.$row['karta_poznamka'].'&doklad_cislo='.$row['doklad_cislo'].'&kod='.$row['kod'].'&prijem='.$row['prijem'].'&vydaj='.$row['vydaj'].'&zostatok='.$row['zostatok'].'&pokladna_konecny_stav='.$row['pokladna_konecny_stav'].'&banka_konecny_stav='.$row['banka_konecny_stav'];?>">
        <button>Opraviť</button>
      </a>
<?php
      }
?>
    </td>

    <td class="right"><?php echo $row['karta_poznamka'];?></td>
  </tr>
<?php
    }
  }
  else{
    $datum_uctovania_skr=date('Y-m', $cas);

    $sql="SELECT pokladna_datum_uctovania_skr, pokladna_konecny_stav, banka_konecny_stav
FROM pokladna ORDER BY pokladna_datum_uctovania_skr DESC LIMIT 1;";

    $run_sql=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
    while($row=mysqli_fetch_array($run_sql)){
      $pokladna_konecny_stav=$row['pokladna_konecny_stav'];
      $banka_konecny_stav=$row['banka_konecny_stav'];

      if($row['pokladna_datum_uctovania_skr']!=$datum_uctovania_skr){
        $sql_vloz="INSERT INTO pokladna (pokladna_datum_uctovania_skr, pokladna_pociatocny_stav, pokladna_konecny_stav, banka_pociatocny_stav, banka_konecny_stav)
VALUES ('".$datum_uctovania_skr."', '$pokladna_konecny_stav', '$pokladna_konecny_stav', '$banka_konecny_stav', '$banka_konecny_stav');";

        $run=mysqli_query($dbcon,$sql_vloz);// or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
      }
    }
    if($_SESSION['obdobie_stav']==0){
      formular();
    }
  }

  $datum_uctovania=date('j.n.Y', $cas);

  if($p>0){
?>
</table>
<?php
  }
  include("pata.php");
}

function formular(){
  global $pokladna_konecny_stav, $banka_konecny_stav;
?>
<fieldset class="siroky">
  <form action="zadanie_pohybov.php?datum_uctovania=<?php echo $_GET['datum_uctovania']; ?>" method="POST">
    <table>
      <tr><td colspan="7"><br /></td></tr>

      <tr>
        <th colspan="2">Číslo dokladu</th>
        <th>Stav pokladne</th>
        <th colspan="2">Stav banky</th>
        <th colspan="2">Poznámka</th>
      </tr>

      <tr>
        <td colspan="2"><textarea placeholder="číslo dokladu" name="doklad_cislo" type="text" rows="1" cols="16"
        <?php if(!isset($_GET['doklad_cislo']))
        {echo 'autofocus="autofocus" required>';}else{echo ' required>'.$_GET['doklad_cislo'];}?></textarea></td>
        <td><textarea name="pokladna_konecny_stav" id="js_pokladna_konecny_stav" type="text" rows="1" cols="10" readonly><?php echo number_format($pokladna_konecny_stav,2,',','');?></textarea></td>
        <td colspan="2"><textarea name="banka_konecny_stav" id="js_banka_konecny_stav" type="text" rows="1" cols="10" readonly><?php echo number_format($banka_konecny_stav,2,',','');?></textarea></td>
        <td colspan="2"><textarea type="text" name="karta_poznamka" type="text" rows="1" cols="20"></textarea></td>
      </tr>

      <tr><td colspan="7"><br /></td></tr>

      <tr>
        <th>Č. pacienta</th>
        <th>Meno</th>
        <th>Zostatok</th>
        <th>Kód</th>
        <th>Príjem</th>
        <th>Výdaj</th>
        <th>Nový zostatok</th>
      </tr>

      <tr>
        <td><input placeholder="č. pac." name="pacient_cislo" type="text" size="4" maxlength="4" required <?php if(isset($_GET['doklad_cislo'])){echo 'autofocus="autofocus"';}?> onkeyup="showHint(this.value)"></input></td>
        <td><textarea placeholder="meno" name="meno" id="js_meno" type="text" rows="1" cols="16" readonly></textarea></td>
        <td><textarea name="zoszatok" id="js_zostatok" type="text" rows="1" cols="8" readonly>0</textarea></td>
        <td>
          <select name="kod">
<?php
  if(!isset($_GET['kod']))
  {$case=1;}else{$case=$_GET['kod'];}
  include("kod.php");
?>
          </select>
        </td>
        <td><input placeholder="0" name="prijem" id="prijem" value="0" type="text" size="5" maxlength="7" onkeyup="vyp_zostatok()"></input></td>
        <td><input placeholder="0" name="vydaj" id="vydaj" value="0" type="text" size="5" maxlength="7" onkeyup="vyp_zostatok()"></input></td>
        <td><textarea type="text" name="novy_zostatok" id="js_novy_zostatok" type="text" rows="1" cols="8" readonly>0</textarea></td>
      </tr>
    </table>
    <input type="submit" value="Vykonať pohyb" name="realizuj">
  </form>
</fieldset>
<?php
}
?>