window.onload = function () {
  document.getElementById("enviar").onclick = function () {
    var connect;
    var email = document.getElementById("email").value;

    if (email != "") {
      console.log("verificando");
      var form = "email=" + email;
      console.log(form);
      connect = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      connect.onreadystatechange = function () {
        // console.log(connect);
        if (connect.readyState == 4 && connect.status == 200) {
          console.log(connect.responseText);
          var respuesta = JSON.parse(connect.responseText);
          console.log(respuesta);
          if (respuesta.code == 201 && respuesta.ok) {
            alert("Se envió un correo para recuperar contraseña");
            location.href = "/index.php";
          } else if (respuesta.code == 401 && !respuesta.ok) {
            alert("Email incorrecto o no existe");
          } else if (respuesta.code == 402) {
            alert(respuesta.message);
          }
        }
      };
      connect.open("POST", "api/checar_usuario.php", true);
      connect.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded",
      );
      connect.send(form);
    } else {
      alert("Ningún campo puede estar vacio");
    }
  };
};
