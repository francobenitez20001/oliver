<?php
    require '../config.php';
    $venta = new Venta;
    $response = $venta->eliminarVenta();
    echo $response;
?>