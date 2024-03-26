<?php
//uploads CRUD
switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    # Crear imagen
    $name="";
    $folder=$_GET['folder'];
    if(isset($_GET['id'])){
      $name=$_GET['id'];
    }else{
      $name=time();
    }

    // echo $folder;
    if ($_FILES['imagen']['size'] > 0 ){
      // print_r($_FILES);
      $archivo = $_FILES["imagen"]["tmp_name"];
      $ext = $_FILES['imagen']['name'];
      $ext = substr($ext, strpos($ext, '.', false));
    // $destino = "../tatic/img/".$folder."/josehdez".$ext;
      session_start();
      // echo $_SESSION['id'];
      $destino = "../static/img/".$folder."/".$name.$ext;
      move_uploaded_file($archivo, $destino);
      $destinof="static/img/".$folder."/".$name.$ext;
      echo json_encode(
        array(
          "code" => 201,
          "ok"=> true,
        "message" => $destinof
      ));
    }else{
    echo json_encode(array(
        "code" => 500,
        "ok"=> false,
        "message" => "Error de servidor"
      ));
    }
    break;
  case 'GET':
    #  Listado Categoria.
    // echo $_GET['id'];
    // $cat=new Categoria();
    // if(isset($_GET['id'])){
    //   // $res = $cat->Categoria($id);
    //   $id=$_GET['id'];
    //   echo json_encode($res);
    // }else{
    //   $buscar="";
    //   if(isset($_GET['buscar'])){
    //     $buscar=$_GET['buscar'];
    //   }
    //   $res = $cat->Listado($buscar);
    //   echo json_encode($res);
  echo "GET";
    // }
    break;
  default:
    # code...
    echo 'Verbo no aceptado';
    break;
}
?>
