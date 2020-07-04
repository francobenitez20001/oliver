<?php
    require '../classes/Conexion.php';
    require '../classes/PagoProveedor.php';
    $pago = new PagoProveedor;
    //si viene criterio es porque quiero filtrar por mes. no por dia.
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $pago->obtenerMontoPagos($_GET['criterio']);
        
    }else{
        $data = $pago->obtenerMontoPagos();
    }
    echo $data;
?>