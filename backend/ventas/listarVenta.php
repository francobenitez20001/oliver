<?php
    require '../classes/Conexion.php';
    require '../classes/Venta.php';
    $venta = new Venta;
    $data = $venta->listarVenta();
    echo $data;
?>