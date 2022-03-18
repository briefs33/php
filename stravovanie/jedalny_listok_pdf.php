<?php
$den0=date('Y-m-d', $startdate=$_GET['datum']);
$pon=date('d.m.Y', $startdate);

$den1=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$uto=date('d.m.Y', $startdate);

$den2=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$str=date('d.m.Y', $startdate);

$den3=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$stv=date('d.m.Y', $startdate);

$den4=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$pia=date('d.m.Y', $startdate);

$den5=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$sob=date('d.m.Y', $startdate);

$den6=date('Y-m-d', $startdate=strtotime('+1 day', $startdate));
$ned=date('d.m.Y', $startdate);

$w=array(6,48.5,48.5,48.5,48.5);
$h=4.65;

$dni=array(
  array('Pondelok', 'pondelok', $pon),
  array('Utorok', 'utorok', $uto),
  array('Streda', 'streda', $str),
  array('Štvrtok', 'stvrtok', $stv),
  array('Piatok', 'piatok', $pia),
  array('Sobota', 'sobota', $sob),
  array('Nedeľa', 'nedela', $ned),
);

//Include the main TCPDF library
require_once('../depozit/TCPDF-master/tcpdf.php');

//Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF{

  //Page header
  public function Header(){
    global $pon, $ned, $h;
    $this->SetFont('freesans','B',10.5);

    $this->MultiCell(/*w*/6+48.5,/*h*/$h,/*txt*/'Predpokladaný jedálny lístok',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $this->MultiCell(/*w*/48.5,/*h*/$h,/*txt*/'od: '.$pon,/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $this->MultiCell(/*w*/48.5,/*h*/$h,/*txt*/'do: '.$ned,/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
	$this->MultiCell(/*w*/48.5,/*h*/$h,/*txt*/date('W', $_GET['datum']).'. týždeň',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  }

  //Page footer
  public function Footer(){
  }
}

//create new PDF document
$pdf=new MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);

//set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Wisdom');
$pdf->SetTitle('Predpokladaný jedálny lístok');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF,PDF,Wisdom');

//set margins
$pdf->SetMargins(/*PDF_MARGIN_LEFT*/5,/*PDF_MARGIN_TOP*/15,/*PDF_MARGIN_RIGHT*/5);
$pdf->SetHeaderMargin(/*PDF_MARGIN_HEADER*/10);
$pdf->SetFooterMargin(/*PDF_MARGIN_FOOTER*/10);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE,/*PDF_MARGIN_BOTTOM*/10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings (optional)
if (@file_exists('../TCPDF-master/config/lang/ces.php')){
  require_once('../TCPDF-master/config/lang/ces.php');
  $pdf->setLanguageArray($l);
}

//---------------------------------------------------------

//$pdf->SetFont('freeserif','BI',12);//set font
$pdf->AddPage();//add a page

//$pdf->SetFont('freeserif','',11);
$pdf->SetFont('freesans','',10);
$pdf->SetFillColor(215, 255, 215);
$farba=array(0,0,0);

//Where T,B,R, and L are Top, Bottom, Right and Left respectively.
$Hrube_TBL = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
);

$Hrube_TB_Bodky_L = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$Hrube_TRB_Bodky_L = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'R' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$Hrube_TL_Tenke_B = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
);

$Hrube_BL_Tenke_T = array(
   'T' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
);

