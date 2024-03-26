<?php
//Modelos
require_once '../modelos/Producto.php';
//Categoria CRUD
switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    # Crear Producto
    $datos = json_decode(file_get_contents('php://input'), true);
  // print_r($datos);
   $cat= new Producto();
    $res=$cat->crearProducto($datos);
    // echo $res;
    echo json_encode($res);
    break;
  case 'GET':
    $prod=new Producto();

    if(isset($_GET["product_id"])){
      $id=$_GET['product_id'];
      $res=$prod->Producto($id);
      echo json_encode($res);
    }else{
      $buscar="";
      if(isset($_GET['buscar'])){
        $buscar=$_GET['buscar'];
      }
      #  Listado Productos.
      $res = $prod->Listado($buscar);
      echo json_encode($res);
    }
    break;
  case 'PUT':
    # cambiar Producto
    $id=$_GET['id'];
    $datos = json_decode(file_get_contents('php://input'), true);
    $cat=new Producto();
    $res=$cat->editaProducto($id, $datos);
    echo json_encode($res);
    break;
  case 'DELETE':
    # Eliminar Producto 
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
