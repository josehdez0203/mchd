<header>
  <div class="menu">
    <div class="logo">
      <a href="index.php">
        <img src="./static/img/logo4.png" alt="MCHD" /><button class="button2" style="vertical-align: middle">
          <span>MCHD </span>
        </button>
      </a>
    </div>
    <div class="search">
      <!-- <form> -->
        <input type="text" placeholder="Buscar servicios..." id="texto" />
        <button type="submit" id="buscar">Buscar</button>
      <!-- </form> -->
    </div>
    <nav>
      <ul>
<?php
if(isset($_SESSION['id'])) {
  // print_r($_SESSION);
    ?>
<li><a href="servicios.php">Servicios</a></li>
<li><a href="productos.php">Mis Servicios</a></li>
<li><a href="cuenta.php">Cuenta</a></li>
<?php
  if($_SESSION['tipo']==='admin'){
?>
<li><a href="admin.php">Administración</a></li>
<?php
  }
?>
<?php
} else {
  ?>
    <li><a href="servicios.php">Servicios</a></li>
    <li><a href="login.php">Ingresar</a></li>
    <li><a href="registro.php">Registrarse</a></li>
  <?php
}
?>
  </ul>
<?php
  if(isset($_SESSION['id'])){
?>
  <div class="foto">
    <a href="cuenta.php">
    <img src="<?php echo $_SESSION['foto'] ?>" />
    </a>
  </div>
  <?php
  }
?>
    </nav>
  </div>
</header>

<body>
