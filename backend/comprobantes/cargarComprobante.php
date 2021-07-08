<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
   require '../config.php';
   $comprobante = new Comprobante;
   $data = $comprobante->cargarComprobante();
   echo $data;
?>
