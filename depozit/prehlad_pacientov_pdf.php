<?php
//Include the main TCPDF library
require_once('../TCPDF-master/tcpdf.php');

//Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF{
  //Page header
  public function Header(){
    $image_file='pnh.png';//Logo
    $this->Image($image_file,12,8,0,'','PNG','','C',false,0,'',false,false,0,false,false,false);

    $this->SetFont('freesans','B',8);//Set font
    $this->Cell(16.5,0,'                     Psychiatrická nemocnica',0,0,'L'); //Title
    $this->Cell(20,10,'Hronovce',0,0,'L'); //Title

    $this->Ln(5); //Line break
    $this->SetFont('freesans','B',14);//Set font
    $this->Cell(0,15,'Prehľad pacientov',1,0,'C',0,'',0,false,'M','M'); //Title
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
$pdf->SetTitle('Prehľad pacientov');
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
if (@file_exists('../TCPDF-master/config/lang/ces.php')){
  require_once('../TCPDF-master/config/lang/ces.php');
  $pdf->setLanguageArray($l);
}

//---------------------------------------------------------

$pdf->SetFont('freeserif','BI',12);//set font
$pdf->AddPage();//add a page

$w=array(20,55,30,25,25,35);

$pdf->SetFont('freeserif','',12);
$pdf->SetFillColor(215, 235, 255);
//$pdf->Hlavicka();

$pdf->MultiCell(/*w*/$w[0],/*h*/0,/*txt*/'Číslo'."\n".'pacienta',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/"\n".'Meno',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'Rodné'."\n".'číslo',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/'Počiatočný'."\n".'stav',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'Zostatok'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'Poznámka'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

$db='default';
include('databaza.php');

$stav=$_GET['stav'];

if(isset($_GET['stav'])){
  $stav=$_GET['stav'];
}
else{
  $stav=1;
}

$i=0;

$db='default';
include('databaza.php');
$sql="SELECT pacient_cislo, meno, priezvisko, rodne_cislo, pociatocny_stav, zostatok, poznamka, stav
FROM pacienti_".$_GET['obdobie']."
WHERE stav='$stav'
ORDER BY pacient_cislo";

$pociatocny_stav=$zostatok=0.00;
$prvy=0;

$run=mysqli_query($dbcon,$sql);
while($row=mysqli_fetch_array($run)){

  switch($row['pacient_cislo']){
    case '9999':
    case '8888':

      if($prvy==0){
        $pdf->MultiCell(/*w*/$w[0]+$w[1]+$w[2],/*h*/5,/*txt*/'',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($pociatocny_stav,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($zostatok,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

        $pdf->Ln(5); //Line break

        $pdf->MultiCell(/*w*/$w[0],/*h*/0,/*txt*/'Číslo'."\n".'pacienta',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/"\n".'Meno',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'Rodné'."\n".'číslo',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/'Počiatočný'."\n".'stav',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'Zostatok'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/"\n".'Poznámka',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

        $prvy++;
      }

      $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/$row['pacient_cislo'],/*border*/1,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/$row['priezvisko'].' '.$row['meno'],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/$row['rodne_cislo'],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($row['pociatocny_stav'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($row['zostatok'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/$row['poznamka'],/*border*/1,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

      break;
    default:
$pociatocny_stav+=$row['pociatocny_stav'];
$zostatok+=$row['zostatok'];

      $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/$row['pacient_cislo'],/*border*/1,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/$row['priezvisko'].' '.$row['meno'],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/$row['rodne_cislo'],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($row['pociatocny_stav'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($row['zostatok'],2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/$row['poznamka'],/*border*/1,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  }
}

if($prvy==0){
  $pdf->MultiCell(/*w*/$w[0]+$w[1]+$w[2],/*h*/5,/*txt*/'',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($pociatocny_stav,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($zostatok,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
}

if(isset($_GET['stav'])){
  if($_GET['stav']==0){$stav='neaktivny';}
  else if($_GET['stav']==1){$stav='aktivny';}
  else{$stav='Všetci';}
}

$pdf->SetFont('freesans','',8);//Set font
$pdf->Cell(10,5,date('j.n.Y (H:i:s)',date(time())),0,1);

//---------------------------------------------------------

$pdf->Output('prehlad_pacientov_'.$stav.'.pdf','I');//Close and output PDF document
?>