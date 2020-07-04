<?php
    require '../classes/Conexion.php';
    require '../classes/PagoProveedor.php';
    $pagoProveedor = new PagoProveedor;
    echo $pagoProveedor->listarPagosPorProveedor();
?>