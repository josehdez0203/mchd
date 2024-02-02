<?php 
class Mail extends PHPMailer{

	function __construct($config){
		parent::__construct();
		//------------- Configuracion Inicial --------------------
		$this->IsSMTP();
		$this->SMTPDebug = 1;
		$this->SMTPAuth = $config['SMTPAuth'];
		$this->SMTPSecure = $config['SMTPSecure'];
		$this->Host = $config['Host'];
		$this->Port = $config['Port'];
		$this->Username = $config['Username'];
		$this->Password = $config['Password'];
		$this->SetFrom($config['SetFrom']);
		// $this->FromName = $config['FromName'];
		// $this->Sender = $config['Sender'];
		// $this->From = $config['From'];
		//----------- Confiuracion del usuario -------------------
	}

	
}
 ?>