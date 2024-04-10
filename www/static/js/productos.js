var text = document.getElementById("text");
var buscar = document.getElementById("buscar");
var creaProd = document.getElementById("crear");
var titulo = document.getElementById("titulo");
var work = document.getElementById("work_area");
var paginacion = document.querySelector(".paginacion");
var workData = document.querySelector("div.admin");
var fotos = document.querySelector("div.fotos");
console.log(text, buscar, creaProd, titulo, work, paginacion);
var mode = "";
let pagina = 1;
let id;
let prodPoster = "";
let categorias = [];
titulo.innerHTML = "xxxx";
titulos_productos = ["Título", "Foto", "Editar"];
// Datos para la forma
var prodID = document.getElementById("prodId");
var prodTitulo = document.getElementById("prodTitulo");
var prodDesc = document.getElementById("prodDesc");
var categoria = document.getElementById("categoria");
var poster = document.getElementById("poster");
var imagenes = []; //= document.getElementById("imagenes");
var cargarImagenes = document.getElementById("agregar-imagen");
var miFoto = document.getElementById("miFoto");

var bAceptar = document.querySelector("button.ok");
var bCancelar = document.querySelector("button.cancel");
var bBorrar = document.querySelector("button.eliminar");
var modal = document.querySelector("section.modal");
console.log(catTitulo, prodID, prodTitulo, bAceptar, bCancelar);
async function cargar_datos(text) {
  console.log("cargando Productos");
  let respuesta;
  let sql =
    "http://localhost/api/productos.php?buscar=" +
    text +
    "&pag=" +
    pagina +
    "&id=" +
    id;
  console.log(sql);
  respuesta = await fetch(sql);
  datos = await respuesta.json();
  console.log(datos);
  poner_datos(datos.registros);
  paginar(parseInt(datos.pag), datos.paginas);
}
function crear(elem) {
  return document.createElement(elem);
}
function poner_datos(registros) {
  while (work.firstChild) {
    work.removeChild(work.firstChild);
  }
  var tb = crear("table");
  tb.classList.add("tabla");
  var row = crear("tr");
  row.classList.add("row");
  titulos_productos.forEach((titulo) => {
    var th = crear("th");
    th.classList.add("titulo");
    th.innerHTML = titulo;
    row.appendChild(th);
    tb.appendChild(row);
  });
  console.log(work);
  registros.forEach((registro) => {
    var row = crear("tr");
    row.classList.add("row");
    llena_producto(registro, row);
    tb.appendChild(row);
    work.appendChild(tb);
  });
}
function llena_producto(registro, row) {
  var td = crear("td");
  td.innerHTML = registro.titulo;
  row.appendChild(td);
  var td = crear("td");
  var div = crear("div");
  div.classList.add("avatar");
  var img = crear("img");
  img.setAttribute("src", registro.poster);
  div.appendChild(img);
  td.appendChild(div);
  row.appendChild(td);
  var td = crear("td");
  var div = crear("div");
  div.classList.add("activo");
  // div.onclick = toogleActivo;
  var img = crear("img");
  img.setAttribute("src", "static/img/edit.png");
  img.onclick = modificar;
  // div.setAttribute("data-active", registro.activo);
  div.setAttribute("data-id", registro.id);
  div.appendChild(img);
  td.appendChild(div);
  row.appendChild(td);
}
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
  let body = {
    usuario_id: id,
    categoria_id: categoria.value,
    titulo: prodTitulo.value,
    poster: prodPoster,
    imagenes: imagenes.join(","),
    // imagenes: JSON.stringify(imagenes),
    desc: prodDesc.value,
  };
  if (mode === "insertar") {
    console.log("insertar", body);
    respuesta = await fetch("api/productos.php", {
      method: "POST",
      body: JSON.stringify(body),
    });
  } else {
    console.log("modificar", body);
    respuesta = await fetch("api/productos.php?id=" + prodID.value, {
      method: "PUT",
      body: JSON.stringify(body),
    });
  }
  var resp = await respuesta.json();
  console.log("resp", resp);
  modal.style.display = "none";
  cargar_datos(text.value);
}
function cancelar() {
  console.log("cancelar");
  modal.style.display = "none";
}
function borrarFoto() {
  var imagen = this.parentNode;
  var img = imagen.querySelector("img").getAttribute("src");
  console.log(img);
  if (confirm("Está seguro de eliminar la imagen?")) {
    imagen.parentNode.removeChild(imagen);
    var indice = imagenes.indexOf(img);
    imagenes.splice(indice, 1);
    console.log(imagenes);
  }
}

