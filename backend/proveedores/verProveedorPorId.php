<?php
    require '../config.php';
    $proveedor = new Proveedor();
    $registros = $proveedor->verProveedorPorId();
    echo $registros;
?>