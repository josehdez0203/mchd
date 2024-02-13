<?php
// session_start();
include "./partes/head.php";
include "./partes/header.php";
?>
<div class="main">
    <div class="tabla">
        <h2>Recuperar Contraseña</h2>
        <p>
            <label>Email:</label><input type="email" value="" id="email" />
        </p>


        <button class="boton" id="enviar">Enviar</button>
        <div class="pie">
            <span class="fa fa-phone"></span>001 1023 567
            <span class="fa fa-envelope-o"></span> MexChambDur@MCHD.com
        </div>
    </div>
</div>
<?php include "./partes/footer.php" ?>
<script src="static/js/recuperar.js"></script>