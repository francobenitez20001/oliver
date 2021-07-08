<?php
    require '../config.php';
    $pedido = new Pedido;
    $bool = $pedido->recibirPedido();
    echo $bool;
?>