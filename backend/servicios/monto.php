<?php
    require '../config.php';
    $servicio = new Servicio;
    $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    $data = $servicio->obtenerMontoServicio($_GET['fecha'],$estado);
    echo $data;
?>