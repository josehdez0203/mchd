<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
//Modelos
require_once '../modelos/Conexion.php';

class Categoria{
  private $id;
  private $nombre;

  private $db = null;
  private $conf = null;


  public function __construct($nombre="", $id=""){
    $this->nombre=$nombre;
    $this->id = $id;
    $this->conf = new Config();
    $this->db = new Conexion($this->conf->config['db']);
  }
  public function Listado(){
    $regXpag=6;
    $pag=$_GET['pag'];
    $buscar=$_GET['buscar'];

    if($buscar == "") {
      $sql = 'SELECT * FROM categorias ORDER BY nombre ASC LIMIT '.(($pag-1)*$regXpag). ",$regXpag";
    } else {
      $sql = "SELECT * FROM categorias
      WHERE nombre LIKE '%$buscar%' ORDER BY nombre ASC LIMIT ".(($pag-1)*$regXpag). ",$regXpag";
    }
    $registros = [];
    $consulta = $this->db->query($sql);
    while ($reg = $this->db->recorrer($consulta)) {
      // print_r($reg);
      $registros[] = $reg;
    }
    if($buscar==""){
      $sql = 'SELECT count(id) AS total FROM categorias';
    }else{
      $sql = "SELECT count(id) AS total FROM categorias WHERE nombre LIKE '%$buscar%'";
    }
    $consulta2=$this->db->query($sql);
    $total=$this->db->recorrer($consulta2);
    $paginas= ceil($total['total'] / $regXpag);

    return array('registros'=>$registros, 'total'=> $total["total"], 'paginas'=>$paginas, 'pag'=>$pag );
  }
  public function Lista(){
    $sql = 'SELECT * FROM categorias ORDER BY nombre';
    $registros = [];
    $consulta = $this->db->query($sql);
    while ($reg = $this->db->recorrer($consulta)) {
      $registros[] = $reg;
    }
    return array('registros'=>$registros );
  }

  public function crearCategoria(){
    // echo "Hola ". $this->nombre;
    if(!empty ($this->nombre)){
      $sql="INSERT INTO categorias(nombre) VALUES('".$this->nombre ."')";
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
  public function editaCategoria(){
    if(!empty ($this->id)){
      $sql="UPDATE categorias set nombre ='".$this->nombre ."' WHERE id=". $this->id;
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
          "message" => "Error al editar categoría"
        );
      }
    }
    return array(
      "code" => 500,
      "ok" => false,
      "message" => "Error al editar categoría"
    );
  }
  public function eliminaCategoria(){
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
