<?php
    require '../classes/Conexion.php';
    require '../classes/Proveedor.php';
    $proveedor = new Proveedor();
    $registros = $proveedor->listarProveedor();
    echo $registros;
?>