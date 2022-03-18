<?php
$db='default';
include('databaza.php');

$datum=$_GET['datum'];
$den_v_tyzdni=array('1'=>'Pondelok','2'=>'Utorok','3'=>'Streda','4'=>'Štvrtok','5'=>'Piatok','6'=>'Sobota','7'=>'Nedeľa');
$cislo=date_create($datum);
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
      }

      if(!empty($row['obed_2'])){
        if(!empty($row['obed_1'])){$obed.=', ';}
        $obed.=$row['obed_2'].' '.$row['obed_2_pocet'].'x';
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
        case 1: array_push($obedy_amb,array('meno'=>$meno,'obed'=>$obed)); break;
        case 2: array_push($obedy_amo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 3: array_push($obedy_azo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 4: array_push($obedy_fro,array('meno'=>$meno,'obed'=>$obed)); break;
        case 5: array_push($obedy_gpo,array('meno'=>$meno,'obed'=>$obed)); break;
        case 6: array_push($obedy_gpo2,array('meno'=>$meno,'obed'=>$obed)); break;
        case 7: array_push($obedy_hts,array('meno'=>$meno,'obed'=>$obed)); break;
        case 8: array_push($obedy_kurici,array('meno'=>$meno,'obed'=>$obed)); break;
        case 9: array_push($obedy_lps,array('meno'=>$meno,'obed'=>$obed)); break;//Strav. prev.
        case 10: array_push($obedy_odlm,array('meno'=>$meno,'obed'=>$obed)); break;
        case 11: array_push($obedy_odlz,array('meno'=>$meno,'obed'=>$obed)); break;
        case 12: array_push($obedy_okb,array('meno'=>$meno,'obed'=>$obed)); break;
        case 13: array_push($obedy_opldz,array('meno'=>$meno,'obed'=>$obed)); break;
        case 15: array_push($obedy_pracovna,array('meno'=>$meno,'obed'=>$obed)); break;
        case 16: array_push($obedy_udrzba,array('meno'=>$meno,'obed'=>$obed)); break;
        case 17: array_push($obedy_uz,array('meno'=>$meno,'obed'=>$obed)); break;//Ustajnenie zv.
        case 18: array_push($obedy_vratnica,array('meno'=>$meno,'obed'=>$obed)); break;
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

//include the file that loads the PhpSpreadsheet classes
require '../PhpSpreadsheet/vendor/autoload.php';

//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//object of the Spreadsheet class to create the excel data
$spreadsheet=new Spreadsheet();
//$sheet=$spreadsheet->getActiveSheet();

/* Nastavení metadat - autor, název, popis, ... */
$spreadsheet->getProperties()
  ->setCreator('Wisdom')
  ->setLastModifiedBy('Wisdom')
  ->setTitle('Zoznam_na_stravu')
  ->setSubject('Office 2010, Open XML a PhpSpreadsheet')
  ->setDescription('Tvorba Excel dokumentu z PHP aplikace.')
  ->setKeywords('Zoznam_na_stravu, PhpSpreadsheet')
  ->setCategory('Zoznam_na_stravu');

/* Nastavení listu, který bude aktivní po otevření souboru */
$spreadsheet->setActiveSheetIndex(0);
/* Vložení hodnot */
$spreadsheet->getActiveSheet()
  ->SetCellValue('A1', 'Zoznam na obed a večeru na deň:')
  ->SetCellValue('D1', $den_v_tyzdni[$cislo])
  ->SetCellValue('H1', 'Dátum:')
  ->SetCellValue('I1', date('j.n.Y', strtotime($datum)));

include('format_xlsx.php');

function zoznam_na_stravu($array_key, $i, $foreach, $stlpec_1, $stlpec_2, $riadok){
  global $oddelenie;
  global $spreadsheet, $styleOArray, $styleBArray;
  if(array_key_exists($array_key, $oddelenie)){$spreadsheet->getActiveSheet()->SetCellValue($stlpec_1.$i, $oddelenie[$array_key]);}
  else{$spreadsheet->getActiveSheet()->SetCellValue($stlpec_1.$i, $array_key);}
  $i++;
  $spreadsheet->getActiveSheet()->getStyle($stlpec_1.$i.':'.$stlpec_2.$riadok)->applyFromArray($styleOArray);/*Orámovanie*/
  $spreadsheet->getActiveSheet()
    ->SetCellValue($stlpec_1.$i, 'Meno')
    ->SetCellValue($stlpec_2.$i, 'Diéta č.,');
  $i++;
  $spreadsheet->getActiveSheet()->SetCellValue($stlpec_2.$i, 'Počet');
  $spreadsheet->getActiveSheet()->getStyle($stlpec_1.$i.':'.$stlpec_2.$i)->applyFromArray($styleBArray);/*Orámovanie*/
  $i++;
  foreach($foreach as $pc=>$obed){
    $spreadsheet->getActiveSheet()
      ->SetCellValue($stlpec_1.$i, current($obed))
      ->SetCellValue($stlpec_2.$i, next($obed));
    $i++;
  }
}

zoznam_na_stravu(10, 3, $obedy_odlm, 'A', 'B', 26);
zoznam_na_stravu(11, 3, $obedy_odlz, 'C', 'D', 26);
zoznam_na_stravu(6, 3, $obedy_gpo2, 'E', 'F', 26);
zoznam_na_stravu(4, 3, $obedy_fro, 'G', 'H', 26);
zoznam_na_stravu(7, 3, $obedy_hts, 'I', 'J', 26);

zoznam_na_stravu(1, 28, $obedy_amb, 'A', 'B', 41);
zoznam_na_stravu(12, 28, $obedy_okb, 'C', 'D', 41);
zoznam_na_stravu(15, 28, $obedy_pracovna, 'E', 'F', 41);
zoznam_na_stravu(16, 28, $obedy_udrzba, 'G', 'H', 41);
zoznam_na_stravu('Večere:', 28, $vecere, 'I', 'J', 41);

zoznam_na_stravu(2, 43, $obedy_amo, 'A', 'B', 73);
zoznam_na_stravu(3, 43, $obedy_azo, 'C', 'D', 73);
zoznam_na_stravu(5, 43, $obedy_gpo, 'E', 'F', 73);
zoznam_na_stravu(13, 43, $obedy_opldz, 'G', 'H', 73);
zoznam_na_stravu(9, 43, $obedy_lps, 'I', 'J', 73);

zoznam_na_stravu(8, 75, $obedy_kurici, 'A', 'B', 81);
zoznam_na_stravu(17, 75, $obedy_uz, 'C', 'D', 81);
zoznam_na_stravu(18, 75, $obedy_vratnica, 'E', 'F', 81);
zoznam_na_stravu('Iné', 75, $obedy_ine, 'G', 'H', 81);

$spreadsheet->getActiveSheet()
  ->SetCellValue('I74', 'Počty diét')
  ->SetCellValue('I75', 'Obedy:')
  ->SetCellValue('I76', '3   = '.$triobed)
  ->SetCellValue('I77', '9   = '.$devobed)
  ->SetCellValue('I78', '4   = '.$styobed)
  ->SetCellValue('I79', '13  = '.$trnobed)
  ->SetCellValue('I80', 'bzl = '.$bzlobed)
  ->SetCellValue('I81', '*   = '.$hviobed)
  ->SetCellValue('I82', '9-S = '.$desobed);

$spreadsheet->getActiveSheet()
  ->SetCellValue('J75', 'Večere:')
  ->SetCellValue('J76', '3   = '.$trivecera)
  ->SetCellValue('J77', '9   = '.$devvecera)
  ->SetCellValue('J78', '4   = '.$styvecera)
  ->SetCellValue('J79', '13  = '.$trnvecera)
  ->SetCellValue('J80', 'bzl = '.$bzlvecera)
  ->SetCellValue('J81', '*   = '.$hvivecera)
  ->SetCellValue('J82', '9-S = '.$desvecera);

$spreadsheet->getActiveSheet()->getStyle('I75:J82')->applyFromArray($styleOArray);/*Orámovanie*/
$spreadsheet->getActiveSheet()->SetCellValue('A82', date('j.n.Y (H:i:s)', date(time())));

/* Nastavení fontů */
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

$spreadsheet->getActiveSheet()->getStyle('A3:J5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A28:J30')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A43:J45')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A75:H77')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('I74:J75')->getFont()->setBold(true);

/* Nastavení šířky sloupců */
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);

/* Zarovnání prvních řádků */
//$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$spreadsheet->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$spreadsheet->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

/* Nastavení jména listu */
$spreadsheet->getActiveSheet()->setTitle('Zoznam_na_stravu');

/* Nastavenie tlače */
$spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);

$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.5);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.25);

$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
$spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

/* Uložení souboru Excel */
$writer=new Xlsx($spreadsheet);
$writer->save('Zoznamy_na_stravu/'.$datum.'.xlsx');
?>