<?php
// session_start();
// print_r($_SESSION);
?>
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
      <form>
        <input type="text" placeholder="Buscar servicios..." />
        <button type="submit">Buscar</button>
      </form>
    </div>
    <nav>
      <ul>
<?php
if(isset($_SESSION['id'])) {
    ?>
<li><a href="servicios.php">Servicios</a></li>
<li><a href="productos.php">Productos</a></li>
<li><a href="cuenta.php">Cuenta</a></li>
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
    </nav>
  </div>
</header>

<body>
