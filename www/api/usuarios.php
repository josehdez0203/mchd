<?php
//Modelos
require_once '../modelos/Usuario.php';
// echo $_SERVER['REQUEST_METHOD'];
header("Content-Type: application/json");
switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    # Agregar usuario...
    $datos = json_decode(file_get_contents('php://input'), true);
    // print_r($datos);
    $usuarios = new Usuario($datos['nombre'], $datos['email'], $datos['tel'], $datos['pass']);
    $res = $usuarios->ApiRegistro();

    echo $res;
    switch ($res) {
      case 201:
        echo json_encode(
          array(
            "code" => $res,
            "ok" => true,
            "message" => "Registro exitoso"
          )
        ); //registro correcto
        break;
      case 202:
        echo json_encode(
          array(
            "code" => $res,
            "ok" => false,
            "message" => "Registro exitoso, Problema al enviar email"
          )
        ); //fallo email
        break;
      case 401:
        $mensaje = $datos['email'];
        echo json_encode(
          array(
            "code" => $res,
            "ok" => false,
            "message" => "Usuario existente",
            "usuario" => $mensaje,
          )
        ); //Usuario existente
        break;
      case 404:
        $mensaje = $datos['email'];
        echo json_encode(
          array(
            "code" => $res,
            "ok" => false,
            "message" => "Datos vacios",
            "usuario" => $mensaje,
          )
        ); //Usuario existente
        break;
      case 501:
        $mensaje = $datos['nombre'] . $datos['email'] . $datos['tel'] . $datos['pass'];
        echo json_encode(
          array(
            "code" => $res,
            "ok" => false,
            "message" => "Error en el servidor",
            "usuario" => $mensaje,
          )
        ); //registro incorrecto o problemas con BD
        break;
      default:
        # code...
        break;
    }
    break;
  case 'GET':
    # Obtener usuarios...
    $usuarios= new Usuario();
    if(isset($_GET['id'])){
      $datos = json_decode(file_get_contents('php://input'), true);
      session_start();
      $res=$usuarios->usuario($_GET['id']);
      echo json_encode($res);
    }else{
      echo json_encode($usuarios->Listado());
    }
  // $res;
    // echo 'GET';
    break;
  case 'PUT':
    # Modificar usuario...
   $usuarios= new Usuario();
    // $id=$_GET['id'];
    // $res= $usuarios->cambiaUsuario($id);
    if(isset($_GET['id'])){
      session_start();
      $datos = json_decode(file_get_contents('php://input'), true);
      $res=$usuarios->cambiaUsuario($_GET['id'], $datos);
      if($res->code==201){
        if(isset($datos['telefono'])){
          $_SESSION['telefono'] = $datos['telefono'];
        }
        if(isset($datos['foto'])){
          $_SESSION['foto'] = $datos['foto'];
        }
        if(isset($datos['direccion'])){
          $_SESSION['direccion'] = $datos['direccion'];
        }
        if(isset($datos['info'])){
          $_SESSION['info'] = $datos['info'];
        }
      }
    }
    // print_r($res);
    echo json_encode($res);
    break;
  case 'DELETE':
    # Borrar usuario...
    echo 'DELETE';
    break;
  default:
    # code...
    echo 'Verbo no aceptado';
    break;
}
