<?php
    require '../config.php';
   $servicio = new Servicio;
   $data = $servicio->cargarComprobante();
   echo $data;
?>