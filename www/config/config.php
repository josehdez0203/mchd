<?php
/** Clase para config de BD */
class Config{
  public $config;
  public function __construct(){
    $this->config = [
      'db' => [
        'host' => 'db',
        'port' => '3306',
        'dbname' => 'mchd',
        'user' => 'root',
        'pass' => 'test'
        // 'socket' => '/run/mysqld/mysqld.sock'
      ],
      'mail' => [
        'IsSMTP' => 'IsSMTP()',
        'SMTPAuth' => 'true',
        'SMTPSecure' => 'tls',
        // 'SMTPSecure' => 'ssl',
        // 'Port' => '465',
        'Host' => 'smtp.gmail.com',
        'Port' => '587',
        'Username' => 'josehernandezc@gmail.com',
        'Password' => 'qxoqczvdumvhdcsh',
        'SetFrom' => 'josehernandezc@gmail.com',
        'FromName' => 'Administrador',            //Nombre del que lo manda
        'Sender' => 'josehernandezc@gmail.com',   //email de quien lo manda
        'From'  => 'josehernandezc@gmail.com'
      ]
    ];
  }
}
?>
