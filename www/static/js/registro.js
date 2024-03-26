var email = document.getElementById("email");
var nombre = document.getElementById("nombre");
var tel = document.getElementById("tel");
var pass = document.getElementById("pass");
var pass2 = document.getElementById("pass2");
var enviar = document.getElementById("enviar");

enviar.onclick = async function () {
  if (
    nombre.value != "" &&
    pass.value != "" &&
    email.value != "" &&
    tel.value != "" &&
    pass.value == pass2.value
  ) {
    console.log("verificando");
    var form = JSON.stringify({
      nombre: nombre.value,
      pass: pass.value,
      email: email.value,
      tel: tel.value,
    });
    const response = await fetch("api/usuarios.php", {
      method: "POST",
      body: form,
    });
    const respuesta = await response.json();
    console.log(respuesta);
    if (respuesta.code == 201 && respuesta.ok) {
      alert("Registro exitoso");
      location.href = "/servicios.php";
    } else if (respuesta.code == 201 && !respuesta.ok) {
      alert("Registro exitoso, error al mandar el Email");
      location.href = "/servicios.php";
    } else if (respuesta.code == 500) {
      alert(respuesta.message);
    } else if (respuesta.code == 401) {
      alert(respuesta.message);
    } else if (respuesta.code == 404) {
      alert(respuesta.message);
    }
  } else if (pass != pass2) {
    alert("Las contraseñas no son iguales, intenta de nuevo");
  } else {
    alert("Ningún campo puede estar vacio");
  }
};
