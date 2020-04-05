<?php
    require '../classes/Conexion.php';
    require '../classes/Proveedor.php';
    $proveedor = new Proveedor();
    $bool = $proveedor->eliminarProveedor();
    echo $bool;
?>