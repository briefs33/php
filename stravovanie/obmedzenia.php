<?php
/*** prehlad_objednavok.php obsahuje premenné:
 * $obmedz_pon
 * $datumobm
 * $koniecdatumobm
***/

/*** objednanie_stravy.php obsahuje premenné:
 * $obmedz_pon
 * $obmedz_uto
 * $obmedz_str
 * $obmedz_stv
 * $obmedz_pia
***/

$startd=strtotime('-1 day', $startdate);
$enddate=strtotime('-1 week', $startd);

/* velka noc 2020 * /
$obmedz_pon=$enddate+385200-3*86400;//345600; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_uto=$enddate+385200-3*86400; //pondelok
$obmedz_str=$enddate+691200; //utorok
$obmedz_stv=$enddate+691200; //streda
$obmedz_pia=$enddate+691200; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+691200;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+7*86400;
$koniecdatumobm=strtotime("tuesday")+7*86400;//-86400;
/**/

/* bez sviatkov */
$obmedz_pon=$enddate+345600;//385200; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_uto=$enddate+691200; //pondelok
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+864000; //streda
$obmedz_pia=$enddate+950400; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+950400;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+86400;
$koniecdatumobm=strtotime("tuesday");//-86400;
/**/

/* sviatok v piatok * /
$obmedz_pon=$enddate+345600;//385200; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_uto=$enddate+691200; //pondelok
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+864000; //streda
$obmedz_pia=$enddate+864000; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+864000;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+86400;
$koniecdatumobm=strtotime("tuesday");//-86400;
/**/

/* sviatok v -- * /
$obmedz_pon=$enddate+345600-86400;//385200; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_uto=$enddate+345600-86400; //pondelok
$obmedz_str=$enddate+691200; //utorok
$obmedz_stv=$enddate+691200; //streda
$obmedz_pia=$enddate+691200; //štvrtok
$obmedz_sob=$enddate+777600;
$obmedz_ned=$enddate+777600;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+5*86400;
$koniecdatumobm=strtotime("monday");//-86400;
/**/

/* sviatok vianoce * /
$obmedz_pon=$enddate+345600-3*86400;//385200; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_uto=$enddate+345600-3*86400;//691200; //pondelok
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+864000; //streda
$obmedz_pia=$enddate+864000; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+864000;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+86400;
$koniecdatumobm=strtotime("tuesday");//-86400;
/**/

/* sviatok 1.maj * /
$obmedz_pon=$enddate+345600;//385200; //štvrtok 0:00
$obmedz_uto=$enddate+691200; //pondelok
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+777600; //utorok
$obmedz_pia=$enddate+777600; //utorok
$obmedz_sob=$obmedz_ned=$enddate+777600; //utorok

$datumobm=date("U")+5*86400;
$koniecdatumobm=strtotime("tuesday");//-86400;
/**/

/* sviatok 8.maj * /  //blokovanie od utorka do nedele
$obmedz_pon=$enddate+345600-86400;//385200; //streda 0:00
$obmedz_uto=$enddate+691200; //pondelok
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+777600; //utorok
$obmedz_pia=$enddate+777600; //utorok
$obmedz_sob=$obmedz_ned=$enddate+777600; //utorok

$datumobm=date("U")+5*86400;
$koniecdatumobm=strtotime("tuesday");//-86400;
/**/

/* sviatok vo stvrtok * / //vianoce
$obmedz_pon=$enddate+385200-3*86400;
$obmedz_uto=$enddate+385200-1*86400;
$obmedz_str=$obmedz_stv=$enddate+385200-1*86400;//štvrtok 11:00h //streda
$obmedz_pia=$obmedz_sob=$enddate+385200;
$obmedz_ned=$enddate//+385200;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h
//
$datumobm=date("U")+4*86400;
$koniecdatumobm=strtotime("tuesday");//+4*86400;//-86400;
/**/


/* sviatok v pondelok * /
$obmedz_pon=$obmedz_uto=$enddate+345600; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
$obmedz_str=$enddate+777600; //utorok
$obmedz_stv=$enddate+864000; //streda
$obmedz_pia=$enddate+950400; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+950400;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+2*86400;
$koniecdatumobm=strtotime("tuesday")+86400;
/**/

/* sviatok v utorok * / //september
$obmedz_pon=$obmedz_uto=$obmedz_str=$enddate+345600; //štvrtok 0:00 385200-7200; //štvrtok 11:00h
//$enddate+777600; //utorok
$obmedz_stv=$enddate+864000; //streda
$obmedz_pia=$enddate+950400-86400; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+950400-86400;//+950400; //štvrtok 0:00 990000-7200; //štvrtok 11:00h

$datumobm=date("U")+4*86400;
$koniecdatumobm=strtotime("tuesday")+1*86400;
/**/

/* sviatok v stredu * /
$obmedz_pon=$enddate+385200; //štvrtok 11:00h
$obmedz_uto=$obmedz_str=$obmedz_stv=$enddate+691200; //pondelok
$obmedz_pia=$enddate+950400; //štvrtok
$obmedz_sob=$obmedz_ned=$enddate+990000; //štvrtok 11:00h

$datumobm=date("U")+3*86400;
$koniecdatumobm=strtotime("tuesday")+2*86400;
/**/
?>