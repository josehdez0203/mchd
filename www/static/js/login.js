window.onload = function () {
  document.getElementById("enviar").onclick = function () {
    var connect;
    var email = document.getElementById("email").value;
    var pass = document.getElementById("pass").value;

    if (pass != "" && email != "") {
      console.log("verificando");
      var form = "pass=" + pass + "&email=" + email;

      console.log(form);
      connect = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      connect.onreadystatechange = function () {
        // console.log(connect);
        if (connect.readyState == 4 && connect.status == 200) {
          var respuesta = JSON.parse(connect.responseText);
          console.log(respuesta);
          if (respuesta.code == 201 && respuesta.ok) {
            console.log("Login exitoso");
            location.href = "/servicios.php";
          } else if (respuesta.code == 401 && !respuesta.ok) {
            alert("Email o contraseña incorrectos");
          } else if (respuesta.code == 402) {
            alert(respuesta.message);
          }
        }
      };
      connect.open("POST", "api/ingreso_usuario.php", true);
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