bAceptar.onclick = aceptar;
bCancelar.onclick = cancelar;

function limpiarDatos(elemento) {
  while (elemento.firstChild) {
    elemento.removeChild(elemento.firstChild);
  }
}
creaProd.onclick = function () {
  prodID.value = "";
  prodTitulo.value = "";
  prodDesc.value = "";
  categoria.value = "0";
  poster.value = "";
  limpiarDatos(fotos);
  miFoto.setAttribute("src", "");
  mode = "insertar";
  modal.style.display = "flex";
  bBorrar.style.display = "none";
  imagenes = [];
  prodTitulo.focus();
};
async function modificar() {
  mode = "modificar";
  let parent = this.parentNode;
  console.log(parent);
  prodID.value = parent.getAttribute("data-id");
  modal.style.display = "flex";
  if (mode === "modificar") {
    bBorrar.style.display = "inline-block";
  } else {
    bBorrar.style.display = "none";
  }
  const respuesta = await fetch("api/productos.php?product_id=" + prodID.value);
  const resp = await respuesta.json();
  const producto = resp.producto;
  console.log(producto);
  prodTitulo.value = producto.titulo;
  prodDesc.innerHTML = producto.descripcion;
  categoria.value = producto.categoria_id;
  prodPoster = producto.poster;
  miFoto.setAttribute("src", producto.poster);
  limpiarDatos(fotos);
  imagenes = producto.imagenes === "" ? [] : producto.imagenes.split(",");
  console.log(imagenes);
  imagenes.forEach((im) => {
    crearImagenes(fotos, im);
  });
  prodTitulo.focus();
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
  cargar_datos(text.value);
}
async function carga_categorias() {
  const response = await fetch("/api/categorias.php?lista=");
  const cat = await response.json();
  console.log(cat);
  cat.registros.forEach((categ) => {
    var option = crear("option");
    option.value = categ.id;
    option.innerHTML = categ.nombre;
    categoria.appendChild(option);
  });
}
poster.onchange = async function () {
  if (this.files && this.files[0]) {
    let formData = new FormData();
    formData.set("imagen", poster.files[0]);
    const imagen = await fetch("api/uploads.php?folder=productos", {
      method: "POST",
      body: formData,
    });
    console.log(imagen);
    const respuesta = await imagen.json();
    console.log(respuesta);
    prodPoster = respuesta.message;
    var reader = new FileReader();
    reader.onload = function (e) {
      miFoto.setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  }
};

cargarImagenes.onchange = function () {
  if (this.files && this.files[0]) {
    var nombre = this.files[0];
    var reader = new FileReader();
    reader.onload = async function (e) {
      let formData = new FormData();
      formData.set("imagen", nombre);
      const sigImagen = await fetch("api/uploads.php?folder=productos", {
        method: "POST",
        body: formData,
      });
      console.log(sigImagen);
      const respuesta = await sigImagen.json();
      console.log(respuesta);
      currentFoto = respuesta.message;
      imagenes.push(currentFoto);
      crearImagenes(fotos, currentFoto);
      // console.log(JSON.stringify(imagenes));
    };
    reader.readAsDataURL(this.files[0], this.files[0].name);
  }
};
function crearImagenes(padre, sigImagen) {
  var imagen = crear("div");
  imagen.classList.add("imagen");
  var img = crear("img");
  var close = crear("div");
  close.classList.add("cerrar");
  close.innerHTML = "x";
  close.onclick = borrarFoto;
  img.setAttribute("src", sigImagen);
  imagen.appendChild(img);
  imagen.appendChild(close);
  padre.appendChild(imagen);
}
titulo.innerHTML = "Servicios de: " + workData.getAttribute("data-name");
id = workData.getAttribute("data-id");
bBorrar.onclick = eliminar;
bBorrar.style.display = "none";
cargar_datos("");
carga_categorias();
