<?php
session_start();
include "./partes/head.php";
include "./partes/header.php";

  require "init.php";
  $token = $_GET['id'];
  $registro = new Token();
  $validar=$registro->ApiChecarToken($token);
?>
<main >
<div class="main"  >
<?php
  if($validar != 0){
    // print_r($validar);
?>
    <div class="tabla">
        <h2>Cambiar contraseña</h2>
        <p>
          <label> Nueva Contraseña: </label>
          <input type="password" id="pass1" placeholder="*****" />
        </p>
        <p>
          <label> Comprobar contraseña: </label>
          <input type="password" id="pass2" placeholder="*****" />
        </p>
        <input type="hidden" id="token" value="<?php echo $token ?>" />
        <button class="boton" id="enviar">Enviar</button>
        <div class="pie">
            <span class="fa fa-phone"></span>001 1023 567
            <span class="fa fa-envelope-o"></span> MexChambDur@MCHD.com
        </div>
    </div>
<?php
  }else{
    echo $validar;
?>
<div class="tabla">
        <h2>Token no encontrado o caducado</h2>
</div>
<?php
  }
?>
</div>
    </main>
<?php include "./partes/footer.php" ?>
<script src="static/js/cambiarpass.js"></script>
