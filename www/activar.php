<?php include "./partes/head.php";
include "./partes/header.php" ?>
<div class="main">
    <div class="activar">
    <?php
      require "init.php";
$id = $_GET['id'];
$usuario = new Usuario();
$usuario->Verifica($id);
?>
    </div>
</div>
<?php include "./partes/footer.php" ?>
