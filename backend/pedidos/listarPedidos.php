<?php
    require '../config.php';
    $pedido = new Pedido;
    $data = $pedido->listarPedidos();
    echo $data;
?>