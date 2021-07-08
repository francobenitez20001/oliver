<?php
    require '../config.php';
    $pedido = new Pedido;
    $bool = $pedido->agregarPedido();
    echo $bool;
?>