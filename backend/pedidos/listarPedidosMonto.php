<?php
    require '../classes/Conexion.php';
    require '../classes/Pedido.php';
    $pedido = new Pedido;
    //si viene criterio es porque quiero filtrar por mes. no por dia.
    if (isset($_GET['criterio']) && $_GET['criterio']!='') {
        $data = $pedido->obtenerMontoPedidos($_GET['criterio']);
        
    }else{
        $data = $pedido->obtenerMontoPedidos();
    }
    echo $data;
?>