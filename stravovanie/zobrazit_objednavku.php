<?php include('hlavicka.php');?>
    <title>Zobrazenie objednanej stravy</title>
  </head>

  <body>
    <section class="bez_obsahu">
<h1>Zobrazenie objednanej stravy</h1>

<fieldset class="uzky">
  <table>
<?php
$datum=$_GET['datum'];

$db='default';
include('databaza.php');
$den=date('Y-m-d',strtotime($datum));
$sql="SELECT jl_obed_3, jl_obed_4, jl_obed_9, jl_obed_13, jl_vecera_3, jl_vecera_4, jl_vecera_9, jl_vecera_13
FROM jedalne_listky
WHERE datum='$den'";
$run=mysqli_query($dbcon, $sql) or die('Chyba: '.mysqli_error($dbcon).'<hr/>\nQuery: $sql');
while($row=mysqli_fetch_array($run)){
?>
    <tr><td></td><th>Dátum:</th><td><?php echo date('j.n.Y',strtotime($datum));?></td><td></td><td></td></tr>
    <tr><td></td><th>Diéta č. 3</th><th>Diéta č. 9</th><th>Diéta č. 4</th><th>Diéta č. 13</th></tr>

    <tr>
      <th class="rotate_objed">Obed</th>
      <td width="120"><?php echo $row['jl_obed_3'];?></td>
      <td width="120"><?php echo $row['jl_obed_9'];?></td>
      <td width="120"><?php echo $row['jl_obed_4'];?></td>
      <td width="120"><?php echo $row['jl_obed_13'];?></td>
    </tr>

    <tr><td colspan="5"><br /></td></tr>

    <tr>
      <th class="rotate_objed">Večera</th>
      <td width="120"><?php echo $row['jl_vecera_3'];?></td>
      <td width="120"><?php echo $row['jl_vecera_9'];?></td>
      <td width="120"><?php echo $row['jl_vecera_4'];?></td>
      <td width="120"><?php echo $row['jl_vecera_13'];?></td>
    </tr>
<?php
}
$dbcon->close();
define('pocet','<th>Počet porcií:</th>');

function prepinac($dieta, $pocet){
  switch($dieta){
    case 'BZL': echo 'Bezlepková</td>'.pocet.'<td>'.$pocet; break;
    case '*': echo '*</td>'.pocet.'<td>'.$pocet; break;
    case 0: echo '</td>'.pocet.'<td>'; break;
    default: echo $dieta.'</td>'.pocet.'<td>'.$pocet;
  }
}
?>
    <tr><th></th><th>Obed 1:</th><td><?php prepinac($_GET['obed_1'], $_GET['obed_1_pocet']);?></td></tr>
    <tr><th></th><th>Obed 2:</th><td><?php prepinac($_GET['obed_2'], $_GET['obed_2_pocet']);?></td></tr>
    <tr><th></th><th>Večera:</th><td><?php prepinac($_GET['vecera_1'], $_GET['vecera_1_pocet']);?></td></tr>
    <tr><td colspan="5"><br /><br /><a href="javascript:window.close()"><button>Zatvoriť okno</button></a></td></tr>
  </table>
</fieldset>
    </section>
  </body>
</html>