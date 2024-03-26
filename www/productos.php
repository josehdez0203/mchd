<?php
session_start();
// print_r($_SESSION);
if(!isset($_SESSION['id'])) {
  header("location: login.php");
}

include "./partes/head.php";
include "./partes/header.php";
?>
<!-- <div class="main"> -->
<div class="admin" data-id="<?php echo $_SESSION['id'] ?>" data-name="<?php echo $_SESSION['nombre'] ?>">
  <!-- <h1 >Productos de: <?php echo $_SESSION['nombre'] ?></h1> -->
  <div class="admin_work">
    <h1 id="titulo">Título</h1>
    <div id="work_area">
    </div>
    <div id="nav">
      <input type="text" id="text" />
      <button id="buscar">Buscar</button>
      <button id="crear">Agregar</button>
      <div class="paginacion">
      </div>
    </div>
  </div>
<section class="modal" >
  <div class="forma" style="width: 400px">
    <div class="header">
      <h3>Servicios: <span id="catTitulo">Agregar</span> </h3>
    </div>
    <div class="body">
      <input type="hidden" id="prodId"/>
      <input type="text" id="prodTitulo" placeholder="Título"/>
      <textarea id="prodDesc" placeholder="Descripción">Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</textarea>
      <label>Categoría</label> <br>
      <select id="categoria">
      </select>
      <input type="file" id="poster" accept="image/*"/>
      <div class="foto">
        <img id="miFoto"  src=""/>
      </div>
      <input type="hidden" id="imagenes"/>
      <!-- <button class="agregar-foto">Agregar fotos</button> -->
      <input type="file" id="agregar-imagen" class="agregar-foto" accept="image/*"/>
      <div class="fotos">
        
      </div>
    </div>
    <div class="footer">
     <button class="ok">Aceptar</button> 
     <button class="cancel">Cancelar</button> 
     <button class="eliminar">Eliminar</button> 
    </div>
  </div>
</section>
<?php 
include "partes/footer_admin.php";
?>
  <script src="static/js/productos.js"></script>
