var productos = document.getElementById("productos");
var paginacion = document.querySelector("div.paginacion");
var pagCat = 1;
var categoriaActiva = null;
var categorias = document.getElementById("categorias");
var tituloAct = document.getElementById("titulo");
var buscar = document.getElementById("buscar");
var texto = document.getElementById("texto");
console.log("Inciando", productos, categorias);
var more = document.querySelector("div.more");
var pagina = 1;
var objParametros = {};
var ListaCategorias = [];
var ListaProductos = [];
console.log("🆎");
function crear(elem) {
  return document.createElement(elem);
}
buscar.onclick = function () {
  console.log("Buscar: ", texto.value);
  cargar_servicios(texto.value);
};
texto.onkeyup = function (e) {
  if (e.keyCode === 13) {
    buscar.click();
  }
};

// Llenar los servicios
function llenarServicios() {
  while (productos.firstChild) {
    productos.removeChild(productos.firstChild);
  }
  for (let i = 0; i < ListaProductos.length; i++) {
    const prod = ListaProductos[i];
    var producto = crear("div");
    producto.classList.add("producto");
    var detalle = crear("div");
    detalle.classList.add("detalle");
    var h3 = crear("h3");
    h3.innerHTML = prod.nombre;
    var h4 = crear("h4");
    h4.innerHTML = prod.titulo;
    var p = crear("p");
    var a = crear("a");
    a.href =
      "servicios.php?categoria_id=" +
      prod.categoria_id +
      "&nombre=" +
      prod.categoria;
    a.innerHTML = prod.categoria;
    p.appendChild(a);
    var desc = crear("p");
    desc.innerHTML = prod.descripcion;
    var a = crear("a");
    a.innerHTML = "Ver detalle";
    a.setAttribute("href", "detalle.php?id=" + prod.id);
    desc.appendChild(a);
    detalle.appendChild(h3);
    detalle.appendChild(h4);
    detalle.appendChild(p);
    detalle.appendChild(desc);

    var imagen = crear("div");
    imagen.classList.add("imagen");
    var img = crear("img");
    img.setAttribute("src", prod.poster);
    imagen.appendChild(img);

    producto.appendChild(detalle);
    producto.appendChild(imagen);
    productos.appendChild(producto);
  }
}
async function cargar_servicios(texto) {
  sql = "";
  if (categoriaActiva !== null) {
    sql =
      "api/servicios.php?pag=" +
      pagina +
      "&buscar=" +
      texto +
      "&categoria_id=" +
      categoriaActiva;
  } else {
    sql = "api/servicios.php?pag=" + pagina + "&buscar=" + texto;
  }
  console.log(sql);
  const respuesta = await fetch(sql);
  const datos = await respuesta.json();
  ListaProductos = [];
  datos.registros.forEach((cat) => {
    ListaProductos.push(cat);
  });
  console.log(ListaProductos);
  llenarServicios();
  paginar(datos.pag, datos.paginas);
}
async function cargar_categorias(texto) {
  const respuesta = await fetch(
    "api/categorias.php?pag=" + pagina + "&buscar=" + texto,
  );
  const datos = await respuesta.json();
  // ListaCategorias = [];
  datos.registros.forEach((cat) => {
    ListaCategorias.push(cat);
  });
  if (datos.registros.length == 0) {
    // more.classList.remove("more");
    more.classList.add("disabled");
  }
  console.log(datos);
  llenarCategorias();
}
function llenarCategorias() {
  while (categorias.firstChild) {
    categorias.removeChild(categorias.firstChild);
  }
  // <a href="<?php echo '?categoria='.$id ?>">
  //   <?php echo $nombre ?>
  for (let i = 0; i < ListaCategorias.length; i++) {
    const cat = ListaCategorias[i];
    var li = document.createElement("li");
    var a = document.createElement("a");
    a.setAttribute(
      "href",
      "/servicios.php?categoria_id=" + cat.id + "&nombre=" + cat.nombre,
    );
    a.innerHTML = cat.nombre;
    li.appendChild(a);
    // console.log(li);
    categorias.appendChild(li);
  }
}

function cargar_mas() {
  pagina++;
  cargar_categorias("");
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

more.onclick = cargar_mas;
window.onload = function () {
  var inicio = location.href.indexOf("?");
  let parametros = [];
  if (inicio > -1) {
    let todos = location.href.substring(inicio + 1).split("&");
    todos.forEach((element) => {
      ele = element.split("=");
      parametros.push(ele);
    });
    objParametros = Object.fromEntries(parametros);
    if (objParametros.categoria_id !== null) {
      categoriaActiva = objParametros.categoria_id;
      tituloAct.innerHTML =
        "Los mejores de: " + decodeURI(objParametros.nombre);
    }
  }
  // console.log(objParametros);
  console.log(inicio, objParametros);
  console.log(ListaCategorias);
  console.log(categoriaActiva);
  cargar_categorias("");
  cargar_servicios("");
};
