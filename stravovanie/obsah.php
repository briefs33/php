<p id="mySidenav" class="nav">
<!--a href="javascript:void(0)"><button class="button_obsah" onclick="myFunction()">Menu</button></a-->
<a href="index.php"><button class="button_obsah">Domov</button></a>
<var class="skrite_hr"><hr /></var>
<a href="profil.php"><button class="button_obsah">Profil</button></a>
<a href="prehlad_stravnikov.php"><button class="button_obsah">Prehľad stravníkov</button></a>
<var class="skrite_hr"><hr /></var>
<a href="objednanie_stravy_kalendar.php"><button class="button_obsah">Objednanie stravy</button></a>
<a href="prehlad_objednavok.php"><button class="button_obsah">Prehľad objednávok</button></a>
<var class="skrite_hr"><hr /></var>
<a href="jedalne_listky.php"><button class="button_obsah">Jedálne lístky</button></a>
<!--a href="jedla.php"><button class="button_obsah">Jedlá</button></a-->
<var class="skrite_hr"><hr /></var>
<?php
$rola=$_SESSION['rola'];

if($rola>=7){// skupina: IT, Stravovacia prevadzka;
?>
<a href="zoznamy_na_stravu.php"><button class="button_obsah">Zoznamy na stravu</button></a>
<a href="zrazkove_listiny.php"><button class="button_obsah">Zrážkové listiny</button></a>
<a href="cennik.php"><button class="button_obsah">Cenník</button></a>
<var class="skrite_hr"><hr /></var>
<?php
}

if($rola>=2){// skupina: IT, AMB, Pracovna, Stravovacia prevadzka, Udrzba, Veduce sestry;
?>
<a href="registracia.php"><button class="button_obsah">Registrácia</button></a>
<?php
}
/**/
switch ($_SESSION['email']){
  case 'monika.fothyova@pnh.sk':
  case 'monika.maczkoova@pnh.sk':
  case 'nikoleta.oraveczova@pnh.sk':
  case 'attila.csontos@pnh.sk':
    echo '<var class="skrite_hr"><hr /></var>';
    echo '<a href="zrazkove_listiny_FU.php"><button class="button_obsah">Zrážkové listiny FU</button></a>';
    echo '<a href="ciselnik_stredisk.php"><button class="button_obsah">Číselník stredísk</button></a>';
    echo '<a href="pracovne_zaradenia.php"><button class="button_obsah">Pracovné zaradenia</button></a>';
    break;
  default:
}
/**/
if(isset($_GET['heslo'])){
  if($_GET['heslo']=='stvorenie' and $rola>=9){
?>
<a href="spravovanie_tabuliek.php"><button class="button_obsah">Spravovanie tabuliek</button></a>
<?php
  }
}
?>
</p>