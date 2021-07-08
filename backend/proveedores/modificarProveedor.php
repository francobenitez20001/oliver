<?php
    require '../config.php';
    $proveedor = new Proveedor();
    $bool = $proveedor->modificarProveedor();
    echo $bool;
?>