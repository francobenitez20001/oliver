<?php
    require '../classes/Conexion.php';
    require '../classes/Venta.php';
    $venta = new Venta;
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $venta->getPagosConTarjeta($_GET['criterio']);
    }else{
        $data = $venta->getPagosConTarjeta();
    }
    echo $data;
?>