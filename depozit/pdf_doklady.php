<?php
//Include the main TCPDF library
require_once('TCPDF-master/tcpdf.php');

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
    $this->Cell(0,15,'Doklady',1,0,'C',0,'',0,false,'M','M'); //Title
    $this->SetFont('freeserif','',12);

    $pokladna_pociatocny_stav=$pokladna_konecny_stav=0;
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
$pdf->SetTitle('Doklady');
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

$pdf->SetFont('freeserif','BI',12);//set font
$pdf->AddPage();//add a page

$w=array(35,55,25,25,25,25);

$db='default';
include('databaza.php');

$prijem=$vydaj=$zostatok=$pociatocny_stav=0.0;
$sql="SELECT pacienti_".$_GET['rok'].".pacient_cislo, karta_id, doklad_cislo, meno, priezvisko, datum_uctovania_skr, datum_uctovania, kod, prijem, vydaj, pokladna_pociatocny_stav, pokladna_konecny_stav, banka_pociatocny_stav, banka_konecny_stav
FROM karty_".$_GET['rok']."
INNER JOIN pacienti_".$_GET['rok']." ON pacienti_".$_GET['rok'].".pacient_cislo = karty_".$_GET['rok'].".pacient_cislo
INNER JOIN pokladna ON pokladna_datum_uctovania_skr = datum_uctovania_skr
WHERE datum_uctovania_skr = '".$_GET['rok'].'-'.$_GET['mesiac']."'
ORDER BY length(doklad_cislo), doklad_cislo, karta_id";

$run=mysqli_query($dbcon,$sql) or die('Chyba: '.mysqli_error($dbcon).'<hr />\nQuery: $sql');
$doklad_cislo=$pocet=$dat=$riadok=0;
$pokladna=$banka=$pokladna_konecny_stav=$banka_konecny_stav=$rozdiel_pokladna=$rozdiel_banka=$spolu_prijem_banka=$spolu_prijem_pokladna=$spolu_vydaj_banka=$spolu_vydaj_pokladna=0.0;

if(mysqli_num_rows($run)>0){
  while($row=mysqli_fetch_array($run)){
    $datum_uctovania=date('j.n.Y', strtotime($row['datum_uctovania']));
    $prijem=$row['prijem'];
    $vydaj=$row['vydaj'];

    if($riadok==0){
      $pokladna=$pokladna_pociatocny_stav=$row['pokladna_pociatocny_stav'];
      $banka=$banka_pociatocny_stav=$row['banka_pociatocny_stav'];
    }

    $pokladna_konecny_stav=$row['pokladna_konecny_stav'];
    $banka_konecny_stav=$row['banka_konecny_stav'];

    if($doklad_cislo!=$row['doklad_cislo']){
      $doklad_cislo=$row['doklad_cislo'];

      if($riadok>0){
        $pdf->SetFont('freeserif','B',12);//set font
        $pdf->MultiCell(/*w*/$w[0]+$w[1],/*h*/5,/*txt*/'Pokladňa:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->SetFont('freeserif','',12);//set font
        $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/number_format($spolu_prijem_pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($spolu_vydaj_pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'',/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

        $pdf->SetFont('freeserif','B',12);//set font
        $pdf->MultiCell(/*w*/$w[0]+$w[1],/*h*/5,/*txt*/'Banka:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->SetFont('freeserif','',12);//set font
        $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/number_format($spolu_prijem_banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($spolu_vydaj_banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'',/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
        $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

        $spolu_prijem_banka=$spolu_prijem_pokladna=$spolu_vydaj_banka=$spolu_vydaj_pokladna=0.0;
      }

      $pdf->Ln(5); //Line break
  //  }
      $pdf->SetFont('freeserif','B',12);//set font
      $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/'Číslo dokladu:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/$doklad_cislo,/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'Kód:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[3]+$w[4]+$w[5],/*h*/5,/*txt*/$row['kod'].' - '.$_GET['kod_'.$row['kod']],/*border*/0,/*align*/'L',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

      $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/'Dátum účtovania:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/date('j.n.Y', strtotime($row['datum_uctovania'])),/*border*/0,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'Pokladňa:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($pokladna,2,',',''),/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'Banka:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($banka,2,',',''),/*border*/0,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

    //$pdf->Ln(5); //Line break

      $pdf->SetFont('freeserif','',12);
      $pdf->SetFillColor(215, 235, 255);
    //$pdf->Hlavicka();

      $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/' '."\n".'Číslo pacienta',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/' '."\n".'Meno',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/'Príjem'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/'Výdaj'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'Pokladňa'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
      $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'Banka'."\n".'(€)',/*border*/1,/*align*/'C',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    }

    switch(strcspn($doklad_cislo,'B')){
      case 0:
        $banka+=round(($prijem-$vydaj)*100)/100;
        $spolu_prijem_banka+=$prijem;
        $spolu_vydaj_banka+=$vydaj;
        break;
      default:
        $pokladna+=round(($prijem-$vydaj)*100)/100;
        $spolu_prijem_pokladna+=$prijem;
        $spolu_vydaj_pokladna+=$vydaj;
    }

    $pdf->MultiCell(/*w*/$w[0],/*h*/5,/*txt*/$row['pacient_cislo'],/*border*/1,/*align*/'C',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[1],/*h*/5,/*txt*/$row['priezvisko'].' '.$row['meno'],/*border*/1,/*align*/'L',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/number_format($prijem,2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($vydaj,2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
    $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/0,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

    $riadok++;
  }

  $pdf->SetFont('freeserif','B',12);//set font
  $pdf->MultiCell(/*w*/$w[0]+$w[1],/*h*/5,/*txt*/'Pokladňa:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->SetFont('freeserif','',12);//set font
  $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/number_format($spolu_prijem_pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($spolu_vydaj_pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/number_format($pokladna,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/'',/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);

  $pdf->SetFont('freeserif','B',12);//set font
  $pdf->MultiCell(/*w*/$w[0]+$w[1],/*h*/5,/*txt*/'Banka:',/*border*/0,/*align*/'R',/*fill*/0,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->SetFont('freeserif','',12);//set font
  $pdf->MultiCell(/*w*/$w[2],/*h*/5,/*txt*/number_format($spolu_prijem_banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[3],/*h*/5,/*txt*/number_format($spolu_vydaj_banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[4],/*h*/5,/*txt*/'',/*border*/1,/*align*/'R',/*fill*/1,/*ln*/0,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
  $pdf->MultiCell(/*w*/$w[5],/*h*/5,/*txt*/number_format($banka,2,',',''),/*border*/1,/*align*/'R',/*fill*/1,/*ln*/1,/*x*/'',/*y*/'',/*reseth*/true,/*stretch*/0);
}
$pdf->SetFont('freesans','',8);//Set font
$pdf->Cell(10,5,date('j.n.Y (H:i:s)',date(time())),0,1);

//---------------------------------------------------------

$pdf->Output('pohyby_pokladne_a_banky'.$_GET['mesiac'].'-'.$_GET['rok'].'.pdf','I');//Close and output PDF document
?>