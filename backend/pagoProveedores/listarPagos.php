<?php
    require '../config.php';
    $pagoProveedor = new PagoProveedor;
    if(isset($_GET['mes'])){
        echo $pagoProveedor->listarPagos($_GET['mes']);
    }else{
        echo $pagoProveedor->listarPagos();
    }
?>