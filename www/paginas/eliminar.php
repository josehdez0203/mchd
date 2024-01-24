<?php
include("conexion.php");
$Numero =$_REQUEST['Numero'];


$query="DELETE from reg_usuario WHERE Numero='$Numero'";
$resultado=$conexion->query($query);

if($resultado){
    
    header("location:llenarT.php");
}else{
    echo "No se pudo eliminar el registro";
}
?>
