<!doctype html>
<html>
    <head>
        <title>Modificar</title>
    </head>
    <body>
        
        <center>
           
               <?php
                    $Numero= $_REQUEST['Numero'];
                    
                    include("conexion.php");
                    $query= "SELECT * FROM reg_usuario WHERE Numero='$Numero'";
                    $resultado=$conexion->query($query);
                    $row=$resultado->fetch_assoc();   				
                    ?>
                      
            <form action="actualizar.php? Numero=<?php echo $row['Numero']; ?>" method="POST"> 
               
                       
             <div>
            <label>Nombre: </label><input type="text" required name="nom" value="<?php echo $row['Nombre'];?>"/><br><br>
            </div>

            <div>
                <label>Apellido: </label><input type="text" required name="ape" value="<?php echo $row['Apellido'];?>"/><br><br>
                </div>
    

          <div> <label> Direccion: </label>  <input type="text" required name="dir" placeholder="Ej: Av. Bolívar 635" value="<?php echo $row['Direccion'];?>"/><br><br>
          </div> 


          <div> <label>Genero: </label> <input type="text" required name="gen" placeholder="Genero" value="<?php echo $row['Genero'];?>"/><br><br>
          </div> <br />
            
           <div>
            <label>Email:</label><input type="Correo_Electronico" required name="cor" value="<?php echo $row['Email'];?>"/><br><br>
          </div>
        
         <div> <label> Contraseña: </label>  <input type="password" required name="pas" placeholder="*****" value="<?php echo $row['Password'];?>"/><br><br>
          </div>
                            
      <div>
        <label>Teléfono</label>
        <input type="Telefono"  required name="numt" placeholder="Ej. 3538899999" pattern="[0-9]{10}" value="<?php echo $row['Telefono'];?>"/><br><br>
        </div>

                        
       
        <input type="submit" value="Actualizar" / >
         <input type="reset" value="Borrar" / ><br><br>
                
            
            </form>
        </center>
    </body>
</html>
