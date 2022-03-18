    <title><?php echo $nadpis;?></title>
  </head>

  <body>
    <header class="noprint">
<?php
echo $_SESSION['email'].' ';

if($_SESSION['rola']<7){$inactive=666;} else{$inactive=3666;}//666 1332 2664 3996
$session_life=0;

if(isset($_SESSION['timeout'])){
  $session_life=time()-$_SESSION['timeout'];
  if($session_life>$inactive){
    echo '<script>alert("Nebola zaznamenaná žiadna aktivita viac ako 10 minút!")</script>';
    header('Location: odhlasenie.php');
  }
}
$_SESSION['timeout']=time();
?>
<a href="odhlasenie.php" onload="startTime()"><button id="odhlasit">Odhlásiť sa (599)</button></a>

<script>
var m=<?php if($_SESSION['rola']<7){echo 599;} else{echo 3599;}?>;
var myVar=setInterval(myTimer,1000);
function myTimer(){
  m--;
  document.getElementById("odhlasit").innerHTML="Odhlásiť sa ("+m+")";
  if(m<1){window.location.assign("odhlasenie.php");}
}
</script>
    </header>
    <nav class="noprint"><?php include('obsah.php');?></nav>
    <section>