<?php
    require '../config.php';
    $producto = new Producto;
    $data = $producto->modificarCodigo();
    echo $data;
?>