<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
// error_reporting(0);
//parametros de configuración
// require '/config/config.php';
//Correo por php
require_once '../lib/phpmailer.php';
require_once '../lib/class.smtp.php';
// require SYS_PATH . 'Model.php';
require_once '../lib/Mail.php';
//Modelos
require_once '../modelos/Conexion.php';


class Usuario
{
  private $id;
  private $nombre;
  private $email;
  private $tipo = 'usuario';
  private $telefono;
  private $password;
  private $pass_enviable;
  private $foto =  'static/img/usuarios/usuariox.png';
  private $direccion;
  private $activo = 0;
  private $token;

  private $db = null;
  private $conf = null;
  private $correo = null;
 
  public function __construct($nombre="", $email="", $tel="", $pass="")
  {
    $this->conf = new Config();
    $this->db = new Conexion($this->conf->config['db']);
    $this->correo = new Mail($this->conf->config['mail']);
    $this->nombre = $nombre;
    $this->email = $email;
    $this->telefono = $tel;
    $this->password = $this->Encript($pass);
    $this->pass_enviable = $pass;
  }

  private function Encript($string)
  {
    $tam = strlen($string) - 1;
    $resultado = "";
    for ($i = $tam; $i >= 0; $i--) {
      $resultado .= $string[$i];
    }
    return md5($resultado);
  }

  public function ApiRegistro(){
    // echo $this->email;
    try {
      if (!empty($this->email) and !empty($this->password)) {
        $host_link = $_SERVER['SERVER_NAME'];
        $consulta = $this->db->query("SELECT * FROM usuarios WHERE email='$this->email'");
        if ($this->db->rows($consulta) == 0) { // no hay usuario insertamos el usuario nuevo
          $hash = hash('sha512', $this->nombre . $this->email . $this->password, false);
          $sql="INSERT INTO
              usuarios(id, nombre, email, tipo, telefono, password, foto, direccion, activo, token, info) 
            values(null, '$this->nombre', '$this->email', '$this->tipo', '$this->telefono', 
          '$this->password', '$this->foto','',  0, '$hash','')";
          // echo $sql;
          $consulta2 = $this->db->query($sql);
          // echo $consulta2;
          if ($consulta2) {
            //----------- Configuracion del Email  -------------------
            $this->correo->AddAddress($this->email); //correo destino
            $this->correo->Subject = 'Registro en el sistema'; //titulo
            $texto="Hola Bienvenido al sistema: <br>Estas son tus credenciales:<br>";
            $texto=$texto."<strong>Usuario: $this->nombre </strong><br>";
            $texto=$texto."<strong>Email: $this->email </strong><br>";
            $texto=$texto."<strong>Password: $this->pass_enviable </strong><br>";
            $texto=$texto."<p>Haz click en el link para activar tu usuario</p>";
            $texto=$texto."<a href='$host_link/activar.php?id=$hash'>Activar usuario</a>";
            $texto=$texto."<p>Una vez activado tu usuario, ingresa a tu cuenta y actualiza tus datos, saludos</p>";
            $this->correo->MsgHTML($texto);
            // $this->db->liberar($consulta2);
            // $this->db->close();
            if ($this->correo->Send()) {
              return 201; //registro y email
            } else {
              return 202; //registro sin email
            }
          } else {
            return 501; //Error de servidor
          }
        } else {
          $this->db->liberar($consulta);
          $this->db->close();
          return 401;  //Usuario existente                      ); //registro correcto
        }
        $this->db->liberar($consulta);
        $this->db->close();
      } else {
        return 404;
      }
    } catch (Exception $error) {
      echo $error->getMessage();
    }
  }

