<?php
    require '../classes/Conexion.php';
    require '../classes/Servicio.php';
    $servicio = new Servicio;
    $response = $servicio->agregarServicio();
    echo $response;
?>