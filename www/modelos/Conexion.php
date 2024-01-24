<?php  
	class Conexion extends mysqli{
		
		function __construct($config){
			parent::__construct($config['host'], 
			$config['user'], $config['pass'], $config['dbname']);
			$this->query('SET NAMES utf8');
			$this->connect_errno ? die('Error de Conexion'): null;
		}

		public function rows($x){
			return mysqli_num_rows($x);
		}

		public function recorrer($x){
			return mysqli_fetch_array($x);
		}

		public function liberar($x){
			return mysqli_free_result($x);
		}

		public function preparado(){
			return mysqli_stmt_init($x);
		}
		
	}
?>
