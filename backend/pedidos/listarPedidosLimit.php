<?php
    require '../config.php';
    $pedido = new Pedido;
    $data = $pedido->listarPedidosLimit();
    echo $data;
?>