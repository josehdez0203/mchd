let cont = 0;
// let inicio = document.querySelector(".detalle-producto");
let titulo = document.querySelector(".titulo");
let poster = document.querySelector(".poster");
let texto = document.querySelector(".texto");
let contenedor = document.querySelector(".carousel");
console.log("empezamos", contenedor);

let atras = contenedor.querySelector(".atras");
let adelante = contenedor.querySelector(".adelante");
let imagen = contenedor.querySelector(".imagen");
let imagenes = [];
var ratio = 4 / 3; //16 / 9;
let x = (y = null);
let altura = (altResp = 0);
imageTemp = imagenes[0];
imagen.style.backgroundImage = "url(" + imageTemp + ")";
console.log("img", imagen.style.backgroundImage);

function carrousel(contenedor) {
  contenedor.addEventListener("click", (e) => {
    let actual = e.target;
    if (actual == atras) {
      if (cont > 0) {
        cont--;
      } else {
        cont = imagenes.length - 1;
      }
    } else if (actual == adelante) {
      if (cont < imagenes.length - 1) {
        cont++;
      } else {
        cont = 0;
      }
    }
    clearInterval(x);
    x = setInterval(() => {
      adelante.click();
    }, 4000);
    let imageTemp = imagenes[cont]; //imagen a cambiar

    imagen.style.backgroundImage = `url("${imageTemp}")`;
    console.log("img", imagen);
  });
}

carrousel(contenedor);

x = setInterval(() => {
  adelante.click();
}, 4000);

window.onresize = function () {
  console.log("width", contenedor.offsetWidth);
  contenedor.offsetHeight = getHeight(contenedor.offsetWidth, ratio); //getWidth(height, ratio);

  imagen.style.height = getHeight(contenedor.offsetWidth, ratio);
  imagen.style.width = getWidth(contenedor.offsetHeight, ratio);
  console.log("heigth1", contenedor.offsetHeight);
};

function getHeight(w, r) {
  var height = w / r;
  // var height = contenedor.style.width / Math.sqrt(Math.pow(ratio, 2) + 1);
  console.log("heigth", height);

  return Math.round(height);
}

function getWidth(length, ratio) {
  var width = length / Math.sqrt(1 / (Math.pow(ratio, 2) + 1));
  return Math.round(width);
}
function isMobile() {
  if (
    navigator.userAgent.match(/Android/i) ||
    navigator.userAgent.match(/webOS/i) ||
    navigator.userAgent.match(/iPhone/i) ||
    navigator.userAgent.match(/iPod/i) ||
    navigator.userAgent.match(/iPad/i) ||
    navigator.userAgent.match(/BlackBerry/i)
  ) {
    return true;
  } else {
    return false;
  }
}

let height = getHeight(contenedor.style.offsetHeight, ratio);
imagen.style.height = height + "px";
imagen.style.width = getWidth(height, ratio) + "px";

console.log(imagen, height, getWidth(height, ratio));
window.onload = function () {
  async function loadDetalle(id) {
    const respuesta = await fetch("api/servicios.php?id=" + id);
    const resp = await respuesta.json();
    console.log(resp);
    cargar_datos(resp.producto);
  }
  var index = window.location.href.indexOf("=");
  if (index === -1) window.location.href = "/";
  var id = window.location.href.substring(index + 1);
  console.log(id);
  loadDetalle(id);
};
function crear(ele) {
  return document.createElement(ele);
}
function cargar_datos(producto) {
  console.log(producto);
  var h2 = titulo.querySelector("h2");
  h2.innerHTML = producto.titulo;
  var img = poster.querySelector("img");
  img.setAttribute("src", producto.poster);
  var p = texto.querySelector("p");
  p.innerHTML = producto.descripcion;
  imagenes = producto.imagenes.split(",");
  imageTemp = imagenes[0];
  imagen.style.backgroundImage = "url(" + imageTemp + ")";
}
