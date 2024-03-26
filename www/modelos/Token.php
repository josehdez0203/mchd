
<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
class Token
{
    private $id;
    private $token;

    private $db = null;
    private $conf = null;


    public function __construct()
    {
        $this->conf = new Config();
        $this->db = new Conexion($this->conf->config['db']);
    }
    public function ApiCrearToken($token){
      
      if(strlen($token)>0){
        $duracion= new DateTime('now');
        date_modify($duracion, '+3 hours');
        // print_r($duracion);
        $tiempo = $duracion->format('Y-m-d H:i:s');
        $sql="INSERT INTO tokens(token, timestamp) VALUES('$token', '$tiempo')";
        $consulta  = $this->db->query($sql);
        if ($consulta){
          return true;
        }else{
          return false;
        }
      }
    }

    public function ApiChecarToken($token)
    {
      // print_r($token);
      if(strlen($token )>0){
        $sql = "SELECT * FROM tokens
        WHERE token =  '$token' AND terminado = 0";
        $consulta = $this->db->query($sql);
        $reg=null;
        if ($this->db->rows($consulta)>0){
          $reg = $this->db->recorrer($consulta);
          $tiempo = new DateTime($reg['timestamp']);
          // Comparar y ver si se venció el token
          $hoy = new DateTime("now");
          // echo ($hoy>$tiempo);
          if(!($hoy > $tiempo)){ 
            return 1;
          }else{
            return 0;
          }
        }
        return 0;
      }
      return 0;
    }
        public function ApiTerminarToken ($token){
      $sql = "SELECT * FROM tokens
        WHERE token =  '$token' AND terminado = 0";
        $consulta = $this->db->query($sql);
        $reg=null;
        if ($this->db->rows($consulta)>0){
          $reg = $this->db->recorrer($consulta);
          $id=$reg['id'];
          $sql2="UPDATE tokens set terminado=1 WHERE id=$id";
          $consulta2=$this->db->query($sql2);
          if($consulta2){
            return true;
          }else {
            return null;
          }
        }else{
          return null;
        }
        return null;
    }
}
