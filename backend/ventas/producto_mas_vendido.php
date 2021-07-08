<?php
    require '../config.php';
    $venta = new Venta;
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $venta->getProductoMasVendido($_GET['criterio']);
    }else{
        $data = $venta->getProductoMasVendido();
    }
    echo $data;
?>