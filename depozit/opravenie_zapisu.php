<?php
include('hlavicka.php');
$nadpis='Opravenie zápisu';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');

if(!isset($_POST['opravit'])){
?>
<fieldset class="siroky">
  <form action="opravenie_zapisu.php?karta_id=<?php echo $_GET['karta_id'].'&pacient_cislo='.$_GET['pacient_cislo'].'&prijem='.$_GET['prijem'].'&vydaj='.$_GET['vydaj'].'&zostatok='.$_GET['zostatok'].'&pokladna_konecny_stav='.$_GET['pokladna_konecny_stav'].'&banka_konecny_stav='.$_GET['banka_konecny_stav'];?>" method="POST">
    <table>
      <tr class="hlavicka">
        <td>Číslo<br />pacienta</td>
        <td>Dátum<br />účtovania</td>
        <td>Číslo<br />dokladu</td>
        <td>Kód</td>
        <td>Príjem</td>
        <td>Výdaj</td>
        <!--td>Zostatok</td-->
        <td>Pozámka</td>
      </tr>

      <tr>
        <td><input placeholder="č. pac." name="pacient_cislo" type="text" size="4" maxlength="4" required autofocus="autofocus" value="<?php echo $_GET['pacient_cislo'];?>"></td>
        <td><input placeholder="dátum účtovania" name="datum_uctovania" type="text" size="10" maxlength="10" required value="<?php echo date('j-n-Y', strtotime($_GET['datum_uctovania']));?>"></td>
        <td><input placeholder="číslo dokladu" name="doklad_cislo" type="text" size="16" maxlength="16" required value="<?php echo $_GET['doklad_cislo'];?>"></td>
        <td>
          <select name="kod">
<?php
    $case=$_GET['kod'];
    include("kod.php");
?>
          </select>
        </td>
        <td><input placeholder="príjem" name="prijem" type="text" size="5" maxlength="7" value="<?php echo str_replace(".",",",$_GET['prijem']);?>"></td>
        <td><input placeholder="výdaj" name="vydaj" type="text" size="5" maxlength="7" value="<?php echo str_replace(".",",",$_GET['vydaj']);?>"></td>
        <!--td><input placeholder="zostatok" name="zostatok" type="text" size="5" maxlength="5" value="<-?php echo $_GET['zostatok'];?>"></td-->
        <td><input placeholder="pozámka" name="karta_poznamka" type="text" size="26" maxlength="50" value="<?php echo $_GET['karta_poznamka'];?>"></td>
      </tr>
<?php
    echo '<tr><td colspan="7"><hr /></td></tr>';
?>
    </table>
    <input type="submit" value="Opraviť zápis" name="opravit">
  </form>
</fieldset>
<?php
}
else{
  $cakanie=1;

  $nahrad=array(','=>'.', ', '=>'.');

  $prijem=strtr($_POST['prijem'], $nahrad);
  $vydaj=strtr($_POST['vydaj'], $nahrad);
  $R_prijem=$prijem-$_GET['prijem'];
  $R_vydaj=$vydaj-$_GET['vydaj'];
  $R=$R_prijem-$R_vydaj;

  $novy_zostatok=$_GET['zostatok']+$R;
  $datum_uctovania_skr=date('Y-m', strtotime($_POST['datum_uctovania']));
  $datum_uctovania=date('Y-m-d', strtotime($_POST['datum_uctovania']));
/**/
//  $pokladna_konecny_stav=$_GET['pokladna_konecny_stav']+$R;

  if(is_numeric(strpos($_POST['doklad_cislo'],'B')) OR is_numeric(strpos($_POST['doklad_cislo'],'b'))){
    $pokladna_konecny_stav=$_GET['pokladna_konecny_stav'];
    $banka_konecny_stav=$_GET['banka_konecny_stav']+$R;
  }
  else{
    $pokladna_konecny_stav=$_GET['pokladna_konecny_stav']+$R;
    $banka_konecny_stav=$_GET['banka_konecny_stav'];
  }

  $sql="UPDATE pokladna SET
pokladna_konecny_stav='".(round($pokladna_konecny_stav*100)/100)."',
banka_konecny_stav='".(round($banka_konecny_stav*100)/100)."',
pokladna_uprava=NOW()
WHERE
pokladna_datum_uctovania_skr='".$datum_uctovania_skr."';";

  $sql.="UPDATE pacienti_".$_SESSION['obdobie']." SET
zostatok='".(round($novy_zostatok*100)/100)."'
WHERE
pacient_cislo='".$_POST['pacient_cislo']."';";

  $sql.="UPDATE karty_".$_SESSION['obdobie']." SET
karta_poznamka='".$_POST['karta_poznamka']."',
pacient_cislo='".$_POST['pacient_cislo']."',
datum_uctovania_skr='".$datum_uctovania_skr."',
datum_uctovania='".$datum_uctovania."',
doklad_cislo='".$_POST['doklad_cislo']."',
kod='".$_POST['kod']."',
prijem='".(round($prijem*100)/100)."',
vydaj='".(round($vydaj*100)/100)."',
karta_uprava=NOW() WHERE
karta_id='".$_GET['karta_id']."'";

  $run=mysqli_query($dbcon,$sql);

  if($dbcon->multi_query($sql)===TRUE){
    $arrlength=count($_SESSION['pacienti']);
      for($row=0;$row<$arrlength;$row++){
        if($_SESSION['pacienti'][$row]['pacient_cislo']==$_POST['pacient_cislo']){
          $_SESSION['pacienti'][$row]['pacient_cislo']=$_POST['pacient_cislo'];
          $_SESSION['pacienti'][$row]['zostatok']=strtr($_POST['novy_zostatok'], $nahrad);
      }
    }

    sleep($cakanie);
    unset($_SESSION['pacienti']);
    header('Location: zadanie_pohybov.php?updated=Oprava_zapisu_prebehla_uspesne&datum_uctovania='.$_POST['datum_uctovania']);
  }
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
/**/

/** /
echo '<br />Príjem:'.$_GET['prijem'].'<br />Výdaj:'.$_GET['vydaj'].'<br />Zostatok:'.$_GET['zostatok'].'<br />Nový príjem:'.$_POST['prijem'].'<br />Nový výdaj:'.$_POST['vydaj'].'<br />Zmena:'.$R.'<br />Nový zostatok:'.$novy_zostatok;
/**/
}

include('pata.php');
?>