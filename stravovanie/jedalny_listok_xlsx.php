<?php
//include the file that loads the PhpSpreadsheet classes
require '../PhpSpreadsheet/vendor/autoload.php';

//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//object of the Spreadsheet class to create the excel data
$spreadsheet=new Spreadsheet();
//$sheet=$spreadsheet->getActiveSheet();

$den0=date('Y-m-d', $startdate=$_GET['datum']);
$den1=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den2=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den3=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den4=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den5=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$den6=date('Y-m-d', strtotime('+1 day', $startdate));

$tyzden=date('W', $startdate);

$dni=array(
  array('Pondelok', $den0),
  array('Utorok', $den1),
  array('Streda', $den2),
  array('Štvrtok', $den3),
  array('Piatok', $den4),
  array('Sobota', $den5),
  array('Nedeľa', $den6),
);

/* Nastavení metadat - autor, název, popis, ... */
$spreadsheet->getProperties()
  ->setCreator('Wisdom')
  ->setLastModifiedBy('Wisdom')
  ->setTitle('Jedalny_listok')
  ->setSubject('Office 2010, Open XML a PhpSpreadsheet')
  ->setDescription('Tvorba Excel dokumentu z PHP aplikace.')
  ->setKeywords('Jedalny_listok, PhpSpreadsheet')
  ->setCategory('Jedalny_listok');

/* Nastavení listu, který bude aktivní po otevření souboru */
$spreadsheet->setActiveSheetIndex(0);
/* Vložení hodnot */
$spreadsheet->getActiveSheet()
  ->SetCellValue('A1', 'Predpokladaný jedálny lístok')
  ->SetCellValue('D1', 'od: '.date('j.n.Y', strtotime($den0)))
  ->SetCellValue('E1', 'do: '.date('j.n.Y', strtotime($den6)))
  ->SetCellValue('B2', 'Diéta č. 3')
  ->SetCellValue('C2', 'Diéta č. 9')
  ->SetCellValue('D2', 'Diéta č. 4')
  ->SetCellValue('E2', 'Diéta č. 13');

include('format_xlsx.php');

$spreadsheet->getActiveSheet()->getStyle('B2:E2')->applyFromArray($styleVArray);/*Orámovanie*/
$spreadsheet->getActiveSheet()->getStyle('B2:E2')->applyFromArray($styleOArray);/*Orámovanie*/

$db='default';
include('databaza.php');

$sql="SELECT datum, jl_obed_3, jl_obed_9, jl_obed_4, jl_obed_13, jl_vecera_3, jl_vecera_9, jl_vecera_4, jl_vecera_13
FROM jedalne_listky
WHERE datum
BETWEEN '$den0' AND '$den6'
ORDER BY datum";
$run=mysqli_query($dbcon, $sql);

if($run->num_rows>0){
  $x=0;
  $riadokm=3;
  $riadok=4;
  $riadokp=5;
  while($row=mysqli_fetch_array($run)){
  $spreadsheet->getActiveSheet()
    ->SetCellValue('B'.$riadokm, $dni[$x][0])
    ->SetCellValue('C'.$riadokm, date('j.n.Y', strtotime($row['datum'])));
  
  $spreadsheet->getActiveSheet()
    ->SetCellValue('A'.$riadok, 'Obed:')
    ->SetCellValue('B'.$riadok, $row['jl_obed_3'])
    ->SetCellValue('C'.$riadok, $row['jl_obed_9'])
    ->SetCellValue('D'.$riadok, $row['jl_obed_4'])
    ->SetCellValue('E'.$riadok, $row['jl_obed_13']);
  $spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':E'.$riadok)->applyFromArray($styleBArray);/*Orámovanie*/
  $spreadsheet->getActiveSheet()->getRowDimension($riadok)->setRowHeight(62);

  $spreadsheet->getActiveSheet()
    ->SetCellValue('A'.$riadokp, 'Večera:')
    ->SetCellValue('B'.$riadokp, $row['jl_vecera_3'])
    ->SetCellValue('C'.$riadokp, $row['jl_vecera_9'])
    ->SetCellValue('D'.$riadokp, $row['jl_vecera_4'])
    ->SetCellValue('E'.$riadokp, $row['jl_vecera_13']);
  $spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':E'.$riadokp)->applyFromArray($styleVArray);/*Orámovanie*/
  $spreadsheet->getActiveSheet()->getStyle('A'.$riadok.':E'.$riadokp)->applyFromArray($styleOArray);/*Orámovanie*/
  $spreadsheet->getActiveSheet()->getRowDimension($riadokp)->setRowHeight(47);
  $riadokm+=3;
  $riadok+=3;
  $riadokp+=3;
  $x++;
  }
}

/* Nastavení fontů */
$spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A2:A23')->getFont()->setBold(true);

/* Nastavení šířky sloupců */
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(24);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(24);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(24);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(24);

/* Zarovnání řádků */
$spreadsheet->getActiveSheet()->getStyle('A2:A23')->applyFromArray($styleFOArray);
$spreadsheet->getActiveSheet()->getStyle('B4:E23')->applyFromArray($styleFTArray);

/* Nastavenie tlače */
$spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);

$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.5);

$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
$spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(true);

/* Nastavení jména listu */
$spreadsheet->getActiveSheet()->setTitle('Jedalny_listok');

/* Uložení souboru Excel */
$writer=new Xlsx($spreadsheet);
$writer->save('jedalne_listky/'.$tyzden.'.xlsx');
?>