<?php 
include "./partes/head.php";
include "./partes/header.php" ?>
<div class="main">
  <div class="activar">
  <?php
    // require "init.php";
  $id = $_GET['id'];
// echo $id
?>
    <input type="hidden" value="<?php echo $id ?>"/>
    <button id="activar">Activar usuario</button>
  </div>
</div>
<?php include "./partes/footer.php" ?>
<script src="static/js/activar.js"></script>