  public function ApiLogin(){
    if (!empty($this->email) or !empty($this->password)) {
      // echo $this->email;
      $consulta = $this->db->query("SELECT * FROM usuarios 
              WHERE email='$this->email' AND password='$this->password'");
      if ($this->db->rows($consulta) > 0) {
        $datos = $this->db->recorrer($consulta);
        if ($datos['activo'] == 0) {
          return array(
            "code" => 402,
            "ok" => false,
            "message"=>'El usuario está inactivo, activar desde el email'
          );
        } else {
          return array(
            "code" => 201,
            "ok" => true,
            "message"=>$datos
          );
        }
      } else {
        return array(
            "code" => 401,
            "ok" => false,
            "message"=>'usuario o contraseña incorrectos'
          );
      }
      $this->db->liberar($consulta);
      $this->db->close();
    } else {
      return array(
            "code" => 404,
            "ok" => false,
            "message" => "Datos vacios"
          );
    }
  }
  public  function Verifica($id):Array{
    $consulta = $this->db->query("SELECT * FROM usuarios WHERE token='$id' ");
    // echo $id;
    if ($this->db->rows($consulta) > 0) {
      // echo  'nombre';
      $reg = $this->db->recorrer($consulta);
      if ($reg['activo'] == 1) {
        $mensaje = $reg['nombre'];
        return array(
          'code' =>201, 
          'ok'=> false,
          'mensaje'=>"El usuario: $mensaje,  ya se encontraba activo!"
        );
      } else {
        if ($this->db->query("UPDATE usuarios SET activo=1 where token = '$id'")) {
          return array(
            'code' =>201, 
            'ok'=> true,
            'mensaje'=>"El usuario: $mensaje,  se activó correctamente!"
          );
        } else {
          return array(
            'code' =>500, 
            'ok'=> false,
            'mensaje'=>"Sucedio un error al activar un usuario!"
          );
        }
      }
    } else {
      return array(
        'code' =>404, 
        'ok'=> false,
        'mensaje'=>"El usuario que buscas no se encuentra"
      );
    }
  }
    public function usuario($id){
    if ($id != "") {
      $sql = 'SELECT * FROM usuarios WHERE id='.$id;
      // echo 'consulta '.$sql;
      $consulta = $this->db->query($sql);
      if($this->db->rows($consulta)>0){
        return array(
          'code' =>201, 
          'ok'=> true,
          'usuario'=> $this->db->recorrer($consulta)
        );
      }else{
        return array(
          'code' =>404, 
          'ok'=> false,
          'message'=> "usuario no encontrado"
        );
      }
    }
    return array(
      'code' =>401, 
      'ok'=> false,
      'message'=> "datos incompletos"
    );
  }
  public function cambiaUsuario($id, $datos){
    if ($id != "") {
    // $datos = json_decode(file_get_contents('php://input'), true);
    // WHERE id='.$id;
      $sql = 'UPDATE usuarios SET ';
      $campos=array();
      if(isset($datos['direccion'])){
        $campos[] = "direccion='".$datos['direccion']."'";
      }
      if(isset($datos['foto'])){
        $campos[] = "foto='".$datos['foto']."'";
      }

      if(isset($datos['info'])){
        $campos[] = "info='".$datos['info']."'";
      }
      if(isset($datos['pass'])){
        $pass=$this->Encript($datos['pass']);
        $campos[] = "password='".$pass."'";
      }
      $sql=$sql . implode(", ",$campos). " WHERE id=".$id;

      // echo 'consulta '.$sql;
      $consulta = $this->db->query($sql);
      if($consulta){
        return array(
          'code' =>201, 
          'ok'=> true,
          'message'=>"Usuario modificado" 
        );
      }else{
        return array(
          'code' =>404, 
          'ok'=> false,
          'message'=> "usuario no encontrado"
        );
      }
    }
    return array(
      'code' =>401, 
      'ok'=> false,
      'message'=> "datos incompletos"
    );
  }

  public function Listado(){
    $regXpag=6;
    $pag=$_GET['pag'];
    $buscar=$_GET['buscar'];
    if ($buscar == "") {
      $sql = 'SELECT * FROM usuarios ORDER BY nombre ASC LIMIT '.(($pag-1)*$regXpag). ",$regXpag";
    } else {
      $sql = "SELECT * FROM usuarios
      WHERE nombre LIKE '%$buscar%' ORDER BY nombre ASC 
      LIMIT ".(($pag-1)*$regXpag). ",$regXpag";
    }
    $registros =array();
    // echo 'consulta '.$sql;
    $consulta = $this->db->query($sql);
    if($this->db->rows($consulta)>0){
      while ($reg = $this->db->recorrer($consulta)) {
        // print_r($reg);
        $registros[] = $reg;
      }
    }
    if($buscar==""){
      $sql = 'SELECT count(id) AS total FROM usuarios';
    }else{
      $sql = "SELECT count(id) AS total FROM usuarios WHERE nombre LIKE '%$buscar%'";
    }
    $consulta2=$this->db->query($sql);
    $total=$this->db->recorrer($consulta2);
    $paginas= ceil($total['total'] / $regXpag);
    return array(
      'registros'=>$registros,
      'total'=> $total["total"], 
      'paginas'=>$paginas, 'pag'=>$pag
    );
  }


  public function ApiToggleActivo()
  {
    $id = $_GET['id'];
    $activo = $_GET['activo'];
    if ($activo == 1) {
      // $activo = 0;
      $sql = "UPDATE usuarios SET activo= 0 WHERE id= $id";
    } else {
      // $activo = 1;
      $sql = "UPDATE usuarios SET activo= 1 WHERE id= $id";
    }
    $consulta = $this->db->query($sql);
    if ($consulta) {
      $reg = $this->db->recorrer($consulta);
      $activo = $reg['activo'];
      http_response_code(200);
      echo json_encode(
        array(
          "ok" => true,
          "message" => "actualizado",
          "activo" => $activo
        )
      );
    } else {
      http_response_code(500);
      echo json_encode(
        array(
          "ok" => false,
          "message" => "Error RToggleActivo",
          "sql" => $sql
        )
      );
    }
  }

  // public function ApiRecuperar()
  // {
  //   if (!empty($_POST['email'])) {
  //     $this->email = $_POST['email'];
  //     $consulta = $this->db->query("SELECT * FROM usuarios WHERE email='$this->email' ");
  //     if ($this->db->rows($consulta) > 0) {
  //       $datos = $this->db->recorrer($consulta);
  //       $token = $datos['token'];
  //       $host_link = $_SERVER['SERVER_NAME'];
  //       //----------- Configuracion del Email  -------------------
  //       $this->correo->AddAddress($this->email); //correo destino
  //       $this->correo->Subject = 'Recuperar password'; //titulo
  //       $this->correo->MsgHTML("<p>Haz click en el link para recuperar tu contraseña</p>
  //               <a href='$host_link/cambiar_password.php?id=$token'>Cambiar contraseña</a>");
  //       $tokens = new Token();
  //       $resp = $tokens->ApiCrearToken($token);
  //       if ($resp) {
  //         if ($this->correo->Send()) {
  //           // set response code
  //           http_response_code(200);
  //           echo json_encode(
  //             array(
  //               "code" => 201,
  //               "ok" => true,
  //               "message" => "Envio exitoso"
  //             )
  //           ); //registro correcto
  //         } else {
  //           // set response code
  //           http_response_code(200);
  //           echo json_encode(
  //             array(
  //               "code" => 201,
  //               "ok" => false,
  //               "message" => "Problema al enviar email"
  //             )
  //           );
  //         }
  //       } else {
  //         http_response_code(401);
  //         echo json_encode(
  //           array(
  //             "code" => 401,
  //             "ok" => false,
  //             "message" => "No se creo el token"
  //           )
  //         );
  //       }
  //     } else {
  //       // throw new Exception(2); //error
  //       http_response_code(401);
  //       echo json_encode(
  //         array(
  //           "code" => 401,
  //           "ok" => false,
  //           "message" => "Correo no encontrado"
  //         )
  //       );
  //     }
  //     $this->db->liberar($consulta);
  //     $this->db->close();
  //   } else {
  //     // throw new Exception("Error Datos vacios");
  //     http_response_code(401);
  //     echo json_encode(
  //       array(
  //         "ok" => false,
  //         "message" => "Datos vacios"
  //       )
  //     );
  //   }
  // }
  // public function ApiCambiarPassword()
  // {
  //   if (!empty($_POST['token']) && !empty($_POST['pass1'])) {
  //     $this->token = $_POST['token'];
  //     $this->password = $this->Encript($_POST['pass1']);
  //     $consulta = $this->db->query("SELECT * FROM usuarios WHERE token='$this->token'");
  //     if ($this->db->rows($consulta) > 0) {
  //       $datos = $this->db->recorrer($consulta);
  //       $id = $datos['id'];
  //       $consulta2 = $this->db->query("UPDATE usuarios set password='$this->password'
  //         WHERE id= $id");
  //       if ($consulta2) {
  //         $token = $_POST['token'];
  //         $tokens = new Token();
  //         $tokens->ApiTerminarToken($token);
  //         http_response_code(201);
  //         echo json_encode(
  //           array(
  //             "code" => 201,
  //             "ok" => true,
  //             "message" => "Constraseña cambiada"
  //           )
  //         );
  //       } else {
  //         // throw new Exception(2); //error
  //         http_response_code(401);
  //         echo json_encode(
  //           array(
  //             "code" => 401,
  //             "ok" => false,
  //             "message" => "Error al cambiar contraseña"
  //           )
  //         );
  //       }
  //       $this->db->liberar($consulta);
  //       $this->db->close();
  //     } else {
  //       // throw new Exception("Error Datos vacios");
  //       http_response_code(401);
  //       echo json_encode(
  //         array(
  //           "code" => 401,
  //           "ok" => false,
  //           "message" => "Datos vacios"
  //         )
  //       );
  //     }
  //   }
  // }

  
  // public function ApiRenovarToken()
  // {
  //     $this->conf->headers();
  //     // $data = json_decode(file_get_contents("php://input"));
  //     $token = $this->jwt->checaToken();
  //     // write_log(100, print_r($data));
  //     $datos = $token['usuario'];
  //     $tiempo = time();
  //     $tokenData =  array(
  //         "id" => $datos->id,
  //         "usuario" => $datos->usuario,
  //         "email" => $datos->email,
  //         "creditos" => $datos->creditos,
  //         "admin" => $this->Encript($datos->admin . 'jhc'),
  //         "avatar" => $datos->avatar,
  //         "nombre" => $datos->nombre,
  //         "apellidos" => $datos->apellidos,
  //         "vercreditos" => $datos->vercreditos,
  //         "conectado" => true
  //     );
  //     $token = $this->jwt->jwt_encode($tokenData);
  //     http_response_code(201);
  //     echo json_encode(
  //         array(
  //             "ok" => true,
  //             "token" => $token,
  //             "datos" => $tokenData
  //         )
  //     );
  // }


  // public function ApiActualizaCuenta()
  // {
  //   $token = $this->jwt->checaToken();
  //   $usuario = $token['usuario'];
  //   $this->jwt->headers();
  //   $data = json_decode(file_get_contents("php://input"));
  //   $this->id = $usuario->id;
  //   $nombre = $data->nombre;
  //   $apellidos = $data->apellidos;
  //   // $foto =$usuario->avatar;
  //   // if(strlen($data->avatar)>0) $foto = $data->avatar;
  //   $password = $data->password;

  //   $passwordNuevo = $this->Encript($data->passwordNuevo);
  //   $consulta = $this->db->query("SELECT * FROM usuarios where id = $this->id");


  //   $reg = $this->db->recorrer($consulta);
  //   if ($password != "" && $this->Encript($password) == $reg['password']) {
  //     $sql = "UPDATE usuarios SET nombre= '$nombre', apellidos='$apellidos', password='$passwordNuevo' WHERE id= $this->id";
  //     $usuario->nombre = $nombre;
  //     $usuario->apellidos = $apellidos;
  //     $usuario->password = $data->passwordNuevo;
  //   } else {
  //     $sql = "UPDATE usuarios SET nombre= '$nombre', apellidos='$apellidos' WHERE id= $this->id";
  //     $usuario->nombre = $nombre;
  //     $usuario->apellidos = $apellidos;
  //   }
  //   if ($this->db->query($sql)) {
  //     $usuario->avatar = $reg['avatar'];
  //     if ($password != "" && $this->Encript($password) != $reg['password']) {
  //       http_response_code(401);
  //       echo json_encode(
  //         array(
  //           "ok" => false,
  //           "message" => "La contraseña original no es correcta"
  //         )
  //       );
  //     } else {
  //       http_response_code(200);
  //       echo json_encode(
  //         array(
  //           "ok" => true,
  //           "message" => "Datos Actualizados",
  //           'usuario' => json_encode($usuario)
  //         )
  //       );
  //     }
  //   } else {
  //     http_response_code(500);
  //     echo json_encode(
  //       array(
  //         "ok" => false,
  //         "message" => "Error de BD",
  //         "sql" => $sql
  //       )
  //     );
  //   }
  // }

  // public function ApiSubirFoto()
  // {
  //   $token = $this->jwt->checaToken();
  //   $usuario = $token['usuario'];
  //   $this->jwt->headers();
  //   if ($_FILES['imagen']['size'] > 0) {
  //     // print_r($_FILES);
  //     $archivo = $_FILES["imagen"]["tmp_name"];
  //     $ext = $_FILES['imagen']['name'];
  //     $name = $ext;
  //     $foto_ant = $usuario->avatar;
  //     $ext = substr($ext, strpos($ext, '.', false));
  //     $destino = SYS_IMG_ASSETS . 'usuarios/' . $usuario->id . "_" . time() . $ext;
  //     move_uploaded_file($archivo, $destino);
  //     $foto = $destino;
  //     // Borrar la otra foto
  //     $sql = "UPDATE usuarios SET avatar='$destino' WHERE id= $usuario->id";
  //     if ($this->db->query($sql)) {
  //       $usuario->avatar = $destino;
  //       unlink($foto_ant);
  //       http_response_code(200);
  //       echo json_encode(
  //         array(
  //           "ok" => true,
  //           "message" => "Datos Actualizados",
  //           'usuario' => json_encode($usuario)
  //         )
  //       );
  //     } else {
  //       http_response_code(500);
  //       echo json_encode(
  //         array(
  //           "ok" => false,
  //           "message" => "Error de BD",
  //           'sql' => $sql
  //         )
  //       );
  //     }
  //   } else {
  //     http_response_code(401);
  //     echo json_encode(
  //       array(
  //         "ok" => false,
  //         "message" => "No existe la imagen",
  //         'imagen' => $name
  //       )
  //     );
  //   }
  // }

  // public function ApiCambiaVerCreditos()
  // {
  //   $token = $this->jwt->checaToken();
  //   $usuario = $token['usuario'];
  //   $this->jwt->headers();

  //   $id = $usuario->id;
  //   $que = $_GET['ver'];

  //   $sql = "UPDATE usuarios SET vercreditos= $que WHERE id= $id";

  //   if ($this->db->query($sql)) {
  //     http_response_code(200);
  //     echo json_encode(
  //       array(
  //         "ok" => true,
  //         "message" => "actualizado",
  //         "ver" => $que
  //       )
  //     );
  //   } else {
  //     http_response_code(500);
  //     echo json_encode(
  //       array(
  //         "ok" => false,
  //         "message" => "Error actualizaCreditos",
  //         "sql" => $sql
  //       )
  //     );
  //   }
  // }

  // public function ApiCargarCreditos()
  // {
  //   $token = $this->jwt->checaToken();
  //   $usuario = $token['usuario'];
  //   $this->jwt->headers();

  //   $id = $usuario->id;

  //   $sql = "SELECT * FROM usuarios WHERE id= $id";
  //   $consulta = $this->db->query($sql);
  //   if ($consulta) {
  //     $reg = $this->db->recorrer($consulta);
  //     $creditos = $reg['creditos'];
  //     http_response_code(200);
  //     echo json_encode(
  //       array(
  //         "ok" => true,
  //         "message" => "actualizado",
  //         "creditos" => $creditos
  //       )
  //     );
  //   } else {
  //     http_response_code(500);
  //     echo json_encode(
  //       array(
  //         "ok" => false,
  //         "message" => "Error RecuperaCreditos",
  //         "sql" => $sql
  //       )
  //     );
  //   }
  // }

  
  //====================Recuerar contraseña=============================
  // public function Recuperar($dato)
  // {
  //     $consulta = $this->db->query("SELECT * FROM usuarios where usuario = '$dato' OR email = '$dato'");
  //     if ($this->db->rows($consulta) > 0) {
  //         $reg = $consulta->fetch_assoc();
  //         $this->id = $reg['id'];
  //         $this->user = $reg['usuario'];
  //         $this->email = $reg['email'];
  //         $host_link = $_SERVER['SERVER_NAME'];
  //         $hash = hash('sha512', $this->user . $this->email, false);
  //         $consulta2 = $this->db->query("UPDATE usuarios SET activo = 1, token='$hash' WHERE id = $this->id");
  //         if ($consulta2) {
  //             //----------- Configuracion del usuario -------------------
  //             $this->correo->AddAddress($this->email, $this->user); //correo destino
  //             $this->correo->Subject = 'CBTis 12: cambiar contraseña'; //titulo
  //             $this->correo->MsgHTML("Hola: <strong>Usuario: " . $this->user . "</strong>
  // 	        <p>Para actualizar tu password de usuario de CBTis 12, ingresa en este link <a href='" . $host_link . "/actualizar/" . $hash . "'>Actualizar</a></p>");
  //             if ($this->correo->Send()) {
  //                 //echo 1; //registro correcto
  //                 return  array('mensaje' =>  "Se envió un link al usuario:<strong> $this->user</strong>,  para cambiar la contraseña!", 'status' => '1', 'link' => '/');
  //             } else {
  //                 echo "Error: no se envió el mensaje";
  //             }
  //         } else {
  //             throw new Exception('No se envió el Email');
  //         }
  //     } else {
  //         return  array('mensaje' =>  "Error,  No existe usuario o Email!", 'status' => '0', 'link' => '/recuperar');
  //     }
  // }
  //====================Activar usuario =============================

  // public function Actualizar($id)
  // {
  //   $consulta = $this->db->query("SELECT * FROM usuarios where token = '$id'");
  //   if ($this->db->rows($consulta) > 0) {
  //     $reg = $consulta->fetch_assoc();
  //     if ($reg['activo'] == 1) {
  //       $mensaje = $reg['usuario'];
  //       return  array('mensaje' =>  "El usuario: $mensaje,  ya se encuentra activo!", 'status' => '1');
  //     } else {
  //       $this->id = $reg['id'];
  //       $this->user = $reg['usuario'];
  //       return  array('id' => $this->id, 'usuario' => $this->user);
  //     }
  //   } else {
  //     return  array('mensaje' => 'El usuario que deseas activar no existe!', 'status' => '1', 'link' => '/');
  //   }
  // }

  // public function  CambiaClave($id, $clave)
  // {
  //   $consulta = $this->db->query("SELECT * FROM usuarios where id = $id");
  //   if ($this->db->rows($consulta) > 0) {
  //     $reg = $consulta->fetch_assoc();
  //     if ($reg['activo'] == 0) {
  //       $this->id = $reg['id'];
  //       $this->user = $reg['usuario'];
  //       $this->pass = $this->Encript($clave);
  //       if ($this->db->query("UPDATE usuarios SET activo = 1, password = '$this->pass' WHERE id= $id")) {
  //         return  array('mensaje' =>  "El usuario: $this->user,  actualizo su contraseña!", 'status' => '0');
  //       } else {
  //         return  array('mensaje' =>  "Error no se actualizo la contraseña!", 'status' => '1');
  //       }
  //     } else {
  //       return  array('mensaje' => 'El usuario  no existe!', 'status' => '1', 'link' => '/');
  //     }
  //   }
  // }
}
