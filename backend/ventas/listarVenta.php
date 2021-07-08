<?php
    require '../config.php';
    $venta = new Venta;
    $data = $venta->listarVenta();
    echo $data;
?>