<?php
    require '../classes/Conexion.php';
    require '../classes/Proveedor.php';
    $proveedor = new Proveedor();
    $bool = $proveedor->agregarProveedor();
    echo $bool;
?>