<?php
    require '../classes/Conexion.php';
    require '../classes/Servicio.php';
    $servicio = new Servicio;
    $data = $servicio->listarServicio();
    echo $data;
?>