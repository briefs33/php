<?php
//include the file that loads the PhpSpreadsheet classes
require '../PhpSpreadsheet/vendor/autoload.php';

//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//object of the Spreadsheet class to create the excel data
$spreadsheet=new Spreadsheet();
//$sheet=$spreadsheet->getActiveSheet();

$db='default';
include('databaza.php');

if(isset($_GET['mesiac'])){$mesiac=$_GET['mesiac'];}else{$mesiac=idate('m');}
if(isset($_GET['rok'])){$rok=$_GET['rok'];}else{$rok=idate('Y');}

str_pad($mesiac,2,0,STR_PAD_LEFT);
$datumZ=$rok.'-'.$mesiac.'-01';
$denK=date('t',strtotime($datumZ));
$datumK=$rok.'-'.$mesiac.'-'.$denK;

/* Nastavení metadat - autor, název, popis, ... */
$spreadsheet->getProperties()
  ->setCreator('Wisdom')
  ->setLastModifiedBy('Wisdom')
  ->setTitle('Zrazkova_listina')
  ->setSubject('Office 2010, Open XML a PhpSpreadsheet')
  ->setDescription('Tvorba Excel dokumentu z PHP aplikace.')
  ->setKeywords('Zrazkova_listina, PhpSpreadsheet')
  ->setCategory('Zrazkova_listina');

/* Nastavení listu, který bude aktivní po otevření souboru */
$spreadsheet->setActiveSheetIndex(0);
/* Vložení hodnot */
$spreadsheet->getActiveSheet()
  ->SetCellValue('A1', 'Zrážková listina')
  ->SetCellValue('E1', 'pre obdobie od:')
  ->SetCellValue('J1', date('j.n.Y', strtotime($datumZ)))
  ->SetCellValue('N1', 'do:')
  ->SetCellValue('P1', date('j.n.Y', strtotime($datumK)));

$spreadsheet->getActiveSheet()
  ->SetCellValue('A3', 'P.')
  ->SetCellValue('A4', 'č.')
  ->SetCellValue('B3', 'Osobné')
  ->SetCellValue('B4', 'číslo')
  ->SetCellValue('C3', 'Meno')
  ->SetCellValue('D3', 'Suma')
  ->SetCellValue('D4', '(€)');

$pole_pismen=array('D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI');
for($i=1;$i<=$denK;$i++){
  $spreadsheet->getActiveSheet()->SetCellValue($pole_pismen[$i].'4', $i);
}
$spreadsheet->getActiveSheet()
  ->SetCellValue('AJ3', '∑')
  ->SetCellValue('AJ4', '(ks)')
  ->SetCellValue('AK3', '∑')
  ->SetCellValue('AK4', '(€)');

//$diety=array(9,4,13,'BZL');
$ceny=$blv=array();

$sql_ceny="SELECT cena_id,cena_obed_3,cena_obed_9 FROM ceny";
$run_ceny=mysqli_query($dbcon, $sql_ceny);
while($row_ceny=mysqli_fetch_array($run_ceny)){
  $ceny+=array($row_ceny['cena_id']=>array(
    'cena_id'=>$row_ceny['cena_id'],
    'cena_trojka'=>$row_ceny['cena_obed_3'],
    'cena_ostatne'=>$row_ceny['cena_obed_9']
  ));
}

$sql_stravnici="SELECT
DISTINCT stravnici.stravnik_id,
stravnici.titul_pm,
stravnici.meno,
stravnici.priezvisko,
stravnici.titul_zm,
stravnici.osobne_cislo,
stravnici.oddelenie_id,
stravnici.cena_id
FROM stravnici
INNER JOIN objednavky
ON stravnici.stravnik_id=objednavky.stravnik_id
WHERE objednavky.datum BETWEEN '$datumZ' AND '$datumK'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
  OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
  ORDER BY stravnici.priezvisko, stravnici.meno, objednavky.datum ASC";
