<?php
    require '../config.php';
    $proveedor = new Proveedor;
    $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    $fecha = $_GET['fecha'];
    $data = $proveedor->obtenerMontoPagos($fecha,$estado);
    echo $data;
?>