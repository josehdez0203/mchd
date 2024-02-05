<?php
session_start();
include "./partes/head.php";
include "./partes/header.php";
?>
    <main >
<div class="main">
      <section class="categorias">
        <h2>Categorías</h2>
        <ul>
<?php
include "init.php";
$categorias = new Categoria();
$cat = $categorias->Listado("");
for ($i = 0; $i < count($cat) ; $i++) {
    $c = $cat[$i];
    $id = $c['id'];
    $nombre = $c['nombre'];
    ?>
  <li>
    <a href="<?php echo '?categoria='.$id ?>">
    <?php echo $nombre ?>
    </a>
  </li>
<?php
}
?>
      </ul>
      </section>
      <section class="productos">
        <h2>Los mejores del mes</h2>
        <div class="producto">
          <div class="detalle">
          <h3>Luis Eduadordo Munguia</h3>
          <h4>Maestro de matematicas</h4>
          <p class="descripcion">Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
          nisi ut aliquip ex ...
          <a href="static/img/detalle_producto1.html">Ver detalles</a>
          </p>
          </div>
          <div class="imagen">
            <img src="static/img/MaestroMatematicas.jfif" alt="Servicio 1" />
          </div>
        </div>
        <div class="producto">
          <div class="detalle">
          <h3>Javier Sosa Ramirez</h3>
          <h4>Albañil</h4>
          <p class="descripcion">Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
          nisi ut aliquip ex ...
          <a href="static/img/Contacto.html">Ver detalles</a>
          </p>
          </div>
          <div class="imagen">
            <img src="static/img/Alba%C3%B1ilDestacado.jfif" alt="Servicio 2" />
          </div>
        </div>
        <div class="producto">
          <div class="detalle">
          <h3>Sandra Alcarez Alvarez</h3>
          <h4>Guitarrista profesional</h4>
          <p class="descripcion">Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
          nisi ut aliquip ex ...
          <a href="static/img/detalle_producto1.html">Ver detalles</a>
          </p>
          </div>
          <div class="imagen">
            <img src="static/img/Productor.jfif" alt="Servicio 3" />
          </div>
        </div>

      </section>
</div>
    </main>
<?php include "./partes/footer.php" ?>
