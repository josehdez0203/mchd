var email = document.getElementById("email");
var pass = document.getElementById("pass");
var enviar = document.getElementById("enviar");

window.onload = function () {
  enviar.onclick = function () {
    var connect;

    if (pass.value != "" && email.value != "") {
      console.log("verificando");
      // var form = "pass=" + pass + "&email=" + email;
      var form = JSON.stringify({
        email: email.value,
        pass: pass.value,
      });
      console.log(form);
      connect = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      connect.onreadystatechange = function () {
        if (connect.readyState == 4 && connect.status == 200) {
          console.log(connect.responseText);
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
      connect.open("POST", "api/auth.php", true);
      connect.setRequestHeader("Content-type", "application/json");
      connect.send(form);
    } else {
      alert("Ningún campo puede estar vacio");
    }
  };
};
function enter(e) {
  console.log(e.keyCode);
  if (e.keyCode === 13) {
    enviar.click();
  }
  // enviar.click();
}
email.onkeyup = enter;
pass.onkeyup = enter;
// pass.onkeyup = function (e) {
//   enviar.click(e.keyCode);
// };
