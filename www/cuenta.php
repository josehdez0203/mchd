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
    <div class="tabla">
      <h2>EDITAR CUENTA</h2>
      <input type="hidden" id="id" value="<?php echo $_SESSION['id'] ?>"/>
      <p>
        <label>Email: </label><span><?php echo $_SESSION['email'] ?></span><br>
        <label>Nombre: </label> <span><?php echo $_SESSION['nombre'] ?></span> 
      </p>
      <p>
        <label>Teléfono</label>
        <input id="tel" type="tel" placeholder="Ej. 3538899999" 
          pattern="[0-9]{10}" value="<?php echo $_SESSION['telefono'] ?>" />
      </p>
      <p>
        <label> Dirección: </label>
        <input type="text" id="direccion" placeholder="Tu dirección" value="<?php echo $_SESSION['direccion'] ?>" />
      </p>

      <p>
        <label> Algo de ti: </label><br>
        <textarea id="info" placeholder="Algo de ti..."><?php echo $_SESSION['info'] ?></textarea> 
      </p>
      <p>
        <label> Contraseña: </label>
        <input type="password" id="pass" placeholder="*****" />
      </p>
      <!-- <p> -->
      <!--   <label> Confirmar Contraseña: </label> -->
      <!--   <input type="password" id="pass2" placeholder="*****" /> -->
      <!-- </p> -->
      <p>
        <label> Foto de perfil: </label>
        <input type="file" id="foto"  accept="image/*" />
      </p>
        <button class="boton" id="enviar">Modificar</button>
      <p>
        <a id="cuenta" href="api/logout.php">Cerrar sesión</a>
      </p>
      <div class="foto">
        <img id="miFoto" src="<?php echo $_SESSION['foto'] ?>"/>
      </div>
    </div>
  <!-- </div> -->
<!-- </div> -->
  <?php include "./partes/footer.php" ?>
  <script src="<?php echo 'static/js/cuenta.js' ?>"></script>