// ORDER BY stravnici.osobne_cislo, objednavky.datum ASC";

$pc=0;
$riadokM=4;
$riadok=$riadok_=5;
$riadokP=$riadokP_=6;

include('format_xlsx.php');

$run_stravnici=mysqli_query($dbcon, $sql_stravnici);
while($row_stravnici=mysqli_fetch_array($run_stravnici)){
  $pc++;
  $spreadsheet->getActiveSheet()
    ->SetCellValue('A'.$riadok, $pc)
    ->SetCellValue('B'.$riadok, $row_stravnici['osobne_cislo'])
    ->SetCellValue('C'.$riadok, $row_stravnici['titul_pm'].' '.$row_stravnici['meno'])
    ->SetCellValue('C'.$riadokP, $row_stravnici['priezvisko'].' '.$row_stravnici['titul_zm']);

  $cena_id=$row_stravnici['cena_id'];

  if(in_array($cena_id, $ceny[$cena_id])){
    $cena_trojka=$ceny[$cena_id]['cena_trojka'];
    $cena_ostatne=$ceny[$cena_id]['cena_ostatne'];
    if($cena_id==3){array_push($blv,$riadok);}
  }
  else{$cena_trojka=$cena_ostatne=0.0;}

  $spreadsheet->getActiveSheet()
    ->SetCellValue('D'.$riadok, $cena_trojka)
    ->SetCellValue('D'.$riadokP, $cena_ostatne);

  $stravnik_id=$row_stravnici['stravnik_id'];
  $spoluT=$spoluO=$spolu=0.0;
  $objednavky=array();

  $sql_objednavky="SELECT
objednavky.stravnik_id,
DAY(objednavky.datum) AS den,
objednavky.obed_1,
objednavky.obed_1_pocet,
objednavky.obed_2,
objednavky.obed_2_pocet,
objednavky.vecera_1,
objednavky.vecera_1_pocet
FROM objednavky
INNER JOIN stravnici
ON objednavky.stravnik_id=stravnici.stravnik_id
WHERE objednavky.stravnik_id='$stravnik_id'
AND ((objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)>0
  OR (objednavky.obed_1 OR objednavky.obed_2 OR objednavky.vecera_1)='BZL')
AND datum BETWEEN '$datumZ' AND '$datumK'
  ORDER BY stravnici.priezvisko, stravnici.meno, objednavky.datum ASC";
// ORDER BY stravnici.osobne_cislo, objednavky.datum ASC";

  $run_objednavky=mysqli_query($dbcon, $sql_objednavky);
  while($row_objednavky=mysqli_fetch_array($run_objednavky)){
    $objednavky+=array($row_objednavky['den']=>array(
      'den'=>$row_objednavky['den'],
      'obed_1'=>$row_objednavky['obed_1'],
      'obed_1_pocet'=>$row_objednavky['obed_1_pocet'],
      'obed_2'=>$row_objednavky['obed_2'],
      'obed_2_pocet'=>$row_objednavky['obed_2_pocet'],
      'vecera_1'=>$row_objednavky['vecera_1'],
      'vecera_1_pocet'=>$row_objednavky['vecera_1_pocet']
    ));
  }
  for($i=1;$i<=$denK;$i++){
    if(!empty($objednavky[$i]['den'])){
      $trojka=$ostatne=0;

      switch($objednavky[$i]['obed_1']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['obed_1_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['obed_1_pocet'];
      }

      switch($objednavky[$i]['obed_2']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['obed_2_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['obed_2_pocet'];
      }

      switch($objednavky[$i]['vecera_1']){
        case 3: case '*': $trojka=$trojka+$objednavky[$i]['vecera_1_pocet']; break;
        case 9: case 4: case 13: case 'BZL': $ostatne=$ostatne+$objednavky[$i]['vecera_1_pocet'];
      }

      $spreadsheet->getActiveSheet()
        ->SetCellValue($pole_pismen[$i].$riadok, $trojka)
        ->SetCellValue($pole_pismen[$i].$riadokP, $ostatne);
    }
  }
  $spreadsheet->getActiveSheet()
    ->SetCellValue('AJ'.$riadok, '=SUM(E'.$riadok.':AI'.$riadok.')')
    ->SetCellValue('AJ'.$riadokP, '=SUM(E'.$riadokP.':AI'.$riadokP.')')
    ->SetCellValue('AK'.$riadokP, '=D'.$riadok.'*AJ'.$riadok.'+D'.$riadokP.'*AJ'.$riadokP);

  switch($riadokP){
    case 38:
      $riadokM+=2;
      $riadok+=2;
      $riadokP+=2;

      $sumar_t='=0';
      $sumar_o='=0';
      for($i=$riadok_;$i<=$riadokM;$i+=2){$sumar_t.='+AJ'.$i;}
      for($i=$riadokP_;$i<=$riadok;$i+=2){$sumar_o.='+AJ'.$i;}
      $spreadsheet->getActiveSheet()
        ->SetCellValue('A'.$riadok, 'D3:')
        ->SetCellValue('A'.$riadokP, 'Ost:')
        ->SetCellValue('B'.$riadok, $sumar_t)
        ->SetCellValue('B'.$riadokP, $sumar_o)
        ->SetCellValue('C'.$riadok, 'Spolu za stranu (€):')
        ->SetCellValue('C'.$riadokP, '=SUM(AK'.($riadok_).':AK'.$riadokM.')');

      /*Orámovanie*/
      $spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':AK'.$riadokP)->applyFromArray($styleOArray);

      $riadokM+=2;
      $riadok+=2;
      $riadokP+=2;
      $riadok_=$riadok;
      $riadokP_=$riadokP;
      break;

    case 78: case 118: case 158: case 198: case 238: case 278: case 318: case 358: case 398: case 438: case 478: case 518: case 558:
      $riadokM+=2;
      $riadok+=2;
      $riadokP+=2;

      $sumar_t='=0';
      $sumar_o='=0';
      for($i=$riadok_;$i<=$riadokM;$i+=2){$sumar_t.='+AJ'.$i;}
      for($i=$riadokP_;$i<=$riadok;$i+=2){$sumar_o.='+AJ'.$i;}
      $spreadsheet->getActiveSheet()
        ->SetCellValue('A'.$riadok, 'D3:')
        ->SetCellValue('A'.$riadokP, 'Ost:')
        ->SetCellValue('B'.$riadok, $sumar_t)
        ->SetCellValue('B'.$riadokP, $sumar_o)
        ->SetCellValue('C'.$riadok, 'Spolu za stranu (€):')
        ->SetCellValue('C'.$riadokP, '=SUM(AK'.($riadok_).':AK'.$riadokM.')');

      /*Orámovanie*/
      $spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':AK'.$riadokP)->applyFromArray($styleOArray);

      $riadokM+=2;
      $riadok+=2;
      $riadokP+=2;
      $riadok_=$riadok;
      $riadokP_=$riadokP;
      break;

    default:
      /*Orámovanie*/
      $spreadsheet->getActiveSheet()->getStyle('A'.$riadokM.':AK'.$riadokM)->applyFromArray($styleBArray);

      $riadokM+=2;
      $riadok+=2;
      $riadokP+=2;
  }
}

