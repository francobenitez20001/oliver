<?php
    require '../config.php';
    $pedido = new Pedido;
    $bool = $pedido->eliminarPedido();
    echo $bool;
?>