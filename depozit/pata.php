    </section>
    <footer class="noprint">
      <img src="pnh_belasa.png" alt="" width="28" height="22">
<?php
if(isset($dbcon)){
  $dbcon->close();
}
?>
 Psychiatrická nemocnica Hronovce<br />2019 - <?php echo date('Y');?>© Wisdom - Bc. Attila Csontos
    </footer>
  </body>
</html>