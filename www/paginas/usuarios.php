<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Servidor</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="Estilopapa.css" />
  </head>
  <body>
    <form action="guardar.php" method="post">
        <div>
            <label>Nombre: </label><input type="text" name="nom" required/>
            </div>

            <div>
                <label>Apellido: </label><input type="text" name="ape" required/>
                </div>
    

          <div> <label> Direccion: </label>  <input type="text" name="dir" placeholder="Ej: Av. Bolívar 635" required />
          </div> 


          <div> <label>Genero: </label> <input type="text" name="gen" placeholder="Genero" required />
          </div> <br />
            
           <div>
            <label>Email:</label><input type="Correo_Electronico"  name="cor" placeholder="Email" required/>
          </div>
        
         <div> <label> Contraseña: </label>  <input type="password" name="pas" placeholder="*****" required />
          </div>
                            
      <div>
        <label>Teléfono</label>
        <input type="Telefono"  name="numt"
          placeholder="Ej. 3538899999"
          pattern="[0-9]{10}"
          required>
        </div>


       

        

          

        
      
      <div><input type="submit" value="Guardar Datos" /></div>
      <div><input type="reset" value="Reiniciar" /></div>
    </form>
  </body>
</html>