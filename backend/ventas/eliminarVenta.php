<?php
    require '../classes/Conexion.php';
    require '../classes/Venta.php';
    $venta = new Venta;
    $response = $venta->eliminarVenta();
    echo $response;
?>