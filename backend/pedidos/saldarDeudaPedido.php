<?php
    require '../config.php';
    $pedido = new Pedido;
    $data = $pedido->saldarDeudaConPedido();
    echo $data;
?>