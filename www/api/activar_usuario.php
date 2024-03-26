<?php
//Modelos
require_once '../modelos/Usuario.php';
// echo $_SERVER['REQUEST_METHOD'];
header("Content-Type: application/json");
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
  # activar/desactivar usuario..
  $usuarios= new Usuario();
  $usuarios->ApiToggleActivo();
}
