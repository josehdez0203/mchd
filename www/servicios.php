<?php
session_start();
include "./partes/head.php";
include "./partes/header.php";
?>
    <main >
<div class="main">
  <section class="categorias">
    <h2>Categorías</h2>
    <ul id="categorias">
    </ul>
    <div class="more"> Mostrar más</div>
  </section>
  <section class="productos">
    <h2 id="titulo">Los mejores del mes</h2>
    <div id="productos"></div>
    <div class="paginacion"></div>
  </section
</div>
    </main>
<!-- <script src="static/js/categorias.js"></script> -->
<script src="static/js/servicios.js"></script>

<?php include "./partes/footer.php" ?>
