<?php
    require '../config.php';
    $venta = new Venta;
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $venta->obtenerMontoVenta($_GET['mes'],$_GET['criterio']);
    }else{
        $data = $venta->obtenerMontoVenta($_GET['dia']);
    }
    echo $data;
?>