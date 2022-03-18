<?php
//Include the main TCPDF library
require_once('TCPDF-master/tcpdf.php');

$obdobie=$_GET['obdobie'];
$pacient_cislo=$_GET['pacient_cislo'];
$meno=$_GET['meno'];

//Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF{
  //Page header
  public function Header(){
    $image_file='pnh.jpg';//Logo
    $this->Image($image_file,12,8,0,'','','','C',false,0,'',false,false,0,false,false,false);

    $this->SetFont('freesans','B',8);//Set font
    $this->Cell(16.5,0,'                     Psychiatrická nemocnica',0,0,'L'); //Title
    $this->Cell(20,10,'Hronovce',0,0,'L'); //Title

    $this->Ln(5); //Line break
    $this->SetFont('freesans','B',14);//Set font
    $this->Cell(0,15,'Prehľad účtu',1,0,'C',0,'',0,false,'M','M'); //Title
    $this->SetFont('freeserif','',12);
//    $pokladna_pociatocny_stav=$pokladna_konecny_stav=0;
  }

  //Page footer
  public function Footer(){
    $this->SetY(-15);//Position at 15 mm from bottom -- -21
    $this->SetFont('freesans','I',8);//Set font
    $this->Cell(0,10,'Strana '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),0,false,'C',0,'',0,false,'T','M');//Page number
  }
}

//create new PDF document
$pdf=new MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);

//set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Wisdom');
$pdf->SetTitle('Prehľad účtu');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF,PDF,Wisdom');

//set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH,PDF_HEADER_TITLE,PDF_HEADER_STRING);

//set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));

//set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(/*PDF_MARGIN_LEFT*/10,/*PDF_MARGIN_TOP*/25,/*PDF_MARGIN_RIGHT*/10);
$pdf->SetHeaderMargin(/*PDF_MARGIN_HEADER*/10);
$pdf->SetFooterMargin(/*PDF_MARGIN_FOOTER*/15);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE,/*PDF_MARGIN_BOTTOM*/15);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings (optional)
if (@file_exists('TCPDF-master/config/lang/ces.php')){
  require_once('TCPDF-master/config/lang/ces.php');
  $pdf->setLanguageArray($l);
}

//---------------------------------------------------------

$pokladna_konecny_stav=$banka_konecny_stav='';

$pdf->SetFont('freeserif','BI',12);//set font
$pdf->AddPage();//add a page

$w=array(25,30,60,25,25,25);

$pdf->SetFont('freeserif','',12);
$pdf->SetFillColor(215, 235, 255);
//$pdf->Hlavicka();

$db='default';
include('databaza.php');

//$pacient='';
//$pocet='149';
$pc=$riadok=0;
$pacienti_zostatok=$prijem=$vydaj=$zostatok=$pociatocny_stav=0.00;

$sql="SELECT karta_id, pacienti_".$_GET['obdobie'].".pacient_cislo, pokladna_konecny_stav, banka_konecny_stav, meno, priezvisko, rodne_cislo, datum_uctovania_skr, datum_uctovania, doklad_cislo, kod, pociatocny_stav, prijem, vydaj, karta_poznamka, zostatok
FROM karty_".$_GET['obdobie']."
INNER JOIN pacienti_".$_GET['obdobie']." ON pacienti_".$_GET['obdobie'].".pacient_cislo = karty_".$_GET['obdobie'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = karty_".$_GET['obdobie'].".datum_uctovania_skr
WHERE pacienti_".$_GET['obdobie'].".pacient_cislo='$pacient_cislo'";
//ORDER BY datum_uctovania";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

while($row=mysqli_fetch_array($run)){
  $pc++;
  $riadok++;
  $karta_id=$row['karta_id'];
  $date=date_create($row['datum_uctovania']);
  date_sub($date,date_interval_create_from_date_string('0 days'));
  $den=$date;
  if($riadok==1){$zostatok=$pociatocny_stav=$row['pociatocny_stav'];}
  $prijem+=$row['prijem'];
  $vydaj+=$row['vydaj'];
  $pacienti_zostatok=$row['zostatok'];

  $zostatok+=$row['prijem']-$row['vydaj'];

  if($pc==1){
    $pdf->SetFont('freesans','B',13);//Set font
    $pdf->MultiCell(/*w*/$w[0],/*h*/0,/*txt*/$pacient_cislo,/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[1]+$w[2],/*h*/5,/*txt*/$meno,/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
//    $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'',/*border*/0,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[3]+$w[4]+$w[5],/*h*/5,/*txt*/$row['rodne_cislo'],/*border*/0,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

    $pdf->SetFont('freeserif','',12);
    $pdf->MultiCell(/*w*/$w[0],/*h*/0,/*txt*/'',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[1]+$w[2],/*h*/5,/*txt*/'',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
//    $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'',/*border*/0,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[3]+$w[4],/*h*/5,/*txt*/'Počiatočný stav:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($pociatocny_stav,2,',','').'€',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

    $pdf->Ln(2); //Line break

    $pdf->MultiCell(/*w*/$w[0],/*h*/0,/*txt*/'Dátum'."\n".'účtovania',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/'Číslo'."\n".'dokladu',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/' '."\n".'Kód',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/'Príjem'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'Výdaj'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'Zostatok'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  }

  $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/date_format($date, 'j.n.Y'),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/$row['doklad_cislo'],/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/$row['kod'].' - '.$_GET['kod_'.$row['kod']],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($row['prijem'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($row['vydaj'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($zostatok,2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

  if($row['karta_poznamka']!=''){
  $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/'Poznámka:',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[1]+$w[2]+$w[3]+$w[4]+$w[5],/*h*/5,/*txt*/$row['karta_poznamka'],/*border*/0,/*align*/'L',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->Ln(2); //Line break
 }
}

if($pc==0){
  $sql="SELECT pacient_cislo, meno, priezvisko, pociatocny_stav, zostatok FROM pacienti_".$obdobie." WHERE pacient_cislo='$pacient_cislo';";

  $run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');

  while($row=mysqli_fetch_array($run)){
    $zostatok=$pociatocny_stav=$row['pociatocny_stav'];
    $pacienti_zostatok=$row['zostatok'];

    //(round($zostatok*100)/100)
    $pdf->MultiCell(/*w*/$w[0]+$w[1]+$w[2],/*h*/5,/*txt*/'',/*border*/0,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/0,/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/0,/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($zostatok,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  }
}

$pdf->MultiCell(/*w*/$w[0]+$w[1]+$w[2],/*h*/5,/*txt*/'',/*border*/0,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($prijem,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($vydaj,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($pacienti_zostatok,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

$pdf->SetFont('freesans','',8);//Set font
$pdf->Cell(10,5,date('j.n.Y (H:i:s)',date(time())),0,1);

//---------------------------------------------------------

$pdf->Output('prehlad_uctu_'.$pacient_cislo.'.pdf','I');//Close and output PDF document
?>