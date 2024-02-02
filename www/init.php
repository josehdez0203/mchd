<?php 
//Constantes del sistema
	define("SYS_PATH", "lib/");
	define("APP_CONFIG", "config/");
	define("MODELS", "modelos/");
	define('SYS_JS_ASSETS', 'static/js/');
	define('SYS_CSS_ASSETS', 'static/css/');
	define('SYS_IMG_ASSETS', 'static/img/');
	//parametros de configuración
 	require APP_CONFIG . 'config.php';
	//Correo por php
	require SYS_PATH . 'phpmailer.php';
	require SYS_PATH . 'class.smtp.php';
	// require SYS_PATH . 'Model.php';
	 require SYS_PATH . 'Mail.php';
  	//Modelos
  require MODELS . 'Conexion.php';
	require MODELS . 'Usuario.php'; 
 ?>
