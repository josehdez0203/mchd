<!doctype html>
<html>
    <head>
        <title>tabla</title>
    </head>
    <body>
        <center>
            
            <table border=3>
                <thead>
                    <tr>
                        <th colspan="1"><a href="usuarios.php">NUEVO</a></th>
                        <th colspan="7">LISTA DE USUARIOS</th>
                        <th colspan="2">FUNCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Numero</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>Direccion</td>
                        <td>Genero</td>
                        <td>Email</td>
                        <td>Password</td>
                        <td>Telefono</td>
                        
                        <td>Modificar</td>
                        <td>Eliminar</td>
                    </tr>
                    <?php
                    include("conexion.php");
                    $query="SELECT * FROM reg_usuario";
                    $resultado=$conexion->query($query);
                    while($row=$resultado->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $row['Numero'];?></td>
                        <td><?php echo $row['Nombre'];?></td> 
                        <td><?php echo $row['Apellido'];?></td>
                        <td><?php echo $row['Direccion'];?></td>
                        <td><?php echo $row['Genero'];?></td>
                        <td><?php echo $row['Email'];?></td>
                        <td><?php echo $row['Password'];?></td>
                        <td><?php echo $row['Telefono'];?></td>
                        
                        <td><a href="modificar.php? Numero=<?php echo $row['Numero']; ?>">Modificar</a></td>
                        
                        <td><a href="eliminar.php? Numero=<?php echo $row['Numero']; ?>">Eliminar</a></td>
                    </tr>    
                    
                    <?php
                    }
                    
                    ?>
                </tbody>
            </table>
        </center>
    </body>
</html>
