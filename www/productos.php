<?php
session_start();
if(!isset($_SESSION)) {
    header("login");
}

include "./partes/head.php";
include "./partes/header.php";
?>
<div class="main">
  <div class="cuenta" >
  <h1 style= {"color": "white"}>Productos de: <?php echo $_SESSION['nombre'] ?></h1>
  </div>
</div>
<?php include "./partes/footer.php" ?>
