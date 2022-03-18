<?php
include('hlavicka.php');
$nadpis='Obrázky jedál';
include('nadpis.php');
?>

<h1><?php echo $nadpis;?></h1>

<?php
$stav=array();

if(isset($_POST['submit'])){
  $priecinok='obrazky_jedal/';
  $target_file=$priecinok.basename($_FILES['fileToUpload']['name']);
  $imageFileType=pathinfo($target_file,PATHINFO_EXTENSION);
  $format_suboru=array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF');
  if(!in_array($imageFileType,$format_suboru)){array_push($stav,'Sú akceptované len JPG, JPEG, PNG a GIF formáty súboru!');}
  if(file_exists($target_file)){array_push($stav,'Obrázok už existuje!');}
  $check=getimagesize($_FILES['fileToUpload']['tmp_name']);
  if($check==false){array_push($stav,'Chyba súboru!"');}
  if($_FILES['fileToUpload']['size']>500000){array_push($stav,'Súbor je príliž veľký (má viac ako 500kB)!');}
  if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)){array_push($stav,'Obrázok_"'.basename($_FILES['fileToUpload']['name']).'"_bol_nahratý.');}
  else{array_push($stav,'Pri_nahrávaní_obrázku_nastala_chyba!');}
}
?>

<fieldset class="uzky">
<?php
/** exec("convert ".$filename." -resize ".$x."x".$y." ".$converted_filename); **/

if($rola>=7){
?>
  <form action="jedla.php" method="post" enctype="multipart/form-data">
    Vyberte obrázok, ktorý chcete nahrať:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Nahrať" name="submit">
  </form>
<?php
  }

  foreach($stav as $x => $x_value){echo $x_value;}
?>
  <div class="tri_stlpce">
<?php
  $files=scandir('obrazky_jedal/');
  $hideName=array('.','..','.DS_Store');

  foreach($files as $filename){
    if(!in_array($filename, $hideName)){
?>
    <a href="obrazky_jedal/<?php echo $filename;?>" onclick="window.open(this.href,'','width=700,height=540,top=60,left=100'); return false"><?php echo $filename;?></a><br />
<?php
    }
  }
?>
  </div>
</fieldset>

<?php include('pata.php');?>