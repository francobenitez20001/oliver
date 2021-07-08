<?php
    require '../config.php';
    $proveedor = new Proveedor();
    $bool = $proveedor->agregarProveedor();
    echo $bool;
?>