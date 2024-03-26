<?php
//Modelos
error_reporting(E_ALL);
require_once '../modelos/Usuario.php';
//Login, recuperar, cambiar password, activar
switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    # Login
    $datos = json_decode(file_get_contents('php://input'), true);
    // print_r($datos);
    $usuarios = new Usuario("", $datos['email'],"", $datos['pass']);
    $res = $usuarios->ApiLogin();
    $usuario=$res['message'];
    // print_r($usuario);
    switch ($res['code']) {
      case 201:
        session_destroy();
        session_start();
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['telefono'] = $usuario['telefono'];
        $_SESSION['tipo'] = $usuario['tipo']; //$this->Encript($datos['tipo'] . 'jhc');
        $_SESSION['foto'] = $usuario['foto'];
        $_SESSION['direccion'] = $usuario['direccion'];
        $_SESSION['info'] = $usuario['info'];
        echo json_encode($res);
      default:
    }
    break;
  case 'GET':
    #  activar...
    if(isset($_GET['id'])){
      // echo $_GET['id'];
      $id=$_GET['id'];
      $usuarios=new Usuario();
      $res = $usuarios->Verifica($id);
      echo json_encode($res);
    // }else{
    }
    break;
  case 'PUT':
    # cambiar password
    echo 'PUT';
    break;
  case 'PATCH':
    # Recuperar 
    echo 'PATCH';
    break;
  default:
    # code...
    // echo 'Verbo no aceptado';
    break;
}
?>
