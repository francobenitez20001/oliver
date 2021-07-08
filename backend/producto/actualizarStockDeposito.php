<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->modificarStockDeposito();
    echo $bool;
?>