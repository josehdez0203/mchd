window.onload = function () {
  document.getElementById("enviar").onclick = function () {
    var connect;
    var pass1 = document.getElementById("pass1").value;
    var pass2 = document.getElementById("pass2").value;
    var token = document.getElementById("token").value;

    if (pass1 != "" && pass2 != "" && token != "") {
      if (pass2 === pass1) {
        console.log("verificando");
        var form = "token=" + token + "&pass1=" + pass1;
        console.log(form);
        connect = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        connect.onreadystatechange = function () {
          alert(connect);
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
        connect.open("POST", "api/cambiar_password.php", true);
        connect.setRequestHeader(
          "Content-type",
          "application/x-www-form-urlencoded",
        );
        connect.send(form);
      } else {
        alert("Las contraseñas no son iguales");
      }
    } else {
      alert("Ningún campo puede estar vacio");
    }
  };
};
