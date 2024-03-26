<?php
//Modelos
require_once '../modelos/Producto.php';
//Categoria CRUD
switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    # Crear Categoria
    $datos = json_decode(file_get_contents('php://input'), true);
    $nombre=$datos['nombre'];
    // echo $nombre;
   $cat= new Categoria(nombre: $nombre);
    $res=$cat->crearCategoria($nombre);
    // echo $res;
    echo json_encode($res);
    break;
  case 'GET':
    #  Listado Categoria.
    // echo $_GET['id'];
    $cat=new Producto();
    // if(isset($_GET['lista'])){
    //   $res=$cat->Lista();
    //   echo json_encode($res);
    // }else 
    if(isset($_GET['id'])){
      $res = $cat->Categoria($id);
      $id=$_GET['id'];
      echo json_encode($res);
    }else{
      $buscar="";
      if(isset($_GET['buscar'])){
        $buscar=$_GET['buscar'];
      }
      $res = $cat->ListadoServicios($buscar);
      echo json_encode($res);
    }
    break;
  case 'PUT':
    # cambiar password
    $id=$_GET['id'];
    $datos = json_decode(file_get_contents('php://input'), true);
    $cat=new Categoria(id: $id, nombre: $datos['nombre']);
    $res=$cat->editaCategoria();
    echo json_encode($res);
    break;
  case 'DELETE':
    # Recuperar 
    $id=$_GET['id'];
    $cat=new Categoria(id: $id);
    $res=$cat->eliminaCategoria();
    echo json_encode($res);
    break;
  default:
    # code...
    echo 'Verbo no aceptado';
    break;
}
?>
