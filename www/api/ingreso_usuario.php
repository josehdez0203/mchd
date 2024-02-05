<?php

session_start();
require "../init.php";
$registro = new Usuario();
$registro->ApiLogin();
