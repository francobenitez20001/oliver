<?php
  require '../classes/Conexion.php';
  require '../classes/Usuario.php';
  session_start();//COMIENZA LA SESION
  if (!isset($_SESSION['login'])) {//SI NO EXISTE LA SESSION LOGIN..
    echo json_encode(false);
  }
?>
