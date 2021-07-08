<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");       
    require '../../config.php';
    $producto = new Producto();
    $response = $producto->listarProductoParaWeb();
    echo $response;
?>