<?php
    require '../config.php';
    $venta = new Venta;
    $fecha = $_GET['fecha'];
    $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    $tipoPago = isset($_GET['tipoDePago']) ? $_GET['tipoDePago'] : null;
    $data = $venta->obtenerMontoVenta($fecha,$estado,$tipoPago);
    echo $data;
?>