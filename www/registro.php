
<?php include "./partes/head.php" ?>
<?php include "./partes/header.php" ?>
<div class="main">

 <!--form action="nuevo_usuario.php" method="POST" -->
    <div class="tabla">
        <h2>UNETE A NOSOTROS</h2>
        <p>
            <label>Email:</label><input type="email"  id="email"  />
        </p>

        <p >
            <label>Nombre: </label>
            <input type="text" requiered id="nombre" placeholder="Mi nombre..." />
        </p>

        <!--p>
            <label> Servicio: </label>
            <input type="text" placeholder="Servicio" name="servicio" required />
        </p-->

        <p >
            <label>Teléfono</label>
            <input id="tel" type="tel" placeholder="Ej. 3538899999" pattern="[0-9]{10}"  />
        </p>
        <p>
            <label> Contraseña: </label>
            <input type="password" id="pass" placeholder="*****"  />
        </p>
        <p>
            <label> Confirmar Contraseña: </label>
            <input type="password" id="pass2" placeholder="*****"  />
        </p>
        <!--p type="">
            <label> Algo de ti: </label>
            <input type="text" requiered name="nombre" placeholder="tu..." value="" />
        </p>
       <p type="">
        //     <label> Foto de perfil: </label>
        //     <input type="file" name="Imagen" placeholder="Imagen... " value="" />
        // </p-->

      <button class="boton" id="enviar">Enviar</button>
      <div class="pie">
        <span class="fa fa-phone"></span>001 1023 567
        <span class="fa fa-envelope-o"></span> MexChambDur@MCHD.com
      </div>
    </div>
  <div id="_AJAX_"></div>
</div>
<?php include "./partes/footer.php" ?>
<script src="<?php echo 'static/js/registro.js' ?>"></script>
