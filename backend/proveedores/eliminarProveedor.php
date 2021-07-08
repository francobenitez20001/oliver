<?php
    require '../config.php';
    $proveedor = new Proveedor();
    $bool = $proveedor->eliminarProveedor();
    echo $bool;
?>