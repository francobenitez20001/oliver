<?php
    require '../config.php';
    $pedido = new Pedido;
    $data = $pedido->obtenerPedidosSinEntregar();
    echo $data;
?>