$Hrube_T_Tenke_B_Bodky_L = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$Hrube_B_Tenke_T_Bodky_L = array(
   'T' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$Hrube_TR_Tenke_B_Bodky_L = array(
   'T' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'R' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$Hrube_BR_Tenke_T_Bodky_L = array(
   'T' => array('width' => 1/4, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'R' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'B' => array('width' => 1/2, 'color' => $farba, 'dash' => 0, 'cap' => 'square'),
   'L' => array('width' => 1/4, 'color' => $farba, 'dash' => 2, 'cap' => 'butt'),
);

$pdf->MultiCell(/*w*/$w[0],/*h*/$h,/*txt*/'',/*border*/0,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[1],/*h*/$h,/*txt*/'Diéta č. 3',/*border*/$Hrube_TBL,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[2],/*h*/$h,/*txt*/'Diéta č. 9',/*border*/$Hrube_TB_Bodky_L,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[3],/*h*/$h,/*txt*/'Diéta č. 4',/*border*/$Hrube_TB_Bodky_L,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
$pdf->MultiCell(/*w*/$w[4],/*h*/$h,/*txt*/'Diéta č. 13',/*border*/$Hrube_TRB_Bodky_L,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

$db='default';
include('databaza.php');

$sql="SELECT datum, jl_obed_3, jl_obed_9, jl_obed_4, jl_obed_13, jl_vecera_3, jl_vecera_9, jl_vecera_4, jl_vecera_13
FROM jedalne_listky
WHERE datum
BETWEEN '$den0' AND '$den6'
ORDER BY datum";
$run=mysqli_query($dbcon, $sql);

$x=0;
while($row=mysqli_fetch_array($run)){
  $pdf->MultiCell(/*w*/$w[0],/*h*/$h,/*txt*/'',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/5,/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[1],/*h*/$h,/*txt*/$dni[$x][0],/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[2],/*h*/$h,/*txt*/$dni[$x][2],/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3]+$w[4],/*h*/$h,/*txt*/'',/*border*/0,/*align*/'L',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

  $pdf->MultiCell(/*w*/$w[0],/*h*/4*$h,/*txt*/'',/*border*/$Hrube_TL_Tenke_B,/*align*/'L',/*fill*/1,/*ln*/0,/*x*/5,/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[1],/*h*/4*$h,/*txt*/$row['jl_obed_3'],/*border*/$Hrube_T_Tenke_B_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[2],/*h*/4*$h,/*txt*/$row['jl_obed_9'],/*border*/$Hrube_T_Tenke_B_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/4*$h,/*txt*/$row['jl_obed_4'],/*border*/$Hrube_T_Tenke_B_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/4*$h,/*txt*/$row['jl_obed_13'],/*border*/$Hrube_TR_Tenke_B_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

/**/
//$pdf->SetFont('freeserif','',9);
$pdf->StartTransform();
$pdf->Rotate(90);
$pdf->Cell(/*w*/4*$h,/*h*/$w[0],'Obed',0,0,'C');
$pdf->StopTransform();
//$pdf->SetFont('freeserif','',11);
/**/

  $pdf->MultiCell(/*w*/$w[0],/*h*/3*$h,/*txt*/'',/*border*/$Hrube_BL_Tenke_T,/*align*/'L',/*fill*/1,/*ln*/0,/*x*/5,/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[1],/*h*/3*$h,/*txt*/$row['jl_vecera_3'],/*border*/$Hrube_B_Tenke_T_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[2],/*h*/3*$h,/*txt*/$row['jl_vecera_9'],/*border*/$Hrube_B_Tenke_T_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/3*$h,/*txt*/$row['jl_vecera_4'],/*border*/$Hrube_B_Tenke_T_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/3*$h,/*txt*/$row['jl_vecera_13'],/*border*/$Hrube_BR_Tenke_T_Bodky_L,/*align*/'L',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

//$pdf->SetFont('freeserif','',9);
//$pdf->SetFont('freesans','',11);
$pdf->StartTransform();
$pdf->Rotate(90);
$pdf->Cell(/*w*/3*$h,/*h*/$w[0],'Večera',0,0,'C');
$pdf->StopTransform();
//$pdf->SetFont('freeserif','',11);
//$pdf->SetFont('freesans','',11);

  $x++;
}

$pdf->SetFont('freesans','',6);//Set font
$pdf->Cell(/*w*/185,/*h*/0,date('j.n.Y (H:i:s)',date(time())),0,0,'R');

//---------------------------------------------------------

$pdf->Output('predpokladany_jedalny_listok_'.date('W', $_GET['datum']).'t.pdf','I');//Close and output PDF document
?>