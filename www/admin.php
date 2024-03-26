<?php 
session_start();
if(!isset($_SESSION['id'])){
  // print_r($_SESSION);
  header("Location: /index.php");
}
include "partes/head.php";
include "partes/header_admin.php";
?>
<style>
  #crear{
    display: none;
  }
</style>
<section class="admin">
  <nav class="nav_admin">
    <ul class="menu_admin">
      <li data-table="usuarios">Usuarios</li>
      <li data-table="categorias">Categorias</li>
    </ul>
  </nav>
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
</section>
<section class="modal">
  <div class="forma">
    <div class="header">
      <h3>Categorias: <span id="catTitulo">Agregar</span> </h3>
    </div>
    <div class="body">
      <input type="hidden" id="catId"/>
      <input type="text" id="catName"/>
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
<script src="static/js/admin.js"></script>
