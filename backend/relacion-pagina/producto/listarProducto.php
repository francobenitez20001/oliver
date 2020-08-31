<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");       
    require '../../classes/Conexion.php';
    require '../../classes/Producto.php';
    $producto = new Producto();
    $response = $producto->listarProductoParaWeb();
    echo $response;
?>