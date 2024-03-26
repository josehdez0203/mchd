var menu = document.querySelector(".menu_admin");
var text = document.getElementById("text");
var buscar = document.getElementById("buscar");
var creaCat = document.getElementById("crear");
var titulo = document.getElementById("titulo");
var work = document.getElementById("work_area");
var paginacion = document.querySelector(".paginacion");
var opciones = menu.querySelectorAll("li");
var mode = "";
let tabla = "";
let pagina = 1;
titulo.innerHTML = "";
titulos_usuarios = [
  "nombre",
  "Email",
  "Dirección",
  "Teléfono",
  "Foto",
  "Activo",
  // "Editar",
];
titulos_categ = ["Categoría", "Edita"];
var catTitulo = document.getElementById("catTitulo");
var catId = document.getElementById("catId");
var catName = document.getElementById("catName");
var bAceptar = document.querySelector("button.ok");
var bCancelar = document.querySelector("button.cancel");
var bBorrar = document.querySelector("button.eliminar");
var modal = document.querySelector("section.modal");
console.log(catTitulo, catId, catName, bAceptar, bCancelar);
for (const li of opciones) {
  li.addEventListener("click", function () {
    titulo.innerHTML = this.innerHTML;
    tabla = this.getAttribute("data-table");
    pagina = 1;
    console.log("Se oprimio", this.innerHTML, tabla);
    if (tabla === "usuarios") {
      creaCat.style.display = "none";
    } else {
      creaCat.style.display = "block";
    }
    cargar_datos(text.value);
  });
}
const rango = (inicio = 0, fin = 1, paso = 1) =>
  Array.from(
    { length: (fin - inicio) / paso + 1 },
    (_, i) => inicio + i * paso,
  );
