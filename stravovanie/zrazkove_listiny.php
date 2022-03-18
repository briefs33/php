<?php
include('hlavicka.php');
$nadpis='Zrážkové listiny';
include('nadpis.php');

echo '<h1>'.$nadpis.'</h1>';

if(isset($_GET['mesiac'])){$mesiac=$_GET['mesiac']; $rok=$_GET['rok'];}
else{$mesiac=idate('m'); $rok=idate('Y');}

str_pad($mesiac,2,0,STR_PAD_LEFT);
$datum_z=$rok.'-'.$mesiac.'-01';
$den_k=date('t',strtotime($datum_z));
$datum_k=$rok.'-'.$mesiac.'-'.$den_k;

if(isset($_GET['xlsx'])){include('zrazkova_listina_xlsx.php');}
?>
<table>
  <form role="form" method="get" action="zrazkove_listiny.php">
    <tr>
      <th>Rok:</th>
      <td>
        <select id="rok" name="rok">
<?php
$rokm=$rok-1;
$rokp=$rok+1;
echo '<option value="'.$rokm.'">'.$rokm.'</option>';
echo '<option value="'.$rok.'" selected>'.$rok.'</option>';
echo '<option value="'.$rokp.'">'.$rokp.'</option>';
?>
        </select>
      </td>
      <th>Mesiac:</th>
      <td>
        <select id="mesiac" name="mesiac">
<?php include('mesiac.php');?>
        </select>
      </td>

      <td>
        <input type="submit" value="Vytvoriť" name="xlsx">
      </td>

      <td>
        <button onclick="window.open(href='zrazkova_listina.php?mesiac='+(document.getElementById('mesiac').value)+'&rok='+(document.getElementById('rok').value));">Zobraziť</button>
      </td>
    </tr>
  </form>

  <tr>
    <td colspan="6">
<?php
if(isset($_GET['subor'])){
  $subor='zrazkove_listiny/'.$_GET['subor'];
  if(!unlink($subor)){echo '<b>Nastala chyba pri odstraňovaní súboru "'.$_GET['subor'].'".</b>';}
  else{echo '<br /><b>Súbor "'.$_GET['subor'].'" bol odstránený.</b>';}
}
else{echo '<br /><br />';}
?>
    </td>
  </tr>
</table>

<table>
  <tr class="hlavicka">
    <th>Stiahnuť súbor</th>
    <th class="upozornenie">Odstrániť súbor</th>
  </tr>
<?php
$files=scandir('zrazkove_listiny/');
$hideName=array('.','..','.DS_Store');

foreach($files as $filename){
  if(!in_array($filename, $hideName)){
?>
  <tr class="farba">
    <td><a href="zrazkove_listiny/<?php echo $filename;?>"><?php echo $filename;?></a></td>
    <td><a href="zrazkove_listiny.php?subor=<?php echo $filename;?>"><button>Odstrániť</button></a></td>
  </tr>
<?php
  }
}
?>
</table>

<?php include('pata.php');?>