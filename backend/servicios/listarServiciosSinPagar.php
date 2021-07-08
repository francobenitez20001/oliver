<?php
    require '../config.php';
    $servicio = new Servicio;
    $data = $servicio->obtenerServiciosSinPagar();
    echo $data;
?>