async function cargar_datos(text) {
  console.log("cargando datos " + tabla);
  let respuesta;
  let sql = "";
  if (tabla === "usuarios") {
    sql = "http://localhost/api/usuarios.php?buscar=" + text + "&pag=" + pagina;
  } else {
    sql =
      "http://localhost/api/categorias.php?buscar=" + text + "&pag=" + pagina;
  }
  console.log(sql);
  respuesta = await fetch(sql);
  datos = await respuesta.json();
  console.log(datos);
  poner_datos(datos.registros, tabla);
  paginar(parseInt(datos.pag), datos.paginas);
}
function crear(elem) {
  return document.createElement(elem);
}
function poner_datos(registros, tabla) {
  while (work.firstChild) {
    work.removeChild(work.firstChild);
  }
  var tb = crear("table");
  tb.classList.add("tabla");
  var row = crear("tr");
  row.classList.add("row");
  if (tabla === "usuarios") {
    console.log(titulos_usuarios);
    titulos_usuarios.forEach((titulo) => {
      var th = crear("th");
      th.classList.add("titulo");
      th.innerHTML = titulo;
      row.appendChild(th);
    });
  } else {
    console.log(titulos_usuarios);
    titulos_categ.forEach((titulo) => {
      var th = crear("th");
      th.classList.add("titulo");
      th.innerHTML = titulo;
      row.appendChild(th);
    });
  }
  tb.appendChild(row);
  console.log(work);
  registros.forEach((registro) => {
    var row = crear("tr");
    row.classList.add("row");
    if (tabla === "usuarios") {
      llena_usuario(registro, row);
    } else {
      llena_categ(registro, row);
    }
    tb.appendChild(row);
    work.appendChild(tb);
  });
}
function llena_usuario(registro, row) {
  var td = crear("td");
  td.innerHTML = registro.nombre;
  row.appendChild(td);
  var td = crear("td");
  td.innerHTML = registro.email;
  row.appendChild(td);
  var td = crear("td");
  td.innerHTML = registro.direccion;
  row.appendChild(td);
  var td = crear("td");
  td.innerHTML = registro.telefono;
  row.appendChild(td);
  var td = crear("td");
  var div = crear("div");
  div.classList.add("avatar");
  var img = crear("img");
  img.setAttribute("src", registro.foto);
  div.appendChild(img);
  td.appendChild(div);
  row.appendChild(td);
  var td = crear("td");
  var div = crear("div");
  div.classList.add("activo");
  div.setAttribute("data-active", registro.activo);
  div.setAttribute("data-id", registro.id);
  div.onclick = toogleActivo;
  var img = crear("img");
  img.setAttribute(
    "src",
    registro.activo === "1" ? "static/img/check.jpg" : "static/img/tache.webp",
  );
  div.appendChild(img);
  td.appendChild(div);
  row.appendChild(td);
}
function toogleActivo() {
  var img = this.querySelector("img");
  console.log("active", this.getAttribute("data-active"));
  if (confirm("Está seguro de Activar/Desactivar usuario?")) {
    var id = this.getAttribute("data-id");
    var active = this.getAttribute("data-active");
    var connect;
    connect = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    connect.onreadystatechange = function () {
      // alert(connect);
      if (connect.readyState == 4 && connect.status == 200) {
        console.log(connect.responseText);
        // var respuesta = JSON.parse(connect.responseText);
        // console.log(respuesta);
      }
    };
    connect.open(
      "GET",
      "api/activar_usuario.php?id=" + id + "&activo=" + active,
      true,
    );
    connect.send();

    if (this.getAttribute("data-active") === "1") {
      this.setAttribute("data-active", "0");
      img.setAttribute("src", "static/img/tache.webp");
    } else {
      this.setAttribute("data-active", "1");
      img.setAttribute("src", "static/img/check.jpg");
    }
  }
}
function llena_categ(registro, row) {
  var td = crear("td");
  td.innerHTML = registro.nombre;
  row.appendChild(td);
  var td = crear("td");
  var div = crear("div");
  div.classList.add("editar");
  div.setAttribute("data-id", registro.id);
  div.setAttribute("data-nombre", registro.nombre);
  var img = crear("img");
  img.setAttribute("src", "static/img/edit.png");
  img.onclick = modificar;
  div.appendChild(img);
  td.appendChild(div);
  row.appendChild(td);
}
buscar.onclick = function () {
  console.log("Buscar: ", text.value);
  cargar_datos(text.value);
};
text.onkeyup = function (e) {
  if (e.keyCode === 13) {
    buscar.click();
  }
};
function cambia_pag() {
  pagina = this.innerHTML;
  cargar_datos(text.value);
}
function paginar(pag, paginas) {
  while (paginacion.firstChild) {
    paginacion.removeChild(paginacion.firstChild);
  }
  for (let i = 1; i <= paginas; i++) {
    const div = crear("div");
    div.innerHTML = i;
    console.log(i === pag);
    if (i == pag) {
      div.classList.add("active");
    } else {
      div.onclick = cambia_pag;
    }
    paginacion.appendChild(div);
  }
}
async function aceptar() {
  // console.log("aceptar");
  var respuesta;
  if (mode === "insertar") {
    console.log("insertar");
    respuesta = await fetch("api/categorias.php", {
      method: "POST",
      body: JSON.stringify({ nombre: catName.value }),
    });
  } else {
    console.log("modificar");
    respuesta = await fetch("api/categorias.php?id=" + catId.value, {
      method: "PUT",
      body: JSON.stringify({ nombre: catName.value }),
    });
  }
  var resp = await respuesta.json();
  console.log("resp", resp);
  modal.style.display = "none";
}
function cancelar() {
  console.log("cancelar");
  modal.style.display = "none";
}

bAceptar.onclick = aceptar;
bCancelar.onclick = cancelar;
creaCat.onclick = function () {
  catId.value = "";
  catName.value = "";
  mode = "insertar";
  modal.style.display = "flex";
  bBorrar.style.display = "none";
  catName.focus();
};
function modificar() {
  mode = "modificar";
  let parent = this.parentNode;
  console.log(parent);
  catId.value = parent.getAttribute("data-id");
  catName.value = parent.getAttribute("data-nombre");
  modal.style.display = "flex";
  if (mode === "modificar") {
    bBorrar.style.display = "inline-block";
  } else {
    bBorrar.style.display = "none";
  }
  catName.focus();
}
async function eliminar() {
  if (confirm("Está seguro de eliminar la categoría?")) {
    console.log("eliminar");
    respuesta = await fetch("api/categorias.php?id=" + catId.value, {
      method: "DELETE",
    });
    console.log("resp", await respuesta.json());
    cargar_datos();
  }
  modal.style.display = "none";
}
bBorrar.onclick = eliminar;
bBorrar.style.display = "none";
