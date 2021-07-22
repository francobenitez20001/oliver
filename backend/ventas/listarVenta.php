<?php
    require '../config.php';
    $venta = new Venta;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : null;
    $data = $venta->listarVenta($limit);
    echo $data;
?>