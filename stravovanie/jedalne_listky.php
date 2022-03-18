<?php
include('hlavicka.php');
$nadpis='Jedálne lístky';
include('nadpis.php');

if(isset($_GET['xlsx'])){include('jedalny_listok_xlsx.php');}
if(isset($_GET['tyzden'])){$tyzden=$_GET['tyzden'];} else{$tyzden=idate('W');}
?>
<h1>Jedálne lístky</h1>

<table>
<?php
$startdate=strtotime('monday');
$startdate=strtotime('-1 week', $startdate);
?>
  <tr><th colspan="4">Týždne:</th></tr>
<?php
for($x=0;$x<=2;$x++){
  echo '<tr>';
  for($y=0;$y<=3;$y++){
    $tyzdeni=date('W / Y',$startdate);
?>
    <td>
      <a href="jedalny_listok.php?datum=<?php echo $startdate;?>">
<?php
    switch($tyzden){
      case $tyzdeni: echo '<button class="dnes">'.date('W / Y',$startdate).'</button>'; break;
      default: echo '<button>'.date('W / Y',$startdate).'</button>';
    }
?>
      </a>
    </td>
<?php
    $startdate=strtotime('+1 week', $startdate);
  }
  echo '</tr>';
}
?>
  <tr>
    <td colspan="4">
<?php
if(isset($_GET['subor'])){
  $subor='jedalne_listky/'.$_GET['subor'];
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
$files=scandir('jedalne_listky/');
$hideName=array('.','..','.DS_Store');

foreach($files as $filename){
  if(!in_array($filename, $hideName)){
?>
  <tr class="farba">
    <td><a href="jedalne_listky/<?php echo $filename;?>"><?php echo $filename;?></a></td>
    <td><a href="jedalne_listky.php?subor=<?php echo $filename;?>"><button>Odstrániť</button></a></td>
  </tr>
<?php
  }
}
?>
</table>

<?php include('pata.php');?>