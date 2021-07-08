<?php
    require '../config.php';
    $servicio = new Servicio;
    $response = $servicio->saldarServicio();
    echo $response;
?>