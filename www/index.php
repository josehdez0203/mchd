<?php
session_start();
// print_r($_SESSION);
include "./partes/head.php";
 include "./partes/header.php" ?>
    <section class="content sau" id="esp">
      <div class="box-container">
        <div class="slide">
          <div class="slide-inner">
            <input
              class="slide-open"
              type="radio"
              id="slide-1"
              name="slide"
              aria-hidden="true"
              hidden=""
              checked="checked"
            />

            <div class="slide-item">
              <img src="./static/img/img/imgone.jfif" />
            </div>
            <input
              class="slide-open"
              type="radio"
              id="slide-2"
              name="slide"
              aria-hidden="true"
              hidden=""
            />

            <div class="slide-item">
              <img src="./static/img/img/img%203.jfif" />
            </div>
            <input
              class="slide-open"
              type="radio"
              id="slide-3"
              name="slide"
              aria-hidden="true"
              hidden=""
            />

            <div class="slide-item">
              <img src="./static/img/img/img6.jfif" />
            </div>
            <input
              class="slide-open"
              type="radio"
              id="slide-4"
              name="slide"
              aria-hidden="true"
              hidden=""
            />

            <div class="slide-item">
              <img src="./static/img/img/img4%20(2).jfif" />
            </div>
            <label for="slide-4" class="slide-control prev control-1">‹</label>
            <label for="slide-3" class="slide-control prev control-4">‹</label>
            <label for="slide-2" class="slide-control next control-1">›</label>
            <label for="slide-1" class="slide-control prev control-2">‹</label>
            <label for="slide-4" class="slide-control next control-3">›</label>
            <label for="slide-3" class="slide-control next control-2">›</label>
            <label for="slide-2" class="slide-control prev control-3">‹</label>
            <label for="slide-1" class="slide-control next control-4">›</label>
            <ol class="slide-indicador">
              <li>
                <label for="slide-1" class="slide-circulo">•</label>
              </li>
              <li>
                <label for="slide-2" class="slide-circulo">•</label>
              </li>
              <li>
                <label for="slide-3" class="slide-circulo">•</label>
              </li>
              <li>
                <label for="slide-4" class="slide-circulo">•</label>
              </li>
            </ol>
          </div>
        </div>
      </div>
      <a class="button" href="Servicios.html">
        <button class="buttonce" style="vertical-align: middle">
          <span> SERVICIOS </span>
        </button>
      </a>
    </section> 
    <div class="info">
      <div class="infocenter">
        <h1>Misión</h1>
        Ayudar a los usuarios a encontrar de manera más rápida y segura el servicio que desee, 
ofreciendo un amplio catálogo en los mejores servicios de acuerdo a valoración de otros 
usuarios, para garantizar el mejor servicio y la satisfacción del usuario.
      </div>
      <div class="infocenter2">
        <h1>Visión</h1>
        Somos líderes en servicios que buscan ayudar a las personas de todo
        México, a encontrar los mejores servicios y los más seguros, con nuestro
        amplio catálogo online.
      </div>
    </div>
<?php include "./partes/footer.php" ?>
    
