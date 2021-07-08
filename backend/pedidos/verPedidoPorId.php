<?php
    require '../config.php';
    $pedido = new Pedido;
    $data = $pedido->verPedidoPorId();
    echo $data;
?>