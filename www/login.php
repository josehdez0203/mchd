<?php include "./partes/head.php" ?>
<?php include "./partes/header.php" ?>
<form action="nuevo_usuario.php" method="POST" enctype="multipart/form-data">


    <div class="tabla">
        <h2>UNETE A NOSOTROS</h2>
        <p type="">
            <label>Cuenta: </label>
            <input type="text" requiered name="cuenta" placeholder="Mi cuenta..." value="" />
        </p>

        <p type="">
            <label> Servicio: </label>
            <input type="text" placeholder="Servicio" name="servicio" required />
        </p>

        <p type="">
            <label>Teléfono</label>
            <input name="tel" type="tel" placeholder="Ej. 3538899999" pattern="[0-9]{10}" required />
        </p>
        <p type="">
            <label>Email:</label><input type="email" value="" name="correo" required />
        </p>
        <p type="">
            <label> Contraseña: </label>
            <input type="password" name="pass" placeholder="*****" required />
        </p>
        <p type="">
            <label> Algo de ti: </label>
            <input type="text" requiered name="nombre" placeholder="tu..." value="" />
        </p>
        <p type="">
            <label> Foto de perfil: </label>
            <input type="file" name="Imagen" placeholder="Imagen... " value="" />
        </p>

        <button class="boton">Enviar</button>
        <div class="pie">
            <span class="fa fa-phone"></span>001 1023 567
            <span class="fa fa-envelope-o"></span> MexChambDur@MCHD.com
        </div>
    </div>
</form>
<?php include "./partes/footer.php" ?>