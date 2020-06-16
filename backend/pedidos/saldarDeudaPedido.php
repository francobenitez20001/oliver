<?php
    require '../classes/Conexion.php';
    require '../classes/Pedido.php';
    $pedido = new Pedido;
    $data = $pedido->saldarDeudaConPedido();
    echo $data;
?>