$sumar_t='=0';
$sumar_o='=0';
for($i=$riadok_;$i<=$riadokM;$i+=2){$sumar_t.='+AJ'.$i;}
for($i=$riadokP_;$i<=$riadok;$i+=2){$sumar_o.='+AJ'.$i;}
$spreadsheet->getActiveSheet()
  ->SetCellValue('A'.$riadok, 'D3:')
  ->SetCellValue('A'.$riadokP, 'Ost:')
  ->SetCellValue('B'.$riadok, $sumar_t)
  ->SetCellValue('B'.$riadokP, $sumar_o)
  ->SetCellValue('C'.$riadok, 'Spolu za stranu (€):')
  ->SetCellValue('C'.$riadokP, '=SUM(AK'.($riadok_).':AK'.$riadokM.')');

/*Orámovanie*/
$spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':AK'.$riadokP)->applyFromArray($styleOArray);

$riadokM+=2;
$riadok+=2;
$riadokP+=2;

for($i=1;$i<=$denK;$i++){$spreadsheet->getActiveSheet()->SetCellValue($pole_pismen[$i].$riadokP, '=SUM('.$pole_pismen[$i].'4:'.$pole_pismen[$i].$riadokM.')');}
$spreadsheet->getActiveSheet()
  ->SetCellValue('AJ'.$riadokP, '=SUM(AJ4:AJ'.$riadokM.')')
  ->SetCellValue('AK'.$riadokP, '=SUM(AK4:AK'.$riadokM.')');

