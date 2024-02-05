window.onload = function () {
  document.getElementById("enviar").onclick = function () {
    var connect, user, pass, session, form, result;
    var email = document.getElementById("email").value;
    var nombre = document.getElementById("nombre").value;
    var tel = document.getElementById("tel").value;
    var pass = document.getElementById("pass").value;
    var pass2 = document.getElementById("pass2").value;

    if (
      nombre != "" &&
      pass != "" &&
      email != "" &&
      tel != "" &&
      pass == pass2
    ) {
      console.log("verificando");
      var form =
        "nombre=" +
        nombre +
        "&pass=" +
        pass +
        "&email=" +
        email +
        "&tel=" +
        tel;
      console.log(form);
      connect = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      connect.onreadystatechange = function () {
        if (connect.readyState == 4 && connect.status == 200) {
          var respuesta = JSON.parse(connect.responseText);
          console.log(respuesta);
          if (respuesta.code == 200 && respuesta.ok) {
            alert("Registro exitoso");
            location.href = "/servicios.php";
          } else if (respuesta.code == 200 && !respuesta.ok) {
            alert("Registro exitoso, error al mandar el Email");
            location.href = "/servicios.php";
          } else if (respuesta.code == 500) {
            alert(respuesta.message);
          } else if (respuesta.code == 401) {
            alert(respuesta.message);
          } else if (respuesta.code == 501) {
            alert(respuesta.message);
          }
        }
      };
      connect.open("POST", "nuevo_usuario.php", true);
      connect.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded",
      );
      connect.send(form);
    } else if (pass != pass2) {
      alert("Las contraseñas no son iguales, intenta de nuevo");
    } else {
      alert("Ningún campo puede estar vacio");
    }
  };
};
