<?php
    require '../config.php';
    $venta = new Venta;
    $data = $venta->listarVentaLimit();
    echo $data;
?>