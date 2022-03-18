<?php
/**
**Border style**
BORDER_NONE             - �iadna ciara
BORDER_DASHDOT          - tenk� bodko-ciarkovan� ciara
BORDER_DASHDOTDOT       ~ tenk� bodko-bodko-ciarkovan� ciara
BORDER_DASHED           - tenk� ciarkovan� ciara
BORDER_DOTTED           ~ tenk� bodkovan� ?? ciarkovan� ciara
BORDER_DOUBLE           - tenk� dvojit� ciara
BORDER_HAIR             - bodkovan� ciara
BORDER_MEDIUM           - hrub� ciara
BORDER_MEDIUMDASHDOT    - hrub� bodko-ciarkovan� ciara
BORDER_MEDIUMDASHDOTDOT ~ hrub� ciara
BORDER_MEDIUMDASHED     ~ hrub� ciara
BORDER_SLANTDASHDOT     ~
BORDER_THICK            - velmi hrub� ciara
BORDER_THIN             - tenk� ciara
**/
$styleBArray=[
  'borders'=>[
    'bottom'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],
    'fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor'=>['argb'=>'3C3C3C3C',],
  ],
];

$styleVArray=[
  'borders'=>[
    'vertical'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,],
    'fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor'=>['argb'=>'3C3C3C3C',],
  ],
];

$styleOArray=[
  'borders'=>[
    'outline'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,],
    'fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor'=>['argb'=>'00000000',],
  ],
];

$styleFCArray=[
  'font'=>['bold'=>false,],
  'alignment'=>['horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,],
];

$styleFOArray=[
  'font'=>['bold'=>false,],
  'alignment'=>['vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,'textRotation'=>90,],
];

$styleFQArray=[
  'font'=>['bold'=>false,],
  'alignment'=>[
    'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//    'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
    'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'textRotation'=>90,],
];

$styleFLArray=[
  'font'=>['bold'=>false,],
  'alignment'=>['horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,],
];

$styleFRArray=[
  'font'=>['bold'=>false,],
  'alignment'=>['horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,],
];

$styleFTArray=[
  'font'=>['bold'=>false,],
  'alignment'=>['vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,'wrapText'=>true,],
];
?>