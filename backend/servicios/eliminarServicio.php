<?php
    require '../config.php';
    $servicio = new Servicio;
    $response = $servicio->eliminarServicio();
    echo $response;
?>