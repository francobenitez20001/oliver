<?php
    require '../classes/Conexion.php';
    require '../classes/Producto.php';
    $producto = new Producto;
    $data = $producto->modificarCodigo();
    echo $data;
?>