<?php
    require '../config.php';
    $servicio = new Servicio;
    $response = $servicio->agregarServicio();
    echo $response;
?>