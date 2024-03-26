console.log("Inciando");
window.onload = function () {
  document.getElementById("activar").onclick = function () {
    var connect;
    var id = document.querySelector("input[type=hidden]").value;
    console.log("id", id);
    connect = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    connect.onreadystatechange = function () {
      // alert(connect);
      if (connect.readyState == 4 && connect.status == 200) {
        console.log(connect.responseText);
        var respuesta = JSON.parse(connect.responseText);
        console.log(respuesta);
        if (respuesta.code === 201 && respuesta.ok) {
          alert("Usuario activado correctamente");
          location.href = "/index.php";
        } else if (respuesta.code === 201 && !respuesta.ok) {
          alert(respuesta.mensaje);
          location.href = "/";
        } else if (respuesta.code == 401 && !respuesta.ok) {
          alert("Email incorrecto o no existe");
        } else if (respuesta.code == 402) {
          alert(respuesta.message);
        }
      }
    };
    connect.open("GET", "api/auth.php?id=" + id, true);
    connect.send();
  };
};
