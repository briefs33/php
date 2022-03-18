<?php
/**
**Border style**
BORDER_NONE             - žiadna ciara
BORDER_DASHDOT          - tenká bodko-ciarkovaná ciara
BORDER_DASHDOTDOT       ~ tenká bodko-bodko-ciarkovaná ciara
BORDER_DASHED           - tenká ciarkovaná ciara
BORDER_DOTTED           ~ tenká bodkovaná ?? ciarkovaná ciara
BORDER_DOUBLE           - tenká dvojitá ciara
BORDER_HAIR             - bodkovaná ciara
BORDER_MEDIUM           - hrubá ciara
BORDER_MEDIUMDASHDOT    - hrubá bodko-ciarkovaná ciara
BORDER_MEDIUMDASHDOTDOT ~ hrubá ciara
BORDER_MEDIUMDASHED     ~ hrubá ciara
BORDER_SLANTDASHDOT     ~
BORDER_THICK            - velmi hrubá ciara
BORDER_THIN             - tenká ciara
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