<p id="mySidenav" class="nav">
<a href="index.php"><button class="button_obsah">Domov</button></a>

  <var class="skrite_hr"><hr /></var>
<a href="prehlad_pacientov.php?stav=1"><button class="button_obsah">Prehľad pacientov</button></a>

  <var class="skrite_hr"><hr /></var>
<a href="kalendar_zadanie_pohybov.php"><button class="button_obsah">Zadanie pohybov</button></a>

  <var class="skrite_hr"><hr /></var>
<a href="pohyby_pacientov.php"><button class="button_obsah">Pohyby pacientov</button></a>
<a href="kalendar_pohyby_pokladne_a_banky.php"><button class="button_obsah">Pohyby PaB</button></a>
<a href="stav_pokladne_a_banky.php"><button class="button_obsah">Stav PaB</button></a>
<a href="kalendar_doklady.php"><button class="button_obsah">Doklady</button></a>

  <var class="skrite_hr"><hr /></var>
<a href="registracia.php"><button class="button_obsah">Registrácia pacienta</button></a>
<a href="kody.php"><button class="button_obsah">Kódy</button></a>

  <var class="skrite_hr"><hr /></var>
<a href="uzavierka.php"><button class="button_obsah">Uzávierka</button></a>
<?php
if(isset($_GET['heslo'])){
  if($_GET['heslo']=='stvorenie'){
?>
<a href="spravovanie_tabuliek.php"><button class="button_obsah">Spravovanie tabuliek</button></a>
<?php
  }
}
?>
</p>