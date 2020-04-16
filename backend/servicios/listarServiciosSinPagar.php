<?php
    require '../classes/Conexion.php';
    require '../classes/Servicio.php';
    $servicio = new Servicio;
    $data = $servicio->obtenerServiciosSinPagar();
    echo $data;
?>