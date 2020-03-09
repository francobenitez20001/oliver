<?php
    require '../classes/Conexion.php';
    require '../classes/Venta.php';
    $venta = new Venta;
    $response = $venta->saldarVenta();
    echo $response;
?>