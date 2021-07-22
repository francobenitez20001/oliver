<?php
    require '../config.php';
    $venta = new Venta;
    $data = $venta->getPagosPorMedioDePago($_GET['fecha']);
    echo $data;
?>