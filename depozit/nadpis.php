    <title><?php echo $nadpis;?></title>
  </head>

  <body>
    <header class="noprint">
<?php echo $_SESSION['email'].' ';?>
<a href="odhlasenie.php"><button id="odhlasit">Odhlásiť sa</button></a>
    </header>
    <nav class="noprint"><?php include('obsah.php');?></nav>
    <section>