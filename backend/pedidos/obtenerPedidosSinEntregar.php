<?php
    require '../classes/Conexion.php';
    require '../classes/Pedido.php';
    $pedido = new Pedido;
    $data = $pedido->obtenerPedidosSinEntregar();
    echo $data;
?>