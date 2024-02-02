<?php  
	// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_WARNING);
	// error_reporting(0);


	class Usuario{
    private $id;
    private $nombre;
		private $email;
    private $tipo='usuario';
    private $telefono;
		private $password;
		private $foto = SYS_IMG_ASSETS.'usuarios/usuariox.png';
    private $direccion;
    private $activo=0;
    private $token;

		private $db = null;
		private $conf = null;


		function __construct(){
			$this->conf = new Config();
      // print_r($this->conf->config['db']);
			$this->db = new Conexion($this->conf->config['db']);
			$this->correo = new Mail($this->conf->config['mail']);
			// $this->sistema = $conf->config['sistema']['nombre'];
			// $this->jwt = new JWT();
		}

		private function Encript($string){
			$tam = strlen($string) -1;
			$resultado = "";
			for ($i=$tam; $i >=0 ; $i--){ 
				$resultado .= $string[$i];
			}
			return md5($resultado);
		}
    private function headers(){
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: POST, GET, PUT');
      header ("Access-Control-Allow-Credentials: true");
      header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Origin, X-Auth-Token");
      header("Content-Type: application/json; charset=UTF-8");
      // header("Access-Control-Allow-Headers: X-PINGOTHER, Content-Type");
      header("Access-Control-Max-Age: 3600"); //una hora (60*60)
      //Cargamos sesion
      session_start();
    }

		public function ApiLogin(){
			$this->conf->headers();
			$data = json_decode(file_get_contents("php://input"));
			// write_log(1, $data);
			
			if(!empty($data->user) and !empty($data->pass) ){
				$this->user = $this->db->real_escape_string($data->user);
				$this->pass = $this->Encript($data->pass);
				$consulta = $this->db->query("SELECT * FROM usuarios WHERE usuario='$this->user' AND password='$this->pass'");
				if($this->db->rows($consulta)>0){
					$datos = $this->db->recorrer($consulta);
					if($datos['activo']==0){
						// throw new Exception(3); //no esta activo
						http_response_code(401);
            echo json_encode(
							array(
								"ok"=> false,
                "message" => "Usuario inactivo"
							)
						);
					}
					else{
						$tiempo = time();
						$tokenData =  array(
							"id" => $datos['id'],
							"usuario" => $datos['usuario'],
							"email" => $datos['email'],
							"creditos" => $datos['creditos'],
							"admin" => $this->Encript($datos['admin'].'jhc'),
							"avatar" => $datos['avatar'],
							"nombre" => $datos['nombre'],
							"apellidos" => $datos['apellidos'],
							"vercreditos" => $datos['vercreditos']
						);
						$tokenData["conectado"] = $data->conectado;
						// write_log('jwt exp', $usuario->conectado);
						$token = $this->jwt->jwt_encode($tokenData);
						http_response_code(201);
            echo json_encode(
							array(
								"ok"=> true,
								"token" => $token,
								"datos" => $tokenData
							)
						);
					}
				}
				else{
					// throw new Exception(2); //error
					http_response_code(401);
          echo json_encode(
            array(
							"ok"=> false,
              "message" => "usuario o contraseña incorrectos"
            )
					);
				}
				$this->db->liberar($consulta);
				$this->db->close();
			}
			else{
				// throw new Exception("Error Datos vacios");
				http_response_code(401);
        echo json_encode(
          array(
						"ok"=> false,
            "message" => "Datos vacios"
          )
				);
			}
		}
		
		public function ApiRenovarToken(){
			$this->conf->headers();
			// $data = json_decode(file_get_contents("php://input"));
			$token = $this->jwt->checaToken();
			// write_log(100, print_r($data));
			$datos = $token['usuario'];
			$tiempo = time();
			$tokenData =  array(
       	"id" => $datos->id,
       	"usuario" => $datos->usuario,
       	"email" => $datos->email,
       	"creditos" => $datos->creditos,
       	"admin" => $this->Encript($datos->admin.'jhc'),
       	"avatar" => $datos->avatar,
       	"nombre" => $datos->nombre,
       	"apellidos" => $datos->apellidos,
       	"vercreditos" => $datos->vercreditos,
       	"conectado" => true
     	);
	    $token = $this->jwt->jwt_encode($tokenData);
			http_response_code(201);
      echo json_encode(
        array(
        		"ok"=> true,
            "token" => $token,
            "datos" => $tokenData
        )
			);
		}
public function ApiRegistro(){
    $this->headers();
    if($_POST){
      try{
				if(!empty($_POST['email']) and !empty($_POST['pass']) ){
					$this->nombre = $this->db->real_escape_string($_POST['nombre']);
					$this->email = $_POST['email'];
          $this->telefono = $_POST['tel'];
					$this->password = $this->Encript($_POST['pass']);
					$pass_enviable = $_POST['pass'];
					$host_link = $_SERVER['SERVER_NAME'];
					$consulta = $this->db->query("SELECT * FROM usuarios WHERE email='$this->email'");
					// echo "$consulta";
					if($this->db->rows($consulta) == 0){ // no hay usuario insertamos el usuario nuevo
						$hash = hash('sha512', $this->nombre.$this->email.$this->password, false);
            $consulta2 = $this->db->query("INSERT INTO 
              usuarios(id, nombre, email, tipo, telefono, password, foto, direccion, activo, token) 
            values(null, '$this->nombre', '$this->email', '$this->tipo', '$this->telefono', 
                  '$this->password', '$this->foto','',  0, '$hash')");
						if ($consulta2){
							//----------- Configuracion del Email  -------------------
							$this->correo->AddAddress($this->email);//correo destino
							$this->correo->Subject = 'Registro en el sistema';//titulo
							$this->correo->MsgHTML("Hola Bienvenido al sistema: <br>
	      				Estas son tus credenciales:<br>
				        <strong>Usuario: ".$this->email."</strong><br>
				        <strong>Password: ".$pass_enviable." </strong><br>
                <p>Haz click en el link para activar tu usuario</p>
                <a href='$host_link/activar?id=$hash'>Activar usuario</a>
                <p>Una vez activado tu usuario, ingresa a tu cuenta y actualiza tus datos, saludos</p>
                ");
              if($this->correo->Send()){
			        	// set response code
	  						http_response_code(200);
		            echo json_encode(
                  array(
                      "code"=>200,
			            		"ok"=> true,
			                "message" => "Registro exitoso"
			            )
	   						); //registro correcto
			        }
               else{
                    // set response code
                  http_response_code(200);
                  echo json_encode(
                    array(
                      "code"=>200,
                        "ok"=> false,
                        "message" => "Registro exitoso, Problema al enviar email"
                    )
                  ); //fallo email
                }
						}
						else{
							$mensaje = $this->nombre.", " .$this->email.", ". $this->password.", ". $this->foto ;
							// set response code
							http_response_code(500);
	            echo json_encode(
                array(
                  "code"=>500,
		            		"ok" => false,
		                "message" => "Error en el servidor",
		                "usuario" => $mensaje,
		            )
	 						); //registro incorrecto o problemas con BD
						}
					}
					else{
						$datos = $this->db->recorrer($consulta);
							// set response code
							http_response_code(401);
	            echo json_encode(
		            array(
                  "code"=> 401,
		            		"ok"=> false,
		                "message" => "Email existente"
		            )
	 						); //registro correcto
						// }
						$this->db->liberar($consulta);
						$this->db->close();
					}
				}
				else{
					// set response code
          $this->headers();
          header("Content-Type: application/json");
					http_response_code(501);
	        echo json_encode(
	          array(
              "code"=> 500,
	          		"ok"=> false,
	              "message" => "Datos vacios"
	          )
					); //registro correcto
				}

      }
			catch(Exception $error){
				echo $error->getMessage();
			}
    }
  }
		// public function ApiRegistro(){
  //     $this->headers();
		// 	$data = json_decode(file_get_contents("php://input"));
		// 	// $data = json_decode(file_get_contents("php://input"));
  //     // print('JHC');
  //     // print_r( $data);
		// 	// echo json_encode($data);
		// 	try{
		// 		if(!empty($data->email) and !empty($data->pass) ){
		// 			$this->nombre = $this->db->real_escape_string($data->nombre);
		// 			$this->email = $data->email;
  //         $this->telefono = $data->tel;
		// 			$this->password = $this->Encript($data->pass);
		// 			$pass_enviable = $data->pass;
		// 			$host_link = $_SERVER['SERVER_NAME'];
		// 			$consulta = $this->db->query("SELECT * FROM usuarios WHERE email='$this->email'");
		// 			// echo "$consulta";
		// 			if($this->db->rows($consulta) == 0){ // no hay usuario insertamos el usuario nuevo
		// 				$hash = hash('sha512', $this->nombre.$this->email.$this->password, false);
  //           $consulta2 = $this->db->query("INSERT INTO 
  //             usuarios(id, nombre, email, tipo, telefono, password, foto, direccion, activo, token) 
  //           values(null, '$this->nombre', '$this->email', '$this->tipo', '$this->telefono', 
  //                 '$this->password', '$this->foto','',  0, '$hash')");
		// 				if ($consulta2){
		// 					//----------- Configuracion del Email  -------------------
		// 					$this->correo->AddAddress($this->email);//correo destino
		// 					$this->correo->Subject = 'Registro en el sistema';//titulo
		// 					$this->correo->MsgHTML("Hola Bienvenido al sistema: <br>
	 //      				Estas son tus credenciales:<br>
		// 		        <strong>Usuario: ".$this->email."</strong><br>
		// 		        <strong>Password: ".$pass_enviable." </strong><br>
  //               <p>Haz click en el link para activar tu usuario</p>
  //               <a href='$host_link/activar?id=$hash'>Activar usuario</a>
  //               <p>Una vez activado tu usuario, ingresa a tu cuenta y actualiza tus datos, saludos</p>
  //               ");
  //             if($this->correo->Send()){
		// 	        	// set response code
	 //  						http_response_code(200);
		//             echo json_encode(
		// 	            array(
		// 	            		"ok"=> true,
		// 	                "message" => "Registro exitoso"
		// 	            )
	 //   						); //registro correcto
		// 	        }
  //              else{
  //                   // set response code
  //                 http_response_code(200);
  //                 echo json_encode(
  //                   array(
  //                       "ok"=> false,
  //                       "message" => "Registro exitoso, Problema al enviar email"
  //                   )
  //                 ); //fallo email
  //               }
		// 				}
		// 				else{
		// 					$mensaje = $this->nombre.", " .$this->email.", ". $this->password.", ". $this->foto ;
		// 					// set response code
		// 					http_response_code(500);
	 //            echo json_encode(
		//             array(
		//             		"ok" => false,
		//                 "message" => "Error en el servidor",
		//                 "usuario" => $mensaje,
		//             )
	 // 						); //registro incorrecto o problemas con BD
		// 				}
		// 			}
		// 			else{
		// 				$datos = $this->db->recorrer($consulta);
		// 					// set response code
		// 					http_response_code(401);
	 //            echo json_encode(
		//             array(
		//             		"ok"=> false,
		//                 "message" => "Email existente"
		//             )
	 // 						); //registro correcto
		// 				// }
		// 				$this->db->liberar($consulta);
		// 				$this->db->close();
		// 			}
		// 		}
		// 		else{
		// 			// set response code
  //         $this->headers();
  //         header("Content-Type: application/json");
		// 			http_response_code(500);
	 //        echo json_encode(
	 //          array(
  //             "code"=> 500,
	 //          		"ok"=> false,
	 //              "message" => "Datos vacios"
	 //          )
		// 			); //registro correcto
		// 		}
		// 	}
		// 	catch(Exception $error){
		// 		echo $error->getMessage();
		// 	}
		// }

		public function ApiActualizaCuenta(){
			$token = $this->jwt->checaToken();
			$usuario = $token['usuario'];
			$this->jwt->headers();
			$data = json_decode(file_get_contents("php://input"));
			$this->id =$usuario->id;
			$nombre = $data->nombre;
			$apellidos = $data->apellidos;
			// $foto =$usuario->avatar;
			// if(strlen($data->avatar)>0) $foto = $data->avatar;
			$password = $data->password;

			$passwordNuevo = $this->Encript($data->passwordNuevo);
			$consulta = $this->db->query("SELECT * FROM usuarios where id = $this->id");

			
			$reg = $this->db->recorrer($consulta);
			if($password != "" && $this->Encript( $password) == $reg['password']){
				$sql="UPDATE usuarios SET nombre= '$nombre', apellidos='$apellidos', password='$passwordNuevo' WHERE id= $this->id";
				$usuario->nombre = $nombre;
				$usuario->apellidos = $apellidos;
				$usuario->password = $data->passwordNuevo;
			}	
			else{
				$sql="UPDATE usuarios SET nombre= '$nombre', apellidos='$apellidos' WHERE id= $this->id";
				$usuario->nombre = $nombre;
				$usuario->apellidos = $apellidos;
			}
			if($this->db->query($sql) ){
				$usuario->avatar = $reg['avatar'];
				if($password != "" && $this->Encript($password) != $reg['password']){
					http_response_code(401);
	        echo json_encode(
	          array(
          		"ok"=> false,
              "message" => "La contraseña original no es correcta"
	          )
						);
				}
				else {
					http_response_code(200);
	        echo json_encode(
	          array(
          		"ok"=> true,
              "message" => "Datos Actualizados",
              'usuario' => json_encode($usuario)
	          )
						);
				}
			}
			else {
				http_response_code(500);
	      echo json_encode(
	        array(
        		"ok"=> false,
            "message" => "Error de BD",
            "sql" => $sql
	        )
				);
			}
		}

		public function ApiSubirFoto(){
			$token = $this->jwt->checaToken();
			$usuario = $token['usuario'];
			$this->jwt->headers();
			if ($_FILES['imagen']['size'] > 0 ){
				// print_r($_FILES);
	      $archivo = $_FILES["imagen"]["tmp_name"];
	      $ext = $_FILES['imagen']['name'];
	      $name = $ext;
	      $foto_ant = $usuario->avatar;
	      $ext = substr($ext, strpos($ext, '.', false));
	      $destino = SYS_IMG_ASSETS.'usuarios/'.$usuario->id."_".time().$ext;
	      move_uploaded_file($archivo, $destino);
	      $foto = $destino;
	      // Borrar la otra foto
	      $sql="UPDATE usuarios SET avatar='$destino' WHERE id= $usuario->id";
	      if($this->db->query($sql) ){
	      	$usuario->avatar = $destino;
	      	unlink($foto_ant);
	      	http_response_code(200);
	        echo json_encode(
	          array(
	        		"ok"=> true,
	            "message" => "Datos Actualizados",
	            'usuario' => json_encode($usuario)
	          )
					);
	      }
	      else{
	      	http_response_code(500);
	        echo json_encode(
	        	array(
	        		"ok"=> false,
	            "message" => "Error de BD",
	            'sql' => $sql
	          )
	      	);
	      }
	  	}
	  	else {
	  		http_response_code(401);
	      echo json_encode(
	      	array(
	      		"ok"=> false,
	          "message" => "No existe la imagen",
	          'imagen' => $name
	        )
	    	);
	  	}
		}

		public function ApiCambiaVerCreditos(){
			$token = $this->jwt->checaToken();
			$usuario = $token['usuario'];
			$this->jwt->headers();
			
			$id = $usuario->id;
			$que = $_GET['ver'];

			$sql="UPDATE usuarios SET vercreditos= $que WHERE id= $id";

			if($this->db->query($sql) ){
				http_response_code(200);
	      echo json_encode(
	        array(
	        		"ok"=> true,
	            "message" => "actualizado",
	            "ver" => $que
	        )
				);
			}
			else {
				http_response_code(500);
	      echo json_encode(
	        array(
	        		"ok"=> false,
	            "message" => "Error actualizaCreditos",
	            "sql" => $sql
	        )
				);
			}
		}

		public function ApiCargarCreditos(){
			$token = $this->jwt->checaToken();
			$usuario = $token['usuario'];
			$this->jwt->headers();
			
			$id = $usuario->id;

			$sql="SELECT * FROM usuarios WHERE id= $id";
			$consulta = $this->db->query($sql);
			if($consulta ){
				$reg = $this->db->recorrer($consulta);
				$creditos = $reg['creditos'];
				http_response_code(200);
	      echo json_encode(
	        array(
	        		"ok"=> true,
	            "message" => "actualizado",
	            "creditos" => $creditos
	        )
				);
			}
			else {
				http_response_code(500);
	      echo json_encode(
	        array(
	        		"ok"=> false,
	            "message" => "Error RecuperaCreditos",
	            "sql" => $sql
	        )
				);
			}
		}

		public function ApiToggleActivo(){
			$token = $this->jwt->checaToken();
			$usuario = $token['usuario'];
			$this->jwt->headers();
			
			$id = $_GET['id'];
			$activo = $_GET['activo'];
			if($activo == 1){
				// $activo = 0;
				$sql="UPDATE usuarios SET activo= 0 WHERE id= $id";
			}
			else{
				// $activo = 1;
				$sql="UPDATE usuarios SET activo= 1 WHERE id= $id";
			}

			$consulta = $this->db->query($sql);
			if($consulta ){
				$reg = $this->db->recorrer($consulta);
				$activo = $reg['activo'];
				http_response_code(200);
	      echo json_encode(
	        array(
	        		"ok"=> true,
	            "message" => "actualizado",
	            "activo" => $activo
	        )
				);
			}
			else {
				http_response_code(500);
	      echo json_encode(
	        array(
	        		"ok"=> false,
	            "message" => "Error RToggleActivo",
	            "sql" => $sql
	        )
				);
			}
		}

	//=================================================
		public function Recuperar($dato){
			$consulta = $this->db->query("SELECT * FROM usuarios where usuario = '$dato' OR email = '$dato'");
			if($this->db->rows($consulta) > 0){
				$reg = $consulta->fetch_assoc();
				$this->id = $reg['id'];
				$this->user = $reg['usuario'];
				$this->email = $reg['email'];
				$host_link = $_SERVER['SERVER_NAME'];
				$hash = hash('sha512', $this->user.$this->email, false);
				$consulta2 = $this->db->query("UPDATE usuarios SET activo = 1, token='$hash' WHERE id = $this->id");
				if ($consulta2){
					//----------- Configuracion del usuario -------------------
					$this->correo->AddAddress($this->email, $this->user);//correo destino
					$this->correo->Subject = 'CBTis 12: cambiar contraseña';//titulo
					$this->correo->MsgHTML("Hola: <strong>Usuario: ".$this->user."</strong>
		        <p>Para actualizar tu password de usuario de CBTis 12, ingresa en este link <a href='".$host_link."/actualizar/".$hash."'>Actualizar</a></p>"); 
	        if($this->correo->Send()) {
	            //echo 1; //registro correcto
	            return  array('mensaje' =>  "Se envió un link al usuario:<strong> $this->user</strong>,  para cambiar la contraseña!", 'status' => '1', 'link' => '/');
	        }else{
	            echo "Error: no se envió el mensaje";
	        }
				}
				else{
					throw new Exception('No se envió el Email');
				}
			}
			else{
				return  array('mensaje' =>  "Error,  No existe usuario o Email!", 'status' => '0', 'link' => '/recuperar');
			}
		}
		public function Verifica($id){
			$consulta = $this->db->query("SELECT * FROM usuarios where token = '$id'");
			if($this->db->rows($consulta) > 0){
				$reg = $consulta->fetch_assoc();
				if($reg['activo'] == 1){
					$mensaje = $reg['usuario'];
					return  array('mensaje' =>  "El usuario: $mensaje,  ya se encontraba activo!", 'status' => '1');
				}
				else{
					if($this->db->query("UPDATE usuarios SET activo=1 where token = '$id'") ){
						$mensaje = $reg['usuario'];
						return  array('mensaje' =>  "El usuario: $mensaje,  se activó correctamente!", 'status' => '0');
					}
					else {
						return  array('mensaje' =>  'Sucedio un error al activar un usuario!', 'status' => '1');
					}
				}
			}
			else{
				return  array('mensaje' => 'El usuario que deseas activar no existe!', 'status' => '1');
			}
		}

		public function Actualizar($id){
			$consulta = $this->db->query("SELECT * FROM usuarios where token = '$id'");
			if($this->db->rows($consulta) > 0){
				$reg = $consulta->fetch_assoc();
				if($reg['activo'] == 1){
					$mensaje = $reg['usuario'];
					return  array('mensaje' =>  "El usuario: $mensaje,  ya se encuentra activo!", 'status' => '1');
				}
				else{
					$this->id = $reg['id'];
					$this->user = $reg['usuario'];
					return  array('id' => $this->id, 'usuario' => $this->user);
				}
			}
			else{
				return  array('mensaje' => 'El usuario que deseas activar no existe!', 'status' => '1', 'link' => '/');
			}
		}

		public function  CambiaClave($id, $clave){
			$consulta = $this->db->query("SELECT * FROM usuarios where id = $id");
			if($this->db->rows($consulta) > 0){
				$reg = $consulta->fetch_assoc();
				if($reg['activo'] == 0){
					$this->id = $reg['id'];
						$this->user = $reg['usuario'];
						$this->pass = $this->Encript($clave);
					if($this->db->query("UPDATE usuarios SET activo = 1, password = '$this->pass' WHERE id= $id")){
						return  array('mensaje' =>  "El usuario: $this->user,  actualizo su contraseña!", 'status' => '0');
					}
					else{
						return  array('mensaje' =>  "Error no se actualizo la contraseña!", 'status' => '1');
					}
				}
				else{
				return  array('mensaje' => 'El usuario  no existe!', 'status' => '1', 'link' => '/');
				}
			}
		}
	}
?>
