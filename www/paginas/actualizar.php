<?php
include("conexion.php");

$Numero =$_REQUEST['Numero'];
$Nombre =$_POST['nom'];
$Apellido =$_POST['ape'];
$Direccion =$_POST['dir'];
$Genero =$_POST['gen'];
$Correo =$_POST['cor'];
$Pass= $_POST['pas'];
$Num =$_POST['numt'];


$query="UPDATE reg_usuario SET Nombre='$Nombre',Apellido='$Apellido',Direccion='$Direccion',Genero='$Genero',Email='$Correo',Password='$Pass',Telefono='$Num' WHERE Numero='$Numero'";

$resultado= $conexion->query($query);

if($resultado){
    

 header ("Location: llenarT.php");
}else{
    echo "inserccion no exitosa";
}
?>
