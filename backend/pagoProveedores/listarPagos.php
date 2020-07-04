<?php
    require '../classes/Conexion.php';
    require '../classes/PagoProveedor.php';
    $pagoProveedor = new PagoProveedor;
    if(isset($_GET['mes'])){
        echo $pagoProveedor->listarPagos($_GET['mes']);
    }else{
        echo $pagoProveedor->listarPagos();
    }
?>