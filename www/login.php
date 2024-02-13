<?php include "./partes/head.php" ?>
<?php include "./partes/header.php" ?>
<!--form action="nuevo_usuario.php" method="POST" enctype="multipart/form-data"-->
<div class="main">
    <div class="tabla">
        <h2>Ingresa al sistema</h2>
        <p>
            <label>Email:</label><input type="email" value="" id="email" />
        </p>
        <p>
            <label> Contraseña: </label>
            <input type="password" id="pass" placeholder="*****" />
        </p>
        <span><a href="recuperar.php">Recuperar contraseña</a></span>

        <button class="boton" id="enviar">Enviar</button>
        <div class="pie">
            <span class="fa fa-phone"></span>001 1023 567
            <span class="fa fa-envelope-o"></span> MexChambDur@MCHD.com
        </div>
    </div>
</div>
<!--/form-->
<?php include "./partes/footer.php" ?>
<script src="static/js/login.js"></script>