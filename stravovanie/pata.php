    </section>
    <footer class="noprint">
<?php
switch ($_SESSION['email']){
//  case 'attila.csontos@pnh.sk':
  case 'seres@pnh.sk': echo '<img src="pnh_fialova.png" alt="" width="28" height="22">'; break;
  default: echo '<img src="pnh_zelena.png" alt="" width="28" height="22">';
}

if(isset($dbcon)){
  $dbcon->close();
}
?>
 Psychiatrická nemocnica Hronovce<br />2015 - <?php echo date('Y');?>© Wisdom - Bc. Attila Csontos
    </footer>
  </body>
</html>