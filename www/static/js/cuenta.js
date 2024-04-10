var id = document.getElementById("id");
var tel = document.getElementById("tel");
var direccion = document.getElementById("direccion");
var info = document.getElementById("info");
var pass = document.getElementById("pass");
var enviar = document.getElementById("enviar");
var mifoto = document.getElementById("miFoto");
var foto = document.getElementById("foto");
console.log(id, tel, direccion, info, pass, enviar);
enviar.onclick = async function () {
  console.log("verificando");
  const resp = await fetch("api/usuarios.php?id=" + id.value);
  const datos = await resp.json();
  const usuario = datos.usuario;
  let send = false;
  console.log(foto.files);
  var form = {};
  if (foto.files.length > 0) {
    let formData = new FormData();
    formData.set("imagen", foto.files[0]);
    const imagen = await fetch(
      "api/uploads.php?folder=usuarios&id=" + usuario.id,
      {
        method: "POST",
        body: formData,
      },
    );
    console.log(imagen);
    const respuesta = await imagen.json();
    console.log(respuesta);
    form.foto = respuesta.message;
    send = true;
  }

  if (direccion.value !== usuario.direccion) {
    form.direccion = direccion.value;
    send = true;
  }
  if (info.value !== usuario.info) {
    form.info = info.value;
    send = true;
  }
  if (pass.value !== "") {
    var pass2 = prompt("Confirma tu password");
    if (pass2 === pass.value) {
      form.pass = pass.value;
      send = true;
    } else {
      alert("los valores no son iguales, intenta de nuevo");
      return;
    }
  }
  console.log(form);
  if (!send) return;
  const cambios = await fetch("api/usuarios.php?id=" + id.value, {
    method: "PUT",
    body: JSON.stringify(form),
  });
  const resp1 = await cambios.json();
  console.log(resp1);
  alert("Usuario modificado");
};

foto.onchange = function () {
  if (this.files && this.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      mifoto.setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  }
};
