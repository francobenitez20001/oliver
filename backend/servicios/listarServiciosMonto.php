<?php
    require '../classes/Conexion.php';
    require '../classes/Servicio.php';
    $servicio = new Servicio;
    //si viene criterio es porque quiero filtrar por mes. no por dia.
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $servicio->obtenerMontoServicio($_GET['criterio']);
        
    }else{
        $data = $servicio->obtenerMontoServicio();
    }
    echo $data;
?>