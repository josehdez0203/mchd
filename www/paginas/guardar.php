<?php
include("conexion.php");
$Nombre =$_POST['nom'];
$Apellido =$_POST['ape'];
$Direccion =$_POST['dir'];
$Genero =$_POST['gen'];
$Correo =$_POST['cor'];
$Pass= $_POST['pas'];
$Num =$_POST['numt'];

$query="INSERT INTO reg_usuario(Nombre,Apellido,Direccion,Genero,Email,Password,Telefono)
VALUES('$Nombre','$Apellido','$Direccion','$Genero','$Correo','$Pass','$Num')";

$resultado= $conexion->query($query);

if($resultado){
    

 header ("Location:llenarT.php");
}else{
    echo "inserccion no exitosa";
}
?>
