<?php
    require '../classes/Conexion.php';
    require '../classes/PagoProveedor.php';
    $pagoProveedor = new PagoProveedor;
    $bool = $pagoProveedor->agregarPago();
    echo $bool;
?>