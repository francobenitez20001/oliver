<?php
    require '../config.php';
    $servicio = new Servicio;
    $data = $servicio->listarServicio();
    echo $data;
?>