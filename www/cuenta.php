<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: /index.php");
}

include "./partes/head.php";
include "./partes/header.php";
?>
<div class="main">
  <div class="cuenta" >
    <a href="api/logout.php">Salir</a>
  </div>
</div>
<?php include "./partes/footer.php" ?>
