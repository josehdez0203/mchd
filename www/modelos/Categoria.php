<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
class Categoria
{
    private $id;
    private $nombre;

    private $db = null;
    private $conf = null;


    public function __construct()
    {
        $this->conf = new Config();
        $this->db = new Conexion($this->conf->config['db']);
    }
    public function Listado($busqueda = "")
    {
        if($busqueda == "") {
            $sql = 'SELECT * FROM categorias ORDER BY nombre ASC';
        } else {
            $sql = "SELECT * FROM categorias
            WHERE nombre iLIKE '%$busqueda%' ORDER BY nombre ASC";
        }
        $registros = [];
        $consulta = $this->db->query($sql);
        while ($reg = $this->db->recorrer($consulta)) {
            // print_r($reg);
            $registros[] = $reg;
        }
        return $registros;
    }
}