/*Orámovanie*/
$spreadsheet->getActiveSheet()->getStyle('E'.$riadokP.':AK'.$riadokP)->applyFromArray($styleVArray);
$spreadsheet->getActiveSheet()->getStyle('A3:AK'.$riadokM)->applyFromArray($styleVArray);
$spreadsheet->getActiveSheet()->getStyle('E'.$riadokP.':AK'.$riadokP)->applyFromArray($styleOArray);
$spreadsheet->getActiveSheet()->getStyle('A3:AK'.$riadokM)->applyFromArray($styleOArray);

$spreadsheet->getActiveSheet()->getStyle('E'.$riadok.':AI'.$riadokP)->applyFromArray($styleFQArray);
$spreadsheet->getActiveSheet()->getRowDimension($riadokP)->setRowHeight(32);//Set the height of line 10 to 100pt

$riadokM+=3;
$riadok+=3;
$riadokP+=3;

$aj_blv=$ak_blv=0;

foreach($blv as $blv_riadok){
  $aj_blv.='+AJ'.$blv_riadok;
  $blv_riadok_plus=$blv_riadok+1;
  $ak_blv.='+AJ'.$blv_riadok_plus;
}

$spreadsheet->getActiveSheet()
  ->SetCellValue('AJ'.$riadok, '='.$aj_blv)
  ->SetCellValue('AJ'.$riadokP, '='.$ak_blv)
  ->SetCellValue('AK'.$riadokP, '=AJ'.$riadok.'*'.$ceny[3]['cena_trojka'].'+AJ'.$riadokP.'*'.$ceny[3]['cena_ostatne'])
  ->SetCellValue('A'.$riadokP, date('j.n.Y (H:i:s)', date(time())));

/* Nastavení fontů */
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);

/* Nastavení šířky sloupců */
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(8);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(7);
for($i=1;$i<=31;$i++){$spreadsheet->getActiveSheet()->getColumnDimension($pole_pismen[$i])->setWidth(3);}
$spreadsheet->getActiveSheet()->getColumnDimension('AJ')->setWidth(7);
$spreadsheet->getActiveSheet()->getColumnDimension('AK')->setWidth(8);

/* Zarovnání řádků */
$spreadsheet->getActiveSheet()->getStyle('A2:AK3')->applyFromArray($styleFCArray);
$spreadsheet->getActiveSheet()->getStyle('A4:B'.$riadok)->applyFromArray($styleFCArray);
$spreadsheet->getActiveSheet()->getStyle('D4:AK'.$riadokP)->applyFromArray($styleFCArray);

/* Nastavení jména listu */
$spreadsheet->getActiveSheet()->setTitle('Zrazkova_listina');

/* Nastavenie tlače */
$spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);

$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.35);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.5);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.35);

$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
$spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

/* Uložení souboru Excel */
$writer=new Xlsx($spreadsheet);
$writer->save('zrazkove_listiny/'.$rok.'-'.$mesiac.'.xlsx');
?>