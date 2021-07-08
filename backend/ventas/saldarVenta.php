<?php
    require '../config.php';
    $venta = new Venta;
    $response = $venta->saldarVenta();
    echo $response;
?>