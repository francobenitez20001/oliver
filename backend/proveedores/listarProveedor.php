<?php
    require '../config.php';
    $proveedor = new Proveedor();
    $registros = $proveedor->listarProveedor();
    echo $registros;
?>