<?php
include('hlavicka.php');
$nadpis='Profil';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

$db='default';
include('databaza.php');
include('pole_pracovisk.php');
include('pole_stredisk.php');
include('pole_zaradeni.php');
$email=$_SESSION['email'];
$rola=$_SESSION['rola'];

$stravnik_id=(isset($_GET['strid']) ? $_GET['strid'] : $_SESSION['stravnik_id']);
//if(isset($_GET['strid'])){$stravnik_id=$_GET['strid'];}
//else{$stravnik_id=$_SESSION['stravnik_id'];}

if(isset($_POST['upravit'])){
  $sql="UPDATE prihlasovacie_udaje SET
email='".$_POST['email']."',
heslo='".$_POST['heslo']."',
rola='".$_POST['rola']."'
WHERE prihlasovacie_udaje.stravnik_id='$stravnik_id';";
  $sql.="UPDATE stravnici SET
titul_pm='".$_POST['titul_pm']."',
meno='".$_POST['meno']."',
priezvisko='".$_POST['priezvisko']."',
titul_zm='".$_POST['titul_zm']."',
osobne_cislo='".$_POST['osobne_cislo']."',
oddelenie_id='".$_POST['oddelenie_id']."',
cena_id='".$_POST['cena_id']."'
WHERE stravnici.stravnik_id='$stravnik_id'";

  $run=mysqli_query($dbcon,$sql);
  if($dbcon->multi_query($sql)===true){header('Location: profil.php?strid='.$stravnik_id);}
  else{echo 'Chyba: '.$sql.'<br />'.$dbcon->error;}
}
else{
  $sql="SELECT
prihlasovacie_udaje.email,
prihlasovacie_udaje.heslo,
prihlasovacie_udaje.rola,
stravnici.titul_pm,
stravnici.meno,
stravnici.priezvisko,
stravnici.titul_zm,
stravnici.osobne_cislo,
stravnici.oddelenie_id,
stravnici.nakladove_stredisko,
stravnici.zaradenie,
stravnici.cena_id,
oddelenia.oddelenie_skratka,
nakladove_strediska.stredisko_id,
nakladove_strediska.stredisko_cislo,
nakladove_strediska.stredisko_nazov
FROM stravnici
INNER JOIN prihlasovacie_udaje ON stravnici.stravnik_id=prihlasovacie_udaje.stravnik_id
INNER JOIN oddelenia ON stravnici.oddelenie_id=oddelenia.oddelenie_id
INNER JOIN nakladove_strediska ON stravnici.nakladove_stredisko=nakladove_strediska.stredisko_id
WHERE stravnici.stravnik_id='$stravnik_id'";

/*
`oddelenia`
`oddelenie_id`, `oddelenie_skratka`, `oddelenie`:

(1, 'AMB a CS', 'Ambulantný trakt a Centrálna služba'),
(2, 'AMO', 'Akútne mužské oddelenie'),
(3, 'AŽO', 'Akútne ženské oddelenie'),
(4, 'FRO', 'Fyziatricko-rehabilitačné oddelenie'),
(5, 'GPO', 'Gerontopsychiatrické oddelenie'),
(6, 'GPOII', 'Nadštandardné gerontopsychiatrické oddelenie'),
(7, 'HTS', 'Hospodársko-technické stredisko'),
(8, 'Kuriči', 'Kotolne, energetické zdroje'),
(9, 'Strav. prev.', 'Liečebná výživa - Stravovacia prevádzka'),
(10, 'ODL-M', 'Oddelenie dlhodobej liečby muži'),
(11, 'ODL-Ž', 'Oddelenie dlhodobej liečby ženy'),
(12, 'OKB', 'Oddelenie klinickej biochémie'),
(13, 'OPLDZ', 'Oddelenie pre liečbu drogových závislostí'),
(14, 'Ost. prev.', 'Ostatná prevádzka'),
(15, 'Práčovňa', 'Práčovňa'),
(16, 'Údržba', 'Údržba'),
(17, 'Ustaj. zv.', 'Ustajnenie zvierat'),
(18, 'Vrátnica', 'Vrátnica');
*/

  $run=mysqli_query($dbcon,$sql);
  $row=mysqli_fetch_array($run);

//<fieldset class="uzky">
?>
<fieldset class="mini">
  <form role="form" method="post" action="profil.php?strid=<?php echo $stravnik_id;?>">
    <table>
      <tr>
        <td class="right">Titul pred menom:</td>
        <td class="left"><input placeholder="titul pred menom" name="titul_pm" type="text" autofocus="autofocus" value="<?php echo $row['titul_pm'];?>"></td>
      </tr>

      <tr>
        <td class="right">Meno:</td>
        <td class="left"><input required placeholder="meno" name="meno" type="text" value="<?php echo $row['meno'];?>"></td>
      </tr>

      <tr>
        <td class="right">Priezvisko:</td>
        <td class="left"><input required placeholder="priezvisko" name="priezvisko" type="text" value="<?php echo $row['priezvisko'];?>"></td>
      </tr>

      <tr>
        <td class="right">Titul za menom:</td>
        <td class="left"><input placeholder="titul za menom" name="titul_zm" type="text" value="<?php echo $row['titul_zm'];?>"></td>
      </tr>

      <tr>
        <td class="right">Osobné číslo:</td>
        <td class="left"><input required placeholder="osobné číslo" name="osobne_cislo" type="text" value="<?php echo $row['osobne_cislo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Pracovisko:<br /></td>
        <td class="left"><select name="oddelenie_id">
<?php
$i=1;
include('pracoviska.php');
?>
        </select></td>
      </tr>

      <tr>
        <td class="right">Nákladové stredisko:<br /></td>
        <td class="left"><select name="nakladove_stredisko">
<?php
$i=1;
include('nakladove_strediska.php');
?>
        </select></td>
      </tr>

      <tr>
        <td class="right">Pracovné zaradenie:<br /></td>
        <td class="left"><select name="zaradenie">
<?php
$i=1;
include('zaradenia.php');
?>
        </select></td>
      </tr>

      <tr>
        <td class="right">E-mail:</td>
        <td class="left"><input required <?php if($rola<8){echo 'readonly';}?> placeholder="email" name="email" type="email" value="<?php echo $row['email'];?>"></td>
      </tr>

      <tr>
        <td class="right">Heslo:</td>
        <td class="left"><input required <?php if($_SESSION['email']===$row['email']){} else if($rola<8){echo 'readonly type="password"';}?> placeholder="heslo" name="heslo" value="<?php echo $row['heslo'];?>"></td>
      </tr>

      <tr>
        <td class="right">Kategória<?php if($rola>8){?><span class='tooltip'>
<em>?</em><div><strong>Rola:</strong><p>
1 - bežný užívateľ<br />
2 - vedúce sestry<br />
3 - skupina (AMB, Údržba)<br />
4 - skupina (Práčovňa)<br />
5 - <br />
6 - <br />
7 - stravovacia prevádzka<br />
8 - administrátor (IT)<br />
9 - úplný prístup (IT)
</p></div></span><?php }?>:</td>

        <td class="left"><input required <?php if($rola<9){echo 'readonly';}?> placeholder="kategoria" name="rola" type="text" value="<?php echo $row['rola'];?>"></td>
      </tr>

      <tr>
        <td class="right">Cenník<?php if($rola==7){?><span class='tooltip'>
<em>?</em><div><p>
Identifikačné číslo cenníka (viď. cenník)
</p></div></span><?php }?>:</td>

        <td class="left"><input required <?php if($rola!=7){echo 'readonly';}?> placeholder="cennik_id" name="cena_id" type="text" value="<?php echo $row['cena_id'];?>"></td>
      </tr>
    </table>
      <input type="submit" value="Upraviť" name="upravit">
  </form>
</fieldset>
<?php
  if(isset($_GET['strid'])){
    if(isset($_SESSION['oddelenie_id'])){echo '<p class="center"><a href="prehlad_stravnikov.php"><button>Prehľad stravníkov</button></a></p>';}
  }
}
include('pata.php');
?>