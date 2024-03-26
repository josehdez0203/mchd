<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
//Modelos
require_once '../modelos/Conexion.php';

class Producto{
  private $id;

  private $db = null;
  private $conf = null;


  public function __construct($id=""){
    $this->id = $id;
    $this->conf = new Config();
    $this->db = new Conexion($this->conf->config['db']);
  }
  public function Listado($buscar){
    $regXpag=6;
    $pag=$_GET['pag'];
    // $buscar=$_GET['buscar'];
    $id=$_GET['id'];

    if($buscar == "") {
      $sql = "SELECT * FROM productos WHERE usuario_id='".$id."' 
        ORDER BY titulo ASC LIMIT ".(($pag-1)*$regXpag). ",$regXpag";
    } else {
      $sql = "SELECT * FROM productos WHERE usuario_id='".$id."' 
        AND titulo LIKE '%$buscar%' ORDER BY titulo ASC LIMIT ".(($pag-1)*$regXpag). ",$regXpag";
    }
    $registros = [];
    $consulta = $this->db->query($sql);
    while ($reg = $this->db->recorrer($consulta)) {
      // print_r($reg);
      $registros[] = $reg;
    }
    if($buscar==""){
      $sql = 'SELECT count(id) AS total FROM productos';
    }else{
      $sql = "SELECT count(id) AS total FROM productos WHERE titulo LIKE '%$buscar%'";
    }
    $consulta2=$this->db->query($sql);
    $total=$this->db->recorrer($consulta2);
    $paginas= ceil($total['total'] / $regXpag);

    return array('registros'=>$registros, 'total'=> $total["total"], 'paginas'=>$paginas, 'pag'=>$pag );
  }

  public function ListadoServicios($buscar){
    $regXpag=6;
    $pag=$_GET['pag'];
    if(isset($_GET['categoria_id'])){
      $categoria_id=$_GET['categoria_id'];
    }
    // $buscar=$_GET['buscar'];

    if($buscar == "") {
      $sql = "SELECT productos.id as id, titulo, descripcion, usuarios.nombre, 
        categoria_id, categorias.nombre as categoria, poster FROM 
        productos JOIN usuarios ON usuario_id= usuarios.id JOIN categorias 
        ON categoria_id= categorias.id ";
    } else {
      $sql = "SELECT productos.id as id, titulo, descripcion, usuarios.nombre, 
        categoria_id, categorias.nombre as categoria, poster FROM 
        productos JOIN usuarios ON usuario_id= usuarios.id JOIN categorias 
        ON categoria_id= categorias.id WHERE titulo LIKE '%$buscar%' 
        OR categorias.nombre LIKE '%$buscar%' OR descripcion LIKE '%$buscar%' ";
    }
    if(isset($categoria_id)){
      $sql=$sql ." AND categoria_id=".$categoria_id;
    }
    $sql=$sql ." ORDER BY titulo ASC LIMIT ".(($pag-1)*$regXpag). ",$regXpag";    
    // echo $sql;
    $registros = [];
    $consulta = $this->db->query($sql);
    if($this->db->rows($consulta)>0){
      while ($reg = $this->db->recorrer($consulta)) {
        // print_r($reg);
        $registros[] = $reg;
      }
    }
    if($buscar==""){
      $sql = 'SELECT count(id) AS total FROM productos';
    }else{
      $sql = "SELECT count(id) AS total FROM productos WHERE titulo LIKE '%$buscar%'";
    }
    if(isset($categoria_id)){
      if($buscar==""){
        $sql = $sql . " WHERE categoria_id=".$categoria_id;
      }else{
        $sql = $sql . " AND categoria_id=".$categoria_id;
      }

    }
    $consulta2=$this->db->query($sql);
    $total=$this->db->recorrer($consulta2);
    $paginas= ceil($total['total'] / $regXpag);

    return array('registros'=>$registros, 'total'=> $total["total"], 'paginas'=>$paginas, 'pag'=>$pag );
  }

  public function Producto($id=""):Array{
    if(!empty($id)){
      $sql="SELECT * FROM productos WHERE id=".$id;
      $consulta=$this->db->query($sql);
      if($this->db->rows($consulta)>0){
        $producto=$this->db->recorrer($consulta);
        return array(
          'code'=>201,
          "ok"=>true,
          "producto"=>$producto
        );
      }else{
        return array(
          'code'=>404,
          "ok"=>false,
          "message"=>"Producto no encontrado"
        );
      }
    }else{
      return array(
        'code'=>500,
        "ok"=>false,
        "message"=>"Error de servidor"
      );
    }
  }
  public function crearProducto($datos){
    if(!empty ($datos['titulo'])){
      $sql="INSERT INTO productos(usuario_id, categoria_id, titulo, 
      poster, descripcion, imagenes) VALUES(".$datos['usuario_id'].", ".$datos['categoria_id']
      .", '".$datos['titulo']."', '". $datos['poster']."', '". $datos['desc'].
      "', '". $datos['imagenes'] ."')";
      // echo $sql;
      $consulta = $this->db->query($sql);
      if($consulta){
        return array(
          "code" => 201,
          "ok" => true,
          "message"=>"Registro creado"
        );
      }else{
        return array(
          "code" => 506,
          "ok" => false,
          "message" => "Error al guardar categoría"
        );
      }
    }
  }
  public function editaProducto($id, $datos){
    if(!empty ($id)){
      $sql="UPDATE productos set titulo ='".$datos['titulo'] 
        ."', descripcion='". $datos['desc']. "', categoria_id=".$datos['categoria_id']
        .", poster='". $datos['poster']. "', imagenes='". $datos['imagenes']."' WHERE id="
        . $id;
      // echo $sql;
      $consulta = $this->db->query($sql);
      if($consulta){
        return array(
          "code" => 201,
          "ok" => true,
          "message"=>"Registro Modificado"
        );
      }else{
        return array(
          "code" => 506,
          "ok" => false,
          "message" => "Error al editar Producto"
        );
      }
    }
    return array(
      "code" => 500,
      "ok" => false,
      "message" => "Error al editar Producto  "
    );
  }
  public function eliminaProducto(){
    if(!empty ($this->id)){
      $sql="DELETE FROM categorias WHERE id=". $this->id;
      // echo $sql;
      $consulta = $this->db->query($sql);
      if($consulta){
        return array(
          "code" => 201,
          "ok" => true,
          "message"=>"Registro Eliminado"
        );
      }else{
        return array(
          "code" => 506,
          "ok" => false,
          "message" => "Error al borrar categoría"
        );
      }
    }
    return array(
      "code" => 500,
      "ok" => false,
      "message" => "Error al borrar categoría"
    );
  }

}
